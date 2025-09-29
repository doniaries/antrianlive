@extends('components.layouts.app', ['title' => 'Edit Data Pasien'])

@section('content')
    <div class="p-4 sm:p-6">
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ __('Edit Data Pasien') }}</h2>
            </div>

            <!-- Card -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-4 sm:p-7">
                    <form action="{{ route('patients.update', $patient) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- No. RM (Readonly) -->
                            <div>
                                <label for="medical_record_number" class="block text-sm font-medium mb-2 dark:text-white">No. Rekam Medis</label>
                                <input 
                                    type="text" 
                                    id="medical_record_number" 
                                    name="medical_record_number" 
                                    value="{{ $patient->medical_record_number }}"
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    readonly
                                >
                            </div>
                            
                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Nama Lengkap</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $patient->name) }}"
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                    required
                                    autofocus
                                >
                                @error('name')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIK -->
                            <div>
                                <label for="nik" class="block text-sm font-medium mb-2 dark:text-white">NIK</label>
                                <input 
                                    type="text" 
                                    id="nik" 
                                    name="nik" 
                                    value="{{ old('nik', $patient->nik) }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                    required
                                    maxlength="16"
                                >
                                @error('nik')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium mb-2 dark:text-white">Tanggal Lahir</label>
                                <input 
                                    type="date" 
                                    id="date_of_birth" 
                                    name="date_of_birth" 
                                    value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}"
                                    max="{{ now()->format('Y-m-d') }}"
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                    required
                                >
                                @error('date_of_birth')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="gender" class="block text-sm font-medium mb-2 dark:text-white">Jenis Kelamin</label>
                                <select 
                                    id="gender" 
                                    name="gender" 
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('gender', $patient->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', $patient->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label for="phone" class="block text-sm font-medium mb-2 dark:text-white">Nomor Telepon (Opsional)</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone', $patient->phone) }}"
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                >
                                @error('phone')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No. BPJS -->
                            <div>
                                <label for="bpjs_number" class="block text-sm font-medium mb-2 dark:text-white">No. BPJS (Opsional)</label>
                                <input 
                                    type="text" 
                                    id="bpjs_number" 
                                    name="bpjs_number" 
                                    value="{{ old('bpjs_number', $patient->bpjs_number) }}"
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                >
                                @error('bpjs_number')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-span-2">
                                <label for="address" class="block text-sm font-medium mb-2 dark:text-white">Alamat</label>
                                <textarea 
                                    id="address" 
                                    name="address" 
                                    rows="3" 
                                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                >{{ old('address', $patient->address) }}</textarea>
                                @error('address')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a href="{{ route('patients.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"/>
                                    <path d="M5 12l6-6m-6 6 6 6"/>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
