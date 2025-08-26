<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profils')->insert([
            'nama_instansi' => 'Kantor Pelayanan Terpadu',
            'alamat' => 'Jl. Pelayanan No. 123, Kota Administrasi',
            'no_telepon' => '(021) 12345678',
            'email' => 'info@kantorpelayanan.go.id',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}