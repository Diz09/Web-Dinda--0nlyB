<?php
// app/Models/Karyawan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jabatan',
        'gaji_per_jam',
    ];

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
?>