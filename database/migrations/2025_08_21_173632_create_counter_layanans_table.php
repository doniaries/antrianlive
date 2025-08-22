<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('counter_layanans', function (Blueprint $table) {
            $table->primary(['counter_id', 'service_id']);
            // Relasi ke tabel 'counters'
            $table->foreignId('counter_id')->constrained('counters')->onDelete('cascade');
            // Relasi ke tabel 'services'
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->timestamps();
        });
    }
};
