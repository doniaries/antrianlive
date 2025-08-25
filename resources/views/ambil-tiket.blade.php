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
    <title>Ambil Tiket - {{ $profil->nama_instansi ?? 'Sistem Antrian' }}</title>
    <title>{{ $profil->nama_aplikasi ?? 'Ambil Tiket Antrian' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            background: white;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .counter-item:last-child {
            margin-bottom: 0;
        }

        .counter-item:hover {
            border-color: var(--primary);
            transform: translateX(4px);
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
            <div class="flex flex-col items-center gap-3 mb-4">
                @if ($profil && $profil->logo)
                    <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="h-16 w-auto">
                @endif
                <h1 class="title text-center">{{ $profil->nama_instansi ?? 'Ambil Tiket Antrian' }}</h1>
            </div>
            @if (($profil && $profil->nama_aplikasi) || config('app.name') !== 'Laravel')
                <div class="flex justify-center mb-6">
                    <h2
                        class="!bg-indigo-800 !text-white px-6 py-2.5 rounded-full inline-block shadow-lg font-semibold text-lg tracking-wide text-center m-0">
                        {{ $profil->nama_aplikasi ?? config('app.name') }}
                    </h2>
                </div>
            @endif

            <div class="flex flex-col md:flex-row items-center justify-between gap-4 max-w-4xl mx-auto mt-6 mb-6">
                <div
                    class="bg-white rounded-xl shadow-md border border-blue-100 p-6 w-full md:w-1/2 min-h-[200px] flex items-center">
                    <div class="text-center w-full">
                        <div class="text-5xl font-['Rajdhani'] font-bold text-white tracking-tight bg-indigo-600 rounded-lg py-3 px-4 shadow-lg"
                            id="digital-clock">00:00:00</div>
                        <div class="text-lg text-white font-medium mt-3 bg-indigo-500 rounded-full py-2 px-5 inline-block shadow-md"
                            id="current-date">Senin, 1 Januari 2023</div>
                    </div>
                </div>
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full md:w-1/2 min-h-[200px] flex items-center">
                    <div class="text-center w-full">
                        <h2 class="text-xl font-semibold text-gray-800 mb-3">Petunjuk Penggunaan</h2>
                        <p class="text-gray-700">Pilih jenis layanan yang diinginkan untuk mengambil tiket antrian</p>
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
                        <div class="counters-list">
                            @forelse($service->counters as $counter)
                                <div class="counter-item">
                                    <div class="counter-name">
                                        <i class="fas fa-desktop text-indigo-500"></i>
                                        {{ $counter->name }}
                                    </div>
                                    <form id="ticketForm-{{ $service->id }}-{{ $counter->id }}" method="POST"
                                        action="{{ route('queue.ticket.take') }}" class="ticket-form">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                                        <input type="hidden" name="counter_id" value="{{ $counter->id }}">
                                        <button type="submit" class="btn-ticket">
                                            <i class="fas fa-ticket-alt"></i> Ambil Tiket
                                        </button>
                                    </form>
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
            <p>© {{ date('Y') }} {{ $profil->nama_instansi ?? 'Sistem Antrian' }} • All rights reserved</p>

        </footer>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function updateClock() {
            const now = new Date();

            // Format time
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('digital-clock').textContent = `${hours}:${minutes}:${seconds}`;

            // Format date
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];
            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            document.getElementById('current-date').textContent =
                `${dayName}, ${date} ${monthName} ${year}`;
        }

        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    <script>
        // Play success sound
        function playSuccessSound() {
            const audio = new Audio('{{ asset('sounds/bell.mp3') }}');
            audio.volume = 0.5;
            return audio.play().catch(e => console.log('Audio play failed:', e));
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
            const forms = document.querySelectorAll('.ticket-form');

            forms.forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

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

                        if (data.success) {
                            // Play sound and show success message
                            await Promise.all([
                                playSuccessSound(),
                                triggerConfetti()
                            ]);

                            // Show success modal
                            const {
                                value: result
                            } = await Swal.fire({
                                title: 'Tiket Berhasil Diambil!',
                                html: `
                                    <div class="text-center py-4">
                                        <div class="text-5xl mb-4 text-green-500">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h3 class="text-xl font-bold mb-2">${data.ticket_number}</h3>
                                        <p class="text-gray-600">Silahkan menunggu antrian Anda dipanggil</p>
                                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                            <p class="text-sm text-blue-700">
                                                <i class="fas fa-info-circle"></i> 
                                                ${data.service_name} - Loket ${data.counter_name}
                                            </p>
                                        </div>
                                    </div>
                                `,
                                showConfirmButton: true,
                                confirmButtonText: 'Tutup',
                                confirmButtonColor: '#4f46e5',
                                allowOutsideClick: false
                            });

                            // Auto refresh after 30 seconds if still on the page
                            setTimeout(() => {
                                location.reload();
                            }, 30000);

                        } else {
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
            document.querySelector('.time-display').textContent = now.toLocaleDateString('id-ID', options);
        }

        // Update time every second
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>

</html>
