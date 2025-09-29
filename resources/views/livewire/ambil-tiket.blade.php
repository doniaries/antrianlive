<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-8" x-data="{ loading: false }">
    @php
        use App\Models\Service;
        use App\Models\Counter;
        use App\Models\Profil;
        
        // Check if user is authenticated
        if (!auth()->check()) {
            header('Location: ' . route('login', ['redirect' => urlencode(request()->fullUrl())]));
            exit();
        }
        
        $services = Service::with(['counters' => function($query) {
            $query->where('status', '!=', 'tutup')
                  ->orderBy('name');
        }])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();
        
        $profil = Profil::first();
        $pageTitle = ($profil->nama_aplikasi ?? 'Ambil Tiket Antrian') . ' - ' . ($profil->nama_instansi ?? 'Sistem Antrian');
        $__env->startSection('title', $pageTitle);
    @endphp

    <!-- Loading Overlay -->
    <div x-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
            <p class="text-gray-700">Memproses...</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto">
        <header class="mb-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    @if ($profil && $profil->logo)
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="h-16 w-auto">
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $profil->nama_instansi ?? 'Sistem Antrian' }}</h1>
                        <p class="text-gray-600">{{ $profil->alamat ?? '' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xl font-semibold time-display"></div>
                    <div class="text-sm text-gray-600">ID: {{ auth()->user()->id ?? 'Guest' }}</div>
                </div>
            </div>
        </header>

        <main>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $service)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-4 bg-blue-600 text-white">
                            <h2 class="text-xl font-semibold">{{ $service->name }}</h2>
                        </div>
                        <div class="p-4">
                            @if($service->counters->isEmpty())
                                <p class="text-gray-500 text-center py-4">Tidak ada loket tersedia</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($service->counters as $counter)
                                        @php
                                            $statusColors = [
                                                'buka' => 'bg-green-100 text-green-800',
                                                'tutup' => 'bg-red-100 text-red-800',
                                                'istirahat' => 'bg-yellow-100 text-yellow-800'
                                            ];
                                            $statusColor = $statusColors[strtolower($counter->status)] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <div class="border rounded-lg p-3">
                                            <div class="flex justify-between items-center mb-2">
                                                <h3 class="font-medium">{{ $counter->name }}</h3>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                                    {{ ucfirst($counter->status) }}
                                                </span>
                                            </div>
                                            @if($counter->description)
                                                <p class="text-sm text-gray-600 mb-3">{{ $counter->description }}</p>
                                            @endif
                                            <button 
                                                wire:click="takeTicket({{ $service->id }}, {{ $counter->id }})"
                                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-150 ease-in-out flex items-center justify-center"
                                                {{ $counter->status !== 'buka' ? 'disabled' : '' }}>
                                                <i class="fas fa-ticket-alt mr-2"></i> Ambil Antrian
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">Tidak ada layanan tersedia saat ini</p>
                    </div>
                @endforelse
            </div>
        </main>

        <footer class="mt-8 text-center text-sm text-gray-500">
            <p>{{ date('Y') }} {{ $profil->nama_instansi ?? 'Sistem Antrian' }} • All rights reserved</p>
        </footer>
    </div>

    <!-- Notification -->
    <div id="notification" class="fixed inset-0 hidden items-center justify-center z-50">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 relative">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h3 id="notificationTitle" class="text-lg font-medium text-gray-900 mb-2"></h3>
                <div id="notificationContent" class="mt-2"></div>
                <div class="mt-6">
                    <button onclick="document.getElementById('notification').classList.add('hidden')" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
        <script>
            // Update time function
            function updateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                    timeZone: 'Asia/Jakarta'
                };
                const timeDisplay = document.querySelector('.time-display');
                if (timeDisplay) {
                    timeDisplay.textContent = now.toLocaleDateString('id-ID', options);
                }
            }

            // Initialize time and update every second
            updateTime();
            setInterval(updateTime, 1000);

            // Confetti effect
            function triggerConfetti() {
                const duration = 3 * 1000;
                const animationEnd = Date.now() + duration;
                const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

                function randomInRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                const interval = setInterval(function() {
                    const timeLeft = animationEnd - Date.now();
                    if (timeLeft <= 0) return clearInterval(interval);
                    
                    const particleCount = 50 * (timeLeft / duration);
                    confetti({
                        ...defaults,
                        particleCount,
                        origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                    });
                    confetti({
                        ...defaults,
                        particleCount,
                        origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                    });
                }, 250);
            }

            // Listen for Livewire events
            document.addEventListener('DOMContentLoaded', function() {
                window.addEventListener('ticket-created', event => {
                    const notification = document.getElementById('notification');
                    const title = document.getElementById('notificationTitle');
                    const content = document.getElementById('notificationContent');
                    
                    title.textContent = 'Berhasil!';
                    content.innerHTML = `
                        <p class="text-2xl font-bold text-gray-900 mb-2">${event.detail.ticket_number}</p>
                        <p class="text-gray-600">${event.detail.service_name} • ${event.detail.counter_name || 'Umum'}</p>
                    `;
                    
                    notification.classList.remove('hidden');
                    notification.classList.add('flex');
                    triggerConfetti();
                });

                window.addEventListener('ticket-error', event => {
                    const notification = document.getElementById('notification');
                    const title = document.getElementById('notificationTitle');
                    const content = document.getElementById('notificationContent');
                    
                    title.textContent = 'Gagal';
                    content.innerHTML = `<p class="text-red-600">${event.detail.message || 'Terjadi kesalahan saat mengambil tiket'}</p>`;
                    
                    notification.classList.remove('hidden');
                    notification.classList.add('flex');
                });
            });
        </script>
    @endpush
</div>
