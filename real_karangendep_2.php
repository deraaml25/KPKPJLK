<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desa = \App\Models\Desa::where('nama', 'like', '%Karangendep%')->first();
$output = [];
if (!$desa) {
    $output['status'] = 'Desa belum terdaftar sama sekali di database.';
} else {
    $output['desa'] = $desa->nama;
    \App\Models\PerangkatDesa::where('desa_id', $desa->id)->forceDelete();

    $realData = [
        ['jabatan' => 'Kepala Desa', 'nama' => 'KARSINAH'],
        ['jabatan' => 'Sekretaris Desa', 'nama' => 'TRIYO WIDODO'],
        ['jabatan' => 'Kasi Pemerintahan', 'nama' => 'KIRTO'],
        ['jabatan' => 'Kasi Kesejahteraan', 'nama' => 'SUTARKO'],
        ['jabatan' => 'Kasi Pelayanan', 'nama' => 'AGUS SUPRIJATNO'],
        ['jabatan' => 'Kaur Keuangan', 'nama' => 'NETY AMI PRABAWATI'],
        ['jabatan' => 'Kaur Perencanaan', 'nama' => 'TRI YUNIA RUBIANTO'],
        ['jabatan' => 'Kaur TU & Umum', 'nama' => 'INAWAN NUR KHOLI'],
    ];

    foreach ($realData as $p) {
        \App\Models\PerangkatDesa::create([
            'desa_id' => $desa->id,
            'nama' => $p['nama'],
            'jabatan' => $p['jabatan'],
            'nomor_sk' => '141/00' . rand(1, 9) . '/2020',
            'tgl_sk' => '2020-01-01',
            'tgl_mulai' => '2020-01-01',
            'status_aktif' => true,
        ]);
    }
    $output['status'] = 'Berhasil disuntik (menggunakan LIKE)!';
}
file_put_contents('result_db.json', json_encode($output));
