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
            $table->string('metode')->default('online')->after('alasan_pemberhentian_id');
            $table->string('berkas_zip')->nullable()->after('folder_path');
            $table->text('catatan_admin')->nullable()->after('berkas_zip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ajuans', function (Blueprint $table) {
            $table->dropColumn(['metode', 'berkas_zip', 'catatan_admin']);
        });
    }
};
