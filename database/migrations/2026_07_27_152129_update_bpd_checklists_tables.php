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
        Schema::table('template_checklist_bpds', function (Blueprint $table) {
            $table->string('jenis_ajuan')->nullable()->after('id');
            $table->foreignId('alasan_pemberhentian_id')->nullable()->after('jenis_ajuan')->constrained('alasan_pemberhentians')->nullOnDelete();
        });

        Schema::table('checklist_ajuan_bpds', function (Blueprint $table) {
            $table->dropColumn('is_checked');
            $table->string('status')->default('belum_diunggah')->after('template_checklist_bpd_id');
            $table->integer('versi')->default(1)->after('catatan');
            $table->foreignId('updated_by')->nullable()->after('versi')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_checklist_bpds', function (Blueprint $table) {
            $table->dropForeign(['alasan_pemberhentian_id']);
            $table->dropColumn(['jenis_ajuan', 'alasan_pemberhentian_id']);
        });

        Schema::table('checklist_ajuan_bpds', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['status', 'versi', 'updated_by']);
            $table->boolean('is_checked')->default(false)->after('template_checklist_bpd_id');
        });
    }
};
