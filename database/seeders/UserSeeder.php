<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin - akses penuh
        $admin = User::firstOrCreate([
            'email' => 'superadmin@gmail.com'
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('@Iamsuperadmin'),
            'email_verified_at' => now(),
            'role' => 'superadmin',
        ]);

        // Petugas 1 - akses ke semua layanan
        $petugas1 = User::firstOrCreate([
            'email' => 'petugas1@gmail.com'
        ], [
            'name' => 'Petugas 1',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'petugas',
        ]);

        // Petugas 2 - akses terbatas
        $petugas2 = User::firstOrCreate([
            'email' => 'petugas2@gmail.com'
        ], [
            'name' => 'Petugas 2',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'petugas',
        ]);

        // Assign layanan ke petugas
        $services = Service::all();
        if ($services->isNotEmpty()) {
            // Petugas 1: akses semua layanan
            $petugas1->services()->sync($services->pluck('id'));
            
            // Petugas 2: akses hanya layanan pertama dan kedua
            $petugas2->services()->sync($services->take(2)->pluck('id'));
        }
    }
}