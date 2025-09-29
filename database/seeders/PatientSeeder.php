<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '081234567890',
                'bpjs_number' => '0001234567890',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@example.com',
                'phone' => '082345678901',
                'bpjs_number' => '0001234567891',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@example.com',
                'phone' => '083456789012',
                'bpjs_number' => '0001234567892',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@example.com',
                'phone' => '084567890123',
                'bpjs_number' => '0001234567893',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Joko Widodo',
                'email' => 'joko.widodo@example.com',
                'phone' => '085678901234',
                'bpjs_number' => '0001234567894',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }

        $this->command->info('Berhasil menambahkan 5 data pasien.');
    }
}
