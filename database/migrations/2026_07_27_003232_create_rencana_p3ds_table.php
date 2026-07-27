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
        Schema::create('rencana_p3ds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->cascadeOnDelete();
            $table->integer('jumlah_formasi_kosong')->default(0);
            $table->text('jabatan_kosong');
            $table->date('rencana_pelaksanaan');
            $table->decimal('rencana_anggaran', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('status')->default('dikirim'); // draft, dikirim, disetujui
            $table->year('tahun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_p3ds');
    }
};
