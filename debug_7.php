<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ajuan = \App\Models\Ajuan::find(7);
$output = "Ajuan ID: " . $ajuan->id . "\n";
$output .= "Jenis Layanan: " . $ajuan->jenis_layanan_id . "\n";

$templates = \App\Models\TemplateChecklist::where('jenis_layanan_id', $ajuan->jenis_layanan_id)->get();
$output .= "Templates Found in DB for this Layanan: " . $templates->count() . "\n";

$checklists = \App\Models\ChecklistAjuan::where('ajuan_id', 7)->get();
$output .= "Checklists Linked to Ajuan: " . $checklists->count() . "\n";

file_put_contents('debug_7.txt', $output);
