<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipRekom extends Model
{
    protected $fillable = [
        'ajuan_id', 'no_surat_rekom', 'file_path', 'tgl_upload', 'uploaded_by',
    ];

    protected $casts = [
        'tgl_upload' => 'datetime',
    ];

    public function ajuan()
    {
        return $this->belongsTo(Ajuan::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
