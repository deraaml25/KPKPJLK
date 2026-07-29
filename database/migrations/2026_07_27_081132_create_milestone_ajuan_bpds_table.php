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
        Schema::create('milestone_ajuan_bpds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuan_bpd_id')->constrained('ajuan_bpds')->cascadeOnDelete();
            $table->string('tahapan');
            $table->string('status');
            $table->dateTime('tgl_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestone_ajuan_bpds');
    }
};
