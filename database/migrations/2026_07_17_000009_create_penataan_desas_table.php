<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penataan_desas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->enum('tipe', ['pemekaran', 'penggabungan', 'perubahan_status', 'perubahan_batas']);
            $table->string('nama_wilayah_baru');
            $table->integer('jumlah_penduduk')->default(0);
            $table->integer('jumlah_kk')->default(0);
            $table->string('proposal_path')->nullable();
            $table->string('peta_geojson_path')->nullable();
            $table->string('rekomendasi_dinas_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->integer('status_evaluasi_tahun')->default(1); // 1-3 tahun
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penataan_desas');
    }
};
