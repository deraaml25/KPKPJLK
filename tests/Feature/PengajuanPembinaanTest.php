<?php

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\PengajuanPembinaan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = User::create([
        'name' => 'Administrator',
        'username' => 'admin',
        'password' => Hash::make('password'),
        'role' => 'super_admin',
    ]);

    $this->kecamatan = Kecamatan::create([
        'nama_kecamatan' => 'Kecamatan Makmur',
    ]);

    $this->desaModel1 = Desa::create([
        'nama_desa' => 'Desa Makmur',
        'kecamatan_id' => $this->kecamatan->id,
    ]);

    $this->desaUser1 = User::create([
        'name' => 'Operator Desa Makmur',
        'username' => 'desa_makmur',
        'password' => Hash::make('password'),
        'role' => 'desa',
        'desa_id' => $this->desaModel1->id,
    ]);

    $this->desaModel2 = Desa::create([
        'nama_desa' => 'Desa Sejahtera',
        'kecamatan_id' => $this->kecamatan->id,
    ]);

    $this->desaUser2 = User::create([
        'name' => 'Operator Desa Sejahtera',
        'username' => 'desa_sejahtera',
        'password' => Hash::make('password'),
        'role' => 'desa',
        'desa_id' => $this->desaModel2->id,
    ]);
});

it('allows desa user to submit pengajuan pembinaan and upload files', function () {
    Storage::fake('public');

    $suratFile = UploadedFile::fake()->create('surat.pdf', 100);
    $undanganFile = UploadedFile::fake()->create('undangan.pdf', 100);

    $response = $this->actingAs($this->desaUser1)->post(route('desa.pengajuan-pembinaan.store'), [
        'judul_kegiatan' => 'Pembinaan Karang Taruna',
        'deskripsi' => 'Pengajuan pembinaan karang taruna tingkat desa...',
        'tanggal_diajukan' => '2026-08-01',
        'file_surat_permohonan' => $suratFile,
        'file_undangan' => $undanganFile,
    ]);

    $response->assertRedirect(route('desa.pengajuan-pembinaan.index'));

    $this->assertDatabaseHas('pengajuan_pembinaans', [
        'desa_id' => $this->desaModel1->id,
        'judul_kegiatan' => 'Pembinaan Karang Taruna',
        'status' => 'menunggu',
    ]);

    $pengajuan = PengajuanPembinaan::first();
    expect($pengajuan->file_surat_permohonan)->not->toBeNull();
    expect($pengajuan->file_undangan)->not->toBeNull();

    // Verify storage
    Storage::disk('public')->assertExists($pengajuan->file_surat_permohonan);
    Storage::disk('public')->assertExists($pengajuan->file_undangan);
});

it('allows desa user to view their own pengajuan but not others', function () {
    $pengajuan1 = PengajuanPembinaan::create([
        'desa_id' => $this->desaModel1->id,
        'user_id' => $this->desaUser1->id,
        'judul_kegiatan' => 'Pembinaan 1',
        'tanggal_diajukan' => '2026-08-01',
        'status' => 'menunggu',
    ]);

    $pengajuan2 = PengajuanPembinaan::create([
        'desa_id' => $this->desaModel2->id,
        'user_id' => $this->desaUser2->id,
        'judul_kegiatan' => 'Pembinaan 2',
        'tanggal_diajukan' => '2026-08-01',
        'status' => 'menunggu',
    ]);

    // View own
    $this->actingAs($this->desaUser1)->get(route('desa.pengajuan-pembinaan.show', $pengajuan1))
        ->assertStatus(200);

    // View other's
    $this->actingAs($this->desaUser1)->get(route('desa.pengajuan-pembinaan.show', $pengajuan2))
        ->assertStatus(403);
});

it('allows admin to manage and reply to pengajuans', function () {
    $pengajuan = PengajuanPembinaan::create([
        'desa_id' => $this->desaModel1->id,
        'user_id' => $this->desaUser1->id,
        'judul_kegiatan' => 'Pembinaan Kearsipan',
        'tanggal_diajukan' => '2026-08-01',
        'status' => 'menunggu',
    ]);

    // Admin index & show
    $this->actingAs($this->admin)->get(route('admin.pengajuan-pembinaan.index'))
        ->assertStatus(200)
        ->assertSee('Pembinaan Kearsipan');

    $this->actingAs($this->admin)->get(route('admin.pengajuan-pembinaan.show', $pengajuan))
        ->assertStatus(200)
        ->assertSee('Pembinaan Kearsipan');

    // Admin replies
    $response = $this->actingAs($this->admin)->post(route('admin.pengajuan-pembinaan.balas', $pengajuan), [
        'status' => 'disetujui',
        'catatan_admin' => 'Telah disetujui. Narasumber akan hadir tanggal 1 Agustus.',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $this->assertDatabaseHas('pengajuan_pembinaans', [
        'id' => $pengajuan->id,
        'status' => 'disetujui',
        'catatan_admin' => 'Telah disetujui. Narasumber akan hadir tanggal 1 Agustus.',
    ]);

    // Desa user sees the reply
    $this->actingAs($this->desaUser1)->get(route('desa.pengajuan-pembinaan.show', $pengajuan))
        ->assertStatus(200)
        ->assertSee('Telah disetujui. Narasumber akan hadir tanggal 1 Agustus.');
});
