<?php
// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Desa;

$desas = Desa::with('kecamatan')->get();
$created = 0;
$skipped = 0;
$hashedPassword = password_hash('password', PASSWORD_BCRYPT);

DB::transaction(function () use ($desas, &$created, &$skipped, $hashedPassword) {
    foreach ($desas as $desa) {
        $username = strtolower(str_replace([' ', '.', ',', "'"], ['_', '', '', ''], $desa->nama_desa));

        $exists = DB::table('users')->where('username', $username)->exists();
        if ($exists) {
            $skipped++;
            continue;
        }

        DB::table('users')->insert([
            'name' => 'Operator ' . $desa->nama_desa,
            'username' => $username,
            'password' => $hashedPassword,
            'role' => 'desa',
            'desa_id' => $desa->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $created++;
        echo "Created: $username\n";
    }
});

echo "\nDone! Created: $created | Skipped: $skipped | Total desas: " . count($desas) . "\n";
echo "Password for all: password\n";
