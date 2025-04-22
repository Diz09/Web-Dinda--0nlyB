<?php

// app/Models/TonIkan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TonIkan extends Model
{
    protected $fillable = ['tanggal', 'jumlah_ton'];
}
