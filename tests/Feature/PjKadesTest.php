<?php

use App\Models\AlasanPemberhentian;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\PjKades;
use App\Models\User;
use Database\Seeders\PjKadesChecklistSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed(PjKadesChecklistSeeder::class);

    $this->kecamatan = Kecamatan::create(['nama_kecamatan' => 'Sumbang']);
    $this->desa = Desa::create(['nama_desa' => 'Karangendep', 'kecamatan_id' => $this->kecamatan->id]);

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

test('desa user can view pjkades index page', function () {
    $this->actingAs($this->userDesa)
        ->get(route('desa.pjkades.index'))
        ->assertStatus(200);
});

test('desa user can create Pj Kades usulan with automatic checklist generation', function () {
    $alasanMeninggal = AlasanPemberhentian::where('nama', 'Meninggal Dunia')->first();

    $response = $this->actingAs($this->userDesa)
        ->post(route('desa.pjkades.store'), [
            'kategori' => 'pj_kades',
            'alasan_pemberhentian_id' => $alasanMeninggal->id,
            'nama_pns' => 'Budi Santoso, S.STP',
            'nip' => '198501012010011001',
            'pangkat' => 'Penata / III c',
        ]);

    $pjKades = PjKades::withoutGlobalScopes()->where('desa_id', $this->desa->id)->first();
    expect($pjKades)->not->toBeNull();
    expect($pjKades->kategori)->toBe('pj_kades');
    expect($pjKades->checklists->count())->toBeGreaterThan(10);

    $response->assertRedirect(route('desa.pjkades.show', $pjKades->id));
});

test('desa user can create Plt Kades usulan with cuti sakit checklist', function () {
    $alasanSakit = AlasanPemberhentian::where('nama', 'Cuti Sakit')->first();

    $response = $this->actingAs($this->userDesa)
        ->post(route('desa.pjkades.store'), [
            'kategori' => 'plt_kades',
            'alasan_pemberhentian_id' => $alasanSakit->id,
            'nama_plt' => 'Siti Rahmawati, S.E.',
            'nip_plt' => '199002022015012002',
            'pangkat_plt' => 'Sekretaris Desa',
        ]);

    $pjKades = PjKades::withoutGlobalScopes()->where('desa_id', $this->desa->id)->first();
    expect($pjKades)->not->toBeNull();
    expect($pjKades->kategori)->toBe('plt_kades');
    expect($pjKades->checklists->count())->toBe(7);

    $response->assertRedirect(route('desa.pjkades.show', $pjKades->id));
});

test('desa user can upload checklist document', function () {
    Storage::fake('public');

    $alasanSakit = AlasanPemberhentian::where('nama', 'Cuti Sakit')->first();
    $this->actingAs($this->userDesa)
        ->post(route('desa.pjkades.store'), [
            'kategori' => 'plt_kades',
            'alasan_pemberhentian_id' => $alasanSakit->id,
            'nama_plt' => 'Siti Rahmawati, S.E.',
        ]);

    $pjKades = PjKades::withoutGlobalScopes()->where('desa_id', $this->desa->id)->first();
    $checklist = $pjKades->checklists->first();

    $file = UploadedFile::fake()->create('surat_dokter.pdf', 500, 'application/pdf');

    $response = $this->actingAs($this->userDesa)
        ->post(route('desa.pjkades.upload', [$pjKades->id, $checklist->id]), [
            'file_dokumen' => $file,
        ]);

    $response->assertSessionHas('success');
    $checklist->refresh();
    expect($checklist->file_path)->not->toBeNull();
});

test('admin can verify checklist document and generate SK', function () {
    Storage::fake('public');

    $alasanMeninggal = AlasanPemberhentian::where('nama', 'Meninggal Dunia')->first();
    $pjKades = PjKades::create([
        'desa_id' => $this->desa->id,
        'kategori' => 'pj_kades',
        'alasan_pemberhentian_id' => $alasanMeninggal->id,
        'alasan_nama' => 'Meninggal Dunia',
        'nama_pns' => 'Budi Santoso',
        'nip' => '198501012010011001',
        'pangkat' => 'III/c',
        'status' => 'submitted',
    ]);

    $skFile = UploadedFile::fake()->create('sk_bupati.pdf', 500, 'application/pdf');

    $response = $this->actingAs($this->userAdmin)
        ->post(route('admin.pjkades.generate-sk', $pjKades->id), [
            'status_bebas_hukdis' => 'clean',
            'sk_bupati' => $skFile,
            'tgl_mulai' => now()->toDateString(),
            'tgl_selesai' => now()->addMonths(6)->toDateString(),
        ]);

    $response->assertRedirect(route('admin.pjkades.show', $pjKades->id));
    $pjKades->refresh();
    expect($pjKades->status)->toBe('approved');
});
