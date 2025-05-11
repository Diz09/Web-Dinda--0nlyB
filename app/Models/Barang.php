<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'qty',
        'exp',
        'harga',
    ];
    
    public function produk()
    {
        return $this->hasOne(BarangProduk::class);
    }

    public function pendukung()
    {
        return $this->hasOne(BarangPendukung::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
