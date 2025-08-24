@php
    use App\Models\Service;
    use App\Models\Counter;
    $services = Service::with('counters')->get();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ambil Tiket</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-y: auto;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        }
        .service-card {
            transition: all 0.3s ease;
            transform: translateY(0);
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        .counter-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .counter-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.02);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-gradient:active {
            transform: translateY(0);
        }
        .floating-header {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col p-4">
        <div class="w-full max-w-6xl mx-auto flex-1 flex flex-col">
            <div class="text-center py-6 floating-header">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">Ambil Tiket</h1>
                <p class="text-white/80 text-base md:text-lg">Pilih layanan dan loket yang Anda inginkan</p>
            </div>
            
            <div class="flex flex-nowrap overflow-x-auto pb-4 gap-4 md:gap-6 flex-1 w-full">
                @foreach($services as $service)
                    <div class="glass-card p-4 md:p-6 service-card h-full flex flex-col min-w-[300px] flex-shrink-0">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">{{ $service->name }}</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3 mt-auto">
                            @foreach($service->counters as $counter)
                                <div class="counter-card rounded-xl p-4 text-center">
                                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-2xl font-bold text-white">{{ $counter->name }}</span>
                                    </div>
                                    <p class="text-white/80 text-sm mb-4">Loket {{ $counter->name }}</p>
                                    
                                    <form id="ticketForm-{{ $service->id }}-{{ $counter->id }}" method="POST" action="{{ route('queue.ticket.take') }}" class="ticket-form">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                                        <input type="hidden" name="counter_id" value="{{ $counter->id }}">
                                        <button type="submit" class="btn-gradient w-full py-3 px-6 text-white font-semibold rounded-xl shadow-lg">
                                            Ambil Tiket
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center py-4 mt-4">
                <p class="text-white/60 text-xs md:text-sm">Sistem Antrian Modern • Desain Responsif • Cepat & Mudah</p>
            </div>
        </div>
    </div>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Handle form submission with AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.ticket-form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Play notification sound
                            const audio = new Audio('{{ asset("sounds/bell.mp3") }}');
                            audio.play().catch(e => console.log('Audio play failed:', e));
                            
                            // Show success toast notification
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });
                            
                            Toast.fire({
                                icon: 'success',
                                title: `Tiket ${data.ticket_number}`,
                                text: `Loket ${data.counter_name} • ${data.waiting_time}`,
                                showConfirmButton: false,
                                width: '350px',
                                padding: '1rem',
                                background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                color: 'white',
                                customClass: {
                                    title: 'text-white text-lg font-bold',
                                    htmlContainer: 'text-white/90 text-sm'
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: data.message || 'Terjadi kesalahan. Silahkan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#667eea'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan. Silahkan coba lagi.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#667eea'
                        });
                    });
                });
            });
        });
    </script>
</body>
</html>
