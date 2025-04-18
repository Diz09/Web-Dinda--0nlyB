<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi plural dari nama model
    protected $table = 'pengeluarans'; // Sesuaikan dengan nama tabel di database

    // Tentukan kolom-kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'deskripsi',
        'jumlah',
        'tanggal', // atau 'created_at' jika kamu menggunakan kolom default
    ];

    // Jika tabel menggunakan timestamp otomatis, pastikan ini diset ke true
    public $timestamps = true;
}
