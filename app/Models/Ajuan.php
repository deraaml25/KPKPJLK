<?php

namespace App\Models;

use App\Models\Scopes\TenantDesaScope;
use Illuminate\Database\Eloquent\Model;

class Ajuan extends Model
{
    protected $fillable = [
        'no_registrasi',
        'desa_id',
        'jenis_layanan_id',
        'alasan_pemberhentian_id',
        'metode',
        'berkas_zip',
        'catatan_admin',
        'status',
        'folder_path',
        'tgl_diajukan',
        'tgl_sla_batas',
        'posisi_surat',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TenantDesaScope);
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

    public function pesertas()
    {
        return $this->hasMany(AjuanPeserta::class);
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
