<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('izin_calons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->string('nama_calon');
            $table->string('jabatan_sekarang');
            $table->enum('jenis_calon', ['kades', 'perangkat', 'pns']);
            $table->string('berkas_syarat_path')->nullable();
            $table->string('bebas_temuan_inspektorat_path')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected
            $table->text('catatan_inspektorat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_calons');
    }
};
