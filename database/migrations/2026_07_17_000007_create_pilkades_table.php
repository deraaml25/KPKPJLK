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
            $table->date('tanggal_pemungutan');
            $table->string('status')->default('persiapan'); // persiapan, pemilihan, selesai
            $table->integer('total_tps')->default(0);
            $table->string('pemenang_nama')->nullable();
            $table->string('sk_bupati_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pilkades');
    }
};
