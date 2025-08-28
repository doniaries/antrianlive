<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\QueueDashboard;
use App\Livewire\ServiceManager;
use App\Livewire\CounterManager;
use App\Livewire\AntrianManager;
use App\Livewire\ProfilManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard utama antrian
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Layanan
    Route::get('/services', ServiceManager::class)->name('services.index');

    // Manajemen Loket
    Route::get('/counters', CounterManager::class)->name('counters.index');

    // Manajemen Antrian
    Route::get('/antrians', AntrianManager::class)->name('antrians.index');
    Route::get('/antrians/selesai', \App\Livewire\AntrianSelesaiManager::class)->name('antrian.selesai');

    // Settings (existing)
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::get('/profil', App\Livewire\ProfilManager::class)->name('profil.index');


// Public display untuk customer (opsional)
Route::get('/display', [\App\Http\Controllers\QueueDisplayController::class, 'index'])->name('display');
Route::get('/display/data', [\App\Http\Controllers\QueueDisplayController::class, 'getQueueData'])->name('display.data');

// Halaman pengambilan tiket antrian
use App\Models\Service;

Route::get('/ambil-tiket', function () {
    $services = Service::with('counters')
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('ambil-tiket', compact('services'));
})->name('ambil.tiket');
Route::post('/ticket/take', [\App\Http\Controllers\QueueTicketController::class, 'takeTicket'])->name('queue.ticket.take');


require __DIR__ . '/auth.php';
