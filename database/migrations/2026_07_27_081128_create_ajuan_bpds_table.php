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
        Schema::create('ajuan_bpds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->string('no_registrasi')->nullable();
            $table->enum('jenis_ajuan', ['pemberhentian', 'peresmian', 'pemberhentian_dan_peresmian']);
            $table->enum('metode', ['online', 'offline']);
            $table->foreignId('alasan_pemberhentian_id')->nullable()->constrained('alasan_pemberhentians')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->date('tgl_diajukan')->nullable();
            $table->date('tgl_sla_batas')->nullable();
            $table->string('berkas_zip')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuan_bpds');
    }
};
