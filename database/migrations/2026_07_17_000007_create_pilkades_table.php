<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pilkades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();

            // Jadwal
            $table->date('tanggal_pemungutan');
            $table->integer('total_tps')->default(1);
            $table->integer('total_dpt')->default(0)->comment('Jumlah DPT per desa');

            // Data Calon (Admin setup sebelum hari-H)
            $table->string('calon_1_nama')->nullable();
            $table->string('calon_2_nama')->nullable();
            $table->string('calon_3_nama')->nullable();

            // Berita Acara dari Desa (upload setelah penghitungan)
            $table->string('berita_acara_path')->nullable();

            // SK Bupati hasil penetapan dari Dinpermasdes
            $table->string('pemenang_nama')->nullable();
            $table->string('sk_bupati_path')->nullable();
            $table->foreignId('disahkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disahkan_at')->nullable();

            // Status: persiapan → pemungutan → selesai → validated
            $table->string('status')->default('persiapan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pilkades');
    }
};
