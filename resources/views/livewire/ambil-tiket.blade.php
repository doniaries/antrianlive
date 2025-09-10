@php
    use App\Models\Service;
    use App\Models\Counter;
    use App\Models\Profil;
    $services = Service::with('counters')->where('is_active', true)->get();
    $profil = Profil::first();
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $profil->nama_instansi ?? 'Sistem Antrian' }} - Ambil Tiket</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $faviconUrl = $profil && $profil->favicon ? asset('storage/' . $profil->favicon) : '/favicon.ico';
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        // Dark mode detection and toggle
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-300">
    <div class="min-h-screen p-4 md:p-8">
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
                        <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-3" id="displayTicketNumber">-
                        </div>
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700" id="displayServiceInfo"></p>
                        </div>
                    </div>
                </div>
            </header>

            <main>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($services as $index => $service)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 fade-in transition-all duration-300 hover:shadow-xl"
                            style="animation-delay: {{ $index * 0.1 }}s">
                            <div class="mb-4">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $service->name }}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pilih loket untuk layanan ini</p>
                            </div>
                            
                            <div class="space-y-3">
                                @forelse($service->counters as $counter)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-desktop text-indigo-600 dark:text-indigo-400"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $counter->name }}</h3>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Loket {{ $counter->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <form id="ticketForm-{{ $service->id }}-{{ $counter->id }}" method="POST"
                                            action="{{ route('queue.ticket.take') }}" class="ticket-form">
                                            @csrf
                                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                                            <input type="hidden" name="counter_id" value="{{ $counter->id }}">
                                            <button type="submit" 
                                                class="w-full bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span>Ambil Tiket {{ $service->name }} - {{ $counter->name }}</span>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-info-circle text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada loket tersedia untuk layanan ini</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-inbox text-2xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Tidak ada layanan tersedia saat ini</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">Silakan hubungi admin untuk mengaktifkan layanan</p>
                        </div>
                    @endforelse
                </div>
            </main>

            <footer class="footer">
                <p> {{ date('Y') }} {{ $profil->nama_instansi ?? 'Sistem Antrian' }} • All rights reserved</p>

            </footer>
        </div>

        <!-- Notification Container -->
        <div id="notification"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
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

                                // Use ticket_number from response
                                const ticketNum = data.ticket_number;

                                // Show success message with confetti (no sound)
                                await triggerConfetti();

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
                                    displayTicketNumber.textContent = ticketNum;
                                    ticketNumber.textContent = ticketNum;
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

                                showNotification();

                                // Trigger display update using localStorage event
                                const eventData = {
                                    ticket_number: ticketNum,
                                    service_name: data.service_name,
                                    counter_name: data.counter_name,
                                    timestamp: new Date().toISOString()
                                };

                                // Try Livewire event first
                                if (typeof Livewire !== 'undefined') {
                                    Livewire.dispatch('ticket-created', eventData);
                                }

                                // Force immediate refresh via localStorage with unique key
                                const uniqueKey = 'ticket-created-' + Date.now();
                                localStorage.setItem(uniqueKey, JSON.stringify(eventData));

                                // Also use the old key for backward compatibility
                                localStorage.setItem('ticket-created', JSON.stringify(eventData));

                                // Clean up after a short delay
                                setTimeout(() => {
                                    localStorage.removeItem(uniqueKey);
                                }, 5000);

                                // Force immediate refresh via fetch to ensure display updates
                                fetch('/api/queue-data')
                                    .then(response => response.json())
                                    .then(queueData => {
                                        console.log('Immediate refresh triggered:', queueData);
                                    })
                                    .catch(error => console.error('Immediate refresh failed:',
                                        error));

                                // Auto refresh after 30 seconds if still on the page
                                setTimeout(() => {
                                    location.reload();
                                }, 30000);

                            } else if (!data.success) {
                                console.error('Server error:', data);
                                throw new Error(data.message ||
                                    'Terjadi kesalahan saat memproses tiket');
                            } else if (!data.ticket_number) {
                                console.error('Missing ticket number in response:', data);
                                console.error('Available fields:', Object.keys(data));
                                throw new Error(
                                    'Nomor antrian tidak ditemukan dalam respons server');
                            }
                        } catch (error) {
                            console.error('Ticket generation error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengambil Tiket',
                                text: error.message ||
                                    'Terjadi kesalahan saat memproses tiket. Silakan coba lagi.',
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
</body>

</html>
