<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$groups = DB::select("SELECT jenis_layanan_id, count(*) as cnt FROM template_checklists GROUP BY jenis_layanan_id");
echo "Jenis Layanan ID in template_checklists:\n";
foreach ($groups as $g) {
    echo "ID: {$g->jenis_layanan_id} -> Count: {$g->cnt}\n";
}

$types = DB::select("SELECT id, nama FROM jenis_layanans");
echo "\nJenis Layanans:\n";
foreach ($types as $t) {
    echo "ID: {$t->id} -> {$t->nama}\n";
}
