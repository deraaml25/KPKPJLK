<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('izin_calons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();

            // Identitas Calon
            $table->string('nama_calon');
            $table->string('jabatan_sekarang');
            $table->enum('jenis_calon', ['kades', 'perangkat', 'pns'])
                ->comment('kades=petahana, perangkat=perangkat desa, pns=ASN');

            // Dokumen Wajib
            $table->string('surat_permohonan_path')->nullable(); // Surat izin cuti/pencalonan
            $table->string('berkas_syarat_path')->nullable();     // Syarat administrasi sesuai Perda
            $table->string('surat_pengunduran_diri_path')->nullable(); // Khusus Perangkat Desa

            // Cuti Petahana (khusus jenis_calon = kades)
            $table->date('tgl_cuti_mulai')->nullable();
            $table->date('tgl_cuti_selesai')->nullable();

            // Verifikasi Inspektorat (Gatekeeper Logic)
            $table->boolean('has_temuan_inspektorat')->default(false)
                ->comment('true = ada temuan kerugian yg blm diselesaikan');
            $table->string('bebas_temuan_inspektorat_path')->nullable(); // SK bebas temuan dari Inspektorat

            // SKA Inspektorat & Catatan
            $table->text('catatan_inspektorat')->nullable();

            // Penerbitan Surat Izin Bupati
            $table->string('surat_izin_bupati_path')->nullable();

            // Status Alur
            $table->string('status')->default('submitted')
                ->comment('submitted, approved, rejected');

            // Audit Trail
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_calons');
    }
};
