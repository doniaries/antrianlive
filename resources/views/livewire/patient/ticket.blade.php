<div class="container mx-auto p-4 dark:bg-gray-900 min-h-screen">
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Ambil Tiket</h1>
    
    <!-- Form Ambil Tiket -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Formulir Pemesanan Tiket</h2>
        
        <form wire:submit.prevent="createTicket">
            <!-- Tipe Pasien -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="patient_type">
                    Tipe Pasien
                </label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="patient_type" value="umum" class="form-radio h-4 w-4 text-blue-600 dark:text-blue-500" checked>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Umum</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="patient_type" value="bpjs" class="form-radio h-4 w-4 text-blue-600 dark:text-blue-500">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">BPJS</span>
                    </label>
                </div>
            </div>

            <!-- Nomor BPJS (hanya tampil jika tipe BPJS dipilih) -->
            @if($patient_type === 'bpjs')
                <div class="mb-6" x-data="{ showBpjs: true }" x-show="showBpjs" x-transition>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="bpjs_number">
                        Nomor BPJS
                    </label>
                    <div class="flex items-center">
                        <input type="text" id="bpjs_number" 
                               wire:model.defer="bpjs_number"
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan nomor BPJS"
                               @if($bpjs_number) value="{{ $bpjs_number }}" @endif>
                        <button type="button" 
                                wire:click="checkBpjs"
                                class="ml-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 ease-in-out">
                            Cek
                        </button>
                    </div>
                    @error('bpjs_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="service">
                    Pilih Layanan
                </label>
                <select id="service" 
                        wire:model.defer="service_id"
                        class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" class="dark:bg-gray-700">{{ $service->name }}</option>
                    @endforeach
                </select>
                @error('service_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                    Ambil Tiket
                </button>
            </div>
        </form>
    </div>
    
    <!-- Riwayat Tiket -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Riwayat Tiket</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No. Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Data akan diisi melalui Livewire -->
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Belum ada riwayat tiket
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-6 py-3 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-400">
                    Menampilkan <span class="font-medium">0</span> dari <span class="font-medium">0</span> data
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50" disabled>
                        Sebelumnya
                    </button>
                    <button class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50" disabled>
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
