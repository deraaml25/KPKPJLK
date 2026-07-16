<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatDesa extends Model
{
    protected $fillable = [
        'desa_id', 'nama', 'jabatan', 'no_sk_terakhir', 'tgl_mulai_jabatan', 'status_aktif'
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'tgl_mulai_jabatan' => 'date',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function ajuans()
    {
        return $this->hasMany(Ajuan::class);
    }
}
