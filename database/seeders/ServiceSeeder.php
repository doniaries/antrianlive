<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('services')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Services
        $services = [
            ['name' => 'Poli Umum', 'code' => 'PU', 'is_active' => true],
            ['name' => 'Poli Gigi', 'code' => 'PG', 'is_active' => true],
            ['name' => 'Poli Anak', 'code' => 'PA', 'is_active' => true],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
