<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'desa')->where('username', 'karangendep')->first();
if ($user) {
    echo 'user_id=' . $user->id . ' desa_id=' . $user->desa_id . PHP_EOL;
    echo 'name=' . $user->name . PHP_EOL;
} else {
    echo 'no_user' . PHP_EOL;
}
