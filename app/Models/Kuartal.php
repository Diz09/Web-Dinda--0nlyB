<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuartal extends Model
{
    protected $fillable = ['nama_kuartal'];

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    public function tonIkan()
    {
        return $this->hasMany(TonIkan::class);
    }



    public function getTanggalMulaiAttribute()
    {
        return $this->presensis()->orderBy('tanggal', 'asc')->value('tanggal');
    }

    public function getTanggalAkhirAttribute()
    {
        return $this->presensis()->orderBy('tanggal', 'desc')->value('tanggal');
    }

    public function getJumlahTonAttribute()
    {
        return $this->tonIkan()->value('jumlah_ton') ?? 0;
    }

}
