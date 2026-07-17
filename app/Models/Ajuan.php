<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ajuan extends Model
{
    protected $fillable = [
        'no_registrasi',
        'desa_id',
        'jenis_layanan_id',
        'alasan_pemberhentian_id',
        'perangkat_desa_id',
        'status',
        'folder_path',
        'tgl_diajukan',
        'tgl_sla_batas'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\TenantDesaScope);
    }

    protected $casts = [
        'tgl_diajukan' => 'date',
        'tgl_sla_batas' => 'date',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class);
    }

    public function alasanPemberhentian()
    {
        return $this->belongsTo(AlasanPemberhentian::class);
    }

    public function perangkatDesa()
    {
        return $this->belongsTo(PerangkatDesa::class);
    }

    public function checklistAjuans()
    {
        return $this->hasMany(ChecklistAjuan::class);
    }

    public function milestoneTrackings()
    {
        return $this->hasMany(MilestoneTracking::class);
    }

    public function arsipRekom()
    {
        return $this->hasOne(ArsipRekom::class);
    }
}
