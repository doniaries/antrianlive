<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



// Route untuk welcome page - menampilkan welcome jika belum login, redirect ke dashboard jika sudah login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
})->name('welcome');

// Route untuk ambil tiket (accessible by all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/ambil-tiket', \App\Livewire\AmbilTiket::class)->name('ambil-tiket');
});

Route::impersonate();

// Route untuk manajemen pasien
Route::middleware(['auth', 'roles:superadmin,petugas'])->group(function () {
    Route::resource('patients', \App\Http\Controllers\PatientController::class);
});

// Route untuk mengambil tiket antrian (POST)
Route::post('/queue/ticket/take', function () {
    $validated = request()->validate([
        'service_id' => 'required|exists:services,id',
        'counter_id' => 'nullable|exists:counters,id',
        'patient_id' => 'required|exists:patients,id', // Add patient_id to the request
    ]);

    // Check if patient already has an active queue
    $hasActiveQueue = \App\Models\Antrian::where('patient_id', $validated['patient_id'])
        ->whereIn('status', ['waiting', 'processing'])
        ->whereDate('created_at', now()->format('Y-m-d'))
        ->exists();

    if ($hasActiveQueue) {
        return response()->json([
            'success' => false,
            'message' => 'Anda sudah memiliki tiket antrian yang aktif. Silakan selesaikan antrian sebelumnya terlebih dahulu.',
        ], 422);
    }

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
        'patient_id' => $validated['patient_id'], // Add patient_id to the queue
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

// API endpoint untuk running teks
Route::get('/api/running-teks', function () {
    $runningTeks = \App\Models\RunningTeks::orderBy('id', 'asc')
        ->get(['id', 'text']);

    return response()->json([
        'running_teks' => $runningTeks,
        'timestamp' => now()->toDateTimeString(),
    ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('api.running-teks');

// API endpoint untuk video
Route::get('/api/video', function () {
    try {
        $video = \App\Models\Video::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!$video) {
            return response()->json([
                'success' => true,
                'video' => null,
                'message' => 'Tidak ada video aktif yang ditemukan',
                'timestamp' => now()->toDateTimeString(),
            ], 200);
        }

        $response = [
            'success' => true,
            'video' => [
                'id' => $video->id,
                'url' => $video->type === 'file' ? asset('storage/' . $video->url) : $video->url,
                'type' => $video->type,
                'is_active' => (bool)$video->is_active,
                'created_at' => $video->created_at->toDateTimeString(),
                'updated_at' => $video->updated_at->toDateTimeString(),
            ],
            'timestamp' => now()->toDateTimeString(),
        ];

        return response()
            ->json($response)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error in video API: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Gagal memuat video',
            'message' => 'Terjadi kesalahan saat memuat video. Silakan coba lagi nanti.',
            'timestamp' => now()->toDateTimeString(),
        ], 500);
    }
})->name('api.video');

// Route untuk tiket front (alternatif tampilan ambil tiket)
Route::get('/tiket-front', function () {
    $profil = \App\Models\Profil::first();
    return view('tiket-front', compact('profil'));
})->name('tiket.front');

// Public patient routes (accessible without authentication)
Route::middleware(['web'])->group(function () {
    // Only allow guest patients to access login/register
    Route::middleware(['guest:patient'])->group(function () {
        Route::view('patient/login', 'patient.login')->name('patient.login');
        Route::view('patient/register', 'patient.register')->name('patient.register');
    });

    // Routes that require patient authentication
    Route::middleware(['auth:patient'])->group(function () {
        Route::get('patient/dashboard', \App\Livewire\Patient\Dashboard::class)->name('patient.dashboard');
        Route::get('patient/ticket', \App\Livewire\Patient\Ticket::class)->name('patient.ticket');

        Route::post('patient/logout', function () {
            auth()->guard('patient')->logout();
            return redirect('/');
        })->name('patient.logout');
    });
});

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

    // Route untuk Running Teks Management
    Route::prefix('running-teks')->group(function () {
        Route::get('/', \App\Livewire\RunningTeksManager::class)->name('running-teks.index');
        Route::get('/list', \App\Livewire\RunningTeksManager::class)->name('running-teks.list');
    });

    // Route untuk Video Management
    Route::prefix('video')->group(function () {
        Route::get('/', \App\Livewire\VideoManager::class)->name('video.index');
        Route::get('/list', \App\Livewire\VideoManager::class)->name('video.list');
    });

    // Route untuk User Management (hanya untuk superadmin)
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/users', \App\Livewire\UserManager::class)->name('users.index');
    });

    // Route untuk Manajemen Pasien
    Route::prefix('patients')->group(function () {
        Route::get('/', [\App\Http\Controllers\PatientController::class, 'index'])->name('patients.index');
        Route::get('/create', [\App\Http\Controllers\PatientController::class, 'create'])->name('patients.create');
        Route::post('/', [\App\Http\Controllers\PatientController::class, 'store'])->name('patients.store');
        Route::get('/{patient}/edit', [\App\Http\Controllers\PatientController::class, 'edit'])->name('patients.edit');
        Route::put('/{patient}', [\App\Http\Controllers\PatientController::class, 'update'])->name('patients.update');
        Route::delete('/{patient}', [\App\Http\Controllers\PatientController::class, 'destroy'])->name('patients.destroy');
    });
});
