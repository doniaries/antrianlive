<?php

use Illuminate\Support\Facades\Route;

// Route untuk welcome page - menampilkan welcome jika belum login, redirect ke dashboard jika sudah login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// Route untuk ambil tiket (public access)
Route::get('/ambil-tiket', \App\Livewire\AmbilTiket::class)->name('ambil.tiket');

Route::impersonate();


// Route untuk ambil tiket (public access)
Route::get('/ambil-tiket', \App\Livewire\AmbilTiket::class)->name('ambil.tiket');

// Route untuk mengambil tiket antrian (POST)
Route::post('/queue/ticket/take', function () {
    $validated = request()->validate([
        'service_id' => 'required|exists:services,id',
        'counter_id' => 'nullable|exists:counters,id',
    ]);

    $service = \App\Models\Service::findOrFail($validated['service_id']);
    $counter = $validated['counter_id'] ? \App\Models\Counter::find($validated['counter_id']) : null;

    // Generate nomor antrian
    $today = now()->format('Y-m-d');
    $lastAntrian = \App\Models\Antrian::where('service_id', $validated['service_id'])
        ->whereDate('created_at', $today)
        ->orderBy('queue_number', 'desc')
        ->first();

    $nextNumber = $lastAntrian ? $lastAntrian->queue_number + 1 : 1;
    $formattedNumber = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    $antrian = \App\Models\Antrian::create([
        'service_id' => $validated['service_id'],
        'counter_id' => $validated['counter_id'],
        'queue_number' => $nextNumber,
        'formatted_number' => $formattedNumber,
        'status' => 'waiting',
    ]);



    return response()->json([
        'success' => true,
        'ticket_number' => $formattedNumber,
        'service_name' => $service->name,
        'counter_name' => $counter ? $counter->name : 'Umum',
    ]);
})->name('queue.ticket.take');

// Route untuk display antrian (public access)
Route::get('/display', function () {
    $profil = \App\Models\Profil::first();
    return view('display', compact('profil'));
})->name('display');

// Route untuk tiket front (alternatif tampilan ambil tiket)
Route::get('/tiket-front', function () {
    $profil = \App\Models\Profil::first();
    return view('tiket-front', compact('profil'));
})->name('tiket.front');

require __DIR__ . '/auth.php';

Route::middleware([
    'auth',
    'verified',
])->group(function () {
    Route::get('/dashboard', \App\Livewire\DashboardStats::class)->name('dashboard');

    // Route untuk Antrian - dikelompokkan
    Route::prefix('antrian')->group(function () {
        Route::get('/', \App\Livewire\AntrianManager::class)->name('antrian.index');
        Route::get('/list', \App\Livewire\AntrianManager::class)->name('antrians.index');
        Route::get('/selesai', \App\Livewire\AntrianManager::class)->name('antrian.selesai');
    });

    // Route untuk Counter - dikelompokkan
    Route::prefix('counter')->group(function () {
        Route::get('/', \App\Livewire\CounterManager::class)->name('counter.index');
        Route::get('/list', \App\Livewire\CounterManager::class)->name('counters.index');
    });

    // Route untuk Service - dikelompokkan
    Route::prefix('service')->group(function () {
        Route::get('/', \App\Livewire\ServiceManager::class)->name('service.index');
        Route::get('/list', \App\Livewire\ServiceManager::class)->name('services.index');
    });

    // Route untuk Profil
    Route::get('/profile', \App\Livewire\ProfilManager::class)->name('profile.edit');
    Route::get('/profil', \App\Livewire\ProfilManager::class)->name('profil.index');

    // Route untuk settings
    Route::prefix('settings')->group(function () {
        Route::get('/profile', \App\Livewire\Settings\Profile::class)->name('settings.profile');
        Route::get('/password', \App\Livewire\Settings\Password::class)->name('settings.password');
        Route::get('/appearance', \App\Livewire\Settings\Appearance::class)->name('settings.appearance');
        Route::get('/delete-account', \App\Livewire\Settings\DeleteUserForm::class)->name('settings.delete-account');
    });

    // Route untuk User Management (hanya untuk superadmin)
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/users', \App\Livewire\UserManager::class)->name('users.index');
    });
});
