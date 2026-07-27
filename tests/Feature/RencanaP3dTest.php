<?php

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\RencanaP3d;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->kecamatan = Kecamatan::create([
        'nama_kecamatan' => 'Sumbang',
        'kode_kecamatan' => '0001',
    ]);

    $this->desa = Desa::create([
        'nama_desa' => 'Karangendep',
        'kecamatan_id' => $this->kecamatan->id,
        'kode_desa' => '0001',
    ]);

    $this->userDesa = User::create([
        'name' => 'Operator Desa',
        'username' => 'desa_karangendep',
        'password' => bcrypt('password'),
        'role' => 'desa',
        'desa_id' => $this->desa->id,
    ]);

    $this->userAdmin = User::create([
        'name' => 'Admin Dinpermasdes',
        'username' => 'admin_dinper',
        'password' => bcrypt('password'),
        'role' => 'super_admin',
    ]);
});

test('desa user can view p3d index page', function () {
    $this->actingAs($this->userDesa)
        ->get(route('desa.rencana-p3d.index'))
        ->assertSuccessful();
});

test('desa user can create P3D recommendation', function () {
    $response = $this->actingAs($this->userDesa)
        ->post(route('desa.rencana-p3d.store'), [
            'jumlah_formasi_kosong' => 2,
            'jabatan_kosong' => 'Kasi Pemerintahan, Kaur TU & Umum',
            'rencana_pelaksanaan' => '2026-11-15',
            'rencana_anggaran' => 15000000,
            'keterangan' => 'Rencana anggaran dari APBDes',
        ]);

    $p3d = RencanaP3d::first();
    expect($p3d)->not->toBeNull();
    expect($p3d->desa_id)->toBe($this->desa->id);
    expect($p3d->jumlah_formasi_kosong)->toBe(2);
    expect($p3d->status)->toBe('dikirim');
    expect($p3d->tahun)->toBe(2026);

    $response->assertRedirect(route('desa.rencana-p3d.index'));
});

test('desa user can edit and update P3D recommendation', function () {
    $p3d = RencanaP3d::create([
        'desa_id' => $this->desa->id,
        'kecamatan_id' => $this->kecamatan->id,
        'jumlah_formasi_kosong' => 1,
        'jabatan_kosong' => 'Kaur Keuangan',
        'rencana_pelaksanaan' => '2026-10-10',
        'rencana_anggaran' => 5000000,
        'keterangan' => 'Sebelum APBDes Perubahan',
        'status' => 'dikirim',
        'tahun' => 2026,
    ]);

    $response = $this->actingAs($this->userDesa)
        ->put(route('desa.rencana-p3d.update', $p3d->id), [
            'jumlah_formasi_kosong' => 2,
            'jabatan_kosong' => 'Kaur Keuangan & Kasi Pelayanan',
            'rencana_pelaksanaan' => '2026-12-01',
            'rencana_anggaran' => 10000000,
            'keterangan' => 'Disesuaikan dengan dana transfer',
        ]);

    $p3d->refresh();
    expect($p3d->jumlah_formasi_kosong)->toBe(2);
    expect($p3d->rencana_anggaran)->toEqual('10000000.00');
    expect($p3d->tahun)->toBe(2026);

    $response->assertRedirect(route('desa.rencana-p3d.index'));
});

test('desa user can delete P3D recommendation', function () {
    $p3d = RencanaP3d::create([
        'desa_id' => $this->desa->id,
        'kecamatan_id' => $this->kecamatan->id,
        'jumlah_formasi_kosong' => 1,
        'jabatan_kosong' => 'Kaur Keuangan',
        'rencana_pelaksanaan' => '2026-10-10',
        'rencana_anggaran' => 5000000,
        'status' => 'dikirim',
        'tahun' => 2026,
    ]);

    $response = $this->actingAs($this->userDesa)
        ->delete(route('desa.rencana-p3d.destroy', $p3d->id));

    expect(RencanaP3d::count())->toBe(0);
    $response->assertRedirect(route('desa.rencana-p3d.index'));
});

test('admin can view P3D rekapitulasi, search and filter', function () {
    $kecamatan2 = Kecamatan::create(['nama_kecamatan' => 'Baturraden', 'kode_kecamatan' => '0002']);
    $desa2 = Desa::create(['nama_desa' => 'Kemutug Lor', 'kecamatan_id' => $kecamatan2->id, 'kode_desa' => '0002']);

    RencanaP3d::create([
        'desa_id' => $this->desa->id,
        'kecamatan_id' => $this->kecamatan->id,
        'jumlah_formasi_kosong' => 2,
        'jabatan_kosong' => 'Kasi Pemerintahan',
        'rencana_pelaksanaan' => '2026-11-15',
        'rencana_anggaran' => 15000000,
        'status' => 'dikirim',
    ]);

    RencanaP3d::create([
        'desa_id' => $desa2->id,
        'kecamatan_id' => $kecamatan2->id,
        'jumlah_formasi_kosong' => 1,
        'jabatan_kosong' => 'Kaur Keuangan',
        'rencana_pelaksanaan' => '2026-12-10',
        'rencana_anggaran' => 10000000,
        'status' => 'dikirim',
    ]);

    // Admin view all
    $response = $this->actingAs($this->userAdmin)
        ->get(route('admin.rencana-p3d.index'));
    $response->assertSuccessful();
    $response->assertSee('Desa Karangendep');
    $response->assertSee('Desa Kemutug Lor');

    // Admin filter by Kecamatan
    $responseFilter = $this->actingAs($this->userAdmin)
        ->get(route('admin.rencana-p3d.index', ['kecamatan_id' => $this->kecamatan->id]));
    $responseFilter->assertSuccessful();
    $responseFilter->assertSee('Desa Karangendep');
    $responseFilter->assertDontSee('Desa Kemutug Lor');

    // Admin search by Desa name
    $responseSearch = $this->actingAs($this->userAdmin)
        ->get(route('admin.rencana-p3d.index', ['search' => 'Kemutug']));
    $responseSearch->assertSuccessful();
    $responseSearch->assertDontSee('Desa Karangendep');
    $responseSearch->assertSee('Desa Kemutug Lor');
});

test('admin can view P3D recommendation detail', function () {
    $p3d = RencanaP3d::create([
        'desa_id' => $this->desa->id,
        'kecamatan_id' => $this->kecamatan->id,
        'jumlah_formasi_kosong' => 2,
        'jabatan_kosong' => 'Kasi Pemerintahan',
        'rencana_pelaksanaan' => '2026-11-15',
        'rencana_anggaran' => 15000000,
        'status' => 'dikirim',
    ]);

    $this->actingAs($this->userAdmin)
        ->get(route('admin.rencana-p3d.show', $p3d->id))
        ->assertSuccessful()
        ->assertSee('Desa Karangendep')
        ->assertSee('Kasi Pemerintahan');
});

test('admin can update P3D recommendation status', function () {
    $p3d = RencanaP3d::create([
        'desa_id' => $this->desa->id,
        'kecamatan_id' => $this->kecamatan->id,
        'jumlah_formasi_kosong' => 2,
        'jabatan_kosong' => 'Kasi Pemerintahan',
        'rencana_pelaksanaan' => '2026-11-15',
        'rencana_anggaran' => 15000000,
        'status' => 'dikirim',
    ]);

    $response = $this->actingAs($this->userAdmin)
        ->post(route('admin.rencana-p3d.update-status', $p3d->id), [
            'status' => 'disetujui',
        ]);

    $p3d->refresh();
    expect($p3d->status)->toBe('disetujui');

    $response->assertRedirect(route('admin.rencana-p3d.index'));
});
