<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinCalon extends Model
{
    protected $table = 'izin_calons';

    protected $fillable = [
        'desa_id',
        'nama_calon',
        'jabatan_sekarang',
        'jenis_calon',
        'berkas_syarat_path',
        'bebas_temuan_inspektorat_path',
        'status',
        'catatan_inspektorat'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
