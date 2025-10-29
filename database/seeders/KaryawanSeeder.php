<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create(); // ✅ Tambahkan ini

        $departemen = ['HRD', 'Finance', 'IT', 'Marketing', 'Produksi', 'Operasional', 'Legal', 'Customer Service'];
        $jabatan = ['Staff', 'Supervisor', 'Manajer', 'Direktur', 'Koordinator', 'Admin', 'Engineer'];

        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $nama = $faker->name;
            $data[] = [
                'nip' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_lengkap' => $nama,
                'email' => Str::slug($nama, '.') . "@example.com",
                'no_hp' => '08' . rand(1000000000, 9999999999),
                'alamat' => $faker->address,
                'jabatan' => $jabatan[array_rand($jabatan)],
                'departemen' => $departemen[array_rand($departemen)],
                'tanggal_masuk' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'status_karyawan' => $faker->randomElement(['Tetap', 'Kontrak', 'Magang']),
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'tanggal_lahir' => $faker->dateTimeBetween('-40 years', '-22 years')->format('Y-m-d'),
                'tempat_lahir' => $faker->city,
                'gaji' => rand(4000000, 15000000),
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('karyawans')->insert($data);
    }
}
