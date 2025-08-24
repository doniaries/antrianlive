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
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard utama antrian
    Route::get('/dashboard', QueueDashboard::class)->name('dashboard');

    // Manajemen Layanan
    Route::get('/services', ServiceManager::class)->name('services.index');

    // Manajemen Loket
    Route::get('/counters', CounterManager::class)->name('counters.index');

    // Manajemen Antrian
    Route::get('/antrians', AntrianManager::class)->name('antrians.index');

    // Settings (existing)
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::get('/profil', App\Livewire\ProfilManager::class)->name('profil.index');


// Public display untuk customer (opsional)
Route::get('/display', QueueDashboard::class)->name('queue.display');

// Halaman pengambilan tiket antrian
Route::get('/ticket', [\App\Http\Controllers\QueueTicketController::class, 'index'])->name('queue.ticket');
Route::post('/ticket/take', [\App\Http\Controllers\QueueTicketController::class, 'takeTicket'])->name('queue.ticket.take');
Route::get('/ticket/success/{antrian}', [\App\Http\Controllers\QueueTicketController::class, 'success'])->name('queue.ticket.success');

require __DIR__ . '/auth.php';
