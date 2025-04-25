<?php

// app/Models/TonIkan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TonIkan extends Model
{
    protected $fillable = [
        // 'tanggal',
        'kuartal_id',
        'jumlah_ton', 
        'harga_ikan_per_ton'
    ];

    public function kuartal()
    {
        return $this->belongsTo(Kuartal::class);
    }

}
