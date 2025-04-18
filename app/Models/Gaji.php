<?php
// app/Models/Gaji.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'presensi_id',
        'total_jam',
        'jam_lembur',
        'gaji_pokok',
        'gaji_lembur',
        'total_gaji',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }
}
