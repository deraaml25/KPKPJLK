<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pilkades_suaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilkades_id')->constrained('pilkades')->cascadeOnDelete();
            $table->string('tps_name');
            $table->integer('suara_calon_1')->default(0); // Calon 1
            $table->integer('suara_calon_2')->default(0); // Calon 2
            $table->integer('suara_calon_3')->default(0); // Calon 3
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pilkades_suaras');
    }
};
