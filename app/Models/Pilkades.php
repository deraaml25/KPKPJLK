<?php

namespace App\Models;

use App\Models\Scopes\TenantDesaScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(TenantDesaScope::class)]
class Pilkades extends Model
{
    protected $fillable = [
        'desa_id',
        'tanggal_pemungutan',
        'total_tps',
        'total_dpt',
        'calon_1_nama',
        'calon_2_nama',
        'calon_3_nama',
        'berita_acara_path',
        'pemenang_nama',
        'sk_bupati_path',
        'disahkan_oleh',
        'disahkan_at',
        'status',
    ];

    protected $casts = [
        'tanggal_pemungutan' => 'date',
        'disahkan_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function suaras()
    {
        return $this->hasMany(PilkadesSuara::class);
    }

    public function pengesah()
    {
        return $this->belongsTo(User::class, 'disahkan_oleh');
    }

    // ── Quick Count Helpers ──────────────────────────────────────────────────

    public function getTotalSuaraCalon1Attribute(): int
    {
        return $this->suaras->sum('suara_calon_1');
    }

    public function getTotalSuaraCalon2Attribute(): int
    {
        return $this->suaras->sum('suara_calon_2');
    }

    public function getTotalSuaraCalon3Attribute(): int
    {
        return $this->suaras->sum('suara_calon_3');
    }

    public function getTotalSuaraSahAttribute(): int
    {
        return $this->suaras->sum('suara_sah');
    }

    public function getTotalPemilihHadirAttribute(): int
    {
        return $this->suaras->sum('total_pemilih_hadir');
    }

    /**
     * Menentukan nama pemenang berdasarkan akumulasi suara terbanyak.
     * Hanya memuatkan suaras jika belum.
     */
    public function getPemenangAttribute(): ?string
    {
        if ($this->suaras->isEmpty()) {
            return null;
        }

        $calons = [
            $this->calon_1_nama => $this->total_suara_calon_1,
            $this->calon_2_nama => $this->total_suara_calon_2,
            $this->calon_3_nama => $this->total_suara_calon_3,
        ];

        // Hanya hitung calon yang ada namanya
        $calons = array_filter($calons, fn ($name) => ! empty($name));
        if (empty($calons)) {
            return null;
        }

        arsort($calons);

        return array_key_first($calons);
    }

    /**
     * Berapa TPS yang sudah melaporkan suara?
     */
    public function getTpsLaporAttribute(): int
    {
        return $this->suaras->count();
    }

    /**
     * Apakah data pilkades terkunci (status sudah validated)?
     * Jika true, Desa tidak boleh mengedit/menambah data suara.
     */
    public function isLocked(): bool
    {
        return in_array($this->status, ['validated', 'selesai']);
    }
}
