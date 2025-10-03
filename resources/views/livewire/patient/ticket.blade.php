<div class="container mx-auto p-4 dark:bg-gray-900 min-h-screen">
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Ambil Tiket</h1>

    <!-- Form Ambil Tiket -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Formulir Pemesanan Tiket</h2>

        <form wire:submit.prevent="createTicket" x-data="{ submitting: false }"
            @ticket-created.window="
                const response = $event.detail;
                if (response.success) {
                    // Show success message
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Tiket berhasil dibuat. Nomor antrian Anda: ' + response.ticket_number,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        // Redirect to tickets page
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
                submitting = false;
              "
            @submit="submitting = true">
            <!-- Tipe Pasien -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-3">
                    Tipe Pasien
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div @class([
                        'border-2 rounded-lg p-4 cursor-pointer transition-all duration-200',
                        'border-blue-500 bg-blue-50 dark:bg-blue-900/20' =>
                            $patient_type === 'umum',
                        'border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700' =>
                            $patient_type !== 'umum',
                    ]) wire:click="$set('patient_type', 'umum')">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-6 w-6 rounded-full border-2 flex items-center justify-center mr-3"
                                    :class="@entangle('patient_type') === 'umum' ? 'border-blue-500' :
                                        'border-gray-300 dark:border-gray-500'">
                                    <template x-if="@entangle('patient_type') === 'umum'">
                                        <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Pasien Umum</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tanpa kartu BPJS</p>
                            </div>
                        </div>
                    </div>

                    <div @class([
                        'border-2 rounded-lg p-4 cursor-pointer transition-all duration-200',
                        'border-blue-500 bg-blue-50 dark:bg-blue-900/20' =>
                            $patient_type === 'bpjs',
                        'border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700' =>
                            $patient_type !== 'bpjs',
                    ]) wire:click="$set('patient_type', 'bpjs')">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-6 w-6 rounded-full border-2 flex items-center justify-center mr-3"
                                    :class="@entangle('patient_type') === 'bpjs' ? 'border-blue-500' :
                                        'border-gray-300 dark:border-gray-500'">
                                    <template x-if="@entangle('patient_type') === 'bpjs'">
                                        <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">BPJS Kesehatan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Dengan kartu BPJS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nomor BPJS (hanya tampil jika tipe BPJS dipilih) -->
            <div x-data="{ patientType: '{{ $patient_type }}' }" x-show="patientType === 'bpjs'" x-transition class="mb-6 space-y-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="bpjs_number">
                        Nomor BPJS
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zM4.5 10a5.5 5.5 0 1111 0 5.5 5.5 0 01-11 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="bpjs_number" wire:model.defer="bpjs_number"
                            wire:keydown.enter.prevent="checkBpjs"
                            class="pl-10 w-full px-4 py-3 border dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan 13 digit nomor BPJS"
                            @if ($bpjs_number) value="{{ $bpjs_number }}" @endif>
                        <button type="button" wire:click="checkBpjs" wire:loading.attr="disabled"
                            class="absolute right-2.5 bottom-2.5 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <span wire:loading.remove>Cek Data</span>
                            <span wire:loading>
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memeriksa...
                            </span>
                        </button>
                    </div>
                    @error('bpjs_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                @if ($showPatientInfo && $patientData)
                    <div
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                    Data Ditemukan
                                </h3>
                                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                    <p>{{ $patientData['name'] }}</p>
                                    <p class="mt-1">NIK: {{ $patientData['nik'] }}</p>
                                    <p class="mt-1">Tanggal Lahir: {{ $patientData['date_of_birth'] }}</p>
                                    <p class="mt-1">Jenis Kelamin: {{ $patientData['gender'] }}</p>
                                    @if (!empty($patientData['phone']))
                                        <p class="mt-1">Telepon: {{ $patientData['phone'] }}</p>
                                    @endif
                                    @if (!empty($patientData['address']))
                                        <p class="mt-1">Alamat: {{ $patientData['address'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-8">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-3">
                        Pilih Layanan
                    </label>
                    <div class="space-y-3">
                        @forelse($services as $service)
                            <div
                                class="flex items-center p-4 border rounded-lg hover:border-blue-400 dark:hover:border-blue-600 transition-colors duration-200 {{ $service_id == $service->id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                                <input id="service-{{ $service->id }}" name="service_id" type="radio"
                                    wire:model.defer="service_id" value="{{ $service->id }}"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                                <label for="service-{{ $service->id }}" class="ml-3 block">
                                    <span
                                        class="block text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</span>
                                    @if ($service->description)
                                        <span
                                            class="block text-sm text-gray-500 dark:text-gray-400">{{ $service->description }}</span>
                                    @endif
                                </label>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                Tidak ada layanan tersedia saat ini.
                            </div>
                        @endforelse
                    </div>
                    @error('service_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                        class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-70 disabled:cursor-not-allowed"
                        :disabled="submitting">
                        <svg x-show="submitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="submitting ? 'Memproses...' : 'Ambil Tiket Sekarang'"></span>
                    </button>

                    <p class="mt-3 text-center text-sm text-gray-500 dark:text-gray-400">
                        Pastikan data yang Anda masukkan sudah benar sebelum mengajukan tiket.
                    </p>
                </div>
        </form>
    </div>

    <!-- Riwayat Tiket -->

    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Riwayat Tiket</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        No. Tiket</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Layanan</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tanggal</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status</th>
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
                <button
                    class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50"
                    disabled>
                    Sebelumnya
                </button>
                <button
                    class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50"
                    disabled>
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>
</div>
</div>
