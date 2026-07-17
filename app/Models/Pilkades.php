<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pilkades extends Model
{
    protected $fillable = [
        'desa_id',
        'tanggal_pemungutan',
        'status',
        'total_tps',
        'pemenang_nama',
        'sk_bupati_path'
    ];

    protected $casts = [
        'tanggal_pemungutan' => 'date',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function suaras()
    {
        return $this->hasMany(PilkadesSuara::class);
    }
}
