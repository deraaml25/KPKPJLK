<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('siltaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->integer('bulan'); // 1-12
            $table->integer('tahun');
            $table->integer('jumlah_perangkat_aktif')->default(0); // Snapshot jumlah perangkat saat submit
            $table->string('rekomendasi_camat_path')->nullable();
            $table->string('bukti_bpjs_path')->nullable();
            $table->string('spj_path')->nullable();
            $table->string('status')->default('menunggu_verifikasi'); // menunggu_verifikasi, disetujui, ditolak, dikirim_bkad
            $table->text('catatan_verifikator')->nullable();
            $table->string('sp2d_path')->nullable();
            // Audit Trail
            $table->unsignedBigInteger('verified_by')->nullable(); // user_id verifikator
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['desa_id', 'bulan', 'tahun']); // 1 pengajuan per bulan per desa
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siltaps');
    }
};
