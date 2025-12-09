<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with('karyawan')->latest();

        // Filter per hari
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter per bulan
        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // Filter per karyawan
        if ($request->karyawan_id) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        $data = $query->paginate(10);
        $karyawan = \App\Models\Karyawan::all();

        return view('absensi.index', compact('data', 'karyawan'));
    }

    public function dashboard(Request $request)
    {
        $today = Carbon::now()->toDateString();

        // --- Filter input ---
        $tanggalFrom = $request->tanggal_from;
        $tanggalTo   = $request->tanggal_to;
        $bulan       = $request->bulan;
        $karyawan    = $request->karyawan_id;

        // ======================================================
        //  SUMMARY
        // ======================================================
        $summaryQuery = Absensi::query()
            ->when($tanggalFrom, fn($q) => $q->whereDate('tanggal', '>=', $tanggalFrom))
            ->when($tanggalTo, fn($q) => $q->whereDate('tanggal', '<=', $tanggalTo))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($karyawan, fn($q) => $q->where('karyawan_id', $karyawan));

        $summary = [
            'hadir' => (clone $summaryQuery)->where('status', 'Hadir')->count(),
            'izin'  => (clone $summaryQuery)->where('status', 'Izin')->count(),
            'sakit' => (clone $summaryQuery)->where('status', 'Sakit')->count(),
            'alpha' => (clone $summaryQuery)->where('status', 'Alpha')->count(),
            'cuti'  => (clone $summaryQuery)->where('status', 'Cuti')->count(),
        ];


        // ======================================================
        //  TABEL ABSENSI
        // ======================================================
        $tableQuery = Absensi::with('karyawan')
            ->when($tanggalFrom, fn($q) => $q->whereDate('tanggal', '>=', $tanggalFrom))
            ->when($tanggalTo, fn($q) => $q->whereDate('tanggal', '<=', $tanggalTo))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($karyawan, fn($q) => $q->where('karyawan_id', $karyawan));

        if (!$tanggalFrom && !$tanggalTo && !$bulan) {
            $tableQuery->whereDate('tanggal', $today);
        }

        $todayAbsensi = $tableQuery->orderBy('tanggal')
        ->paginate(10)   // <-- pagination aktif
        ->withQueryString();


        // ======================================================
        //  GRAFIK PER HARI
        // ======================================================

        // Tentukan bulan & tahun yang dipilih
        $monthNames = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];
        $selectedMonth = request()->get('bulan', date('m')); // default bulan sekarang
        $selectedYear  = request()->get('tahun', date('Y')); // default tahun sekarang

        if ($tanggalFrom && $tanggalTo) {
            $start = Carbon::parse($tanggalFrom);
            $end   = Carbon::parse($tanggalTo);

            // Jika bulan & tahun sama → tampilkan satu bulan
            if ($start->format('m-Y') === $end->format('m-Y')) {
                $bulan_ini = $monthNames[$start->month] . " " . $start->year;
            } else {
                // Jika beda bulan → tampilkan dua bulan
                $bulan_ini =
                    $monthNames[$start->month] . " " . $start->year .
                    " - " .
                    $monthNames[$end->month] . " " . $end->year;
            }

        } else {
            // Default (original behavior)
            $bulan_ini = $monthNames[$selectedMonth] . " " . $selectedYear;
        }

        // ======================================================
        //  QUERY: TOTAL PER HARI PER STATUS (BERDASARKAN TANGGAL PENUH)
        // ======================================================
        $dailyData = Absensi::selectRaw('
                DATE(tanggal) as tanggal_full,
                SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alpha,
                SUM(CASE WHEN status = "Cuti" THEN 1 ELSE 0 END) as cuti
            ')
            ->when($tanggalFrom, fn($q) => $q->whereDate('tanggal', '>=', $tanggalFrom))
            ->when($tanggalTo, fn($q) => $q->whereDate('tanggal', '<=', $tanggalTo))
            ->when(!$tanggalFrom && !$tanggalTo, fn($q) => $q->whereMonth('tanggal', $selectedMonth)->whereYear('tanggal', $selectedYear))
            ->when($karyawan, fn($q) => $q->where('karyawan_id', $karyawan))
            ->groupBy('tanggal_full')
            ->orderBy('tanggal_full')
            ->get();

        // ======================================================
        //  SIAPKAN ARRAY CHART PER HARI (KEY = 'YYYY-MM-DD')
        //  - jika ada tanggal_from & tanggal_to gunakan CarbonPeriod
        //  - jika tidak, gunakan seluruh bulan yg dipilih
        // ======================================================
        $dailyChart = [];

        if ($tanggalFrom && $tanggalTo) {
            // pastikan format tanggal valid
            $period = CarbonPeriod::create($tanggalFrom, $tanggalTo);

            foreach ($period as $date) {
                $key = $date->format('Y-m-d');
                $dailyChart[$key] = [
                    'Hadir' => 0,
                    'Izin'  => 0,
                    'Sakit' => 0,
                    'Alpha' => 0,
                    'Cuti'  => 0,
                ];
            }
        } else {
            // seluruh hari dalam bulan yang dipilih
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = Carbon::create($selectedYear, $selectedMonth, $d)->format('Y-m-d');
                $dailyChart[$date] = [
                    'Hadir' => 0,
                    'Izin'  => 0,
                    'Sakit' => 0,
                    'Alpha' => 0,
                    'Cuti'  => 0,
                ];
            }
        }

        // ======================================================
        //  ISI DATA CHART DARI QUERY (cocokkan berdasarkan tanggal_full)
        // ======================================================
        foreach ($dailyData as $row) {
            $key = Carbon::parse($row->tanggal_full)->format('Y-m-d');
            if (isset($dailyChart[$key])) {
                $dailyChart[$key] = [
                    'Hadir' => (int) $row->hadir,
                    'Izin'  => (int) $row->izin,
                    'Sakit' => (int) $row->sakit,
                    'Alpha' => (int) $row->alpha,
                    'Cuti'  => (int) $row->cuti,
                ];
            } else {
                // Jika tanggal hasil query berada di luar period (kemungkinan kecil), tambahkan juga
                $dailyChart[$key] = [
                    'Hadir' => (int) $row->hadir,
                    'Izin'  => (int) $row->izin,
                    'Sakit' => (int) $row->sakit,
                    'Alpha' => (int) $row->alpha,
                    'Cuti'  => (int) $row->cuti,
                ];
            }
        }

        // OPTIONAL: pastikan urutan chronologis (terutama bila keys ditambahkan di luar loop)
        if (!empty($dailyChart)) {
            ksort($dailyChart);
        }




        // ======================================================
        //  GRAFIK PER BULAN (TOTAL SEMUA STATUS) — FIXED
        // ======================================================
        $statusList = ['Hadir', 'Izin', 'Sakit', 'Alpha', 'Cuti'];
        $chartData = [];

        foreach ($statusList as $status) {

            $rows = Absensi::query()
                ->when($tanggalFrom, fn($q) => $q->whereDate('tanggal', '>=', $tanggalFrom))
                ->when($tanggalTo, fn($q) => $q->whereDate('tanggal', '<=', $tanggalTo))
                ->when($karyawan, fn($q) => $q->where('karyawan_id', $karyawan))
                ->where('status', $status)
                ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $arr = [];
            for ($i = 1; $i <= 12; $i++) {
                $found = $rows->firstWhere('bulan', $i);
                $arr[] = $found ? $found->total : 0;
            }

            $chartData[$status] = $arr;
        }


        $karyawanList = Karyawan::all();

        return view('absensi.dashboard', compact(
            'summary',
            'todayAbsensi',
            'chartData',
            'dailyChart',
            'bulan_ini',
            'karyawanList'
        ));
    }

    public function create()
    {
        $karyawans = Karyawan::all();
        return view('absensi.create', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required'
        ]);

        Absensi::create($request->all());

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = Absensi::findOrFail($id);
        $karyawans = Karyawan::all();

        return view('absensi.edit', compact('data', 'karyawans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'karyawan_id' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required'
        ]);

        $data = Absensi::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diupdate!');
    }

    public function destroy($id)
    {
        Absensi::findOrFail($id)->delete();
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus!');
    }
}

