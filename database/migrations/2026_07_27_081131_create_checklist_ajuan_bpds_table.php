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
        Schema::create('checklist_ajuan_bpds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuan_bpd_id')->constrained('ajuan_bpds')->cascadeOnDelete();
            $table->foreignId('template_checklist_bpd_id')->constrained('template_checklist_bpds')->cascadeOnDelete();
            $table->boolean('is_checked')->default(false);
            $table->string('file_path')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_ajuan_bpds');
    }
};
