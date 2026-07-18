<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Karena secara teknis tabel ini sudah dibuat melalui upgrade_kolektif.php,
        // kita abaikan pembuatannya jika sudah exist
        if (!Schema::hasTable('ajuan_pesertas')) {
            Schema::create('ajuan_pesertas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ajuan_id')->constrained('ajuans')->cascadeOnDelete();
                $table->foreignId('perangkat_desa_id')->constrained('perangkat_desas')->cascadeOnDelete();
                $table->string('jabatan_baru')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuan_pesertas');
    }
};
