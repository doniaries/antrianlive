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
        // Truncate the table to avoid duplicate entries
        DB::table('counter_layanans')->truncate();
        
        // Get all services and counters
        $services = DB::table('services')->get();
        $counters = DB::table('counters')->get();
        
        // Map service codes to counter IDs based on their descriptions
        $serviceCodeToCounterId = [
            'PU' => 1, // Klaster 1 - Poli Umum
            'PS' => 2, // Klaster 2 - Poli Syaraf
            'PA' => 3, // Klaster 3 - Poli Anak
        ];
        
        $counterLayanans = [];
        
        foreach ($services as $service) {
            // Extract service code from the service name or code field
            $serviceCode = strtoupper(substr($service->name, 0, 2));
            
            // If we have a mapping for this service code, create the relationship
            if (isset($serviceCodeToCounterId[$serviceCode])) {
                $counterLayanans[] = [
                    'counter_id' => $serviceCodeToCounterId[$serviceCode],
                    'service_id' => $service->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }
        
        // If no relationships were created automatically, fall back to the original mapping
        if (empty($counterLayanans)) {
            $counterLayanans = [
                [
                    'counter_id' => 1, // Klaster 1
                    'service_id' => 1, // Poli Umum (PU)
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'counter_id' => 2, // Klaster 2
                    'service_id' => 2, // Poli Syaraf (PS)
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'counter_id' => 3, // Klaster 3
                    'service_id' => 3, // Poli Anak (PA)
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ];
        }

        // Insert the relationships
        DB::table('counter_layanans')->insert($counterLayanans);
    }
}