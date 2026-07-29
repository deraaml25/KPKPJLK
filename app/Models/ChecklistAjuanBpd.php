<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistAjuanBpd extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function templateChecklist()
    {
        return $this->belongsTo(TemplateChecklistBpd::class, 'template_checklist_bpd_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
