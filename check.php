<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desa = \App\Models\Desa::where('nama', 'like', '%Karangendep%')->first();
$output = '';
if (!$desa) {
    $output .= "Desa tidak ditemukan.\n";
} else {
    $output .= "Desa ditemukan: {$desa->id} - {$desa->nama}\n";

    // HAPUS BUDI SECARA PAKSA!
    $count = \App\Models\PerangkatDesa::where('desa_id', $desa->id)->count();
    $output .= "Ditemukan $count perangkat lama sebelum hapus.\n";

    // PerangkatDesa tidak punya SoftDeletes di class, jadi delete() harusnya permanen.
    // Tapi kita pakai Truncate style jika perlu, atau query DELETE SQL asli.
    \Illuminate\Support\Facades\DB::table('perangkat_desas')->where('desa_id', $desa->id)->delete();

    $count_after = \App\Models\PerangkatDesa::where('desa_id', $desa->id)->count();
    $output .= "Jumlah perangkat setelah hapus: $count_after\n";

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
        \Illuminate\Support\Facades\DB::table('perangkat_desas')->insert([
            'desa_id' => $desa->id,
            'nama' => $p['nama'],
            'jabatan' => $p['jabatan'],
            'no_sk_terakhir' => '141/00' . rand(1, 9) . '/2020',
            'tgl_mulai_jabatan' => '2020-01-01',
            'status_aktif' => 1, // boolean
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    $count_final = \App\Models\PerangkatDesa::where('desa_id', $desa->id)->count();
    $output .= "Berhasil diinsert. Total sekarang: $count_final\n";
    $output .= "Nama salah satu: " . \App\Models\PerangkatDesa::where('desa_id', $desa->id)->first()->nama . "\n";
}

file_put_contents('fix_out.txt', $output);
