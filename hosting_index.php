<?php

/**
 * Laravel entry point for shared hosting (InfinityFree)
 * Upload file ini ke root htdocs/
 * Laravel project di-upload ke htdocs/ juga
 */

// Arahkan semua request ke public/index.php
define('LARAVEL_START', microtime(true));

// Load autoloader
require __DIR__.'/vendor/autoload.php';

// Bootstrap application
$app = require_once __DIR__.'/bootstrap/app.php';

// Run HTTP kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
