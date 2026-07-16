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
        Schema::create('checklist_ajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuan_id')->constrained('ajuans')->cascadeOnDelete();
            $table->foreignId('template_checklist_id')->constrained('template_checklists')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->string('status')->default('belum_diunggah'); // belum_diunggah, pending, lengkap, kurang, tidak_sesuai
            $table->text('catatan')->nullable();
            $table->integer('versi')->default(1);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_ajuans');
    }
};
