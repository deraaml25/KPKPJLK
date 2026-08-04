<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuanBpd extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function alasanPemberhentian()
    {
        return $this->belongsTo(AlasanPemberhentian::class);
    }

    public function pesertas()
    {
        return $this->hasMany(AjuanBpdPeserta::class);
    }

    public function checklists()
    {
        return $this->hasMany(ChecklistAjuanBpd::class);
    }

    public function milestones()
    {
        return $this->hasMany(MilestoneAjuanBpd::class)->orderBy('created_at');
    }
}
