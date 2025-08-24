@push('styles')
<style>
    .service-card {
        transition: all 0.3s ease;
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .counter-badge {
        position: absolute;
        top: -10px;
        right: -10px;
        z-index: 10;
    }
</style>
@endpush

<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Ambil Tiket Antrian</h1>
        <p class="text-gray-600 dark:text-gray-300">Pilih layanan yang tersedia di bawah ini</p>
    </div>

    <!-- Service Selection -->
    <div class="max-w-7xl mx-auto">
        <!-- Active Services -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Layanan Tersedia</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($activeServices as $service)
                    <div class="relative">
                        <div class="service-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-colors h-full flex flex-col">
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $service->name }}
                                    </h3>
                                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $service->code }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-6 flex-1">
                                    {{ $service->description ?? 'Layanan pelanggan' }}
                                </p>
                                
                                @if($service->counters->count() > 0)
                                    <div class="mt-auto">
                                        <label for="counter-{{ $service->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Pilih Loket
                                        </label>
                                        <select 
                                            id="counter-{{ $service->id }}"
                                            wire:model="selectedCounters.{{ $service->id }}"
                                            class="py-2 px-3 pr-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                        >
                                            <option value="">-- Pilih Loket --</option>
                                            @foreach($service->counters as $counter)
                                                <option value="{{ $counter->id }}">
                                                    {{ $counter->name }} ({{ $counter->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                
                                <div class="mt-6">
                                    <button 
                                        type="button"
                                        wire:click="takeTicket({{ $service->id }})"
                                        @if(!isset($selectedCounters[$service->id]) || empty($selectedCounters[$service->id]))
                                            disabled
                                            title="Pilih loket terlebih dahulu"
                                        @endif
                                        class="w-full py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                    >
                                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                        </svg>
                                        Ambil Antrian
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        @if($service->waiting_count > 0)
                            <span class="counter-badge inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-500 text-white text-xs font-semibold">
                                {{ $service->waiting_count }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        
        @if($inactiveServices->count() > 0)
            <div class="mt-12">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Layanan Sementara Tidak Tersedia</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($inactiveServices as $service)
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 opacity-70">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $service->name }}
                                </h3>
                                <span class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $service->code }}
                                </span>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                Layanan ini sementara tidak tersedia
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <!-- Success Modal -->
    @if($showTicketModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeTicketModal"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-t-xl sm:rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="h-10 w-10 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                Tiket Berhasil Dibuat
                            </h3>
                            <div class="mt-4">
                                <div class="bg-blue-50 dark:bg-blue-900/30 p-6 rounded-lg border border-blue-100 dark:border-blue-800">
                                    <div class="text-5xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                                        {{ $currentTicket?->formatted_number ?? '' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $currentTicket?->service?->name ?? '' }}
                                    </div>
                                    @if($currentTicket?->counter)
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Loket: {{ $currentTicket?->counter?->name }}
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                    Silakan menunggu nomor antrian Anda dipanggil.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <button 
                            type="button"
                            onclick="window.print()"
                            class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
                        >
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak
                        </button>
                        <button 
                            type="button"
                            wire:click="closeTicketModal"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Audio Elements -->
    <audio id="ticketSound" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>
</div>

@push('scripts')
<script>
    // Play ticket sound when modal is shown
    document.addEventListener('livewire:initialized', () => {
        @this.on('ticket-created', () => {
            const audio = document.getElementById('ticketSound');
            if (audio) {
                audio.currentTime = 0;
                audio.play().catch(e => console.error('Error playing sound:', e));
            }
        });
    });
</script>
@endpush
