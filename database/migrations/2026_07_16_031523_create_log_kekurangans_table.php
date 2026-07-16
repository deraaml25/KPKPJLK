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
        Schema::create('log_kekurangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_ajuan_id')->constrained('checklist_ajuans')->cascadeOnDelete();
            $table->string('status_lama');
            $table->string('status_baru');
            $table->text('catatan');
            $table->timestamp('tgl')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_kekurangans');
    }
};
