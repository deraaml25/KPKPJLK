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
        Schema::table('ajuan_bpds', function (Blueprint $table) {
            $table->string('posisi_surat')->default('Berkas Diterima')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ajuan_bpds', function (Blueprint $table) {
            $table->dropColumn('posisi_surat');
        });
    }
};
