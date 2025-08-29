<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use App\Livewire\DashboardStats;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::post('/antrian', [AntrianController::class, 'store'])->name('antrian.store');
    Route::get('/antrian/{antrian}', [AntrianController::class, 'show'])->name('antrian.show');
    Route::patch('/antrian/{antrian}/call', [AntrianController::class, 'call'])->name('antrian.call');
    Route::patch('/antrian/{antrian}/finish', [AntrianController::class, 'finish'])->name('antrian.finish');
    Route::patch('/antrian/{antrian}/skip', [AntrianController::class, 'skip'])->name('antrian.skip');
    Route::patch('/antrian/{antrian}/recall', [AntrianController::class, 'recall'])->name('antrian.recall');
    Route::get('/counter', [CounterController::class, 'index'])->name('counter.index');
    Route::get('/service', [ServiceController::class, 'index'])->name('service.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Test route for debugging chart data
Route::get('/test-chart-data', function() {
    $component = new DashboardStats();
    $component->loadData();
    
    return response()->json([
        'chartType' => $component->chartType,
        'chartData' => $component->chartData,
        'statistics' => $component->statistics,
        'hasData' => !empty($component->chartData['data']) && count($component->chartData['data']) > 0
    ]);
});
