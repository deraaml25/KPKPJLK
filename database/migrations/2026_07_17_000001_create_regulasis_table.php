<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('regulasis', function (Blueprint $table) {
            $table->id();
            $table->string('no_regulasi')->nullable()->unique();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['perdes', 'perkades', 'sk_kades']);
            $table->string('file_path')->nullable(); // Draf awal/revisi (.docx) dari Desa
            $table->string('file_catatan_dinas')->nullable(); // Draf coretan evaluasi (.docx) dari Dinas
            $table->string('file_pdf')->nullable(); // PDF final yang disahkan
            $table->string('status')->default('menunggu_evaluasi'); // menunggu_evaluasi, perlu_revisi, evaluasi_lanjutan, disahkan
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->text('catatan_revisi')->nullable(); // Catatan Evaluasi Text

            $table->date('tgl_diajukan')->nullable();
            $table->date('tgl_disahkan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulasis');
    }
};
