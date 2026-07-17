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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status_presensi')->default('absen'); // hadir, absen
            $table->string('file_rtl')->nullable(); // Rencana Tindak Lanjut docx/pdf
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimtek_pendaftarans');
    }
};
