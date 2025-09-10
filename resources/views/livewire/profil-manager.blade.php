<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <div class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 px-4 py-4 sm:px-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Pengaturan Profil Instansi</h1>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Kelola informasi profil instansi Anda</p>
    </div>

    <main class="p-4 sm:p-6">
        <form wire:submit.prevent="save" class="space-y-6" enctype="multipart/form-data">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri - Informasi Utama -->
                <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Informasi Utama</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Nama Instansi</label>
                            <input type="text" wire:model="nama_instansi" placeholder="Masukkan nama instansi" required
                                class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nama_instansi')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Alamat</label>
                            <textarea wire:model="alamat" placeholder="Masukkan alamat lengkap" rows="4" required
                                class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('alamat')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Nomor Telepon</label>
                                <input type="text" wire:model="no_telepon" placeholder="Masukkan nomor telepon" required
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('no_telepon')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Email</label>
                                <input type="email" wire:model="email" placeholder="Masukkan email" required
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan - Logo & Favicon -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Logo & Favicon</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Logo Instansi</label>
                            <div class="mt-2 space-y-3">
                                @if ($existing_logo && !$logo)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $existing_logo) }}" alt="Logo" class="h-24 w-auto rounded border border-zinc-200 dark:border-zinc-600 mx-auto">
                                        <p class="text-xs text-center text-zinc-500 dark:text-zinc-400 mt-2">Logo saat ini</p>
                                    </div>
                                @endif
                                @if ($logo)
                                    <div class="relative">
                                        <img src="{{ $logo->temporaryUrl() }}" alt="Logo Preview" class="h-24 w-auto rounded border border-zinc-200 dark:border-zinc-600 mx-auto">
                                        <p class="text-xs text-center text-zinc-500 dark:text-zinc-400 mt-2">Preview logo baru</p>
                                    </div>
                                @endif
                                <input type="file" wire:model="logo" accept="image/*"
                                    class="w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-600 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                                @error('logo')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Favicon</label>
                            <div class="mt-2 space-y-3">
                                @if ($existing_favicon && !$favicon)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $existing_favicon) }}" alt="Favicon" class="h-16 w-auto rounded border border-zinc-200 dark:border-zinc-600 mx-auto">
                                        <p class="text-xs text-center text-zinc-500 dark:text-zinc-400 mt-2">Favicon saat ini</p>
                                    </div>
                                @endif
                                @if ($favicon)
                                    <div class="relative">
                                        <img src="{{ $favicon->temporaryUrl() }}" alt="Favicon Preview" class="h-16 w-auto rounded border border-zinc-200 dark:border-zinc-600 mx-auto">
                                        <p class="text-xs text-center text-zinc-500 dark:text-zinc-400 mt-2">Preview favicon baru</p>
                                    </div>
                                @endif
                                <input type="file" wire:model="favicon" accept="image/*"
                                    class="w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-600 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                                @error('favicon')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition-all duration-200 shadow-lg hover:shadow-xl dark:from-blue-500 dark:to-blue-600 dark:hover:from-blue-600 dark:hover:to-blue-700 dark:focus:border-blue-600 dark:focus:ring-blue-400"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Simpan Perubahan</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>
        </form>

        <div x-data="{ showSuccess: false, showError: false, message: '' }" 
             @profile-saved.window="showSuccess = true; message = $event.detail.message; setTimeout(() => { showSuccess = false; window.location.reload(); }, 1500)"
             @profile-error.window="showError = true; message = $event.detail.message; setTimeout(() => showError = false, 5000)">
            
            <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform translate-x-4" 
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50">
                <span x-text="message"></span>
            </div>

            <div x-show="showError" x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform translate-x-4" 
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50">
                <span x-text="message"></span>
            </div>
        </div>
    </main>
</div>
