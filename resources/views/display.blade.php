<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Digital</title>
    @php
        $faviconUrl = $profil && $profil->favicon ? asset('storage/' . $profil->favicon) : '/favicon.ico';
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #0f172a;
            padding: 1rem 2rem;
            border-bottom: 1px solid #334155;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            background-color: #3b82f6;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .datetime {
            text-align: right;
        }

        .time {
            font-size: 1.75rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            color: #fbbf24;
            margin-bottom: 0.25rem;
        }

        .date {
            font-size: 1rem;
            color: #cbd5e1;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background-color: #1e293b;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #334155;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: #f8fafc;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .calling-card {
            grid-column: 1 / 2;
        }

        .video-card {
            grid-column: 2 / 3;
            grid-row: 1 / 3;
        }

        .next-card {
            grid-column: 1 / 2;
        }

        .services-card {
            grid-column: 1 / 3;
        }

        .queue-number {
            font-size: 4rem;
            font-weight: 800;
            text-align: center;
            color: #3b82f6;
            margin: 1rem 0;
            text-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            animation: pulse 2s infinite;
        }

        .queue-counter {
            font-size: 1.5rem;
            text-align: center;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .counter-icon {
            color: #06b6d4;
        }

        .video-container {
            width: 100%;
            height: 280px;
            border-radius: 8px;
            overflow: hidden;
            background-color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-placeholder {
            text-align: center;
            color: #64748b;
        }

        .video-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .next-queue {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .next-item {
            background: linear-gradient(to right, #1e293b, #334155);
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .next-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #8b5cf6;
            margin-bottom: 0.25rem;
        }

        .next-counter {
            font-size: 1rem;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .service-item {
            background: linear-gradient(to right, #1e293b, #334155);
            border-radius: 8px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .service-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #f8fafc;
        }

        .service-range {
            font-size: 1.25rem;
            font-weight: 700;
            color: #3b82f6;
        }

        .service-range.secondary {
            color: #8b5cf6;
        }

        .running-text {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            padding: 1rem 0;
            overflow: hidden;
            margin-top: 2rem;
        }

        .marquee {
            white-space: nowrap;
            animation: marquee 25s linear infinite;
            font-size: 1.1rem;
            color: #0f172a;
            font-weight: 500;
        }

        .marquee i {
            margin-right: 1rem;
        }

        .footer {
            background-color: #0f172a;
            padding: 1rem 0;
            text-align: center;
            color: #94a3b8;
            font-size: 0.9rem;
            border-top: 1px solid #334155;
            margin-top: 2rem;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .calling-card,
            .video-card,
            .next-card,
            .services-card {
                grid-column: 1 / 2;
            }

            .video-card {
                grid-row: auto;
            }

            .queue-number {
                font-size: 3rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-list-ol text-white text-xl"></i>
            </div>
            <div class="logo-text">Sistem Antrian Digital</div>
        </div>
        <div class="datetime">
            <div id="current-time" class="time">14:25:36</div>
            <div id="current-date" class="date">Selasa, 12 Maret 2024</div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Sedang Dipanggil Card -->
        <div class="card calling-card">
            <h2 class="card-title">
                <div class="card-icon" style="background-color: #3b82f6;">
                    <i class="fas fa-volume-up text-white"></i>
                </div>
                Sedang Dipanggil
            </h2>
            <div class="queue-number" id="current-number">B007</div>
            <div class="queue-counter">
                <i class="fas fa-map-marker-alt counter-icon"></i>
                <span id="current-counter">Loket 2</span>
            </div>
        </div>

        <!-- Video Informasi Card -->
        <div class="card video-card">
            <h2 class="card-title">
                <div class="card-icon" style="background-color: #ef4444;">
                    <i class="fab fa-youtube text-white"></i>
                </div>
                Video Informasi
            </h2>
            <div class="video-container">
                <div class="video-placeholder">
                    <i class="fas fa-play-circle"></i>
                    <div>IoTI hip hop radio</div>
                    <div>Informatija</div>
                    <div>Tagikan</div>
                    <div>Info</div>
                </div>
            </div>
        </div>

        <!-- Akan Dipanggil Card -->
        <div class="card next-card">
            <h2 class="card-title">
                <div class="card-icon" style="background-color: #8b5cf6;">
                    <i class="fas fa-forward text-white"></i>
                </div>
                Akan Dipanggil
            </h2>
            <div class="next-queue">
                <div class="next-item">
                    <div class="next-number">B008</div>
                    <div class="next-counter">
                        <i class="fas fa-map-marker-alt counter-icon"></i>
                        Loket 2
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Layanan Card -->
        <div class="card services-card">
            <h2 class="card-title">
                <div class="card-icon" style="background-color: #10b981;">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                Informasi Layanan
            </h2>
            <div class="services-grid">
                <div class="service-item">
                    <div class="service-name">Layanan A</div>
                    <div class="service-range">A001 – A050</div>
                </div>
                <div class="service-item">
                    <div class="service-name">Layanan B</div>
                    <div class="service-range secondary">B001 – B030</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Running Text -->
    <div class="running-text">
        <div class="marquee">
            <i class="fas fa-info-circle"></i>
            Sedang dipanggil: B007 di Loket 2. Silakan menunggu jika nomor Anda belum dipanggil.
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Sistem Antrian Digital. All rights reserved.</p>
    </footer>

    <!-- Audio Element -->
    <audio id="callSound" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>
    
    <!-- Audio Test Button -->
    <button id="testAudioBtn" style="position: fixed; top: 10px; right: 10px; z-index: 9999; background: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
        🔔 Test Audio
    </button>

    <script>
        // Audio notification functions
        function playCallSound() {
            const audio = document.getElementById('callSound');
            if (audio) {
                audio.currentTime = 0;
                const playPromise = audio.play();
                
                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        console.log('Audio played successfully');
                    }).catch(error => {
                        console.log('Audio play failed:', error);
                        // Try to show notification instead
                        alert('Audio blocked by browser. Please click "Test Audio" button first to enable audio.');
                    });
                }
            }
        }

        function speakText(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.pitch = 1;
                speechSynthesis.speak(utterance);
            }
        }

        // Test audio button functionality
        document.addEventListener('DOMContentLoaded', function() {
            const testBtn = document.getElementById('testAudioBtn');
            testBtn.addEventListener('click', function() {
                console.log('Testing audio...');
                playCallSound();
                speakText('Testing audio sistem antrian');
            });
        });

        // Update waktu dan tanggal
        function updateDateTime() {
            const now = new Date();
            const timeElement = document.getElementById('current-time');
            const dateElement = document.getElementById('current-date');

            // Format waktu
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            timeElement.textContent = `${hours}:${minutes}:${seconds}`;

            // Format tanggal Indonesia
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];

            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            dateElement.textContent = `${dayName}, ${date} ${monthName} ${year}`;
        }

        // Handle events for audio
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for antrian-called event (browser event)
            window.addEventListener('antrian-called', function(event) {
                const data = event.detail;
                console.log('Received antrian-called event:', data);
                
                // Update display immediately
                if (data.number) {
                    document.getElementById('current-number').textContent = data.number;
                }
                if (data.counter) {
                    document.getElementById('current-counter').textContent = data.counter;
                }
                
                // Update running text
                const runningText = document.querySelector('.marquee');
                if (runningText) {
                    runningText.innerHTML = `<i class="fas fa-info-circle"></i> Sedang dipanggil: ${data.number} di ${data.counter}. Silakan menunggu jika nomor Anda belum dipanggil.`;
                }
                
                // Play sound
                playCallSound();
                
                // Speak the announcement
                const textToSpeak = `Nomor antrian ${data.number}, silakan ke ${data.counter}`;
                setTimeout(() => {
                    speakText(textToSpeak);
                }, 500);
            });

            // Listen for queue-called event (browser event)
            window.addEventListener('queue-called', function(event) {
                const data = event.detail;
                console.log('Received queue-called event:', data);
                
                // Update display immediately
                if (data.number) {
                    document.getElementById('current-number').textContent = data.number;
                }
                if (data.counter) {
                    document.getElementById('current-counter').textContent = data.counter;
                }
                
                // Update running text
                const runningText = document.querySelector('.marquee');
                if (runningText) {
                    runningText.innerHTML = `<i class="fas fa-info-circle"></i> Sedang dipanggil: ${data.number} di ${data.counter}. Silakan menunggu jika nomor Anda belum dipanggil.`;
                }
                
                // Play sound
                playCallSound();
                
                // Speak the announcement
                const textToSpeak = `Nomor antrian ${data.number}, silakan ke ${data.counter}`;
                setTimeout(() => {
                    speakText(textToSpeak);
                }, 500);
            });

            // Listen for Livewire events (for pages with Livewire)
            if (typeof Livewire !== 'undefined') {
                Livewire.on('antrian-called', function(data) {
                    console.log('Received Livewire antrian-called event:', data);
                    
                    // Create and dispatch browser event
                    const event = new CustomEvent('antrian-called', {
                        detail: data
                    });
                    window.dispatchEvent(event);
                });

                Livewire.on('queue-called', function(data) {
                    console.log('Received Livewire queue-called event:', data);
                    
                    // Create and dispatch browser event
                    const event = new CustomEvent('queue-called', {
                        detail: data
                    });
                    window.dispatchEvent(event);
                });
            }

            // Listen for any global events
            document.addEventListener('livewire:initialized', function() {
                console.log('Livewire initialized on display page');
            });
        });

        // Inisialisasi
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Simulasi perubahan antrian (hanya untuk demo)
        function simulateQueueChange() {
            const numbers = ['B007', 'B008', 'B009', 'A012', 'A013'];
            const counters = ['Loket 2', 'Loket 1', 'Loket 3'];

            setInterval(() => {
                const randomNumber = numbers[Math.floor(Math.random() * numbers.length)];
                const randomCounter = counters[Math.floor(Math.random() * counters.length)];

                document.getElementById('current-number').textContent = randomNumber;
                document.getElementById('current-counter').textContent = randomCounter;

                // Update running text
                document.querySelector('.marquee').innerHTML =
                    `<i class="fas fa-info-circle"></i> Sedang dipanggil: ${randomNumber} di ${randomCounter}. Silakan menunggu jika nomor Anda belum dipanggil.`;

            }, 10000);
        }

        // Jalankan simulasi (bisa dihapus di production)
        simulateQueueChange();
    </script>
</body>

</html>
