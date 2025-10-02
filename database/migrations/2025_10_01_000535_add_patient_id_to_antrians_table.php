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
        Schema::table('antrians', function (Blueprint $table) {
            // Tambahkan kolom patient_id yang nullable untuk kompatibilitas dengan data yang sudah ada
            $table->foreignId('patient_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('patients')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['patient_id']);
            // Hapus kolom
            $table->dropColumn('patient_id');
        });
    }
};
