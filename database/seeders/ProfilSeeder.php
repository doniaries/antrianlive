<?php

namespace Database\Seeders;

use App\Models\Profil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('profils')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create directories if they don't exist
        if (!Storage::disk('public')->exists('logos')) {
            Storage::disk('public')->makeDirectory('logos');
        }
        if (!Storage::disk('public')->exists('favicons')) {
            Storage::disk('public')->makeDirectory('favicons');
        }

        // Create default profile with placeholder images
        Profil::create([
            'nama_instansi' => 'Klinik Sehat Bahagia',
            'alamat' => 'Jl. Contoh No. 123, Kota Bandung, Jawa Barat',
            'no_telepon' => '(022) 1234567',
            'email' => 'info@kliniksehatbahagia.com',
            'logo' => 'https://via.placeholder.com/200x100/3490dc/ffffff?text=Klinik+Sehat',
            'favicon' => 'https://via.placeholder.com/32/3490dc/ffffff?text=KSB',
        ]);
    }
}
