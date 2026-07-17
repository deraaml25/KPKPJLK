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
            $table->integer('sisa_kuota')->default(0);
            $table->date('tanggal_pelaksanaan');
            $table->string('file_materi')->nullable();
            $table->string('tempat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimteks');
    }
};
