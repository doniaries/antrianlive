<div>
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kelola Video</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola video yang ditampilkan di layar antrian
                </p>
            </div>
            <button wire:click="create()"
                class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Video
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full">
            <div class="bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
                <button @click="show = false" class="text-white hover:text-gray-100 ml-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Tabel Video -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
        <div
            class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            No
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Tipe
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Preview
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($videos as $index => $video)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">
                                {{ $index + 1 }}
                            </td>
                            {{-- <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs break-words">
                                {{ $video->title }}
                            </td> --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r
                                        {{ $video->type === 'youtube' ? 'from-red-100 to-red-200 text-red-800 dark:from-red-900 dark:to-red-800 dark:text-red-200 border border-red-200 dark:border-red-700' : 'from-blue-100 to-blue-200 text-blue-800 dark:from-blue-900 dark:to-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-700' }}">
                                    {{ $video->type === 'youtube' ? 'YouTube' : 'File' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $type = $video->type ?? 'youtube';
                                    $url = $video->url ?? '';
                                @endphp
                                @if ($type === 'youtube' && !empty($url))
                                    @php
                                        $youtubeId = '';
                                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $url, $matches)) {
                                            $youtubeId = $matches[1];
                                        }
                                    @endphp
                                    @if (!empty($youtubeId))
                                        <img src="https://img.youtube.com/vi/{{ $youtubeId }}/default.jpg"
                                            alt="YouTube Thumbnail"
                                            class="w-20 h-15 object-cover rounded border border-gray-300 dark:border-gray-600 mx-auto"
                                            onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 20 20\' fill=\'%23ccc\'><path d=\'M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z\'/><path d=\'M12 10l-4 2V8l4 2z\'/></svg>'">
                                    @else
                                        <div class="w-20 h-15 flex items-center justify-center mx-auto bg-gray-100 dark:bg-gray-700 rounded">
                                            <i class="fas fa-youtube text-red-500 text-2xl"></i>
                                        </div>
                                    @endif
                                @elseif($type === 'file' && !empty($url))
                                    <div class="w-20 h-15 flex items-center justify-center mx-auto bg-gray-100 dark:bg-gray-700 rounded">
                                        <i class="fas fa-video text-blue-500 text-2xl"></i>
                                    </div>
                                @else
                                    <div class="w-20 h-15 flex items-center justify-center mx-auto bg-gray-100 dark:bg-gray-700 rounded">
                                        <i class="fas fa-question-circle text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r
                                        {{ $video->is_active ? 'from-green-100 to-green-200 text-green-800 dark:from-green-900 dark:to-green-800 dark:text-green-200 border border-green-200 dark:border-green-700' : 'from-gray-100 to-gray-200 text-gray-800 dark:from-gray-700 dark:to-gray-600 dark:text-gray-200 border border-gray-200 dark:border-gray-600' }}">
                                    {{ $video->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <button wire:click="edit({{ $video->id }})"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105"
                                        title="Edit">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $video->id }})"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus video ini?')"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105"
                                        title="Hapus">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada
                                data video</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $videos->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{}" x-show="$wire.isOpen" x-cloak x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="$wire.set('isOpen', false)">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="$wire.set('isOpen', false)"></div>

        <!-- Modal Content -->
        <div x-show="$wire.isOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg mx-4 transform transition-all"
            @click.away="$wire.set('isOpen', false)">

            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $editId ? 'Edit Video' : 'Tambah Video Baru' }}
                </h3>
                <button @click="$wire.set('isOpen', false)"
                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form wire:submit.prevent="store" class="space-y-5">
                    <!-- Judul Video -->
                    {{-- <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Judul Video
                        </label>
                        <input type="text" wire:model="title" id="title"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            placeholder="Masukkan judul video">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <!-- URL Video -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            URL Video
                        </label>
                        <input type="url" wire:model="url" id="url"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            placeholder="https://www.youtube.com/watch?v=... atau path file">
                        @error('url')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Video -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipe Video
                        </label>
                        <select wire:model="type" id="type"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="youtube">YouTube</option>
                            <option value="file">File Video</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload (hanya untuk tipe file) -->
                    <div x-show="type === 'file'" x-transition>
                        <label for="video_file"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload File Video
                        </label>
                        <input type="file" wire:model="video_file" id="video_file" accept="video/*"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800 transition-colors duration-200">
                        @error('video_file')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        @if ($video_file)
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                File: {{ $video_file->getClientOriginalName() }}
                            </div>
                        @endif
                    </div>

                    <!-- Status Aktif -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_active"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktifkan video</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Video yang aktif akan ditampilkan di
                            layar antrian</p>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" @click="$wire.set('isOpen', false)"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" wire:click="store"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                    {{ $editId ? 'Update Video' : 'Simpan Video' }}
                </button>
            </div>
        </div>
    </div>
</div>
