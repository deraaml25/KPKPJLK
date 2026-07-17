<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenataanDesa extends Model
{
    protected $table = 'penataan_desas';

    protected $fillable = [
        'desa_id',
        'tipe',
        'nama_wilayah_baru',
        'jumlah_penduduk',
        'jumlah_kk',
        'proposal_path',
        'peta_geojson_path',
        'rekomendasi_dinas_path',
        'status',
        'status_evaluasi_tahun'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
