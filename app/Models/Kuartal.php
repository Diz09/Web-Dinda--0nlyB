<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuartal extends Model
{
    protected $fillable = ['nama_kuartal', 'tanggal_mulai', 'tanggal_akhir', 'jumlah_ton'];

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


}
