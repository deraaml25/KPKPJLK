<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimtek_informasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('konten');
            $table->string('foto')->nullable();
            $table->string('file_lampiran')->nullable();
            $table->string('kategori')->default('informasi'); // informasi, dokumentasi, pengumuman
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimtek_informasis');
    }
};
