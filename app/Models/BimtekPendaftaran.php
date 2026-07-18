<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BimtekPendaftaran extends Model
{
    protected $table = 'bimtek_pendaftarans';

    protected $fillable = [
        'bimtek_id',
        'user_id',
        'desa_id',
        'perangkat_desa_id',
        'status_presensi',
        'file_rtl',
        'catatan_revisi_rtl',
        'status_rtl',
    ];

    public function bimtek()
    {
        return $this->belongsTo(Bimtek::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function perangkatDesa()
    {
        return $this->belongsTo(PerangkatDesa::class);
    }
}
