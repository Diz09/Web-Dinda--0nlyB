<?php
// app/Models/Presensi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'kuartal_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function gaji()
    {
        return $this->hasOne(Gaji::class);
    }

    public function kuartal()
    {
        return $this->belongsTo(Kuartal::class);
    }

}
