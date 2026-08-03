<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$regulasis = App\Models\Regulasi::whereNull('no_regulasi')->orderBy('id', 'asc')->get();
foreach ($regulasis as $reg) {
    $prefix = match($reg->tipe) {
        'perdes' => 'PRD',
        'perkades' => 'PKD',
        'sk_kades' => 'SKK',
        default => 'REG'
    };
    $year = $reg->created_at ? $reg->created_at->format('Y') : date('Y');
    $month = $reg->created_at ? $reg->created_at->format('m') : date('m');

    $latestReg = App\Models\Regulasi::whereNotNull('no_regulasi')->where('no_regulasi', 'like', "{$prefix}/{$year}/{$month}/%")->orderBy('id', 'desc')->first();
    $nextNumber = '0001';
    if ($latestReg && preg_match('/(\d{4})$/', $latestReg->no_regulasi, $matches)) {
        $nextNumber = str_pad(intval($matches[1]) + 1, 4, '0', STR_PAD_LEFT);
    }
    $noRegulasi = "{$prefix}/{$year}/{$month}/{$nextNumber}";
    $reg->no_regulasi = $noRegulasi;
    $reg->save();
}

echo "Done.\n";
