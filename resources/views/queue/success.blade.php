@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900">
                <svg class="h-10 w-10 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Tiket Berhasil Dibuat
            </h2>
        </div>

        <div class="mt-8 bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="text-center">
                <div class="bg-blue-50 dark:bg-blue-900/30 p-6 rounded-lg border border-blue-100 dark:border-blue-800 inline-block">
                    <div class="text-5xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                        {{ $antrian->formatted_number }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $antrian->service->name }}
                    </div>
                    @if($antrian->counter)
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Loket: {{ $antrian->counter->name }}
                        </div>
                    @endif
                </div>
                
                <p class="mt-6 text-sm text-gray-600 dark:text-gray-300">
                    Silakan menunggu nomor antrian Anda dipanggil.
                </p>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button 
                        type="button"
                        onclick="window.print()"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Tiket
                    </button>
                    <a 
                        href="{{ route('queue.ticket') }}"
                        class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Kembali ke Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Play sound when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        const audio = new Audio('{{ asset('sounds/bell.mp3') }}');
        audio.play().catch(e => console.error('Error playing sound:', e));
    });
</script>
@endpush
@endsection
