<div>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Ambil Nomor Antrian</h1>
                <p class="text-gray-600">Silakan pilih layanan yang Anda butuhkan</p>
            </div>

            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <form wire:submit.prevent="generateAntrian">
                        <div class="mb-6">
                            <label class="block text-lg font-medium text-gray-700 mb-4">Pilih Layanan</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($services as $service)
                                    <div class="relative">
                                        <input type="radio" 
                                               wire:model="selectedService" 
                                               value="{{ $service->id }}" 
                                               id="service_{{ $service->id }}"
                                               class="peer sr-only">
                                        <label for="service_{{ $service->id }}" 
                                               class="flex flex-col items-center p-6 bg-gray-50 rounded-lg border-2 border-gray-200 cursor-pointer peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-600 hover:bg-gray-100">
                                            <div class="text-2xl mb-2">{{ $service->icon ?? '📋' }}</div>
                                            <div class="font-semibold text-center">{{ $service->nama }}</div>
                                            <div class="text-sm text-gray-500">{{ $service->kode }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedService') 
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p> 
                            @enderror
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-blue-700 transition duration-200">
                            Ambil Nomor Antrian
                        </button>
                    </form>
                </div>

                <!-- Informasi -->
                <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ \App\Models\Antrian::whereDate('created_at', \Carbon\Carbon::today())->count() }}
                            </p>
                            <p class="text-sm text-gray-600">Total Antrian Hari Ini</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">
                                {{ \App\Models\Antrian::whereDate('created_at', \Carbon\Carbon::today())->where('status', 'completed')->count() }}
                            </p>
                            <p class="text-sm text-gray-600">Sudah Dilayani</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ \App\Models\Antrian::whereDate('created_at', \Carbon\Carbon::today())->where('status', 'pending')->count() }}
                            </p>
                            <p class="text-sm text-gray-600">Sedang Menunggu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nomor Antrian -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
                <div class="text-center">
                    <div class="mb-4">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Nomor Antrian Anda</h3>
                    <p class="text-gray-600 mb-6">Silakan catat nomor antrian Anda</p>
                    
                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <p class="text-5xl font-bold text-blue-600">{{ $nomorAntrian }}</p>
                    </div>
                    
                    <button wire:click="closeModal" 
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>