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
        // Assign services to counters
        $counterServices = [
            // Loket 1 handles Pendaftaran (A) and Pembayaran (B)
            ['counter_id' => 1, 'service_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['counter_id' => 1, 'service_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Loket 2 handles Poliklinik (C) and Farmasi (D)
            ['counter_id' => 2, 'service_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['counter_id' => 2, 'service_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Loket 3 handles all services
            ['counter_id' => 3, 'service_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['counter_id' => 3, 'service_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['counter_id' => 3, 'service_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['counter_id' => 3, 'service_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('counter_layanans')->insert($counterServices);
    }
}