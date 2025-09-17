@php
    use App\Models\Service;
    use App\Models\Counter;
    use App\Models\Profil;
    $services = Service::with('counters')->where('is_active', true)->get();
    $profil = Profil::first();
@endphp
@extends('layouts.app')

@section('html_attributes', 'class="h-full"')

@section('title', ($profil->nama_aplikasi ?? 'Ambil Tiket Antrian') . ' - ' . ($profil->nama_instansi ?? 'Sistem Antrian'))

@section('scripts_head')
<script>
    // Auto-refresh halaman saat menerima event dari counter-manager
    window.addEventListener('storage', function(e) {
        if (e.key === 'counter_status_changed') {
            console.log('Counter status changed, reloading page...');
            location.reload(true);
        }
    });
</script>
@endsection

@section('fonts')
<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('styles')
<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --secondary: #7c3aed;
        --accent: #8b5cf6;
        --text: #1f2937;
        --text-light: #6b7280;
        --bg: #f9fafb;
        --card-bg: rgba(255, 255, 255, 0.9);
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
            margin: 0;
            padding: 2rem 1rem;
            color: var(--text);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 1rem;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.1rem;
            text-align: center;
        }

        /* App name styles moved to inline Tailwind classes */

        /* Guide card styles removed - using Tailwind classes instead */

        .subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .service-card {
            background: var(--card-bg);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .service-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .service-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            text-align: center;
            width: 100%;
        }

        .counters-list {
            padding: 1.25rem;
        }

        .counter-item {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }

        .counter-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .counter-item:last-child {
            margin-bottom: 0;
        }

        .ticket-form {
            display: block;
            width: 100%;
        }

        .ticket-form button:disabled {
            background: #e5e7eb !important;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .counter-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .counter-name {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-ticket {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .btn-ticket:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--accent) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-ticket:active {
            transform: translateY(0);
        }

        .btn-ticket i {
            margin-right: 0.5rem;
        }

        .footer {
            text-align: center;
            margin-top: 4rem;
            color: var(--text-light);
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: 1fr;
            }

            .title {
                font-size: 1.75rem;
            }

            .subtitle {
                font-size: 1rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
                <div class="flex items-center gap-4">
                    @if ($profil && $profil->logo)
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="h-16 w-auto">
                    @endif
                    <div>
                        <h1 class="title text-left !text-2xl md:!text-3xl">
                            {{ $profil->nama_instansi ?? 'Ambil Tiket Antrian' }}</h1>
                        @if (($profil && $profil->nama_aplikasi) || config('app.name') !== 'Laravel')
                            <h2
                                class="bg-indigo-800 text-white px-4 py-2 rounded-full inline-block shadow-lg font-semibold text-sm md:text-base tracking-wide mt-2">
                                {{ $profil->nama_aplikasi ?? config('app.name') }}
                            </h2>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 min-w-[200px]">
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-['Rajdhani'] font-bold text-indigo-600 tracking-tight"
                            id="digital-clock">00:00:00</div>
                        <div class="text-sm md:text-base text-gray-600 font-medium mt-1" id="current-date">Senin, 1
                            Januari 2023</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-3" id="displayTicketNumber">-</div>
                    <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-700" id="displayServiceInfo"></p>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <div class="services-grid">
                @forelse($services as $index => $service)
                    <div class="service-card fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                        <div class="service-header">
                            <h2 class="service-title">{{ $service->name }}</h2>
                        </div>
                        <div class="counters-list space-y-4">
                            @forelse($service->counters as $counter)
                                @php
                                    $statusClass = [
                                        'buka' => [
                                            'icon' => 'fa-check-circle',
                                            'color' => 'text-green-500',
                                            'bg' => 'bg-green-100',
                                            'text' => 'Buka',
                                        ],
                                        'tutup' => [
                                            'icon' => 'fa-times-circle',
                                            'color' => 'text-red-500',
                                            'bg' => 'bg-red-100',
                                            'text' => 'Tutup',
                                        ],
                                        'istirahat' => [
                                            'icon' => 'fa-coffee',
                                            'color' => 'text-yellow-500',
                                            'bg' => 'bg-yellow-100',
                                            'text' => 'Istirahat',
                                        ],
                                    ][$counter->status] ?? [
                                        'icon' => 'fa-question-circle',
                                        'color' => 'text-gray-500',
                                        'bg' => 'bg-gray-100',
                                        'text' => 'Tidak Diketahui',
                                    ];
                                    $isOpen = $counter->status === 'buka';
                                @endphp
                                <div
                                    class="counter-item bg-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-{{ $isOpen ? 'green-500' : 'gray-200' }} transform hover:-translate-y-1">
                                    <div class="flex flex-col">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-xl font-bold text-gray-800">
                                                Loket {{ $counter->name }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass['bg'] }} {{ $statusClass['color'] }} shadow">
                                                <i class="fas {{ $statusClass['icon'] }} mr-1"></i>
                                                {{ $statusClass['text'] }}
                                            </span>
                                        </div>
                                        <form id="ticketForm-{{ $service->id }}-{{ $counter->id }}" method="POST"
                                            action="{{ route('queue.ticket.take') }}" class="ticket-form w-full">
                                            @csrf
                                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                                            <input type="hidden" name="counter_id" value="{{ $counter->id }}">
                                            <button type="submit"
                                                class="w-full py-5 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl text-lg font-bold transition-all duration-300 transform hover:scale-[1.03] flex items-center justify-center space-x-3 shadow-lg {{ !$isOpen ? 'opacity-70 cursor-not-allowed' : '' }}"
                                                {{ !$isOpen ? 'disabled' : '' }}>
                                                <i class="fas fa-ticket-alt text-xl"></i>
                                                <span>AMBIL TIKET</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    <i class="fas fa-info-circle"></i> Tidak ada loket tersedia
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">Tidak ada layanan tersedia saat ini</p>
                    </div>
                @endforelse
            </div>
        </main>

        <footer class="footer">
            <p> {{ date('Y') }} {{ $profil->nama_instansi ?? 'Sistem Antrian' }} • All rights reserved</p>

        </footer>
    </div>

    <!-- Notification Container -->
    <div id="notification" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div
            class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-3xl text-green-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Tiket Berhasil Diambil!</h3>
                <p class="text-gray-600 mb-4">Nomor antrian Anda:</p>
                <div class="text-5xl font-bold text-indigo-600 mb-4 font-mono" id="ticketNumber">-</div>
                <div class="mt-4 p-3 bg-blue-50 rounded-lg mb-4">
                    <p class="text-sm text-blue-700" id="notificationServiceInfo"></p>
                </div>
                <p class="text-sm text-gray-500 mb-4">Silakan menunggu panggilan di loket yang tertera</p>
                <button onclick="closeNotification()"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Show notification
        function showNotification() {
            const notification = document.getElementById('notification');
            if (!notification) return;

            notification.classList.remove('hidden');
            notification.classList.add('flex');

            // Trigger animation
            setTimeout(() => {
                const content = notification.querySelector('div');
                if (content) {
                    content.style.transform = 'scale(1)';
                    content.style.opacity = '1';
                }
            }, 10);

            // Play success sound
            playSuccessSound();
        }

        // Close notification
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (!notification) return;

            const content = notification.querySelector('div');
            if (content) {
                content.style.transform = 'scale(0.95)';
                content.style.opacity = '0';
            }

            setTimeout(() => {
                notification.classList.add('hidden');
                notification.classList.remove('flex');

                // Reset animation
                if (content) {
                    content.style.transform = 'scale(0.95)';
                    content.style.opacity = '0';
                }
            }, 300);
        }


        // Play success sound (dinonaktifkan)
        function playSuccessSound() {
            // Suara bell telah dihilangkan
            console.log('Sound disabled');
            return Promise.resolve();
        }

        // Global audio functions for consistency with antrian manager
        window.playCallSound = function() {
            return playSuccessSound();
        };

        window.speakNumber = function(number) {
            // Text-to-speech for consistency
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(number);
                utterance.lang = 'id-ID';
                utterance.rate = 0.8;
                utterance.pitch = 1;
                speechSynthesis.speak(utterance);
            }
        };

        // Confetti effect
        function triggerConfetti() {
            const duration = 3 * 1000;
            const animationEnd = Date.now() + duration;
            const defaults = {
                startVelocity: 30,
                spread: 360,
                ticks: 60,
                zIndex: 0
            };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 50 * (timeLeft / duration);
                confetti({
                    ...defaults,
                    particleCount,
                    origin: {
                        x: randomInRange(0.1, 0.3),
                        y: Math.random() - 0.2
                    }
                });
                confetti({
                    ...defaults,
                    particleCount,
                    origin: {
                        x: randomInRange(0.7, 0.9),
                        y: Math.random() - 0.2
                    }
                });
            }, 250);
        }

        // Handle form submission with AJAX
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize clock
            initializeClock();
            
            // Load last ticket number from localStorage if exists
            const lastTicketNumber = localStorage.getItem('lastTicketNumber');
            const lastServiceInfo = localStorage.getItem('lastServiceInfo');
            
            if (lastTicketNumber) {
                const displayTicketNumber = document.getElementById('displayTicketNumber');
                const displayServiceInfo = document.getElementById('displayServiceInfo');
                
                if (displayTicketNumber) {
                    displayTicketNumber.textContent = lastTicketNumber;
                }
                
                if (displayServiceInfo && lastServiceInfo) {
                    displayServiceInfo.textContent = lastServiceInfo;
                }
            }

            const forms = document.querySelectorAll('.ticket-form');

            forms.forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div> Memproses...';

                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        console.log('Ticket response:', data); // Debug log

                        if (data.success && data.ticket_number) {
                            console.log('Ticket response:', data);
                            // Play sound and show success message
                            await Promise.all([
                                playSuccessSound(),
                                triggerConfetti()
                            ]);

                            // Show notification with ticket number and service info
                            const displayTicketNumber = document.getElementById(
                                'displayTicketNumber');
                            const displayServiceInfo = document.getElementById(
                                'displayServiceInfo');
                            const ticketNumber = document.getElementById(
                                'ticketNumber');
                            const notificationServiceInfo = document.getElementById(
                                'notificationServiceInfo');

                            if (displayTicketNumber && ticketNumber) {
                                displayTicketNumber.textContent = data.ticket_number;
                                ticketNumber.textContent = data.ticket_number;
                            } else {
                                console.error('Ticket number elements not found');
                            }

                            if (displayServiceInfo && notificationServiceInfo && data
                                .service_name && data.counter_name) {
                                const serviceInfo =
                                    `${data.service_name} - ${data.counter_name}`;
                                displayServiceInfo.textContent = serviceInfo;
                                notificationServiceInfo.textContent = serviceInfo;
                            }

                            // Store ticket info in localStorage
                            localStorage.setItem('lastTicketNumber', data.ticket_number);
                            localStorage.setItem('lastServiceInfo', `${data.service_name} - ${data.counter_name}`);
                            
                            showNotification();

                        } else if (!data.ticket_number) {
                            console.error('Missing ticket number in response:', data);
                            throw new Error(
                                'Nomor antrian tidak ditemukan dalam respons server');
                        } else {
                            console.error('Server error:', data);
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message ||
                                'Terjadi kesalahan saat memproses tiket',
                            confirmButtonColor: '#4f46e5',
                        });
                    } finally {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            });
        });

        // Update clock function
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const clockElement = document.getElementById('digital-clock');
            const dateElement = document.getElementById('current-date');

            if (clockElement) clockElement.textContent = timeString;
            if (dateElement) dateElement.textContent = dateString;
        }

        // Initialize clock
        function initializeClock() {
            updateClock();
            setInterval(updateClock, 1000);
        }

        // Initialize Toast notifications
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Auto update time
        function updateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeDisplay = document.querySelector('.time-display');
            if (timeDisplay) {
                timeDisplay.textContent = now.toLocaleDateString('id-ID', options);
            }
        }

        // Update time every second
        updateTime();
        setInterval(updateTime, 1000);
    </script>
@endsection

@section('body_class', 'font-sans antialiased')
@section('container_class', 'min-h-screen bg-gray-100 dark:bg-gray-900')
