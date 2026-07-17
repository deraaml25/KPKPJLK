<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('regulasis', function (Blueprint $table) {
            $table->id();
            $table->string('no_regulasi')->unique();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['perdes', 'perkades', 'sk_kades']);
            $table->string('file_path')->nullable();
            $table->string('status')->default('draft'); // draft, diajukan, direvisi, disahkan
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->text('catatan_revisi')->nullable(); // Legal Drafting Note
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
