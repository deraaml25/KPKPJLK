<?php

use App\Models\Desa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('creates a desa account for each imported desa', function () {
    $jsonPath = base_path('data_desa.json');

    if (!file_exists($jsonPath)) {
        $this->markTestSkipped('data_desa.json not found.');
    }

    $this->artisan('app:import-perangkat')
        ->expectsOutputToContain('Berhasil mengimpor');

    $desa = Desa::query()->latest('id')->first();

    expect($desa)->not->toBeNull();

    $user = User::query()
        ->where('desa_id', $desa->id)
        ->where('role', 'desa')
        ->first();

    expect($user)->not->toBeNull();
    expect($user->username)->toBe(strtolower(str_replace([' ', '.', ',', '/', '\\'], '_', $desa->nama_desa)));
    expect(Hash::check('password', $user->password))->toBeTrue();
});

it('reuses an existing desa username account when the imported desa matches the same name', function () {
    $jsonPath = base_path('data_desa.json');

    if (!file_exists($jsonPath)) {
        $this->markTestSkipped('data_desa.json not found.');
    }

    $kecamatan = \App\Models\Kecamatan::create(['nama_kecamatan' => 'Test']);
    $existingDesa = Desa::create(['nama_desa' => 'Karangendep', 'kecamatan_id' => $kecamatan->id]);

    $legacyUser = User::create([
        'name' => 'Karangendep',
        'username' => 'karangendep',
        'password' => Hash::make('password'),
        'role' => 'desa',
        'desa_id' => $existingDesa->id,
    ]);

    $this->artisan('app:import-perangkat')
        ->expectsOutputToContain('Berhasil mengimpor');

    $updatedUser = $legacyUser->fresh();
    expect($updatedUser->desa_id)->toBeGreaterThan($existingDesa->id);
    expect($updatedUser->username)->toBe('karangendep');
});
