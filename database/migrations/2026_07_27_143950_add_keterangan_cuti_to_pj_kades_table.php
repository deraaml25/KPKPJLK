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
            $table->string('keterangan_cuti')->nullable()->after('alasan_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pj_kades', function (Blueprint $table) {
            $table->dropColumn('keterangan_cuti');
        });
    }
};
