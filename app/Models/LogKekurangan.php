<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogKekurangan extends Model
{
    protected $fillable = [
        'checklist_ajuan_id', 'status_lama', 'status_baru', 'catatan', 'tgl'
    ];

    protected $casts = [
        'tgl' => 'datetime',
    ];

    public function checklistAjuan()
    {
        return $this->belongsTo(ChecklistAjuan::class);
    }
}
