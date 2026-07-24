<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_pembinaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_diajukan');
            $table->string('file_surat_permohonan')->nullable(); // Surat permohonan narasumber
            $table->string('file_undangan')->nullable();         // Surat undangan
            $table->string('status')->default('menunggu');       // menunggu, disetujui, ditolak, selesai
            $table->text('catatan_admin')->nullable();           // Balasan dari Dinpermasdes
            $table->timestamp('dibalas_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pembinaans');
    }
};
