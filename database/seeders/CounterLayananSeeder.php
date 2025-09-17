<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CounterLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all services and counters
        $services = DB::table('services')->get();
        $counters = DB::table('counters')->get();

        // Create relationships between counters and services (1:1 mapping)
        $counterLayanans = [
            // Loket Poli Umum untuk layanan Penyakit Dalam
            [
                'counter_id' => 1, // Loket Poli Umum
                'service_id' => 1, // Penyakit Dalam (PU)
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Loket Poli Syaraf untuk layanan Syaraf
            [
                'counter_id' => 2, // Loket Poli Syaraf
                'service_id' => 2, // Syaraf (PS)
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Loket Poli Anak untuk layanan Anak
            [
                'counter_id' => 3, // Loket Poli Anak
                'service_id' => 3, // Anak (PA)
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('counter_layanans')->insert($counterLayanans);
    }
}