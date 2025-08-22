<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel 'services'
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            // Relasi ke tabel 'counters' (loket), bisa kosong saat antrian baru dibuat
            $table->foreignId('counter_id')->nullable()->constrained('counters')->onDelete('set null');
            $table->unsignedInteger('queue_number'); // Nomor urut antrian, contoh: 1, 2, 3
            $table->string('formatted_number'); // Nomor yang ditampilkan ke user, contoh: "A-001"
            // Status antrian: 'waiting', 'calling', 'finished', 'skipped'
            $table->string('status', 20)->default('waiting');
            $table->timestamp('called_at')->nullable(); // Waktu antrian dipanggil
            $table->timestamp('finished_at')->nullable(); // Waktu antrian selesai dilayani
            $table->timestamps();
        });
    }
};
