<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;

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
        $today = now()->toDateString();

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

        $todayAbsensi = $tableQuery->orderBy('tanggal')->get();


        // ======================================================
        //  GRAFIK PER HARI (1 bulan)
        // ======================================================

        // Tentukan bulan & tahun yang dipakai
        if ($bulan) {
            $selectedMonth = $bulan;
            $selectedYear  = now()->year;
        } elseif ($tanggalFrom) {
            $selectedMonth = Carbon::parse($tanggalFrom)->month;
            $selectedYear  = Carbon::parse($tanggalFrom)->year;
        } else {
            $selectedMonth = now()->month;
            $selectedYear  = now()->year;
        }

        // Nama bulan
        $monthNames = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
            7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];

        $bulan_ini = $monthNames[$selectedMonth] . " " . $selectedYear;

        // Ambil data per hari
        $dailyData = Absensi::selectRaw('DAY(tanggal) as hari, COUNT(*) as total')
            ->whereMonth('tanggal', $selectedMonth)
            ->whereYear('tanggal', $selectedYear)
            ->where('status',"Hadir")
            ->when($karyawan, fn($q) => $q->where('karyawan_id', $karyawan))
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        $dailyChart = [];

        // Jika tanggalFrom & tanggalTo ada → pakai range tanggal
        if ($tanggalFrom && $tanggalTo) {

            $startDay = Carbon::parse($tanggalFrom)->day;
            $endDay   = Carbon::parse($tanggalTo)->day;

            for ($i = $startDay; $i <= $endDay; $i++) {
                $dailyChart[$i] = 0;
            }

        } else {
            // Jika tidak → pakai 1 bulan penuh
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $dailyChart[$i] = 0;
            }
        }


        foreach ($dailyData as $row) {
            if (isset($dailyChart[$row->hari])) {
                $dailyChart[$row->hari] = $row->total;
            }
        }

        // ======================================================
        //  GRAFIK PER BULAN (12 bulan)
        // ======================================================
        $chartQuery = Absensi::query()
            ->when($tanggalFrom, fn($q) => $q->whereDate('tanggal', '>=', $tanggalFrom))
            ->when($tanggalTo, fn($q) => $q->whereDate('tanggal', '<=', $tanggalTo))
            ->when($karyawan, fn($q) => $q->where('karyawan_id', $karyawan));

        $rawChart = $chartQuery
            ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Lengkapi 12 bulan
        $chartData = collect([]);
        for ($i = 1; $i <= 12; $i++) {
            $found = $rawChart->firstWhere('bulan', $i);
            $chartData->push([
                'bulan' => $i,
                'total' => $found ? $found->total : 0
            ]);
        }


        // ======================================================
        //  LIST KARYAWAN
        // ======================================================
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

