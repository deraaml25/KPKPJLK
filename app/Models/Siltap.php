<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siltap extends Model
{
    protected $fillable = [
        'desa_id',
        'bulan',
        'tahun',
        'rekomendasi_camat_path',
        'bukti_bpjs_path',
        'spj_path',
        'status',
        'notes',
        'sp2d_path'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
