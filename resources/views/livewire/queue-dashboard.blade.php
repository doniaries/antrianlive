<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Antrian</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sistem Antrian Pelayanan</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ now()->format('d/m/Y') }}</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white" x-data="{ time: '' }" x-init="() => {
                        const updateTime = () => {
                            const now = new Date();
                            const hours = String(now.getHours()).padStart(2, '0');
                            const minutes = String(now.getMinutes()).padStart(2, '0');
                            const seconds = String(now.getSeconds()).padStart(2, '0');
                            time = `${hours}:${minutes}:${seconds}`;
                        };
                        updateTime();
                        setInterval(updateTime, 1000);
                    }" x-text="time"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $statistics['total_today'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Hari Ini</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $statistics['waiting'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Menunggu</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $statistics['called'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Dipanggil</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $statistics['finished'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Selesai</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $statistics['skipped'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Dilewati</div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Currently Called -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Antrian yang Dipanggil</h2>
                    </div>
                    <div class="p-4">
                        @if($calledQueues->isEmpty())
                            <div class="text-center py-8">
                                <div class="text-gray-500 dark:text-gray-400">Belum ada antrian yang dipanggil</div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($calledQueues as $queue)
                                    <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg transition-colors duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-3xl font-bold text-yellow-800 dark:text-yellow-400">
                                                {{ $queue->formatted_number }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-yellow-100">{{ $queue->service->name }}</div>
                                                <div class="text-sm text-gray-600 dark:text-yellow-300/80">
                                                    Loket: {{ $queue->counter?->name ?? 'Tidak ditentukan' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600 dark:text-yellow-300/80">{{ $queue->called_at->format('H:i:s') }}</div>
                                            <button
                                                wire:click="finishQueue({{ $queue->id }})"
                                                class="mt-1 px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors duration-200"
                                            >
                                                Selesai
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Waiting Queues by Service -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Antrian Menunggu</h2>
                    </div>
                    <div class="p-4">
                        @if($waitingQueues->isEmpty())
                            <div class="text-center py-8">
                                <div class="text-gray-500 dark:text-gray-400">Semua antrian telah selesai</div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($services as $service)
                                    @if(isset($waitingQueues[$service->id]) && $waitingQueues[$service->id]->isNotEmpty())
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-gray-200 mb-2">{{ $service->name }}</h3>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($waitingQueues[$service->id]->take(5) as $queue)
                                                <div class="px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded text-sm font-medium transition-colors duration-200">
                                                    {{ $queue->formatted_number }}
                                                </div>
                                            @endforeach
                                            @if($waitingQueues[$service->id]->count() > 5)
                                                <div class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded text-sm transition-colors duration-200">
                                                    +{{ $waitingQueues[$service->id]->count() - 5 }} lagi
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Control Panel -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Panel Kontrol</h2>
                    </div>
                    <div class="p-4">
                        @foreach($services as $service)
                            <div class="mb-4 p-3 border border-gray-200 dark:border-gray-700 rounded-lg transition-colors duration-200">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-200 mb-2">{{ $service->name }}</h3>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    Menunggu: {{ isset($waitingQueues[$service->id]) ? $waitingQueues[$service->id]->count() : 0 }}
                                </div>

                                @php
                                    $nextQueue = $this->getNextQueue($service->id);
                                @endphp

                                @if($nextQueue)
                                    <div class="mb-2">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Berikutnya:</div>
                                        <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $nextQueue->formatted_number }}</div>
                                    </div>
                                @endif

                                <div class="space-y-2">
                                    @foreach($counters as $counter)
                                        @if($counter->services->contains($service))
                                            <button
                                                wire:click="callNext({{ $service->id }}, {{ $counter->id }})"
                                                class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                @if(!$nextQueue) disabled @endif
                                            >
                                                Panggil ke {{ $counter->name }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aksi Cepat</h2>
                    </div>
                    <div class="p-4 space-y-2">
                        <button
                            wire:click="$refresh"
                            class="w-full px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors duration-200"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                        >
                            <div class="flex items-center justify-center">
                                <svg wire:loading wire:target="$refresh" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Refresh Data</span>
                            </div>
                        </button>
                        <a
                            href="{{ route('antrians.index') }}"
                            class="w-full px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded transition-colors duration-200 text-center block"
                        >
                            Kelola Antrian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sound Notification Script -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('queue-called', (data) => {
                // Play sound notification
                const audio = new Audio('/audio/notification.mp3');
                audio.play().catch(e => console.log('Audio play failed:', e));

                // Show browser notification if supported
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('Antrian Dipanggil', {
                        body: `Nomor ${data[0].number} ke ${data[0].counter}`,
                        icon: '/favicon.ico'
                    });
                }
            });
        });

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Auto refresh every 30 seconds
        setInterval(() => {
            Livewire.dispatch('refresh-dashboard');
        }, 30000);
    </script>
</div>
