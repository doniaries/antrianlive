<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Antrian</h1>
                    <p class="text-sm text-gray-600">Sistem Antrian Pelayanan</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">{{ now()->format('d/m/Y') }}</div>
                    <div class="text-lg font-bold text-gray-900">{{ now()->format('H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $statistics['total_today'] }}</div>
                    <div class="text-sm text-gray-600">Total Hari Ini</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $statistics['waiting'] }}</div>
                    <div class="text-sm text-gray-600">Menunggu</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ $statistics['called'] }}</div>
                    <div class="text-sm text-gray-600">Dipanggil</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $statistics['finished'] }}</div>
                    <div class="text-sm text-gray-600">Selesai</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $statistics['skipped'] }}</div>
                    <div class="text-sm text-gray-600">Dilewati</div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Currently Called -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Antrian yang Dipanggil</h2>
                    </div>
                    <div class="p-4">
                        @if($calledQueues->isEmpty())
                            <div class="text-center py-8">
                                <div class="text-gray-500">Belum ada antrian yang dipanggil</div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($calledQueues as $queue)
                                    <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-3xl font-bold text-yellow-800">
                                                {{ $queue->formatted_number }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $queue->service->name }}</div>
                                                <div class="text-sm text-gray-600">
                                                    Loket: {{ $queue->counter?->name ?? 'Tidak ditentukan' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600">{{ $queue->called_at->format('H:i:s') }}</div>
                                            <button 
                                                wire:click="finishQueue({{ $queue->id }})" 
                                                class="mt-1 px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700"
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
                <div class="mt-6 bg-white rounded-lg shadow">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Antrian Menunggu</h2>
                    </div>
                    <div class="p-4">
                        @if($waitingQueues->isEmpty())
                            <div class="text-center py-8">
                                <div class="text-gray-500">Semua antrian telah selesai</div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($services as $service)
                                    @if(isset($waitingQueues[$service->id]) && $waitingQueues[$service->id]->isNotEmpty())
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($waitingQueues[$service->id]->take(5) as $queue)
                                                <div class="px-3 py-2 bg-blue-100 text-blue-800 rounded text-sm font-medium">
                                                    {{ $queue->formatted_number }}
                                                </div>
                                            @endforeach
                                            @if($waitingQueues[$service->id]->count() > 5)
                                                <div class="px-3 py-2 bg-gray-100 text-gray-600 rounded text-sm">
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
                <div class="bg-white rounded-lg shadow">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Panel Kontrol</h2>
                    </div>
                    <div class="p-4">
                        @foreach($services as $service)
                            <div class="mb-4 p-3 border border-gray-200 rounded-lg">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
                                <div class="text-sm text-gray-600 mb-2">
                                    Menunggu: {{ isset($waitingQueues[$service->id]) ? $waitingQueues[$service->id]->count() : 0 }}
                                </div>
                                
                                @php
                                    $nextQueue = $this->getNextQueue($service->id);
                                @endphp

                                @if($nextQueue)
                                    <div class="mb-2">
                                        <div class="text-sm text-gray-600">Berikutnya:</div>
                                        <div class="text-lg font-bold text-blue-600">{{ $nextQueue->formatted_number }}</div>
                                    </div>
                                @endif

                                <div class="space-y-2">
                                    @foreach($counters as $counter)
                                        @if($counter->services->contains($service))
                                            <button 
                                                wire:click="callNext({{ $service->id }}, {{ $counter->id }})" 
                                                class="w-full px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
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
                <div class="mt-4 bg-white rounded-lg shadow">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Aksi Cepat</h2>
                    </div>
                    <div class="p-4 space-y-2">
                        <button 
                            wire:click="$refresh" 
                            class="w-full px-3 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
                        >
                            Refresh Data
                        </button>
                        <a 
                            href="{{ route('antrians.index') }}" 
                            class="w-full px-3 py-2 bg-purple-600 text-white text-sm rounded hover:bg-purple-700 text-center block"
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
