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
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-6xl">
            <div class="text-center mb-12 floating-header">
                <h1 class="text-5xl font-bold text-white mb-4">Ambil Tiket</h1>
                <p class="text-white/80 text-lg">Pilih layanan dan loket yang Anda inginkan</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($services as $service)
                    <div class="glass-card p-8 service-card">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">{{ $service->name }}</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($service->counters as $counter)
                                <div class="counter-card rounded-xl p-6 text-center">
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
            
            <div class="text-center mt-12">
                <p class="text-white/60 text-sm">Sistem Antrian Modern • Desain Responsif • Cepat & Mudah</p>
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
                            
                            // Show success message
                            Swal.fire({
                                title: 'Tiket Berhasil Diambil!',
                                html: `
                                    <div class="text-center">
                                        <div class="text-6xl mb-4">🎫</div>
                                        <p class="text-xl font-bold mb-2">Nomor Antrian Anda:</p>
                                        <div class="text-5xl font-bold text-blue-600 mb-4">${data.ticket_number}</div>
                                        <p class="text-gray-600">Silahkan menunggu panggilan di loket ${data.counter_name}</p>
                                    </div>
                                `,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#667eea',
                                backdrop: `
                                    rgba(0,0,123,0.4)
                                    url("{{ asset('images/nyan-cat.gif') }}")
                                    left top
                                    no-repeat
                                `
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
