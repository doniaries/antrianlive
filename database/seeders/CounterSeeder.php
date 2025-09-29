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
                'description' => 'Loket untuk layanan Poli Umum (PU)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Klaster 2',
                'description' => 'Loket untuk layanan Poli Syaraf (PS)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Klaster 3',
                'description' => 'Loket untuk layanan Poli Anak (PA)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('counters')->insert($counters);
    }
}
