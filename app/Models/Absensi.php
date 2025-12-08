<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}

