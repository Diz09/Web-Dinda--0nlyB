<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = 'absen'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'jam_masuk',
        'jam_pulang',
        'total_jam',
        'gaji_per_jam',
        'gaji',
        'tanggal',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
