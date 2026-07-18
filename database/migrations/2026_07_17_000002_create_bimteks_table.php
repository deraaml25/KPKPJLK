<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bimteks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->integer('kuota')->default(0);
            $table->date('tanggal_pelaksanaan');
            $table->string('tempat')->nullable();
            $table->string('file_undangan')->nullable(); // Surat Undangan Resmi (PDF)
            $table->string('file_materi')->nullable(); // Materi Bimtek
            $table->string('file_sertifikat')->nullable(); // Template Sertifikat
            $table->string('status')->default('terjadwal'); // terjadwal, selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimteks');
    }
};
