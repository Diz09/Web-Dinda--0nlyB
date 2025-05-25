<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/HistoryGajiKloter.php
class HistoryGajiKloter extends Model
{
    protected $table = 'history_gaji_kloter';

    protected $fillable = [
        'kloter_id', 'jml_karyawan', 'total_gaji', 'waktu'
    ];

    public function kloter()
    {
        return $this->belongsTo(Kloter::class);
    }
}
