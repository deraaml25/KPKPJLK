<?php

use App\Models\BimtekInformasi;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    $this->desaModel = Desa::create([
        'nama_desa' => 'Desa Makmur',
        'kecamatan_id' => $this->kecamatan->id,
    ]);

    $this->desaUser = User::create([
        'name' => 'Operator Desa Makmur',
        'username' => 'desa_makmur',
        'password' => Hash::make('password'),
        'role' => 'desa',
        'desa_id' => $this->desaModel->id,
    ]);
});

it('allows super admin to CRUD bimtek informasis', function () {
    // 1. Index
    $this->actingAs($this->admin)->get(route('admin.bimtek-informasi.index'))
        ->assertStatus(200);

    // 2. Create Form
    $this->actingAs($this->admin)->get(route('admin.bimtek-informasi.create'))
        ->assertStatus(200);

    // 3. Store
    $response = $this->actingAs($this->admin)->post(route('admin.bimtek-informasi.store'), [
        'judul' => 'Pembinaan Pengelolaan Keuangan',
        'konten' => 'Isi materi pembinaan keuangan...',
        'kategori' => 'informasi',
        'published_at' => now()->toDateTimeString(),
    ]);

    $response->assertRedirect(route('admin.bimtek-informasi.index'));
    $this->assertDatabaseHas('bimtek_informasis', [
        'judul' => 'Pembinaan Pengelolaan Keuangan',
    ]);

    $info = BimtekInformasi::first();

    // 4. Edit Form
    $this->actingAs($this->admin)->get(route('admin.bimtek-informasi.edit', $info))
        ->assertStatus(200);

    // 5. Update
    $response = $this->actingAs($this->admin)->put(route('admin.bimtek-informasi.update', $info), [
        'judul' => 'Pembinaan Pengelolaan Keuangan Updated',
        'konten' => 'Isi materi pembinaan keuangan updated...',
        'kategori' => 'dokumentasi',
        'published_at' => now()->toDateTimeString(),
    ]);

    $response->assertRedirect(route('admin.bimtek-informasi.index'));
    $this->assertDatabaseHas('bimtek_informasis', [
        'judul' => 'Pembinaan Pengelolaan Keuangan Updated',
        'kategori' => 'dokumentasi',
    ]);

    // 6. Delete
    $response = $this->actingAs($this->admin)->delete(route('admin.bimtek-informasi.destroy', $info));
    $response->assertRedirect(route('admin.bimtek-informasi.index'));
    $this->assertDatabaseMissing('bimtek_informasis', [
        'id' => $info->id,
    ]);
});

it('prevents desa user from managing bimtek informasis', function () {
    $this->actingAs($this->desaUser)->get(route('admin.bimtek-informasi.index'))
        ->assertStatus(403);

    $this->actingAs($this->desaUser)->post(route('admin.bimtek-informasi.store'), [])
        ->assertStatus(403);
});

it('shows published informasis to desa users on bimtek index', function () {
    BimtekInformasi::create([
        'judul' => 'Berita Pembinaan Hari Ini',
        'konten' => 'Isi berita pembinaan...',
        'kategori' => 'informasi',
        'published_at' => now()->subDay(),
    ]);

    BimtekInformasi::create([
        'judul' => 'Draft Berita Pembinaan',
        'konten' => 'Isi berita draft...',
        'kategori' => 'informasi',
        'published_at' => null,
    ]);

    $response = $this->actingAs($this->desaUser)->get(route('desa.bimtek.index'));
    $response->assertStatus(200);
    $response->assertSee('Berita Pembinaan Hari Ini');
    $response->assertDontSee('Draft Berita Pembinaan');
});
