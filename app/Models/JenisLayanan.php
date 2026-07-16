<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
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
