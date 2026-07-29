<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuanBpdPeserta extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bpd()
    {
        return $this->belongsTo(Bpd::class, 'bpd_id');
    }

    public function ajuanBpd()
    {
        return $this->belongsTo(AjuanBpd::class);
    }
}
