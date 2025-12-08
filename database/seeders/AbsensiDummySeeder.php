<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;

class AbsensiDummySeeder extends Seeder
{
    public function run()
    {
        $karyawans = Karyawan::all();

        if ($karyawans->count() == 0) {
            echo "⚠ Tidak ada karyawan! Buat data karyawan dulu.\n";
            return;
        }

        $startDate = Carbon::now()->subMonths(2)->startOfMonth();
        $endDate   = Carbon::now();

        $statusList = ['Hadir', 'Izin', 'Sakit', 'Alpha'];

        while ($startDate <= $endDate) {

            // Lewati hari Minggu
            if ($startDate->dayOfWeek != Carbon::SUNDAY) {

                foreach ($karyawans as $karyawan) {

                    $status = $statusList[rand(0, 3)];

                    // Jika hadir → buat jam masuk/pulang
                    if ($status == 'Hadir') {
                        $jamMasuk  = Carbon::createFromTime(rand(7, 9), rand(0, 59));
                        $jamPulang = Carbon::createFromTime(rand(16, 18), rand(0, 59));
                    } else {
                        $jamMasuk = null;
                        $jamPulang = null;
                    }

                    Absensi::create([
                        'karyawan_id' => $karyawan->id,
                        'tanggal'     => $startDate->format('Y-m-d'),
                        'jam_masuk'   => $jamMasuk,
                        'jam_pulang'  => $jamPulang,
                        'status'      => $status
                    ]);

                }
            }

            $startDate->addDay();
        }
    }
}
