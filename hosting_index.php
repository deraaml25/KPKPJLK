<?php

/**
 * =============================================
 * SIDMINI - Entry Point untuk InfinityFree
 * =============================================
 * File ini menggantikan public/index.php
 * Upload ke: htdocs/index.php
 * =============================================
 */

define('LARAVEL_START', microtime(true));

// Path ke folder laravel (relatif dari htdocs/)
// Semua file Laravel ada di htdocs/ juga, jadi __DIR__ = root project
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Override APP_PUBLIC_PATH ke htdocs/ bukan public/
// supaya assets bisa diakses langsung
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = tap($kernel->handle(
    $request = Illuminate\Http\Request::capture()
))->send();

$kernel->terminate($request, $response);
