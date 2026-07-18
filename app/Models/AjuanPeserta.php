<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuanPeserta extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the ajuan that owns the peserta.
     */
    public function ajuan()
    {
        return $this->belongsTo(Ajuan::class);
    }

    /**
     * Get the perangkat desa profile associated with this peserta.
     */
    public function perangkatDesa()
    {
        return $this->belongsTo(PerangkatDesa::class);
    }
}
