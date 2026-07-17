<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('siltaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->integer('bulan'); // 1-12
            $table->integer('tahun');
            $table->string('rekomendasi_camat_path')->nullable();
            $table->string('bukti_bpjs_path')->nullable();
            $table->string('spj_path')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected
            $table->text('notes')->nullable();
            $table->string('sp2d_path')->nullable(); // Uploaded when approved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siltaps');
    }
};
