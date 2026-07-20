<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$jsonData = json_decode(file_get_contents(__DIR__ . '/../data_desa.json'), true);
array_shift($jsonData);
$found = null;
foreach ($jsonData as $row) {
    if (($row['B'] ?? '') === 'Patikraja' && trim((string)($row['C'] ?? '')) === 'KARANGENDEP') {
        $found = $row;
        break;
    }
}
if (!$found) {
    echo "not found\n";
    exit(1);
}

$perangkatList = [
    ['jabatan' => 'Kepala Desa', 'nama' => trim($found['D'] ?? '')],
    ['jabatan' => 'Sekretaris Desa', 'nama' => trim($found['E'] ?? '')],
    ['jabatan' => 'Kasi Pemerintahan', 'nama' => trim($found['F'] ?? '')],
    ['jabatan' => 'Kasi Kesejahteraan', 'nama' => trim($found['G'] ?? '')],
    ['jabatan' => 'Kasi Pelayanan', 'nama' => trim($found['H'] ?? '')],
    ['jabatan' => 'Kaur Keuangan', 'nama' => trim($found['I'] ?? '')],
    ['jabatan' => 'Kaur Perencanaan', 'nama' => trim($found['J'] ?? '')],
    ['jabatan' => 'Kaur TU & Umum', 'nama' => trim($found['K'] ?? '')],
    ['jabatan' => 'Kadus I', 'nama' => trim($found['L'] ?? '')],
    ['jabatan' => 'Kadus II', 'nama' => trim($found['M'] ?? '')],
    ['jabatan' => 'Kadus III', 'nama' => trim($found['N'] ?? '')],
];

if (!empty(trim($found['P'] ?? '')) && stripos(trim($found['O'] ?? ''), 'Tidak Ada') === false) {
    $perangkatList[] = ['jabatan' => 'Kadus IV', 'nama' => trim($found['P'])];
}
if (!empty(trim($found['R'] ?? '')) && stripos(trim($found['Q'] ?? ''), 'Tidak Ada') === false) {
    $perangkatList[] = ['jabatan' => 'Kadus V', 'nama' => trim($found['R'])];
}
if (!empty(trim($found['U'] ?? '')) && stripos(trim($found['S'] ?? ''), 'Tidak Ada') === false) {
    $perangkatList[] = ['jabatan' => trim($found['V'] ?? 'Staf Perangkat Desa'), 'nama' => trim($found['U'])];
}
if (!empty(trim($found['Y'] ?? '')) && stripos(trim($found['W'] ?? ''), 'Tidak Ada') === false) {
    $perangkatList[] = ['jabatan' => trim($found['Z'] ?? 'Staf Non Perangkat Desa 1'), 'nama' => trim($found['Y'])];
}
if (!empty(trim($found['AA'] ?? '')) && trim($found['AA']) !== '-') {
    $perangkatList[] = ['jabatan' => trim($found['AB'] ?? 'Staf Non Perangkat Desa 2'), 'nama' => trim($found['AA'])];
}
if (!empty(trim($found['AC'] ?? '')) && trim($found['AC']) !== '-') {
    $perangkatList[] = ['jabatan' => trim($found['AD'] ?? 'Staf Non Perangkat Desa 3'), 'nama' => trim($found['AC'])];
}

foreach ($perangkatList as $p) {
    if (!empty($p['nama']) && $p['nama'] !== '-') {
        echo $p['jabatan'] . ' => ' . $p['nama'] . PHP_EOL;
    }
}
