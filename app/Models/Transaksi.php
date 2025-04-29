<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'barang_id',
        'supplier_id',
        'pengeluaran_id',
        'pemasukan_id',
        'jumlahRp',
        'waktu_transaksi',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class);
    }

    public function pemasukan()
    {
        return $this->belongsTo(Pemasukan::class);
    }
}
