<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $fillable = ['kode'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
