<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateChecklistBpd extends Model
{
    protected $guarded = [];

    public function alasanPemberhentian()
    {
        return $this->belongsTo(AlasanPemberhentian::class);
    }
}
