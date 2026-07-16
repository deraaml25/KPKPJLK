<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistAjuan extends Model
{
    protected $fillable = [
        'ajuan_id', 'template_checklist_id', 'file_path', 'status', 'catatan', 'versi', 'updated_by'
    ];

    public function ajuan()
    {
        return $this->belongsTo(Ajuan::class);
    }

    public function templateChecklist()
    {
        return $this->belongsTo(TemplateChecklist::class);
    }

    public function logKekurangans()
    {
        return $this->hasMany(LogKekurangan::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
