<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desas = \App\Models\Desa::where('nama_desa', 'like', '%Karangendep%')->get();
foreach ($desas as $d) {
    echo "ID: $d->id - Nama: $d->nama_desa - Perangkat Count: " . $d->perangkatDesas()->count() . "\n";
}
