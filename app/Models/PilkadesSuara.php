<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilkadesSuara extends Model
{
    protected $table = 'pilkades_suaras';

    protected $fillable = [
        'pilkades_id',
        'tps_name',
        'total_pemilih_hadir',
        'suara_sah',
        'suara_tidak_sah',
        'suara_calon_1',
        'suara_calon_2',
        'suara_calon_3',
        'is_locked',
        'input_by',
        'ip_address',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    public function pilkades()
    {
        return $this->belongsTo(Pilkades::class);
    }

    public function inputter()
    {
        return $this->belongsTo(\App\Models\User::class, 'input_by');
    }

    /**
     * Validasi integritas suara:
     * suara_sah + suara_tidak_sah harus = total_pemilih_hadir
     */
    public function isValid(): bool
    {
        return ($this->suara_sah + $this->suara_tidak_sah) === $this->total_pemilih_hadir;
    }

    /**
     * Validasi calon: total suara calon (1+2+3) harus = suara_sah
     */
    public function isCalalonValid(): bool
    {
        return ($this->suara_calon_1 + $this->suara_calon_2 + $this->suara_calon_3) === $this->suara_sah;
    }
}
