<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bimtek_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimtek_id')->constrained('bimteks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Admin Desa submitter
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('perangkat_desa_id')->constrained('perangkat_desas')->cascadeOnDelete(); // Tahap 2: Menunjuk perangkat
            $table->string('status_presensi')->default('terdaftar'); // terdaftar, hadir, absen
            $table->string('file_rtl')->nullable(); // Tahap 4: Unggah RTL
            $table->text('catatan_revisi_rtl')->nullable(); // Catatan Evaluasi RTL dari Dinas
            $table->string('status_rtl')->default('menunggu_rtl'); // menunggu_rtl, menunggu_validasi, revisi, selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimtek_pendaftarans');
    }
};
