<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $counters = [
            [
                'name' => 'Klaster 1',
                'description' => 'Melayani: Poli Umum (PU)',
                'status' => 'buka', // buka, tutup, istirahat
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Klaster 2',
                'description' => 'Melayani: Poli Syaraf (PS)',
                'status' => 'buka',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Klaster 3',
                'description' => 'Melayani: Poli Anak (PA)',
                'status' => 'buka',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert counters if they don't exist
        foreach ($counters as $counter) {
            DB::table('counters')->updateOrInsert(
                ['name' => $counter['name']],
                $counter
            );
        }
        
        $this->command->info('Counters seeded successfully');
    }
}
