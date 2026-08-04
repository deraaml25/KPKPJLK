<?php

namespace App\Models;

use App\Models\Scopes\TenantDesaScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(TenantDesaScope::class)]
class PenataanDesa extends Model
{
    protected $fillable = [
        'desa_id',
        'jumlah_penduduk',
        'jumlah_kk',
        'luas_wilayah_km2',
        'peta_geospasial_path',
        'perbup_persiapan_path',
        'status',
        'alasan_penolakan',
        'tgl_mulai_persiapan',
        'tgl_batas_persiapan',
        'kode_desa_kemendagri',
        'diproses_oleh',
        'diproses_at',
    ];

    protected $casts = [
        'tgl_mulai_persiapan' => 'date',
        'tgl_batas_persiapan' => 'date',
        'diproses_at' => 'datetime',
        'luas_wilayah_km2' => 'decimal:2',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function prosesor()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    // ── Kalkulator UU Desa ──────────────────────────────────────────────────

    /**
     * Syarat pemekaran desa Pulau Jawa sesuai UU Desa No. 6 Tahun 2014 & turunannya.
     * Sebagai contoh, minimal penduduk 6000 jiwa ATAU 1200 KK.
     *
     * @return array ['is_valid' => bool, 'messages' => array]
     */
    public function runKalkulatorUU(): array
    {
        $messages = [];
        $isValid = true;

        if ($this->jumlah_penduduk < 6000) {
            $messages[] = 'Jumlah penduduk ('.number_format($this->jumlah_penduduk).') kurang dari batas minimal UU (6.000 jiwa).';
            $isValid = false;
        }

        if ($this->jumlah_kk < 1200) {
            $messages[] = 'Jumlah Kepala Keluarga ('.number_format($this->jumlah_kk).') kurang dari batas minimal UU (1.200 KK).';
            $isValid = false;
        }

        if ($this->luas_wilayah_km2 < 3.0) { // contoh asumsi batas min
            $messages[] = 'Luas wilayah ('.$this->luas_wilayah_km2.' km²) di bawah batas ambang kelayakan otonomi desa baru.';
            $isValid = false;
        }

        return [
            'is_valid' => $isValid,
            'messages' => $messages,
        ];
    }

    // ── Helper Timeline Desa Persiapan ───────────────────────────────────────

    public function sisaHariPersiapan(): int
    {
        if (! $this->tgl_batas_persiapan) {
            return 0;
        }

        $sisa = Carbon::now()->diffInDays($this->tgl_batas_persiapan, false);

        return $sisa > 0 ? (int) $sisa : 0;
    }

    public function isHampirBatasPersiapan(): bool
    {
        $sisa = $this->sisaHariPersiapan();

        return $sisa > 0 && $sisa <= 180; // 6 bulan terakhir
    }
}
