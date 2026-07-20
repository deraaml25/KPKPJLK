<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desa = App\Models\Desa::where('nama_desa', 'KARANGENDEP')->first();
if (!$desa) {
    echo "desa_not_found\n";
    exit(1);
}

$rows = App\Models\PerangkatDesa::where('desa_id', $desa->id)->orderBy('jabatan')->get(['id','nama','jabatan','status_aktif']);
echo "desa_id={$desa->id}\n";
echo "count={$rows->count()}\n";
foreach ($rows as $row) {
    echo $row->jabatan . ' | ' . $row->nama . ' | status=' . ($row->status_aktif ? 1 : 0) . PHP_EOL;
}
