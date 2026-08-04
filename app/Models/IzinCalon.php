<?php

namespace App\Models;

use App\Models\Scopes\TenantDesaScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(TenantDesaScope::class)]
class IzinCalon extends Model
{
    protected $table = 'izin_calons';

    protected $fillable = [
        'desa_id',
        'nama_calon',
        'jabatan_sekarang',
        'jenis_calon',
        'surat_permohonan_path',
        'berkas_syarat_path',
        'surat_pengunduran_diri_path',
        'tgl_cuti_mulai',
        'tgl_cuti_selesai',
        'has_temuan_inspektorat',
        'bebas_temuan_inspektorat_path',
        'catatan_inspektorat',
        'surat_izin_bupati_path',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'tgl_cuti_mulai' => 'date',
        'tgl_cuti_selesai' => 'date',
        'has_temuan_inspektorat' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ── Business Logic ──────────────────────────────────────────────────────

    /**
     * Apakah calon ini memiliki temuan Inspektorat yang belum diselesaikan?
     * Jika true, tombol Approve di Admin harus dikunci (gatekeeper).
     */
    public function hasTemuanInspektorat(): bool
    {
        return (bool) $this->has_temuan_inspektorat;
    }

    /**
     * Label jenis calon dalam Bahasa Indonesia.
     */
    public function getLabelJenisCalonAttribute(): string
    {
        return match ($this->jenis_calon) {
            'kades' => 'Kades Petahana',
            'perangkat' => 'Perangkat Desa',
            'pns' => 'Aparatur Sipil Negara (PNS)',
            default => ucfirst($this->jenis_calon),
        };
    }
}
