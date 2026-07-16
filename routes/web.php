<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Desa\DashboardController as DesaDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'super_admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('desa.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Ajuan
    Route::get('/ajuan', [\App\Http\Controllers\Admin\AjuanController::class, 'index'])->name('ajuan.index');
    Route::get('/ajuan/{ajuan}', [\App\Http\Controllers\Admin\AjuanController::class, 'show'])->name('ajuan.show');
    Route::patch('/ajuan/{ajuan}/checklist/{checklistAjuan}/verifikasi', [\App\Http\Controllers\Admin\AjuanController::class, 'verifikasiChecklist'])->name('ajuan.verifikasi-checklist');
    Route::post('/ajuan/{ajuan}/milestone', [\App\Http\Controllers\Admin\AjuanController::class, 'updateMilestone'])->name('ajuan.update-milestone');

    // Arsip
    Route::get('/arsip', [\App\Http\Controllers\Admin\ArsipRekomController::class, 'index'])->name('arsip.index');
    Route::get('/arsip/{ajuan}/create', [\App\Http\Controllers\Admin\ArsipRekomController::class, 'create'])->name('arsip.create');
    Route::post('/arsip/{ajuan}', [\App\Http\Controllers\Admin\ArsipRekomController::class, 'store'])->name('arsip.store');
    Route::get('/arsip/{arsipRekom}/download', [\App\Http\Controllers\Admin\ArsipRekomController::class, 'download'])->name('arsip.download');

    // Drive Dokumen
    Route::get('/drive', [\App\Http\Controllers\Admin\DriveController::class, 'index'])->name('drive.index');
    Route::get('/drive/download-zip', [\App\Http\Controllers\Admin\DriveController::class, 'downloadZip'])->name('drive.download-zip');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('kecamatan', \App\Http\Controllers\Admin\MasterDataController::class);
    });
});

Route::middleware(['auth', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    Route::get('/dashboard', [DesaDashboardController::class, 'index'])->name('dashboard');

    // Ajuan — /buat MUST come before /{ajuan} or it gets swallowed
    Route::get('/ajuan/buat', [\App\Http\Controllers\Desa\AjuanController::class, 'create'])->name('ajuan.create');
    Route::get('/ajuan', [\App\Http\Controllers\Desa\AjuanController::class, 'index'])->name('ajuan.index');
    Route::post('/ajuan', [\App\Http\Controllers\Desa\AjuanController::class, 'store'])->name('ajuan.store');
    Route::get('/ajuan/{ajuan}', [\App\Http\Controllers\Desa\AjuanController::class, 'show'])->name('ajuan.show');
    Route::post('/ajuan/{ajuan}/upload/{checklistAjuan}', [\App\Http\Controllers\Desa\AjuanController::class, 'uploadDokumen'])->name('ajuan.upload');
    Route::post('/ajuan/{ajuan}/bulk-upload', [\App\Http\Controllers\Desa\AjuanController::class, 'bulkUpload'])->name('ajuan.bulk-upload');

    // Arsip
    Route::get('/arsip', [\App\Http\Controllers\Desa\ArsipController::class, 'index'])->name('arsip.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
