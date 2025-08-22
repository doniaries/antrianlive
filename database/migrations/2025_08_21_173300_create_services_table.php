<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama layanan, contoh: "Poli Umum", "Pembayaran Pajak"
            $table->string('code', 10)->unique(); // Kode unik untuk layanan, contoh: "A", "B", "PJK"
            $table->boolean('is_active')->default(true); // Status untuk mengaktifkan/menonaktifkan layanan
            $table->timestamps();
        });
    }
};
