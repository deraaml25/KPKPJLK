<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pilkades_suaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilkades_id')->constrained('pilkades')->cascadeOnDelete();

            $table->string('tps_name')->comment('Contoh: TPS 001, TPS 002');

            // Statistik kehadiran
            $table->integer('total_pemilih_hadir')->default(0);
            $table->integer('suara_sah')->default(0);
            $table->integer('suara_tidak_sah')->default(0);

            // Perolehan suara per calon
            $table->integer('suara_calon_1')->default(0);
            $table->integer('suara_calon_2')->default(0);
            $table->integer('suara_calon_3')->default(0);

            // Kunci immutable setelah disahkan Admin
            $table->boolean('is_locked')->default(false);

            // Audit trail keamanan (IP + User)
            $table->foreignId('input_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            // Unique: satu TPS hanya bisa input satu kali per Pilkades
            $table->unique(['pilkades_id', 'tps_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pilkades_suaras');
    }
};
