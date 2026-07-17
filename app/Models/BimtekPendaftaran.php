<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BimtekPendaftaran extends Model
{
    protected $table = 'bimtek_pendaftarans';

    protected $fillable = [
        'bimtek_id',
        'user_id',
        'status_presensi',
        'file_rtl'
    ];

    public function bimtek()
    {
        return $this->belongsTo(Bimtek::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
