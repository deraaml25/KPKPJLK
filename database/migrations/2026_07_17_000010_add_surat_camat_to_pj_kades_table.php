<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pj_kades', function (Blueprint $table) {
            $table->string('surat_camat_path')->nullable()->after('riwayat_hidup_path');
        });
    }

    public function down(): void
    {
        Schema::table('pj_kades', function (Blueprint $table) {
            $table->dropColumn('surat_camat_path');
        });
    }
};
