<?php

namespace App\Models;

use App\Models\Scopes\TenantDesaScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(TenantDesaScope::class)]
class PjKades extends Model
{
    protected $table = 'pj_kades';

    protected $fillable = [
        'desa_id',
        'kategori',
        'alasan_pemberhentian_id',
        'alasan_nama',
        'no_registrasi',
        'keterangan_cuti',
        'nama_pns',
        'nip',
        'pangkat',
        'nama_plt',
        'nip_plt',
        'pangkat_plt',
        'surat_camat_path',
        'riwayat_hidup_path',
        'sk_pangkat_path',
        'status_bebas_hukdis',
        'sk_bupati_path',
        'folder_path',
        'tgl_diajukan',
        'tgl_mulai',
        'tgl_selesai',
        'status',
        'metode',
        'berkas_zip',
        'catatan_admin',
        'posisi_surat',
    ];

    protected $casts = [
        'tgl_diajukan' => 'date',
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function alasanPemberhentian()
    {
        return $this->belongsTo(AlasanPemberhentian::class, 'alasan_pemberhentian_id');
    }

    public function checklists()
    {
        return $this->hasMany(ChecklistPjKades::class, 'pj_kades_id')->orderBy('urutan');
    }

    /**
     * Hitung sisa hari masa jabatan.
     */
    public function getSisaHariAttribute(): ?int
    {
        if (! $this->tgl_selesai) {
            return null;
        }

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
