<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('counter_layanans')->truncate();
        DB::table('counters')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define counters with their associated service codes
        $counters = [
            [
                'name' => 'Loket 1',
                'description' => 'Loket untuk Pendaftaran',
                'services' => ['PD'] // Pendaftaran
            ],
            [
                'name' => 'Loket 2',
                'description' => 'Loket untuk layanan gigi',
                'services' => ['PG'] // Poli Gigi
            ],
            [
                'name' => 'Loket 3',
                'description' => 'Loket khusus Poli Umum',
                'services' => ['PU'] // Poli Umum
            ],
            [
                'name' => 'Loket 4',
                'description' => 'Loket khusus Poli Anak',
                'services' => ['PA'] // Poli Anak
            ],
            [
                'name' => 'Loket 5',
                'description' => 'Loket Pembayaran',
                'services' => ['PB'] // Pembayaran
            ],
        ];

        foreach ($counters as $counterData) {
            // Create the counter
            $counter = Counter::create([
                'name' => $counterData['name'],
                'description' => $counterData['description']
            ]);

            // Attach services to the counter
            $services = Service::whereIn('code', $counterData['services'])->pluck('id');
            $counter->services()->attach($services);
        }
    }
}
