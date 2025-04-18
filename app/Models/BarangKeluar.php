<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $fillable = ['barang_id', 'jumlah', 'tanggal'];
    protected $table = 'barang_keluar'; // <- ini penting

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
