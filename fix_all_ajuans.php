<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ajuans = \App\Models\Ajuan::all();
$fixedCount = 0;

foreach ($ajuans as $ajuan) {
    if ($ajuan->checklistAjuans()->count() == 0) {
        $checklists = \App\Models\TemplateChecklist::where('jenis_layanan_id', $ajuan->jenis_layanan_id)
            ->where(function ($q) use ($ajuan) {
                $q->whereNull('alasan_pemberhentian_id')
                    ->orWhere('alasan_pemberhentian_id', $ajuan->alasan_pemberhentian_id);
            })
            ->orderBy('urutan')
            ->get();

        if ($checklists->count() > 0) {
            foreach ($checklists as $template) {
                \App\Models\ChecklistAjuan::create([
                    'ajuan_id' => $ajuan->id,
                    'template_checklist_id' => $template->id,
                    'status' => 'belum_diunggah',
                    'versi' => 1,
                ]);
            }
            echo "Checklist untuk Ajuan " . $ajuan->id . " (" . $ajuan->no_registrasi . ") berhasil direstore!\n";
            $fixedCount++;
        } else {
            echo "Ajuan " . $ajuan->id . ": Gagal, template tidak ditemukan.\n";
        }
    }
}

echo "\nTOTAL " . $fixedCount . " AJUAN BERHASIL DISINKRONKAN CEKLISTNYA.\n";
