<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BimtekInformasi extends Model
{
    protected $table = 'bimtek_informasis';

    protected $fillable = [
        'judul',
        'konten',
        'foto',
        'file_lampiran',
        'kategori',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'foto' => 'array',
    ];

    /**
     * Apakah informasi sudah dipublikasikan?
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->lte(now());
    }

    /**
     * Scope: hanya yang sudah dipublikasikan.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
