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
            // Relasi ke tabel 'patients', bisa kosong untuk pasien umum
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('set null');
            
            $table->unsignedInteger('queue_number'); // Nomor urut antrian, contoh: 1, 2, 3
            $table->string('formatted_number'); // Nomor yang ditampilkan ke user, contoh: "A-001"
            
            // Tipe pasien: 'umum' atau 'bpjs'
            $table->enum('patient_type', ['umum', 'bpjs'])->default('umum');
            // Nomor BPJS (untuk pasien BPJS)
            $table->string('bpjs_number')->nullable();
            
            // Status antrian: 'menunggu', 'dipanggil', 'selesai', 'batal'
            $table->string('status', 20)->default('menunggu');
            
            $table->timestamp('called_at')->nullable(); // Waktu antrian dipanggil
            $table->timestamp('finished_at')->nullable(); // Waktu antrian selesai dilayani
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('bpjs_number');
            $table->index('patient_id');
            $table->index('status');
        });
    }
};
