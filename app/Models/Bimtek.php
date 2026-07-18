<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bimtek extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'kuota',
        'tanggal_pelaksanaan',
        'tempat',
        'file_undangan',
        'file_materi',
        'file_sertifikat',
        'status',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(BimtekPendaftaran::class);
    }

    /**
     * Hitung sisa kuota secara dinamis (tidak perlu kolom terpisah).
     */
    public function getSisaKuotaAttribute(): int
    {
        return max(0, $this->kuota - $this->pendaftarans()->count());
    }

    /**
     * Apakah kuota masih tersedia?
     */
    public function kuotaTersedia(): bool
    {
        return $this->sisa_kuota > 0;
    }
}
