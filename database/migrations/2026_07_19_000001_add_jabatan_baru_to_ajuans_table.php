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
        Schema::table('ajuans', function (Blueprint $table) {
            if (! Schema::hasColumn('ajuans', 'jabatan_baru')) {
                $table->string('jabatan_baru')->nullable()->after('jenis_layanan_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ajuans', function (Blueprint $table) {
            $table->dropColumn('jabatan_baru');
        });
    }
};
