<?php

use Illuminate\Support\Facades\Route;

// Route untuk welcome page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route untuk ambil tiket (public access)
Route::get('/ambil-tiket', function () {
    return view('ambil-tiket');
})->name('ambil.tiket');

// Route untuk display antrian (public access)
Route::get('/display', function () {
    return view('display');
})->name('display');

require __DIR__.'/auth.php';

Route::middleware([
    'auth',
    'verified',
])->group(function () {
    Route::get('/dashboard', \App\Livewire\DashboardStats::class)->name('dashboard');
    Route::get('/antrian', \App\Livewire\AntrianIndex::class)->name('antrian.index');
    Route::get('/antrians', \App\Livewire\AntrianIndex::class)->name('antrians.index');
    Route::get('/counter', \App\Livewire\CounterManager::class)->name('counter.index');
    Route::get('/counters', \App\Livewire\CounterManager::class)->name('counters.index');
    Route::get('/services', \App\Livewire\ServiceManager::class)->name('services.index');
    Route::get('/service', \App\Livewire\ServiceManager::class)->name('service.index');
    Route::get('/profile', \App\Livewire\ProfilManager::class)->name('profile.edit');
    Route::get('/profil', \App\Livewire\ProfilManager::class)->name('profil.index');
    
    // Route untuk settings
    Route::prefix('settings')->group(function () {
        Route::get('/profile', \App\Livewire\Settings\Profile::class)->name('settings.profile');
        Route::get('/password', \App\Livewire\Settings\Password::class)->name('settings.password');
        Route::get('/appearance', \App\Livewire\Settings\Appearance::class)->name('settings.appearance');
        Route::get('/delete-account', \App\Livewire\Settings\DeleteUserForm::class)->name('settings.delete-account');
    });
});
