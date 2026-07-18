<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pj_kades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->string('nama_pns');
            $table->string('nip');
            $table->string('pangkat');
            $table->string('riwayat_hidup_path')->nullable();
            $table->string('surat_camat_path')->nullable();
            $table->string('sk_pangkat_path')->nullable();
            $table->string('status_bebas_hukdis')->default('pending'); // pending, clean, has_issues
            $table->string('sk_bupati_path')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, rejected, approved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pj_kades');
    }
};
