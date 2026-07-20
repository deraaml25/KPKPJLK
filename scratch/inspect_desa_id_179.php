<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desaId = 179;
$rows = App\Models\PerangkatDesa::where('desa_id', $desaId)->get(['id','nama','jabatan','status_aktif']);
echo 'count=' . $rows->count() . PHP_EOL;
foreach ($rows as $row) {
    echo $row->jabatan . ' | ' . $row->nama . PHP_EOL;
}
