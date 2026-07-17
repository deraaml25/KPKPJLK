<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$alasanId = \App\Models\AlasanPemberhentian::where('nama', 'Diberhentikan')->value('id');
$items = \App\Models\TemplateChecklist::where('alasan_pemberhentian_id', $alasanId)->get();

echo "TOTAL ITEMS: " . $items->count() . "\n";
foreach ($items as $i) {
    echo $i->urutan . " - " . $i->wajib . " - " . $i->nama_dokumen . "\n";
}

// Delete stale ajuans so they don't see wrong items
\App\Models\Ajuan::whereHas('jenisLayanan', function ($q) {
    $q->where('nama', 'Pemberhentian');
})->delete();
echo "DELETED STALE DRAFTS\n";
