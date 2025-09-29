<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        try {
            // Only run seeders that exist and are needed
            $seeders = [
                'ProfilSeeder',
                'UserSeeder',
                'ServiceSeeder',
                'CounterSeeder',
                'PatientSeeder',
                'RunningTextSeeder'
            ];

            foreach ($seeders as $seeder) {
                if (class_exists("Database\\Seeders\\{$seeder}")) {
                    $this->call("Database\\Seeders\\{$seeder}");
                    $this->command->info("Seeded: {$seeder}");
                } else {
                    $this->command->warn("Skipping non-existent seeder: {$seeder}");
                }
            }

            $this->command->info('Database seeding completed successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error seeding database: ' . $e->getMessage());
        }
    }
}
