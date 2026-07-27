<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistPjKades extends Model
{
    protected $table = 'checklist_pj_kades';

    protected $fillable = [
        'pj_kades_id',
        'template_checklist_id',
        'nama_dokumen',
        'wajib',
        'urutan',
        'file_path',
        'status_verifikasi',
        'catatan_revisi',
        'tgl_diunggah',
    ];

    protected $casts = [
        'wajib' => 'boolean',
        'tgl_diunggah' => 'datetime',
    ];

    public function pjKades()
    {
        return $this->belongsTo(PjKades::class, 'pj_kades_id');
    }

    public function templateChecklist()
    {
        return $this->belongsTo(TemplateChecklist::class, 'template_checklist_id');
    }
}
