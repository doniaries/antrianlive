<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Poli Umum',
                'code' => 'PU',
                'description' => 'Poli Umum melayani pemeriksaan kesehatan umum',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Poli Syaraf',
                'code' => 'PS',
                'description' => 'Poli Syaraf melayani pemeriksaan kesehatan saraf',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Poli Anak',
                'code' => 'PA',
                'description' => 'Poli Anak melayani pemeriksaan kesehatan anak',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Check if description column exists
        $hasDescriptionColumn = Schema::hasColumn('services', 'description');
        
        // Insert services if they don't exist
        foreach ($services as $service) {
            // If description column doesn't exist, remove it from the data
            if (!$hasDescriptionColumn) {
                unset($service['description']);
            }
            
            DB::table('services')->updateOrInsert(
                ['code' => $service['code']],
                $service
            );
        }
        
        $this->command->info('Services seeded successfully');
        
        if (!$hasDescriptionColumn) {
            $this->command->warn('⚠️  The description column does not exist in the services table.');
            $this->command->info('   Consider running: php artisan make:migration add_description_to_services_table --table=services');
            $this->command->info('   Then add: $table->text(\'description\')->nullable()->after(\'code\');');
        }
    }
}
