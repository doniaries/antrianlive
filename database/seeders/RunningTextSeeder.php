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
            ['text' => 'Selamat datang di Sistem Antrian Digital kami!!!'],
            ['text' => 'Silakan ambil nomor antrian sesuai layanan yang Anda butuhkan'],
            ['text' => 'Pastikan Anda menunggu di area yang telah ditentukan'],
            ['text' => 'Terima kasih atas kesabaran Anda dalam menunggu'],
            ['text' => 'Sistem antrian ini dirancang untuk kenyamanan Anda'],
        ];

        foreach ($texts as $text) {
            RunningTeks::create($text);
        }
    }
}
