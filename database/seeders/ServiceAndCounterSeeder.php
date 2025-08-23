<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceAndCounterSeeder extends Seeder
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
        DB::table('services')->truncate();

        // Create Services
        $services = [
            ['name' => 'Poli Umum', 'code' => 'PU', 'is_active' => true],
            ['name' => 'Poli Gigi', 'code' => 'PG', 'is_active' => true],
            ['name' => 'Poli Anak', 'code' => 'PA', 'is_active' => true],
            ['name' => 'Pendaftaran', 'code' => 'PD', 'is_active' => true],
            ['name' => 'Pembayaran', 'code' => 'PB', 'is_active' => true],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create Counters
        $counters = [
            [
                'name' => 'Loket 1',
                'description' => 'Loket untuk layanan umum dan pendaftaran',
                'services' => ['A', 'D'] // Kode layanan yang dilayani
            ],
            [
                'name' => 'Loket 2',
                'description' => 'Loket untuk layanan gigi dan anak',
                'services' => ['B', 'C']
            ],
            [
                'name' => 'Loket 3',
                'description' => 'Loket khusus pembayaran',
                'services' => ['E']
            ],
        ];

        foreach ($counters as $counterData) {
            $services = Service::whereIn('code', $counterData['services'])->pluck('id');
            unset($counterData['services']);

            $counter = Counter::create($counterData);
            $counter->services()->attach($services);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
