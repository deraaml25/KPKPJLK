<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateChecklist extends Model
{
    protected $fillable = [
        'jenis_layanan_id', 'alasan_pemberhentian_id', 'nama_dokumen', 'wajib', 'urutan'
    ];

    protected $casts = [
        'wajib' => 'boolean',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class);
    }

    public function alasanPemberhentian()
    {
        return $this->belongsTo(AlasanPemberhentian::class);
    }
}
