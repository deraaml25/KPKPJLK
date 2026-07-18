<?php

namespace App\Models;

use App\Models\Scopes\TenantDesaScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

#[ScopedBy(TenantDesaScope::class)]
class PjKades extends Model
{
    protected $table = 'pj_kades';

    protected $fillable = [
        'desa_id',
        'nama_pns',
        'nip',
        'pangkat',
        'surat_camat_path',
        'riwayat_hidup_path',
        'sk_pangkat_path',
        'status_bebas_hukdis',
        'sk_bupati_path',
        'tgl_mulai',
        'tgl_selesai',
        'status',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Hitung sisa hari masa jabatan.
     */
    public function getSisaHariAttribute(): ?int
    {
        if (!$this->tgl_selesai)
            return null;
        return (int) now()->startOfDay()->diffInDays($this->tgl_selesai, false);
    }

    /**
     * Apakah masa jabatan akan habis dalam 30 hari?
     */
    public function getHampirBerakhirAttribute(): bool
    {
        return $this->sisa_hari !== null && $this->sisa_hari <= 30 && $this->sisa_hari >= 0;
    }

    /**
     * Apakah sudah melewati batas akhir?
     */
    public function getSudahBerakhirAttribute(): bool
    {
        return $this->sisa_hari !== null && $this->sisa_hari < 0;
    }

    /**
     * Scope: hanya Pj Kades yang berstatus aktif (approved).
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'approved');
    }
}
