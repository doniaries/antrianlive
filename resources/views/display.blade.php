<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Digital</title>
    @php
        // Mock data for Blade variables since we are not in a Laravel environment
        class Profil
        {
            public static function first()
            {
                return (object) [
                    'favicon' => null,
                    'logo' => null,
                    'nama_instansi' => 'Nama Instansi Anda',
                ];
            }
        }
        $profil = Profil::first();
        $faviconUrl = $profil && $profil->favicon ? asset('storage/' . $profil->favicon) : '/favicon.ico';
        $logoUrl = $profil && $profil->logo ? asset('storage/' . $profil->logo) : null;
        $namaInstansi = $profil && $profil->nama_instansi ? $profil->nama_instansi : 'Sistem Antrian Digital';
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        :root {
            --bg-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(255, 255, 255, 0.1);
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --accent-blue: #3b82f6;
            --accent-cyan: #06b6d4;
            --accent-amber: #fbbf24;
            --accent-red: #ef4444;
            --accent-green: #22c55e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            background-image: url('https://images.unsplash.com/photo-1593814681464-e5873a069f2d?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--text-primary);
            height: 100vh;
            overflow: hidden;
        }

        /* --- Header --- */
        .header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.5rem 2rem;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-img {
            height: 45px;
            width: 45px;
            object-fit: contain;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 5px;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .datetime {
            text-align: right;
        }

        .time {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent-amber);
            font-family: 'Courier New', monospace;
        }

        .date {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        /* --- Main Layout --- */
        .main-container {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            grid-template-rows: auto 1fr;
            gap: 1rem;
            min-height: calc(100vh - 60px);
            padding: 0.75rem;
            padding-top: 70px;
            box-sizing: border-box;
            margin: 0;
        }

        /* --- Card Base Style --- */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--card-border);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
            color: var(--text-secondary);
        }

        .card-title i {
            font-size: 1.5rem;
            width: 30px;
            text-align: center;
        }

        /* --- Grid Item Placement --- */
        .calling-card {
            grid-column: 1 / 2;
            grid-row: 1 / 2;
            margin-top: 0;
            padding: 1.25rem;
            min-height: 360px;
            max-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .video-card {
            grid-column: 2 / 3;
            grid-row: 1 / 2;
            height: 100%;
            min-height: 360px;
            max-height: 400px;
            display: flex;
            flex-direction: column;
            margin-top: 0;
            padding: 0.75rem;
        }

        .video-container {
            flex: 1;
            min-height: 0; /* Allow container to shrink */
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            background: #000;
        }
        
        .video-container iframe {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border: none;
        }

        .services-card {
            grid-column: 1 / 3;
            grid-row: 2 / 3;
            max-height: 50vh;
            overflow-y: auto;
            margin-top: 0;
            padding: 0.5rem;
        }

        .running-text-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            border-top: 1px solid var(--card-border);
            padding: 0.75rem 0;
            z-index: 1000;
            overflow: hidden;
            white-space: nowrap;
        }

        /* --- Calling Card --- */
        .calling-card {
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 2px solid var(--accent-blue);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.4);
        }

        .calling-card .card-title {
            color: var(--text-primary);
            font-size: 2rem;
        }

        .calling-card .card-title i {
            color: var(--accent-blue);
        }

        .queue-number {
            font-size: 14vw;
            /* Responsive font size */
            font-weight: 800;
            line-height: 1;
            color: var(--accent-amber);
            text-shadow: 0 0 40px rgba(251, 191, 36, 0.7);
            margin: 2rem 0;
            animation: pulse-glow 2s infinite ease-in-out;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                transform: scale(1);
                text-shadow: 0 0 40px rgba(251, 191, 36, 0.7);
            }

            50% {
                transform: scale(1.05);
                text-shadow: 0 0 60px rgba(251, 191, 36, 1);
            }
        }

        .queue-service {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .queue-counter {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .queue-counter i {
            color: var(--accent-cyan);
        }

        .current-service {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0.75rem 0;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        .current-number {
            font-size: 5rem;
            font-weight: 800;
            color: var(--primary);
            margin: 1rem 0;
            line-height: 1.1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* --- Video Card --- */
        .video-card .card-title i {
            color: var(--accent-red);
        }

        .video-container {
            flex-grow: 1;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-container iframe,
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            border: none;
        }

        .video-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748b;
            background: rgba(30, 41, 59, 0.5);
            z-index: 1;
        }

        .video-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #475569;
        }

        /* --- Services Card --- */
        .services-card .card-title i {
            color: var(--accent-green);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 0.9rem;
            padding: 0.5rem;
        }

        .services-grid::-webkit-scrollbar {
            width: 6px;
        }

        .services-grid::-webkit-scrollbar-track {
            background: transparent;
        }

        .services-grid::-webkit-scrollbar-thumb {
            background: var(--accent-blue);
            border-radius: 3px;
        }

        .service-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--card-border);
            border-radius: 10px;
            padding: 1rem 0.5rem;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            transition: all 0.2s ease;
        }

        .calling-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
            padding: 1rem;
            flex: 1;
            text-align: center;
        }

        .service-item:hover {
            transform: translateY(-5px);
            border-color: var(--accent-cyan);
        }

        .service-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
            color: var(--text-primary);
        }

        .service-current {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1.2;
        }

        .service-item.active {
            border-color: var(--accent-amber);
            background: rgba(251, 191, 36, 0.1);
            animation: blink-border 2s infinite;
        }

        @keyframes blink-border {
            50% {
                border-color: transparent;
            }
        }

        /* --- Running Text --- */
        .running-text-content {
            display: inline-block;
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--text-primary);
            padding-left: 100%;
            animation: marquee 30s linear infinite;
        }

        .running-text-content span {
            margin: 0 2rem;
        }

        .running-text-content i {
            color: var(--accent-amber);
            margin-right: 0.5rem;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* --- Fullscreen Button --- */
        #fullscreen-btn {
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--card-bg);
            color: white;
            border: 1px solid var(--card-border);
            cursor: pointer;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            font-size: 1.25rem;
        }

        #fullscreen-btn:hover {
            transform: scale(1.1);
            background: var(--accent-blue);
            border-color: var(--accent-blue);
        }

        /* --- Responsive Design --- */
        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto;
                padding-bottom: 80px;
                /* Space for running text */
            }

            .calling-card,
            .video-card,
            .services-card {
                grid-column: 1 / 2;
                grid-row: auto;
            }

            .calling-card {
                height: 40vh;
            }

            .video-card {
                height: 35vh;
            }

            .services-card {
                flex-grow: 1;
            }

            .queue-number {
                font-size: 20vw;
            }

            .queue-service,
            .queue-counter {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0.5rem 1rem;
                height: 60px;
                position: fixed;
                z-index: 1000;
            }

            .logo-text {
                font-size: 1.2rem;
            }

            .time {
                font-size: 1.5rem;
            }

            .date {
                font-size: 0.8rem;
            }

            .main-container {
                padding: 1rem;
                padding-top: 70px;
                gap: 1rem;
                margin-top: 60px;
            }

            .card {
                padding: 1rem;
            }

            .calling-card {
                min-height: 250px;
                height: auto;
            }

            .video-card {
                min-height: 200px;
                height: auto;
            }

            .queue-number {
                font-size: 25vw;
            }

            .queue-service,
            .queue-counter {
                font-size: 1.5rem;
            }

            .services-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .service-current {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="logo">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo Instansi" class="logo-img">
            @else
                <i class="fas fa-list-ol fa-2x"></i>
            @endif
            <div class="logo-text">{{ $namaInstansi }}</div>
        </div>
        <div class="datetime">
            <div id="current-time" class="time">--:--:--</div>
            <div id="current-date" class="date">--</div>
        </div>
    </header>

    <div class="main-container">
        <div class="card calling-card">
            <h2 class="card-title">
                <i class="fas fa-bullhorn"></i>
                Sedang Dipanggil
            </h2>
            <div class="queue-number" id="current-number">---</div>
            <div class="queue-service" id="current-service">-</div>
            <div class="queue-counter">
                <i class="fas fa-desktop"></i>
                <span id="current-counter">-</span>
            </div>
        </div>

        <div class="card video-card">
            <h2 class="card-title">
                <i class="fab fa-youtube"></i>
                Video Informasi
            </h2>
            <div class="video-container">
                <div id="video-player" class="video-player">
                    <div class="video-placeholder">
                        <i class="fas fa-play-circle"></i>
                        <div>Memuat video...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card services-card">
            <h2 class="card-title">
                <i class="fas fa-users"></i>
                Dalam Antrian
            </h2>
            <div class="services-grid" id="services-container">
                <div class="no-data">Memuat data layanan...</div>
            </div>
        </div>
    </div>

    <div class="running-text-wrapper">
        <div class="running-text-content" id="running-text-content">
            <span><i class="fas fa-info-circle"></i> Memuat informasi...</span>
        </div>
    </div>

    <button id="fullscreen-btn" title="Toggle Fullscreen">
        <i class="fas fa-expand-arrows-alt"></i>
    </button>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // --- UTILITY FUNCTIONS ---
        function safeQuerySelector(selector) {
            const element = document.querySelector(selector);
            if (!element) console.warn(`Element not found: ${selector}`);
            return element;
        }

        function safeGetElementById(id) {
            const element = document.getElementById(id);
            if (!element) console.warn(`Element not found: ${id}`);
            return element;
        }

        // --- CORE FUNCTIONS ---

        // 1. Update Date and Time
        function updateDateTime() {
            const now = new Date();
            const timeEl = safeGetElementById('current-time');
            const dateEl = safeGetElementById('current-date');
            if (!timeEl || !dateEl) return;

            const timeString = now.toLocaleTimeString('id-ID', {
                hour12: false
            });
            timeEl.textContent = timeString;

            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            dateEl.textContent = dateString;
        }

        // 2. Fetch and Update Queue Data
        async function fetchQueueData() {
            try {
                const response = await fetch('/api/display-data?t=' + Date.now(), {
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                updateDisplay(data);
            } catch (error) {
                console.error("Failed to fetch queue data:", error);
            }
        }

        // 3. Update Display with Fetched Data
        function updateDisplay(data) {
            const currentNumberEl = safeGetElementById('current-number');
            const currentCounterEl = safeGetElementById('current-counter');
            const currentServiceEl = safeGetElementById('current-service');
            const servicesContainer = safeGetElementById('services-container');

            if (!data) return;

            // Update currently called number
            const currentCall = data.currentCalled && data.currentCalled.length > 0 ? data.currentCalled[0] : null;
            if (currentCall) {
                if (currentNumberEl.textContent !== currentCall.formatted_number) {
                    currentNumberEl.textContent = currentCall.formatted_number;
                }
                currentCounterEl.textContent = currentCall.counter_name || 'Loket';
                currentServiceEl.textContent = currentCall.service_name || '-';
            } else {
                currentNumberEl.textContent = '---';
                currentCounterEl.textContent = '-';
                currentServiceEl.textContent = '-';
            }

            // Update services grid
            if (servicesContainer) {
                if (data.services && data.services.length > 0) {
                    servicesContainer.innerHTML = data.services.map(service => {
                        const isBeingCalled = currentCall && currentCall.service_id === service.id;
                        return `
                            <div class="service-item ${isBeingCalled ? 'active' : ''}" data-service-id="${service.id}">
                                <div class="service-name">${service.name}</div>
                                <div class="service-current">${isBeingCalled ? currentCall.formatted_number : '---'}</div>
                            </div>
                        `;
                    }).join('');
                } else {
                    servicesContainer.innerHTML = '<div class="no-data">Tidak ada layanan aktif</div>';
                }
            }
        }

        // 4. Load Video Player
        let currentVideoId = null;
        async function loadVideo() {
            const videoPlayer = safeGetElementById('video-player');
            if (!videoPlayer) return;

            try {
                const response = await fetch('/api/video', {
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });
                if (!response.ok) throw new Error('Video API request failed');
                const data = await response.json();

                if (!data.success || !data.video) {
                    videoPlayer.innerHTML =
                        `<div class="video-placeholder"><i class="fas fa-video-slash"></i><div>Tidak ada video aktif</div></div>`;
                    return;
                }

                if (currentVideoId === data.video.id) return; // Don't reload the same video
                currentVideoId = data.video.id;

                if (data.video.type === 'youtube') {
                    const youtubeUrl = new URL(data.video.url);
                    youtubeUrl.searchParams.set('autoplay', '1');
                    youtubeUrl.searchParams.set('mute', '1'); // Mute is required for autoplay in most browsers
                    youtubeUrl.searchParams.set('loop', '1');
                    youtubeUrl.searchParams.set('playlist', youtubeUrl.searchParams.get(
                        'v')); // Required for loop to work
                    videoPlayer.innerHTML =
                        `<iframe src="${youtubeUrl.toString()}" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
                } else if (data.video.type === 'file') {
                    videoPlayer.innerHTML = `<video autoplay loop muted playsinline src="${data.video.url}"></video>`;
                }
            } catch (error) {
                console.error('Error loading video:', error);
                videoPlayer.innerHTML =
                    `<div class="video-placeholder"><i class="fas fa-exclamation-triangle"></i><div>Gagal memuat video</div></div>`;
            }
        }

        // 5. Load and Animate Running Text
        async function loadRunningTeks() {
            const contentEl = safeGetElementById('running-text-content');
            if (!contentEl) return;
            try {
                const response = await fetch('/api/running-teks', {
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });
                if (!response.ok) throw new Error('Running Teks API failed');
                const data = await response.json();

                let texts =
                    "<span><i class='fas fa-info-circle'></i> Selamat Datang di Sistem Antrian Digital Kami.</span>";
                if (data.running_teks && data.running_teks.length > 0) {
                    texts = data.running_teks.map(item =>
                        `<span><i class='fas fa-info-circle'></i> ${item.text}</span>`).join('');
                }

                // Duplicate content for seamless loop
                contentEl.innerHTML = texts + texts;

                // Adjust animation duration based on content width
                const wrapperWidth = contentEl.parentElement.offsetWidth;
                const contentWidth = contentEl.scrollWidth / 2; // width of a single set of texts
                const duration = (contentWidth + wrapperWidth) / 100; // Adjust 100 to change speed
                contentEl.style.animationDuration = `${Math.max(duration, 20)}s`;

            } catch (error) {
                console.error("Failed to load running text:", error);
            }
        }

        // --- FULLSCREEN TOGGLE ---
        function setupFullscreenButton() {
            const fullscreenBtn = safeGetElementById('fullscreen-btn');
            const icon = fullscreenBtn.querySelector('i');

            function updateIcon() {
                const isInFullscreen = !!document.fullscreenElement;
                icon.className = isInFullscreen ? 'fas fa-compress' : 'fas fa-expand-arrows-alt';
                fullscreenBtn.title = isInFullscreen ? 'Keluar Fullscreen' : 'Masuk Fullscreen';
            }

            fullscreenBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().catch(err => console.error(err));
                } else {
                    document.exitFullscreen();
                }
            });

            document.addEventListener('fullscreenchange', updateIcon);
            updateIcon(); // Initial check
        }

        // --- INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', () => {
            // Initial calls
            updateDateTime();
            fetchQueueData();
            loadVideo();
            loadRunningTeks();
            setupFullscreenButton();

            // Set intervals for updates
            setInterval(updateDateTime, 1000); // Every second for the clock
            setInterval(fetchQueueData, 3000); // Every 3 seconds for queue data
            setInterval(loadVideo, 5 * 60 * 1000); // Every 5 minutes for video
            setInterval(loadRunningTeks, 5 * 60 * 1000); // Every 5 minutes for running text
        });
    </script>
</body>

</html>
