<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Cek data di template_checklist_bpds
$templates = DB::select("SELECT id, jenis_ajuan, urutan, nama_dokumen FROM template_checklist_bpds ORDER BY jenis_ajuan, urutan");
$grouped = [];
foreach ($templates as $t) {
    if (!isset($grouped[$t->jenis_ajuan])) {
        $grouped[$t->jenis_ajuan] = [];
    }
    $grouped[$t->jenis_ajuan][] = "[#{$t->urutan}] {$t->nama_dokumen}";
}

echo "=== TEMPLATE CHECKLIST BPD ===\n";
foreach ($grouped as $jenis => $items) {
    echo "Jenis: $jenis\n";
    foreach ($items as $i) {
        echo "  $i\n";
    }
    echo "\n";
}
