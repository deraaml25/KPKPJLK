<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$desaRows = DB::select("SELECT id, nama_desa, kecamatan_id FROM desas WHERE LOWER(nama_desa) LIKE ?", ['%karangendep%']);
print_r($desaRows);

if ($desaRows) {
    $desaId = $desaRows[0]->id;
    $rows = DB::select("SELECT id, desa_id, nama, jabatan, status_aktif FROM perangkat_desas WHERE desa_id = ? ORDER BY id", [$desaId]);
    echo "COUNT=" . count($rows) . PHP_EOL;
    foreach ($rows as $row) {
        echo json_encode((array)$row, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
