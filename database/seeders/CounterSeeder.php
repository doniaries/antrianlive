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
                'name' => 'Loket Poli Umum',
                'description' => 'Counter untuk layanan Poli Umum',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Loket Poli Syaraf',
                'description' => 'Counter untuk layanan Poli Syaraf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Loket Poli Anak',
                'description' => 'Counter untuk layanan Poli Anak',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Loket Umum',
                'description' => 'Counter umum untuk semua layanan poliklinik',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('counters')->insert($counters);
    }
}