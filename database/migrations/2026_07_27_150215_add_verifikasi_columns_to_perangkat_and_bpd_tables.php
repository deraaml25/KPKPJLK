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
        Schema::table('perangkat_desas', function (Blueprint $table) {
            $table->string('status_verifikasi', 50)->default('disetujui')->after('status_aktif');
            $table->json('draft_perubahan')->nullable()->after('status_verifikasi');
        });

        Schema::table('bpds', function (Blueprint $table) {
            $table->string('status_verifikasi', 50)->default('disetujui')->after('status_aktif');
            $table->json('draft_perubahan')->nullable()->after('status_verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perangkat_desas', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'draft_perubahan']);
        });

        Schema::table('bpds', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'draft_perubahan']);
        });
    }
};
