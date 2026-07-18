<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siltap extends Model
{
    protected $fillable = [
        'desa_id',
        'bulan',
        'tahun',
        'jumlah_perangkat_aktif',
        'rekomendasi_camat_path',
        'bukti_bpjs_path',
        'spj_path',
        'status',
        'catatan_verifikator',
        'sp2d_path',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Auto-Lock: Cek apakah desa boleh mengajukan bulan ini.
     * Syarat: SPJ bulan sebelumnya harus sudah diajukan (bukan draft/ditolak).
     */
    public static function canSubmit(int $desaId, int $bulan, int $tahun): array
    {
        // Cek duplikasi: sudah pernah ajukan bulan ini?
        $exists = static::where('desa_id', $desaId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();

        if ($exists) {
            return ['allowed' => false, 'reason' => 'Desa sudah mengajukan pencairan Siltap untuk periode ini.'];
        }

        // Cek SPJ bulan sebelumnya
        $prevBulan = $bulan === 1 ? 12 : $bulan - 1;
        $prevTahun = $bulan === 1 ? $tahun - 1 : $tahun;

        // Bulan pertama (Januari tahun pertama) selalu diizinkan
        if ($bulan === 1 && $tahun <= 2026) {
            return ['allowed' => true, 'reason' => ''];
        }

        $prevSiltap = static::where('desa_id', $desaId)
            ->where('bulan', $prevBulan)
            ->where('tahun', $prevTahun)
            ->first();

        if (!$prevSiltap) {
            // Tidak ada pengajuan bulan lalu, mungkin belum pernah submit sama sekali — izinkan
            return ['allowed' => true, 'reason' => ''];
        }

        if ($prevSiltap->status === 'ditolak') {
            return ['allowed' => false, 'reason' => 'Pencairan bulan sebelumnya (' . $prevBulan . '/' . $prevTahun . ') berstatus DITOLAK. Perbaiki terlebih dahulu.'];
        }

        return ['allowed' => true, 'reason' => ''];
    }

    /**
     * Label bulan Indonesia.
     */
    public function getNamaBulanAttribute(): string
    {
        $bulanNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        return $bulanNames[$this->bulan] ?? '-';
    }
}
