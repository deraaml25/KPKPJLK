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

            // Parameter Demografi
            $table->integer('jumlah_penduduk');
            $table->integer('jumlah_kk');

            // Parameter Spasial
            $table->decimal('luas_wilayah_km2', 10, 2);
            $table->string('peta_geospasial_path')->nullable(); // format doc/pdf/shp
            $table->string('perbup_persiapan_path')->nullable();

            // Administrasi
            $table->string('status')->default('diajukan'); // diajukan, persiapan, definitif, ditolak
            $table->text('alasan_penolakan')->nullable();

            // Timeline Desa Persiapan
            $table->date('tgl_mulai_persiapan')->nullable();
            $table->date('tgl_batas_persiapan')->nullable(); // max 3 years dari tgl_mulai

            // Kode Final dari Kemendagri
            $table->string('kode_desa_kemendagri')->nullable()->unique();

            // Audit Log
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diproses_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penataan_desas');
    }
};
