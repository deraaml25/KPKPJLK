<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regulasi extends Model
{
    protected $fillable = [
        'no_regulasi',
        'judul',
        'deskripsi',
        'tipe',
        'file_path',
        'status',
        'desa_id',
        'catatan_revisi',
        'tgl_diajukan',
        'tgl_disahkan'
    ];

    protected $casts = [
        'tgl_diajukan' => 'date',
        'tgl_disahkan' => 'date',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
