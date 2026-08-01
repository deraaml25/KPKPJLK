<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = DB::select("SHOW TABLES LIKE '%template%'");
echo "Tables with 'template':\n";
foreach ($tables as $t) {
    echo current((array)$t) . "\n";
}

$tables2 = DB::select("SHOW TABLES LIKE '%rekomendasi%'");
echo "\nTables with 'rekomendasi':\n";
foreach ($tables2 as $t) {
    echo current((array)$t) . "\n";
}
