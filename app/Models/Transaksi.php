<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = 
        [
            'barang_id',
            'supplier_id',
            'kategori', 
            'waktu_transaksi'
        ];

    public function barang()
    {
        return $this->belongsTo(BarangProduk::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function pemasukan()
    {
        return $this->hasOne(Pemasukan::class);
    }

    public function pengeluaran()
    {
        return $this->hasOne(Pengeluaran::class);
    }
}
