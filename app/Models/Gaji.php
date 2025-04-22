<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $table = 'gaji'; // Pastikan ini cocok dengan nama tabel

    protected $fillable = [
        'presensi_id',
        'total_jam',
        'gaji_pokok',
        'total_gaji',
    ];
}
