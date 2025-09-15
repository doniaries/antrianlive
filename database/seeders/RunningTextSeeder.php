<?php

namespace Database\Seeders;

use App\Models\RunningTeks;
use Illuminate\Database\Seeder;

class RunningTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $texts = [
            [
                'text' => 'Selamat datang di Sistem Antrian Digital kami',
                'is_active' => true,
            ],
            [
                'text' => 'Silakan ambil nomor antrian sesuai layanan yang Anda butuhkan',
                'is_active' => true,
            ],
            [
                'text' => 'Pastikan Anda menunggu di area yang telah ditentukan',
                'is_active' => true,
            ],
            [
                'text' => 'Terima kasih atas kesabaran Anda dalam menunggu',
                'is_active' => true,
            ],
            [
                'text' => 'Sistem antrian ini dirancang untuk kenyamanan Anda',
                'is_active' => true,
            ],
        ];

        foreach ($texts as $text) {
            RunningTeks::create($text);
        }
    }
}