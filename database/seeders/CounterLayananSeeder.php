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
            // Loket 1 untuk Poli Umum
            [
                'counter_id' => 1, // Loket Poli Umum
                'service_id' => 1, // Poli Umum
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Loket 2 untuk Poli Syaraf
            [
                'counter_id' => 2, // Loket Poli Syaraf
                'service_id' => 2, // Poli Syaraf
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Loket 3 untuk Poli Anak
            [
                'counter_id' => 3, // Loket Poli Anak
                'service_id' => 3, // Poli Anak
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('counter_layanans')->insert($counterLayanans);
    }
}