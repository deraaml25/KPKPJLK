<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = Illuminate\Support\Facades\DB::table('users')->where('role', 'desa')->orderByDesc('id')->limit(10)->get(['id','name','username','role','desa_id']);
foreach ($rows as $row) {
    echo $row->id . ' | ' . $row->name . ' | ' . $row->username . ' | ' . $row->role . ' | ' . $row->desa_id . PHP_EOL;
}
