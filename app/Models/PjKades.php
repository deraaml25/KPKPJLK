<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjKades extends Model
{
    protected $table = 'pj_kades';

    protected $fillable = [
        'desa_id',
        'nama_pns',
        'nip',
        'pangkat',
        'riwayat_hidup_path',
        'sk_pangkat_path',
        'status_bebas_hukdis',
        'sk_bupati_path',
        'tgl_mulai',
        'tgl_selesai',
        'status'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
