<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desa = App\Models\Desa::where('nama_desa', 'KARANGENDEP')->orWhere('nama_desa', 'Karangendep')->orderBy('id')->get();
foreach ($desa as $row) {
    echo $row->id . ' | ' . $row->nama_desa . PHP_EOL;
}
