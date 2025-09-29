<div>
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Data Pasien</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola data pasien dengan mudah</p>
            </div>
            <a href="{{ route('patients.create') }}"
                class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl shadow-lg hover:from-blue-600 hover:to-blue-700 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Pasien
            </a>
        </div>
    </div>
                        <!-- End Header -->

    <!-- Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <div class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                placeholder="Cari pasien...">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            NIK
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            BPJS
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Telepon
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                                    </tr>
                                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $patient->name ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $patient->email ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $patient->nik ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $patient->bpjs_number ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $patient->phone ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Edit Button -->
                                <button type="button" 
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    data-hs-overlay="#edit-patient-modal-{{ $patient->id }}">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </button>

                                <!-- Delete Button -->
                                <button type="button"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    data-hs-overlay="#delete-patient-modal-{{ $patient->id }}">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Hapus
                                </button>

                                                    <!-- Edit Modal -->
                                                    <div id="edit-patient-modal-{{ $patient->id }}" class="hs-overlay hidden w-full h-full fixed top-0 left-0 z-[80] overflow-x-hidden overflow-y-auto">
                                                        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all w-full max-w-3xl mx-auto p-4">
                                                            <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700 max-h-[90vh]">
                                                                <!-- Header -->
                                                                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                                                        <svg class="flex-shrink-0 size-5 inline-block mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                                            <path d="m15 5 4 4"></path>
                                                                        </svg>
                                                                        Edit Data Pasien
                                                                    </h3>
                                                                    <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700" data-hs-overlay="#edit-patient-modal-{{ $patient->id }}">
                                                                        <span class="sr-only">Tutup</span>
                                                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                            <path d="M18 6 6 18"/>
                                                                            <path d="m6 6 12 12"/>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                
                                                                <!-- Form -->
                                                                <div class="p-4 overflow-y-auto flex-1">
                                                                    <form id="edit-patient-form-{{ $patient->id }}" action="{{ route('patients.update', $patient) }}" method="POST" class="space-y-4" onsubmit="return handleFormSubmit(this, 'Menyimpan...')">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        
                                                                        <!-- Nama Lengkap -->
                                                                        <div class="space-y-1.5">
                                                                            <label for="name-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                                                                            <div class="relative">
                                                                                <input type="text" id="name-{{ $patient->id }}" name="name" value="{{ old('name', $patient->name) }}" 
                                                                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                                                                                    required>
                                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                        <path d="M10 2a5 5 0 015 5v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2h2V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v2h6V7a3 3 0 00-3-3z" />
                                                                                    </svg>
                                                                                </div>
                                                                            </div>
                                                                            @error('name')
                                                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                                            @enderror
                                                                        </div>
                                                                        
                                                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                                            <!-- NIK -->
                                                                            <div class="space-y-1.5">
                                                                                <label for="nik-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIK <span class="text-red-500">*</span></label>
                                                                                <div class="relative">
                                                                                    <input type="text" id="nik-{{ $patient->id }}" name="nik" value="{{ old('nik', $patient->nik) }}" 
                                                                                        class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                                        pattern="\d{16}" 
                                                                                        title="NIK harus terdiri dari 16 digit angka"
                                                                                        required>
                                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                            <path fill-rule="evenodd" d="M6 3.75A2.75 2.75 0 018.75 1h2.5A2.75 2.75 0 0114 3.75v.443c.572.055 1.14.122 1.706.2C17.053 4.582 18 5.75 18 7.07v3.469c0 1.126-.694 2.191-1.83 2.54-1.952.599-4.024.921-6.17.921s-4.219-.322-6.17-.921C2.694 12.73 2 11.665 2 10.539V7.07c0-1.321.947-2.489 2.294-2.676A41.047 41.047 0 016 4.193V3.75zm6.5 0v.325a41.622 41.622 0 00-5 0V3.75c0-.69.56-1.25 1.25-1.25h2.5c.69 0 1.25.56 1.25 1.25zM10 10a1 1 0 00-1 1v.01a1 1 0 001 1h.01a1 1 0 001-1V11a1 1 0 00-1-1H10z" clip-rule="evenodd" />
                                                                                            <path d="M3 15.055v-.684c.126.053.255.1.39.142 2.092.642 4.313.987 6.61.987 2.297 0 4.518-.345 6.61-.987.135-.041.264-.089.39-.142v.684c0 1.347-.985 2.53-2.363 2.686a41.454 41.454 0 01-9.274 0C3.985 17.585 3 16.402 3 15.055z" />
                                                                                        </svg>
                                                                                    </div>
                                                                                </div>
                                                                                @error('nik')
                                                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                                                @enderror
                                                                            </div>
                                                                            
                                                                            <!-- Email -->
                                                                            <div class="space-y-1.5">
                                                                                <label for="email-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                                                                <div class="relative">
                                                                                    <input type="email" id="email-{{ $patient->id }}" name="email" value="{{ old('email', $patient->email) }}" 
                                                                                        class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                            <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                                                                                            <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                                                                                        </svg>
                                                                                    </div>
                                                                                </div>
                                                                                @error('email')
                                                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                                            <!-- Password -->
                                                                            <div class="space-y-1.5">
                                                                                <label for="password-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                                                                <div class="relative">
                                                                                    <input type="password" id="password-{{ $patient->id }}" name="password" 
                                                                                        class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                                        autocomplete="new-password"
                                                                                        placeholder="Kosongkan jika tidak diubah">
                                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                                                                        </svg>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                                                                                @error('password')
                                                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                                                @enderror
                                                                            </div>
                                                                            
                                                                            <!-- Phone -->
                                                                            <div class="space-y-1.5">
                                                                                <label for="phone-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon <span class="text-red-500">*</span></label>
                                                                                <div class="relative">
                                                                                    <input type="tel" id="phone-{{ $patient->id }}" name="phone" value="{{ old('phone', $patient->phone) }}" 
                                                                                        class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                                        pattern="^[0-9]{10,13}$"
                                                                                        title="Masukkan nomor telepon yang valid (10-13 angka)"
                                                                                        required>
                                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                            <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" />
                                                                                        </svg>
                                                                                    </div>
                                                                                </div>
                                                                                @error('phone')
                                                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                                            <!-- Tanggal Lahir -->
                                                                            <div class="space-y-2">
                                                                                <label for="date_of_birth-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir <span class="text-red-500">*</span></label>
                                                                                <div class="relative">
                                                                                    <input type="date" id="date_of_birth-{{ $patient->id }}" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" 
                                                                                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                                                                                        required>
                                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                            <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                                                                                        </svg>
                                                                                    </div>
                                                                                </div>
                                                                                @error('date_of_birth')
                                                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                                @enderror
                                                                            </div>
                                                                            
                                                                            <!-- Jenis Kelamin -->
                                                                            <div class="space-y-2">
                                                                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin <span class="text-red-500">*</span></span>
                                                                                <div class="grid grid-cols-2 gap-3">
                                                                                    <div>
                                                                                        <input type="radio" name="gender" id="gender_l-{{ $patient->id }}" value="L" 
                                                                                            class="peer hidden [&:checked_+_label_svg]:block"
                                                                                            {{ old('gender', $patient->gender) == 'L' ? 'checked' : '' }} required>
                                                                                        <label for="gender_l-{{ $patient->id }}" class="flex flex-col h-full p-3 text-center border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 dark:peer-checked:border-blue-500 dark:peer-checked:ring-blue-500">
                                                                                            <svg class="hidden w-5 h-5 mx-auto text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                                                            </svg>
                                                                                            <span class="mt-1 text-sm font-medium text-gray-900 dark:text-white">Laki-laki</span>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div>
                                                                                        <input type="radio" name="gender" id="gender_p-{{ $patient->id }}" value="P" 
                                                                                            class="peer hidden [&:checked_+_label_svg]:block"
                                                                                            {{ old('gender', $patient->gender) == 'P' ? 'checked' : '' }} required>
                                                                                        <label for="gender_p-{{ $patient->id }}" class="flex flex-col h-full p-3 text-center border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 peer-checked:border-pink-500 peer-checked:ring-1 peer-checked:ring-pink-500 dark:peer-checked:border-pink-500 dark:peer-checked:ring-pink-500">
                                                                                            <svg class="hidden w-5 h-5 mx-auto text-pink-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                                                            </svg>
                                                                                            <span class="mt-1 text-sm font-medium text-gray-900 dark:text-white">Perempuan</span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                @error('gender')
                                                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <!-- Nomor Telepon -->
                                                                        <div class="space-y-2">
                                                                            <label for="phone-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Telepon</label>
                                                                            <div class="relative">
                                                                                <input type="tel" id="phone-{{ $patient->id }}" name="phone" value="{{ old('phone', $patient->phone) }}" 
                                                                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                                    pattern="[0-9]{10,13}"
                                                                                    title="Masukkan nomor telepon yang valid (10-13 digit)">
                                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                        <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" />
                                                                                    </svg>
                                                                                </div>
                                                                            </div>
                                                                            @error('phone')
                                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                            @enderror
                                                                        </div>
                                                                        
                                                                        <!-- Alamat -->
                                                                        <div class="space-y-2">
                                                                            <label for="address-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                                                                            <div class="relative">
                                                                                <textarea id="address-{{ $patient->id }}" name="address" rows="3" 
                                                                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('address', $patient->address) }}</textarea>
                                                                                <div class="absolute top-3 left-3">
                                                                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                        <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.682 9.175 9.175 0 00.997.54l.008.004.002.001zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" />
                                                                                    </svg>
                                                                                </div>
                                                                            </div>
                                                                            @error('address')
                                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                            @enderror
                                                                        </div>
                                                                        
                                                                        <!-- Nomor BPJS -->
                                                                        <div class="space-y-2">
                                                                            <label for="bpjs_number-{{ $patient->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor BPJS</label>
                                                                            <div class="relative">
                                                                                <input type="text" id="bpjs_number-{{ $patient->id }}" name="bpjs_number" value="{{ old('bpjs_number', $patient->bpjs_number) }}" 
                                                                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                                    placeholder="Kosongkan jika tidak memiliki">
                                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                                        <path d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" />
                                                                                    </svg>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-1 text-xs text-gray-500">Contoh: 0001122334455</p>
                                                                            @error('bpjs_number')
                                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                                            @enderror
                                                                        </div>
                                                                        
                                                                        <!-- Footer -->
                                                                        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                                                                            <button type="button" 
                                                                                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600" 
                                                                                data-hs-overlay="#edit-patient-modal-{{ $patient->id }}">
                                                                                Batal
                                                                            </button>
                                                                            <button type="submit" 
                                                                                class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 inline-flex items-center justify-center">
                                                                                <span class="submit-text">Simpan Perubahan</span>
                                                                                <svg class="animate-spin -mr-1 ml-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Delete Confirmation Modal -->
                                                    <div id="delete-patient-modal-{{ $patient->id }}" class="hs-overlay hidden fixed top-0 left-0 right-0 z-[80] w-full h-full overflow-x-hidden overflow-y-auto">
                                                        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                                                            <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
                                                                <!-- Header -->
                                                                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700 bg-red-50 dark:bg-gray-800/50">
                                                                    <h3 class="font-bold text-red-700 dark:text-red-500">
                                                                        <svg class="flex-shrink-0 size-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                                        </svg>
                                                                        Hapus Data Pasien
                                                                    </h3>
                                                                    <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700" data-hs-overlay="#delete-patient-modal-{{ $patient->id }}">
                                                                        <span class="sr-only">Tutup</span>
                                                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                            <path d="M18 6 6 18"/>
                                                                            <path d="m6 6 12 12"/>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                
                                                                <!-- Body -->
                                                                <div class="p-6 text-center">
                                                                    <!-- Icon -->
                                                                    <div class="mx-auto flex items-center justify-center size-16 bg-red-100 rounded-full dark:bg-red-900/30 mb-4">
                                                                        <svg class="size-8 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </div>
                                                                    
                                                                    <h3 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white">
                                                                        Konfirmasi Penghapusan
                                                                    </h3>
                                                                    
                                                                    <div class="text-center">
                                                                        <p class="text-gray-600 dark:text-gray-400">
                                                                            Anda akan menghapus data pasien:
                                                                        </p>
                                                                        <p class="mt-2 font-medium text-gray-800 dark:text-white">
                                                                            {{ $patient->name }} <span class="text-gray-500">({{ $patient->nik }})</span>
                                                                        </p>
                                                                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                                                            Data yang sudah dihapus tidak dapat dikembalikan. Pastikan data yang akan dihapus sudah benar.
                                                                        </p>
                                                                    </div>

                                                                    <!-- Form -->
                                                                    <form id="delete-patient-form-{{ $patient->id }}" action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="mt-6" onsubmit="return handleDeleteSubmit(this, 'Menghapus...')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        
                                                                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                                                                            <button type="button" 
                                                                                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600" 
                                                                                data-hs-overlay="#delete-patient-modal-{{ $patient->id }}">
                                                                                Batal
                                                                            </button>
                                                                            <button type="submit" 
                                                                                class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 inline-flex items-center justify-center">
                                                                                <span class="submit-text">Ya, Hapus Data</span>
                                                                                <svg class="animate-spin -mr-1 ml-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration-300 opacity-0 hidden min-w-40 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-gray-800 dark:border dark:border-gray-700" 
                                                        aria-labelledby="hs-table-dropdown-{{ $loop->index }}">
                                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700" 
                                                            href="{{ route('patients.edit', $patient) }}">
                                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                                <path d="m15 5 4 4"></path>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pasien ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M3 6h18"></path>
                                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                                                </svg>
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada data pasien yang ditemukan.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table -->

                        <!-- Footer -->
                        <div class="px-6 py-4 grid gap-3 md:flex md:items-center md:justify-between border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $patients->firstItem() ?? 0 }}-{{ $patients->lastItem() ?? 0 }}</span> dari <span class="font-semibold">{{ $patients->total() }}</span> pasien
                                </p>
                            </div>

                            @if ($patients->hasPages())
                            <div>
                                <div class="inline-flex gap-x-2">
                                    {{ $patients->withQueryString()->links() }}
                                </div>
                            </div>
                            @endif
                        </div>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $patients->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dropdowns
        document.querySelectorAll('.hs-dropdown').forEach(function(dropdown) {
            new HSDropdown(dropdown);
        });

        // Show success message if exists
        @if(session('success'))
        const successMessage = `{{ session('success') }}`;
        const successElement = `
            <div class="max-w-3xl mx-auto mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            ${successMessage}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Insert the message at the top of the content
        const content = document.querySelector('div > div:first-child');
        if (content) {
            content.insertAdjacentHTML('afterbegin', successElement);
            
            // Add click event to close the message
            const closeButton = content.querySelector('button');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    this.closest('.bg-green-50').remove();
                });
            }
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                const message = content.querySelector('.bg-green-50');
                if (message) message.remove();
            }, 5000);
        }
        @endif

        // Handle errors if any
        @if($errors->any())
        const errorMessages = `
            <div class="max-w-3xl mx-auto mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-700">
                            Terjadi kesalahan!
                        </p>
                        <div class="mt-2 text-sm text-red-600">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const content = document.querySelector('div > div:first-child');
        if (content) {
            content.insertAdjacentHTML('afterbegin', errorMessages);
            
            // Add click event to close the message
            const closeButton = content.querySelector('button');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    this.closest('.bg-red-50').remove();
                });
            }
            
            // Auto hide after 10 seconds
            setTimeout(() => {
                const message = content.querySelector('.bg-red-50');
                if (message) message.remove();
            }, 10000);
        }
        @endif
    });
</script>
@endpush
