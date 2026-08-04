<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilestoneTracking extends Model
{
    protected $fillable = [
        'ajuan_id', 'tahap', 'tgl_mulai', 'tgl_selesai', 'catatan', 'updated_by',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    public function ajuan()
    {
        return $this->belongsTo(Ajuan::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
