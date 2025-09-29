<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        try {
            // Disable foreign key checks to avoid constraint issues
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Clear existing data in the correct order to respect foreign key constraints
            $this->truncateTables();
            
            // Run seeders in the correct order
            $this->call([
                ProfilSeeder::class,      // 1. Profile data first
                ServiceSeeder::class,     // 2. Services next
                CounterSeeder::class,     // 3. Then counters
                CounterLayananSeeder::class, // 4. Then counter-service relationships
                PatientSeeder::class,     // 5. Then patients
                UserSeeder::class,        // 6. Then users
                RunningTextSeeder::class, // 7. Then running texts
            ]);
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->command->info('\n✅ Database seeding completed successfully!');
            
        } catch (\Exception $e) {
            // Make sure to re-enable foreign key checks even if there's an error
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->command->error('❌ Error seeding database: ' . $e->getMessage());
            throw $e; // Re-throw the exception to see the full stack trace
        }
    }
    
    /**
     * Truncate all tables in the correct order to respect foreign key constraints
     */
    protected function truncateTables(): void
    {
        // List tables in reverse order of dependency
        $tables = [
            'counter_layanans', // Depends on both counters and services
            'antrians',         // Depends on services and counters
            'counters',         // Independent
            'services',         // Independent
            'patients',         // Independent
            'users',            // Independent
            'running_texts',    // Independent
            'profils',          // Independent
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("Truncated table: {$table}");
            }
        }
    }
}
