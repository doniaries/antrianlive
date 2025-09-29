<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class CounterLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing relationships
        DB::table('counter_layanans')->truncate();
        
        // Get all services and counters
        $services = DB::table('services')->get();
        $counters = DB::table('counters')->get();
        
        if ($services->isEmpty() || $counters->isEmpty()) {
            $this->command->warn('No services or counters found. Please run ServiceSeeder and CounterSeeder first.');
            return;
        }
        
        $counterLayanans = [];
        
        // Map service codes to counter names
        $serviceCounterMap = [
            'PU' => 'Klaster 1', // Poli Umum
            'PS' => 'Klaster 2', // Poli Syaraf
            'PA' => 'Klaster 3', // Poli Anak
        ];
        
        // Create relationships based on the mapping
        $createdRelations = 0;
        
        foreach ($services as $service) {
            $serviceCode = $service->code ?? strtoupper(substr($service->name, 0, 2));
            
            if (isset($serviceCounterMap[$serviceCode])) {
                $counterName = $serviceCounterMap[$serviceCode];
                $counter = $counters->firstWhere('name', $counterName);
                
                if ($counter) {
                    $counterLayanans[] = [
                        'counter_id' => $counter->id,
                        'service_id' => $service->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    $createdRelations++;
                    $this->command->info("✅ Mapped service '{$service->name}' to counter '{$counter->name}'");
                } else {
                    $this->command->warn("⚠️  Counter '{$counterName}' not found for service '{$service->name}'");
                }
            } else {
                $this->command->warn("⚠️  No mapping found for service code: {$serviceCode} ({$service->name})");
            }
        }
        
        // If no relationships were created, create default mappings
        if (empty($counterLayanans)) {
            $this->command->warn('No mappings found, creating default relationships...');
            
            // Simple 1:1 mapping if counts match
            $count = min($services->count(), $counters->count());
            for ($i = 0; $i < $count; $i++) {
                $counterLayanans[] = [
                    'counter_id' => $counters[$i]->id,
                    'service_id' => $services[$i]->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $createdRelations++;
                $this->command->info("➕ Mapped service '{$services[$i]->name}' to counter '{$counters[$i]->name}' (default mapping)");
            }
        }
        
        // Insert the relationships
        if (!empty($counterLayanans)) {
            DB::table('counter_layanans')->insert($counterLayanans);
            $this->command->info("✅ Successfully created {$createdRelations} counter-service relationships");
            
            // Verify the relationships
            $this->verifyRelationships();
        } else {
            $this->command->error('❌ No counter-service relationships were created');
        }
    }
    
    /**
     * Verify that the relationships were created correctly
     */
    protected function verifyRelationships(): void
    {
        $relationships = DB::table('counter_layanans')
            ->join('services', 'counter_layanans.service_id', '=', 'services.id')
            ->join('counters', 'counter_layanans.counter_id', '=', 'counters.id')
            ->select(
                'services.name as service_name',
                'counters.name as counter_name',
                'counter_layanans.created_at'
            )
            ->get();
            
        if ($relationships->isEmpty()) {
            $this->command->warn('⚠️  No relationships found in the database');
            return;
        }
        
        $this->command->info("\n📋 Current Relationships:");
        $this->command->table(
            ['Service', 'Counter', 'Created At'],
            $relationships->map(function ($item) {
                return [
                    'Service' => $item->service_name,
                    'Counter' => $item->counter_name,
                    'Created At' => $item->created_at,
                ];
            })->toArray()
        );
    }
}