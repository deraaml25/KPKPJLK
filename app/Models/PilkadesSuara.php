<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilkadesSuara extends Model
{
    protected $table = 'pilkades_suaras';

    protected $fillable = [
        'pilkades_id',
        'tps_name',
        'suara_calon_1',
        'suara_calon_2',
        'suara_calon_3'
    ];

    public function pilkades()
    {
        return $this->belongsTo(Pilkades::class);
    }
}
