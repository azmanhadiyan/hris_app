<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama_lengkap',
        'email',
        'no_hp',
        'alamat',
        'jabatan',
        'departemen',
        'tanggal_masuk',
        'status_karyawan',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'gaji',
        'foto',
    ];
}
