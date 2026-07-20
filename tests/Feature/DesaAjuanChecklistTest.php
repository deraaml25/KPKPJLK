<?php

use App\Http\Controllers\Desa\AjuanController;
use App\Models\Ajuan;
use App\Models\Desa;
use App\Models\JenisLayanan;
use App\Models\Kecamatan;
use App\Models\TemplateChecklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('desa ajuan show auto-populates missing checklist rows from templates', function () {
    $kecamatan = Kecamatan::create([
        'nama_kecamatan' => 'Kecamatan Uji',
        'kode_kecamatan' => '0001',
    ]);

    $desa = Desa::create([
        'kecamatan_id' => $kecamatan->id,
        'nama_desa' => 'Desa Uji',
        'kode_desa' => '0001',
    ]);

    $user = User::create([
        'name' => 'Desa User',
        'username' => 'desa_uji',
        'password' => bcrypt('password'),
        'role' => 'desa',
        'desa_id' => $desa->id,
    ]);

    $jenisLayanan = JenisLayanan::create([
        'nama' => 'Pengangkatan',
        'kode' => 'PKT',
    ]);

    TemplateChecklist::create([
        'jenis_layanan_id' => $jenisLayanan->id,
        'nama_dokumen' => 'Surat Pengantar',
        'wajib' => true,
        'urutan' => 1,
    ]);

    $ajuan = Ajuan::create([
        'no_registrasi' => 'PGKT/2026/01/0001',
        'desa_id' => $desa->id,
        'jenis_layanan_id' => $jenisLayanan->id,
        'status' => 'draft',
        'folder_path' => 'dokumen/uji',
        'tgl_diajukan' => now()->toDateString(),
        'tgl_sla_batas' => now()->toDateString(),
    ]);

    $this->actingAs($user);

    $response = app(AjuanController::class)->show($ajuan);

    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($ajuan->fresh()->checklistAjuans()->count())->toBe(1);
    expect($ajuan->fresh()->checklistAjuans()->first()->templateChecklist->nama_dokumen)->toBe('Surat Pengantar');
});
