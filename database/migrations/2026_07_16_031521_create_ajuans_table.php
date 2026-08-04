<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ajuans', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi')->unique();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanans')->cascadeOnDelete();
            $table->foreignId('alasan_pemberhentian_id')->nullable()->constrained('alasan_pemberhentians')->cascadeOnDelete();
            $table->string('posisi_surat')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, direvisi, diproses, selesai, ditolak
            $table->string('folder_path')->nullable();
            $table->date('tgl_diajukan')->nullable();
            $table->date('tgl_sla_batas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuans');
    }
};
