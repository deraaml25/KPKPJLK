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
        Schema::table('pj_kades', function (Blueprint $table) {
            $table->string('kategori')->default('pj_kades')->after('desa_id'); // pj_kades, plt_kades
            $table->foreignId('alasan_pemberhentian_id')->nullable()->after('kategori')->constrained('alasan_pemberhentians')->nullOnDelete();
            $table->string('alasan_nama')->nullable()->after('alasan_pemberhentian_id');
            $table->string('no_registrasi')->nullable()->after('alasan_nama');
            $table->string('nama_pns')->nullable()->change();
            $table->string('nip')->nullable()->change();
            $table->string('pangkat')->nullable()->change();
            $table->string('nama_plt')->nullable()->after('pangkat');
            $table->string('nip_plt')->nullable()->after('nama_plt');
            $table->string('pangkat_plt')->nullable()->after('nip_plt');
            $table->string('folder_path')->nullable()->after('sk_bupati_path');
            $table->date('tgl_diajukan')->nullable()->after('folder_path');
        });

        Schema::create('checklist_pj_kades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pj_kades_id')->constrained('pj_kades')->cascadeOnDelete();
            $table->foreignId('template_checklist_id')->nullable()->constrained('template_checklists')->nullOnDelete();
            $table->string('nama_dokumen');
            $table->boolean('wajib')->default(true);
            $table->integer('urutan')->default(1);
            $table->string('file_path')->nullable();
            $table->string('status_verifikasi')->default('pending'); // pending, disetujui, ditolak
            $table->text('catatan_revisi')->nullable();
            $table->timestamp('tgl_diunggah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_pj_kades');
        Schema::table('pj_kades', function (Blueprint $table) {
            $table->dropForeign(['alasan_pemberhentian_id']);
            $table->dropColumn([
                'kategori',
                'alasan_pemberhentian_id',
                'alasan_nama',
                'no_registrasi',
                'nama_plt',
                'nip_plt',
                'pangkat_plt',
                'folder_path',
                'tgl_diajukan',
            ]);
        });
    }
};
