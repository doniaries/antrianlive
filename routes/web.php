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

// API endpoint untuk data display (optimized)
    Route::get('/api/display-data', function () {
        $today = now()->format('Y-m-d');
        
        // Get all active services with single query
        $services = \App\Models\Service::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
        
        // Get current called queues - only active calls (not finished)
        $currentCalled = \App\Models\Antrian::with(['service:id,name', 'counter:id,name'])
            ->whereDate('created_at', $today)
            ->where('status', 'called')
            ->whereNull('finished_at') // Pastikan antrian belum selesai
            ->where('called_at', '>=', now()->subMinutes(3)) // Hanya tampilkan yang baru dipanggil
            ->orderBy('called_at', 'desc')
            ->limit(1) // Hanya ambil 1 antrian yang sedang aktif dipanggil
            ->get(['id', 'service_id', 'counter_id', 'formatted_number', 'called_at'])
            ->map(function ($antrian) {
                return [
                    'id' => $antrian->id,
                    'service_id' => $antrian->service_id,
                    'formatted_number' => $antrian->formatted_number,
                    'service_name' => $antrian->service->name,
                    'counter_name' => $antrian->counter?->name,
                    'called_at' => $antrian->called_at,
                ];
            });

        // Get next queues efficiently
        $nextQueues = collect();
        $serviceIds = $services->pluck('id');
        
        foreach ($serviceIds as $serviceId) {
            $nextQueue = \App\Models\Antrian::where('service_id', $serviceId)
                ->whereDate('created_at', $today)
                ->where('status', 'waiting')
                ->orderBy('queue_number')
                ->first(['id', 'service_id', 'formatted_number', 'queue_number']);
                
            if ($nextQueue) {
                $service = $services->firstWhere('id', $serviceId);
                $nextQueues->push([
                    'id' => $nextQueue->id,
                    'service_id' => $serviceId,
                    'service_name' => $service->name,
                    'formatted_number' => $nextQueue->formatted_number,
                    'queue_number' => $nextQueue->queue_number,
                ]);
            }
        }

        // Format services data efficiently
        $servicesData = $services->map(function ($service) use ($today) {
            // Get range with single query per service
            $queues = \App\Models\Antrian::where('service_id', $service->id)
                ->whereDate('created_at', $today)
                ->orderBy('queue_number')
                ->get(['queue_number']);
                
            $range = '';
            if ($queues->count() > 0) {
                $first = $queues->first()->queue_number;
                $last = $queues->last()->queue_number;
                $range = $service->code . str_pad($first, 3, '0', STR_PAD_LEFT) . ' - ' . 
                         $service->code . str_pad($last, 3, '0', STR_PAD_LEFT);
            }

            return [
                'id' => $service->id,
                'name' => $service->name,
                'code' => $service->code,
                'range' => $range,
            ];
        });

        return response()->json([
            'currentCalled' => $currentCalled,
            'nextQueues' => $nextQueues,
            'services' => $servicesData,
            'timestamp' => now()->toDateTimeString(),
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
           ->header('Pragma', 'no-cache')
           ->header('Expires', '0')
           ->header('Content-Type', 'application/json');
    })->name('api.display-data');

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
