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
        Schema::create('arsip_rekoms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuan_id')->constrained('ajuans')->cascadeOnDelete();
            $table->string('no_surat_rekom');
            $table->string('file_path');
            $table->timestamp('tgl_upload')->useCurrent();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_rekoms');
    }
};
