<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bimtek extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'kuota',
        'sisa_kuota',
        'tanggal_pelaksanaan',
        'file_materi',
        'tempat'
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(BimtekPendaftaran::class);
    }
}
