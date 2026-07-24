<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bpd extends Model
{
    protected $fillable = [
        'desa_id',
        'nama',
        'jabatan',
        'no_sk_terakhir',
        'tgl_mulai_jabatan',
        'status_aktif'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\TenantDesaScope);
    }

    protected $casts = [
        'status_aktif' => 'boolean',
        'tgl_mulai_jabatan' => 'date',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
