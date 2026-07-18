<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desa = \App\Models\Desa::where('nama', 'like', '%Karangendep%')->first();
$output = [];
if (!$desa) {
    echo 'Desa belum terdaftar.';
} else {
    // Delete any existing (Budi Santoso)
    \App\Models\PerangkatDesa::where('desa_id', $desa->id)->delete();

    $realData = [
        ['jabatan' => 'Kepala Desa', 'nama' => 'KARSINAH'],
        ['jabatan' => 'Sekretaris Desa', 'nama' => 'TRIYO WIDODO'],
        ['jabatan' => 'Kasi Pemerintahan', 'nama' => 'KIRTO'],
        ['jabatan' => 'Kasi Kesejahteraan', 'nama' => 'SUTARKO'],
        ['jabatan' => 'Kasi Pelayanan', 'nama' => 'AGUS SUPRIJATNO'],
        ['jabatan' => 'Kaur Keuangan', 'nama' => 'NETY AMI PRABAWATI'],
        ['jabatan' => 'Kaur Perencanaan', 'nama' => 'TRI YUNIA RUBIANTO'],
        ['jabatan' => 'Kaur TU & Umum', 'nama' => 'INAWAN NUR KHOLIQ'],
    ];

    foreach ($realData as $p) {
        \App\Models\PerangkatDesa::create([
            'desa_id' => $desa->id,
            'nama' => $p['nama'],
            'jabatan' => $p['jabatan'],
            'no_sk_terakhir' => '141/00' . rand(1, 9) . '/2020', // CORRECT FIELD
            'tgl_mulai_jabatan' => '2020-01-01',          // CORRECT FIELD
            'status_aktif' => true,
        ]);
    }
    echo 'Berhasil disuntik (dengan schema yang benar)!';
}
