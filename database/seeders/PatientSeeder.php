<?php

namespace Database\Seeders;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
                'password' => bcrypt('password123'),
                'nik' => '1234560101010001',
                'date_of_birth' => '1990-01-01',
                'gender' => 'L',
                'phone' => '081234567890',
                'bpjs_number' => '0001234567890',
                'address' => 'Jl. Mawar No. 10, Jakarta Selatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@example.com',
                'password' => bcrypt('password123'),
                'nik' => '1234560202020002',
                'date_of_birth' => '1992-02-02',
                'gender' => 'P',
                'phone' => '082345678901',
                'bpjs_number' => '0001234567891',
                'address' => 'Jl. Melati No. 15, Jakarta Pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@example.com',
                'password' => bcrypt('password123'),
                'nik' => '1234560303030003',
                'date_of_birth' => '1988-03-03',
                'gender' => 'L',
                'phone' => '083456789012',
                'bpjs_number' => '0001234567892',
                'address' => 'Jl. Anggrek No. 20, Jakarta Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@example.com',
                'password' => bcrypt('password123'),
                'nik' => '1234560404040004',
                'date_of_birth' => '1995-04-04',
                'gender' => 'P',
                'phone' => '084567890123',
                'bpjs_number' => '0001234567893',
                'address' => 'Jl. Kamboja No. 25, Jakarta Utara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Joko Widodo',
                'email' => 'joko.widodo@example.com',
                'password' => bcrypt('password123'),
                'nik' => '1234560505050005',
                'date_of_birth' => '1961-06-21',
                'gender' => 'L',
                'phone' => '085678901234',
                'bpjs_number' => '0001234567894',
                'address' => 'Jl. Kenanga No. 30, Jakarta Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Use transaction to ensure data integrity
        DB::beginTransaction();
        
        try {
            foreach ($patients as $patient) {
                Patient::create($patient);
            }
            
            DB::commit();
            $this->command->info('Berhasil menambahkan ' . count($patients) . ' data pasien.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal menambahkan data pasien: ' . $e->getMessage());
        }
    }
}
