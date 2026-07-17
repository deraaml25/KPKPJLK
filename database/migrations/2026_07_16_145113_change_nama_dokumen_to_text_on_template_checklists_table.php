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
        Schema::table('template_checklists', function (Blueprint $table) {
            $table->text('nama_dokumen')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_checklists', function (Blueprint $table) {
            $table->string('nama_dokumen')->change();
        });
    }
};
