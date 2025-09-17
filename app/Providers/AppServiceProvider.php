<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Reset antrian otomatis saat berganti hari
        if (app()->environment() !== 'testing') {
            $this->setupDailyQueueReset();
        }
    }
    
    /**
     * Setup reset antrian otomatis setiap hari
     */
    protected function setupDailyQueueReset(): void
    {
        // Cek apakah ada file penanda hari terakhir reset
        $lastResetFile = storage_path('app/last_queue_reset.txt');
        $today = now()->format('Y-m-d');
        
        // Jika file tidak ada atau tanggal berbeda, lakukan reset
        if (!file_exists($lastResetFile) || file_get_contents($lastResetFile) !== $today) {
            // Reset antrian dari hari sebelumnya
            \App\Models\Antrian::whereDate('created_at', '<', $today)->delete();
            
            // Simpan tanggal reset terakhir
            file_put_contents($lastResetFile, $today);
            
            \Log::info('Antrian otomatis direset pada: ' . now());
        }
    }
}
