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
        Schema::create('ajuan_bpd_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuan_bpd_id')->constrained('ajuan_bpds')->cascadeOnDelete();
            $table->foreignId('bpd_id')->constrained('bpds')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuan_bpd_pesertas');
    }
};
