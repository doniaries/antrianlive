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
    <meta name="debugbar" content="disabled">
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #0f172a;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #334155;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            flex-shrink: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo-icon {
            background-color: #3b82f6;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-text {
            font-size: 1.25rem;
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
            font-size: 1.5rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            color: #fbbf24;
            margin-bottom: 0.125rem;
        }

        .date {
            font-size: 0.875rem;
            color: #cbd5e1;
        }

        .main-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto 1fr auto;
            gap: 1rem;
            height: calc(100vh - 70px);
            padding: 1rem;
            overflow: hidden;
        }

        .card {
            background-color: #1e293b;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #334155;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #f8fafc;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .card-icon {
            width: 25px;
            height: 25px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .calling-card {
            grid-column: 1 / 2;
            grid-row: 1 / 2;
        }

        .video-card {
            grid-column: 2 / 3;
            grid-row: 1 / 3;
        }

        .next-card {
            grid-column: 1 / 2;
            grid-row: 2 / 3;
        }

        .services-card {
            grid-column: 1 / 3;
            grid-row: 3 / 4;
        }

        .queue-number {
            font-size: 3.5rem;
            font-weight: 800;
            text-align: center;
            color: #3b82f6;
            margin: 0.5rem 0;
            text-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            animation: pulse 2s infinite;
            line-height: 1;
        }

        .queue-service {
            font-size: 1.25rem;
            text-align: center;
            color: #f8fafc;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .queue-counter {
            font-size: 1.25rem;
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
            flex: 1;
            border-radius: 8px;
            overflow: hidden;
            background-color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .video-placeholder {
            text-align: center;
            color: #64748b;
        }

        .video-placeholder i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .next-queue {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            flex: 1;
            overflow-y: auto;
        }

        .next-item {
            background: linear-gradient(to right, #1e293b, #334155);
            border-radius: 8px;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .next-number {
            font-size: 2rem;
            font-weight: 700;
            color: #8b5cf6;
            margin-bottom: 0.125rem;
            line-height: 1;
        }

        .next-counter {
            font-size: 0.875rem;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
            flex: 1;
            overflow-y: auto;
            padding-right: 0.25rem;
        }

        .service-item {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 8px;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #475569;
            position: relative;
            overflow: hidden;
        }

        .service-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .service-item.active {
            border-color: #3b82f6;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }

        .service-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .service-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .service-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #f8fafc;
            text-align: center;
            line-height: 1.2;
        }

        .service-info {
            text-align: center;
            width: 100%;
        }

        .service-current {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3b82f6;
            margin: 0.25rem 0;
            line-height: 1;
        }

        .service-next {
            font-size: 1rem;
            font-weight: 600;
            color: #8b5cf6;
            margin-bottom: 0.125rem;
            line-height: 1;
        }

        .service-counter {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-bottom: 0.25rem;
        }

        .service-range {
            font-size: 0.7rem;
            color: #64748b;
            font-family: 'Courier New', monospace;
            padding: 0.125rem 0.25rem;
            background: rgba(30, 41, 59, 0.7);
            border-radius: 3px;
            display: inline-block;
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

        /* Real-time update animation */
        .updating {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        .queue-number.updating {
            animation: pulse 0.5s ease-in-out;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* Loading indicator */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-indicator {
            margin-top: 1rem;
            text-align: center;
        }

        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
                padding: 0.75rem;
            }
            
            .queue-number {
                font-size: 3rem;
            }
            
            .service-current {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto auto;
                gap: 0.5rem;
                padding: 0.5rem;
            }

            .calling-card,
            .video-card,
            .next-card,
            .services-card {
                grid-column: 1 / 2;
                grid-row: auto;
            }

            .header {
                padding: 0.5rem 1rem;
                height: 60px;
            }

            .logo-text {
                font-size: 1rem;
            }

            .time {
                font-size: 1.25rem;
            }

            .date {
                font-size: 0.75rem;
            }

            .queue-number {
                font-size: 2.5rem;
            }

            .queue-service {
                font-size: 1rem;
            }

            .queue-counter {
                font-size: 1rem;
            }

            .services-grid {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 0.5rem;
            }

            .service-item {
                padding: 0.5rem;
            }

            .service-name {
                font-size: 0.75rem;
            }

            .service-current {
                font-size: 1.25rem;
            }

            .service-next {
                font-size: 0.875rem;
            }

            .service-counter,
            .service-range {
                font-size: 0.65rem;
            }
        }

        @media (max-width: 480px) {
            .main-container {
                gap: 0.375rem;
                padding: 0.375rem;
            }

            .queue-number {
                font-size: 2rem;
            }

            .services-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* Hide scrollbar for better appearance */
        .services-grid::-webkit-scrollbar,
        .next-queue::-webkit-scrollbar {
            width: 4px;
        }

        .services-grid::-webkit-scrollbar-track,
        .next-queue::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .services-grid::-webkit-scrollbar-thumb,
        .next-queue::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 2px;
        }

        .services-grid::-webkit-scrollbar-thumb:hover,
        .next-queue::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .no-data {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 2rem;
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
            <div id="current-time" class="time">--:--:--</div>
            <div id="current-date" class="date">--</div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Sedang Dipanggil Card -->
        <div class="card calling-card">
            <h2 class="card-title">
                <div class="card-icon" style="background-color: #3b82f6;">
                    <i class="fas fa-volume-up text-white"></i>
                </div>
                Sedang Dipanggil
            </h2>
            <div class="queue-service" id="current-service">-</div>
            <div class="queue-number" id="current-number">---</div>
            <div class="queue-counter">
                <i class="fas fa-desktop counter-icon"></i>
                <span>Silakan menuju ke:</span>
                <span id="current-counter">-</span>
            </div>
            <div class="loading-indicator" id="loading-indicator" style="display: none; margin-top: 1rem; text-align: center;">
                <div class="loading"></div>
                <span style="color: #64748b; font-size: 0.875rem;">Memuat data...</span>
            </div>
            <div class="polling-status" id="polling-status" style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.5rem;">
                <span id="polling-info">Polling aktif (3 detik)</span>
                <button onclick="manualRefresh()" style="margin-left: 1rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; background: #3b82f6; color: white; border: none; border-radius: 0.25rem; cursor: pointer;">
                    Refresh
                </button>
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
            <div class="next-queue" id="next-queue-container">
                <div class="no-data">Memuat data antrian...</div>
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
            <div class="services-grid" id="services-container">
                <div class="no-data">Memuat data layanan...</div>
            </div>
        </div>
    </div>

    <!-- Running Text -->
    <div class="running-text">
        <div class="marquee" id="running-text">
            <i class="fas fa-info-circle"></i>
            Sistem Antrian Digital - Selamat datang di layanan kami
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Sistem Antrian Digital. All rights reserved.</p>
    </footer>

    <!-- Audio Element -->
    <audio id="callSound" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>

    <!-- Audio Test Button -->
    <button id="testAudioBtn"
        style="position: fixed; top: 10px; right: 10px; z-index: 9999; background: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
        🔔 Test Audio
    </button>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Audio notification functions
        function playCallSound() {
            const audio = document.getElementById('callSound');
            if (audio) {
                audio.currentTime = 0;
                audio.volume = 1.0; // Set volume to maximum
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
                utterance.rate = 0.8; // Slower for clarity
                utterance.pitch = 1.2; // Slightly higher pitch for better clarity
                utterance.volume = 1.0; // Maximum volume
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
<<<<<<< HEAD

                // Update running text
                const runningText = document.querySelector('.marquee');
                if (runningText) {
                    runningText.innerHTML =
                        `<i class="fas fa-info-circle"></i> Sedang dipanggil: ${data.number} di ${data.counter}. Silakan menunggu jika nomor Anda belum dipanggil.`;
                }

                // Play sound
                playCallSound();

                // Speak the announcement
                const textToSpeak = `Nomor antrian ${data.number}, silakan ke ${data.counter}`;
=======
                if (data.service) {
                    document.getElementById('current-service').textContent = data.service;
                }
                
                // Update running text
                const runningText = document.querySelector('.marquee');
                if (runningText) {
                    const serviceName = data.service || 'layanan';
                    runningText.innerHTML = `<i class="fas fa-info-circle"></i> Sedang dipanggil: Nomor ${data.number} untuk ${serviceName} di ${data.counter}.`;
                }
                
                // Play bell sound first
                playCallSound();
                
                // Speak the announcement after bell finishes (bell duration ~2-3 seconds)
                const serviceName = data.service || 'layanan';
                const textToSpeak = `Nomor antrian ${data.number}, silakan ke ${data.counter} untuk ${serviceName}`;
>>>>>>> 023110444ca7f535ec1659552ea30260067c1d1c
                setTimeout(() => {
                    speakText(textToSpeak);
                }, 2500); // Increased delay to ensure bell finishes
                
                // Refresh data from API
                fetchQueueData();
            });

            // Listen for queue-called event (browser event)
            window.addEventListener('queue-called', function(event) {
                const data = event.detail;
                console.log('Received queue-called event:', data);
<<<<<<< HEAD

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
                    runningText.innerHTML =
                        `<i class="fas fa-info-circle"></i> Sedang dipanggil: ${data.number} di ${data.counter}. Silakan menunggu jika nomor Anda belum dipanggil.`;
                }

                // Play sound
                playCallSound();

                // Speak the announcement
                const textToSpeak = `Nomor antrian ${data.number}, silakan ke ${data.counter}`;
=======
                
                // Update display immediately with smooth transition
                updateDisplayWithAnimation(data);
                
                // Update running text
                const runningText = document.querySelector('.marquee');
                if (runningText) {
                    const serviceName = data.service || 'layanan';
                    runningText.innerHTML = `<i class="fas fa-info-circle"></i> Sedang dipanggil: Nomor ${data.number} untuk ${serviceName} di ${data.counter}.`;
                }
                
                // Play bell sound first
                playCallSound();
                
                // Speak the announcement after bell finishes
                const serviceName = data.service || 'layanan';
                const textToSpeak = `Nomor antrian ${data.number}, silakan ke ${data.counter} untuk ${serviceName}`;
>>>>>>> 023110444ca7f535ec1659552ea30260067c1d1c
                setTimeout(() => {
                    speakText(textToSpeak);
                }, 2500); // Wait for bell to complete
                
                // Force immediate refresh
                fetchQueueData();
            });

            // Enhanced update function with animation
        function updateDisplayWithAnimation(data) {
            const currentNumberEl = document.getElementById('current-number');
            const currentCounterEl = document.getElementById('current-counter');
            const currentServiceEl = document.getElementById('current-service');
            
            // Add animation class
            [currentNumberEl, currentCounterEl, currentServiceEl].forEach(el => {
                if (el) el.classList.add('updating');
            });
            
            // Update content
            setTimeout(() => {
                if (data.number) currentNumberEl.textContent = data.number;
                if (data.counter) currentCounterEl.textContent = data.counter;
                if (data.service) currentServiceEl.textContent = data.service;
                
                // Remove animation class
                [currentNumberEl, currentCounterEl, currentServiceEl].forEach(el => {
                    if (el) el.classList.remove('updating');
                });
            }, 100);
        }
        
        // Manual refresh function for user
        function manualRefresh() {
            console.log('Manual refresh triggered');
            consecutiveErrors = 0;
            pollingInterval = 3000;
            fetchQueueData();
        }

            // Listen for Livewire events (for pages with Livewire)
            if (typeof Livewire !== 'undefined') {
                Livewire.on('antrian-called', function(data) {
                    console.log('Received Livewire antrian-called event:', data);
<<<<<<< HEAD

                    // Fetch fresh data from API
=======
                    
                    // Force immediate refresh
>>>>>>> 023110444ca7f535ec1659552ea30260067c1d1c
                    fetchQueueData();

                    // Create and dispatch browser event
                    const event = new CustomEvent('antrian-called', {
                        detail: data
                    });
                    window.dispatchEvent(event);
                });

                Livewire.on('queue-called', function(data) {
                    console.log('Received Livewire queue-called event:', data);
<<<<<<< HEAD

                    // Fetch fresh data from API
=======
                    
                    // Force immediate refresh
>>>>>>> 023110444ca7f535ec1659552ea30260067c1d1c
                    fetchQueueData();

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

            // Smart polling with visibility awareness
            let pollingTimer = null;
            
            function startPolling() {
                if (pollingTimer) return; // Already running
                
                // Initial load
                fetchQueueData();
                
                // Start interval polling
                pollingTimer = setInterval(() => {
                    if (!document.hidden && pollingActive) { // Only poll when tab is visible
                        fetchQueueData();
                    }
                }, pollingInterval);
            }
            
            function stopPolling() {
                if (pollingTimer) {
                    clearInterval(pollingTimer);
                    pollingTimer = null;
                }
            }
            
            // Show error message to user
        function showErrorMessage(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #ef4444;
                color: white;
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                max-width: 300px;
                font-size: 0.875rem;
            `;
            errorDiv.textContent = message;
            
            document.body.appendChild(errorDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.parentNode.removeChild(errorDiv);
                }
            }, 5000);
        }

        // Update polling status display
        function updatePollingStatus() {
            const pollingInfo = document.getElementById('polling-info');
            if (pollingInfo) {
                if (document.hidden) {
                    pollingInfo.textContent = 'Polling dijeda (tab tersembunyi)';
                } else if (consecutiveErrors > maxErrors) {
                    pollingInfo.textContent = `Polling lambat (${pollingInterval/1000} detik) - ${consecutiveErrors} error`;
                } else {
                    pollingInfo.textContent = `Polling aktif (${pollingInterval/1000} detik)`;
                }
            }
        }
            
            // Start smart polling
            startPolling();
            updatePollingStatus();
            
            // Test API endpoint on load
            setTimeout(() => {
                fetch('/api/display-data')
                    .then(response => {
                        if (!response.ok) {
                            console.warn('API endpoint might have issues:', response.status);
                        } else {
                            console.log('API endpoint is working correctly');
                        }
                    })
                    .catch(error => {
                        console.error('Cannot connect to API endpoint:', error);
                        showErrorMessage('Tidak dapat terhubung ke server. Pastikan server berjalan.');
                    });
            }, 1000);
            
            // Handle tab visibility changes
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopPolling(); // Stop when tab is hidden
                } else {
                    pollingInterval = 3000; // Reset to normal interval
                    startPolling(); // Restart when tab is visible
                }
                updatePollingStatus();
            });
            
            // Force refresh on window focus
            window.addEventListener('focus', function() {
                if (!document.hidden) {
                    fetchQueueData();
                }
            });
            
            // Handle page unload
            window.addEventListener('beforeunload', function() {
                stopPolling();
            });
            
            // Update status every 30 seconds
            setInterval(updatePollingStatus, 30000);
        });

        // Inisialisasi
        updateDateTime();
        setInterval(updateDateTime, 1000);

<<<<<<< HEAD
        // Fetch queue data from server
        async function fetchQueueData() {
            try {
                const response = await fetch('/api/display-data', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                updateDisplay(data);
            } catch (error) {
                console.error('Error fetching queue data:', error);
=======
        // Real-time polling configuration
        let lastUpdateTime = Date.now();
        let pollingInterval = 5000; // Poll every 5 seconds (reduced frequency)
        let pollingActive = true;
        let consecutiveErrors = 0;
        let maxErrors = 2; // More sensitive to errors
        let lastDataHash = null; // Cache hash for data comparison

        // Show error message function
        function showErrorMessage(message) {
            const existingError = document.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
>>>>>>> 023110444ca7f535ec1659552ea30260067c1d1c
            }
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            errorDiv.textContent = message;
            
            document.body.appendChild(errorDiv);
            
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.remove();
                }
            }, 5000);
        }

        // Enhanced fetch function with better error handling and fallback
        async function fetchQueueData() {
            updatePollingStatus('loading', 'Memuat data...');
            
            fetch('/api/display-data', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (checkDataChanges(data)) {
                    updateDisplay(data);
                }
                updatePollingStatus('success', 'Data terbaru');
                // Schedule next fetch
                pollingTimer = setTimeout(fetchQueueData, 5000);
            })
            .catch(error => {
                console.error('Error:', error);
                updatePollingStatus('error', 'Gagal memuat, mencoba ulang...');
                // Retry with longer delay
                pollingTimer = setTimeout(fetchQueueData, 10000);
            });
        }

        // Fungsi untuk update status polling
        function updatePollingStatus(status, message) {
            const statusElement = document.getElementById('polling-status');
            if (!statusElement) return;

            let color = 'text-gray-600';
            let icon = '🔄';

            switch (status) {
                case 'success':
                    color = 'text-green-600';
                    icon = '✅';
                    break;
                case 'error':
                    color = 'text-red-600';
                    icon = '❌';
                    break;
                case 'loading':
                    color = 'text-blue-600';
                    icon = '⏳';
                    break;
            }

            statusElement.innerHTML = `<span class="${color}">${icon} ${message}</span>`;
        }

        // Cache untuk data terakhir
        let lastData = null;
        
        // Check if queue data has changed
        function checkDataChanges(newData) {
            // Skip if no data
            if (!newData) return false;
            
            // First time load
            if (!lastData) {
                lastData = newData;
                return true;
            }
            
            // Compare timestamps to avoid unnecessary updates
            if (lastData.timestamp === newData.timestamp) {
                return false;
            }
            
            // Create simple hash for comparison
            const currentHash = JSON.stringify({
                currentCalled: newData.currentCalled || [],
                nextQueues: newData.nextQueues || []
            });
            
            if (lastDataHash === currentHash) {
                return false; // No changes
            }
            
            lastDataHash = currentHash;
            lastData = newData;
            return true;
        }

        // Helper function to get service icon
        function getServiceIcon(serviceName) {
            const icons = {
                'Pendaftaran': 'fas fa-user-plus',
                'Pembayaran': 'fas fa-money-bill',
                'Poliklinik': 'fas fa-user-md',
                'Apotek': 'fas fa-pills',
                'Laboratorium': 'fas fa-flask',
                'Radiologi': 'fas fa-x-ray',
                'Administrasi': 'fas fa-file-alt',
                'Informasi': 'fas fa-info-circle'
            };
            
            const normalizedName = serviceName.toLowerCase();
            for (const [key, icon] of Object.entries(icons)) {
                if (normalizedName.includes(key.toLowerCase())) {
                    return icon;
                }
            }
            return 'fas fa-list-ol';
        }

        // Update display with real data
        function updateDisplay(data) {
            // Update current calling queue
            if (data.currentCalled && data.currentCalled.length > 0) {
                const current = data.currentCalled[0];
                document.getElementById('current-number').textContent = current.formatted_number;
                document.getElementById('current-counter').textContent = current.counter_name || 'Loket';
                document.getElementById('current-service').textContent = current.service_name || '-';
            } else {
                document.getElementById('current-number').textContent = '---';
                document.getElementById('current-counter').textContent = '-';
                document.getElementById('current-service').textContent = '-';
            }

            // Update next queues - group by service
            const nextContainer = document.getElementById('next-queue-container');
            if (data.nextQueues && data.nextQueues.length > 0) {
                nextContainer.innerHTML = '';
                
                // Group by service
                const services = {};
                data.nextQueues.forEach(queue => {
                    if (!services[queue.service_name]) {
                        services[queue.service_name] = [];
                    }
                    if (services[queue.service_name].length < 2) { // Max 2 per service
                        services[queue.service_name].push(queue);
                    }
                });
                
                // Display next queues
                Object.entries(services).forEach(([serviceName, queues]) => {
                    queues.forEach(queue => {
                        const nextItem = document.createElement('div');
                        nextItem.className = 'next-item';
                        nextItem.innerHTML = `
                            <div class="next-number">${queue.formatted_number}</div>
                            <div class="next-counter">
                                <i class="fas fa-list-ol counter-icon"></i>
                                ${serviceName}
                            </div>
                        `;
                        nextContainer.appendChild(nextItem);
                    });
                });
            } else {
                nextContainer.innerHTML = '<div class="no-data">Tidak ada antrian</div>';
            }

            // Update services info with range
            const servicesContainer = document.getElementById('services-container');
            if (data.services && data.services.length > 0) {
                servicesContainer.innerHTML = '';
                data.services.forEach(service => {
                    const serviceItem = document.createElement('div');
                    serviceItem.className = 'service-item';

                    const currentCalled = data.currentCalled.find(q => q.service_id === service.id);
                    const nextQueue = data.nextQueues.find(q => q.service_id === service.id);
<<<<<<< HEAD

=======
                    
                    // Check if this service has current call
                    const isActive = currentCalled && currentCalled.service_id === service.id;
                    if (isActive) {
                        serviceItem.classList.add('active');
                    }
                    
                    const iconClass = getServiceIcon(service.name);
                    
>>>>>>> 023110444ca7f535ec1659552ea30260067c1d1c
                    serviceItem.innerHTML = `
                        <div class="service-header">
                            <div class="service-icon" style="background: ${isActive ? '#3b82f6' : '#475569'}">
                                <i class="${iconClass} text-white"></i>
                            </div>
                            <div class="service-name">${service.name}</div>
                        </div>
                        <div class="service-info">
                            <div class="service-current">${currentCalled ? currentCalled.formatted_number : '---'}</div>
                            <div class="service-next">${nextQueue ? nextQueue.formatted_number : '-'}</div>
                            <div class="service-counter">${currentCalled ? (currentCalled.counter_name || 'Loket') : ''}</div>
                            <div class="service-range">${service.range || ''}</div>
                        </div>
                    `;
                    servicesContainer.appendChild(serviceItem);
                });
            } else {
                servicesContainer.innerHTML = '<div class="no-data">Tidak ada layanan aktif</div>';
            }

            // Update running text
            const runningText = document.getElementById('running-text');
            if (data.currentCalled && data.currentCalled.length > 0) {
<<<<<<< HEAD
                const calls = data.currentCalled.map(q =>
                    `${q.formatted_number} di ${q.counter_name || 'Loket'}`
                ).join(', ');
=======
                const calls = data.currentCalled.map(q => 
                    `Nomor ${q.formatted_number} untuk ${q.service_name} di ${q.counter_name || 'Loket'}`
                ).join(' • ');
>>>>>>> 023110444ca7f535ec1659552ea30260067c1d1c
                runningText.innerHTML = `
                    <i class="fas fa-info-circle"></i>
                    ${calls}. Silakan menunggu jika nomor Anda belum dipanggil.
                `;
            } else {
                runningText.innerHTML = `
                    <i class="fas fa-info-circle"></i>
                    Sistem Antrian Digital - Selamat datang di layanan kami
                `;
            }
        }

        function updatePollingStatus(status, message) {
            const statusElement = document.getElementById('polling-status');
            if (!statusElement) return;

            let color = 'text-gray-600';
            let icon = '🔄';

            switch (status) {
                case 'success':
                    color = 'text-green-600';
                    icon = '✅';
                    break;
                case 'error':
                    color = 'text-red-600';
                    icon = '❌';
                    break;
                case 'loading':
                    color = 'text-blue-600';
                    icon = '⏳';
                    break;
            }

            statusElement.innerHTML = `<span class="${color}">${icon} ${message}</span>`;
        }

        function showErrorMessage(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            errorDiv.textContent = message;
            
            const existingError = document.querySelector('.fixed.top-4.right-4.bg-red-500');
            if (existingError) {
                existingError.remove();
            }
            
            document.body.appendChild(errorDiv);
            
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.remove();
                }
            }, 5000);
        }

        // Simplified polling configuration
        let pollingTimer = null;

        function startPolling() {
            fetchQueueData();
        }

        function stopPolling() {
            if (pollingTimer) {
                clearTimeout(pollingTimer);
                pollingTimer = null;
            }
        }

        function manualRefresh() {
            stopPolling();
            fetchQueueData();
        }

        // Start polling when page loads
        document.addEventListener('DOMContentLoaded', startPolling);
    </script>
</body>

</html>
