<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlasanPemberhentian extends Model
{
    protected $fillable = ['nama'];

    public function templateChecklists()
    {
        return $this->hasMany(TemplateChecklist::class);
    }

    public function ajuans()
    {
        return $this->hasMany(Ajuan::class);
    }
}
