<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Digital</title>
    @php
        // Mock data for Blade variables
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
        $faviconUrl = $profil->favicon ? asset('storage/' . $profil->favicon) : '/favicon.ico';
        $logoUrl = $profil->logo ? asset('storage/' . $profil->logo) : null;
        $namaInstansi = $profil->nama_instansi ?: 'Sistem Antrian Digital';
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap');

        :root {
            --bg-main: #eef2f6;
            /* Blue Gray 100 */
            --bg-panel: #ffffff;
            --primary-blue: #0d47a1;
            /* Deep Blue */
            --primary-blue-light: #1976d2;
            /* Lighter Blue */
            --accent-yellow: #ffab00;
            /* Amber */
            --text-dark: #212121;
            --text-light: #ffffff;
            --border-color: #d1d9e2;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* --- Header & Footer --- */
        .header,
        .footer {
            background-color: var(--primary-blue);
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            flex-shrink: 0;
            width: 100%;
            z-index: 10;
        }

        .header {
            height: var(--header-height);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-text {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .datetime {
            text-align: right;
        }

        .time {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .date {
            font-size: 1rem;
            opacity: 0.8;
        }

        .footer {
            overflow: hidden;
            white-space: nowrap;
            padding: 0.75rem 0;
        }

        .running-text-content {
            display: inline-block;
            font-size: 1.25rem;
            padding-left: 100%;
            animation: marquee 30s linear infinite;
        }

        .running-text-content span {
            margin: 0 2rem;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* --- Main Layout --- */
        .main-container {
            flex-grow: 1;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            padding: 1.5rem;
            overflow: hidden;
        }

        .main-display {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* --- Panels --- */
        .panel {
            background-color: var(--bg-panel);
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            background-color: var(--primary-blue-light);
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .panel-body {
            padding: 1.5rem;
            flex-grow: 1;
        }

        /* --- Calling Panel --- */
        .calling-panel {
            flex-grow: 1;
        }

        .calling-panel .panel-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .label {
            font-size: 2rem;
            font-weight: 500;
            color: #555;
        }

        #current-number {
            font-size: clamp(10rem, 22vh, 18rem);
            font-weight: 900;
            color: var(--text-dark);
            line-height: 1;
            margin: 1rem 0;
            color: var(--accent-yellow);
            -webkit-text-stroke: 4px var(--text-dark);
            text-shadow: 6px 6px 0 var(--text-dark);
        }

        #current-counter {
            font-size: 4rem;
            font-weight: 700;
        }

        /* --- Video Panel --- */
        .video-panel .panel-body {
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .video-container {
            width: 100%;
            height: 100%;
            min-height: 280px;
            max-height: 400px;
            aspect-ratio: 16/9;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .video-container iframe,
        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border: none;
        }

        .video-placeholder {
            color: #888;
            text-align: center;
        }

        .video-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* --- History Panel --- */
        .history-panel {
            display: flex;
            flex-direction: column;
        }

        #history-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease-in-out;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-item:nth-child(odd) {
            background-color: #f7f9fc;
        }

        .history-number {
            font-size: 2.25rem;
            font-weight: 700;
        }

        .history-counter {
            font-size: 1.5rem;
            font-weight: 500;
            color: #333;
        }

        /* -- Animation for New Call -- */
        .new-call-main {
            animation: highlight-main 1s ease;
        }

        @keyframes highlight-main {
            0% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .new-call-item {
            background-color: var(--accent-yellow) !important;
            color: var(--text-dark);
            animation: highlight-item 2s ease forwards;
        }

        @keyframes highlight-item {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }

            40% {
                transform: translateX(0);
                opacity: 1;
                background-color: var(--accent-yellow);
            }

            100% {
                background-color: var(--bg-panel);
            }
        }

        /* --- Responsive --- */
        @media (max-width: 1024px) {
            .main-container {
                grid-template-columns: 1fr;
                overflow-y: auto;
            }

            .main-display {
                order: 1;
            }

            .history-panel {
                order: 2;
                min-height: 300px;
            }

            .calling-panel {
                min-height: 400px;
            }

            #current-number {
                font-size: 25vw;
            }

            #current-counter {
                font-size: 8vw;
            }
        }

        @media (max-width: 768px) {
            :root {
                --header-height: 60px;
            }

            .header,
            .footer {
                padding: 0 1rem;
            }

            .logo-text {
                font-size: 1.25rem;
            }

            .time {
                font-size: 1.5rem;
            }

            .main-container {
                padding: 1rem;
                gap: 1rem;
            }

            .panel-header {
                font-size: 1.1rem;
                padding: 0.5rem 1rem;
            }

            .panel-body {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="logo">
            <i class="fas fa-layer-group fa-2x"></i>
            <span class="logo-text">{{ $namaInstansi }}</span>
        </div>
        <div class="datetime">
            <div id="current-time" class="time">--:--:--</div>
            <div id="current-date" class="date">--</div>
        </div>
    </header>

    <main class="main-container">
        <div class="main-display">
            <div class="panel calling-panel">
                <div class="panel-header">NOMOR ANTRIAN</div>
                <div class="panel-body">
                    <div id="calling-content">
                        <span class="label">Nomor</span>
                        <div id="current-number">---</div>
                        <span class="label">Silakan Menuju</span>
                        <div id="current-counter">-</div>
                    </div>
                </div>
            </div>
            <div class="panel video-panel">
                <div class="panel-body">
                    <div id="video-player" class="video-container">
                        <div class="video-placeholder">
                            <i class="fas fa-play-circle"></i>
                            <div>Memuat video...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel history-panel">
            <div class="panel-header">RIWAYAT PANGGILAN</div>
            <ul id="history-list">
            </ul>
        </div>
    </main>

    <footer class="footer">
        <div class="running-text-content" id="running-text-content"></div>
    </footer>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // --- State Management ---
        let lastCalledNumber = null;
        let callHistory = [];
        const MAX_HISTORY = 5;

        // --- UTILITY FUNCTIONS ---
        function safeGetElementById(id) {
            return document.getElementById(id);
        }

        // --- CORE FUNCTIONS ---
        function updateDateTime() {
            const now = new Date();
            const timeEl = safeGetElementById('current-time');
            const dateEl = safeGetElementById('current-date');
            if (timeEl) timeEl.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            if (dateEl) dateEl.textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            });
        }

        async function fetchQueueData() {
            try {
                const response = await fetch('/api/display-data?t=' + Date.now());
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                updateDisplay(data);
            } catch (error) {
                console.error("Failed to fetch queue data:", error);
            }
        }

        function updateDisplay(data) {
            const currentCall = data.currentCalled && data.currentCalled.length > 0 ? data.currentCalled[0] : null;

            if (currentCall && currentCall.formatted_number !== lastCalledNumber) {
                lastCalledNumber = currentCall.formatted_number;

                // Update main display with animation
                const callingContent = safeGetElementById('calling-content');
                safeGetElementById('current-number').textContent = currentCall.formatted_number;
                safeGetElementById('current-counter').textContent = currentCall.counter_name || 'Loket';
                callingContent.classList.remove('new-call-main');
                void callingContent.offsetWidth; // Trigger reflow
                callingContent.classList.add('new-call-main');

                // Update and render history
                callHistory.unshift({
                    number: currentCall.formatted_number,
                    counter: currentCall.counter_name || 'Loket'
                });
                if (callHistory.length > MAX_HISTORY) {
                    callHistory.pop();
                }
                renderHistory();
            } else if (!currentCall && lastCalledNumber !== null) {
                // Handle case when there are no calls
                lastCalledNumber = null;
                safeGetElementById('current-number').textContent = '---';
                safeGetElementById('current-counter').textContent = '-';
            }
        }

        function renderHistory() {
            const historyListEl = safeGetElementById('history-list');
            if (!historyListEl) return;

            historyListEl.innerHTML = callHistory.map((call, index) => `
                <li class="history-item ${index === 0 ? 'new-call-item' : ''}">
                    <span class="history-number">${call.number}</span>
                    <span class="history-counter">${call.counter}</span>
                </li>
            `).join('');

            // Remove animation class after it finishes
            const newItem = historyListEl.querySelector('.new-call-item');
            if (newItem) {
                setTimeout(() => {
                    newItem.classList.remove('new-call-item');
                }, 2000);
            }
        }

        let currentVideoId = null;
        async function loadVideo() {
            const videoPlayer = safeGetElementById('video-player');
            try {
                const response = await fetch('/api/video');
                if (!response.ok) return;
                const data = await response.json();

                if (!data.success || !data.video) {
                    if (currentVideoId !== null) {
                        videoPlayer.innerHTML =
                            `<div class="video-placeholder"><i class="fas fa-video-slash"></i><div>Tidak ada video aktif</div></div>`;
                    }
                    currentVideoId = null;
                    return;
                }
                if (currentVideoId === data.video.id) return;
                currentVideoId = data.video.id;

                if (data.video.type === 'youtube') {
                    // The URL is already processed by the model to be an embed URL
                    videoPlayer.innerHTML = 
                        `<iframe src="${data.video.url}" 
                                allow="autoplay; encrypted-media" 
                                allowfullscreen 
                                loading="lazy"
                                style="width: 100%; height: 100%; border: none;">
                        </iframe>`;
                } else if (data.video.type === 'file') {
                    videoPlayer.innerHTML = 
                        `<video autoplay loop muted playsinline 
                                style="width: 100%; height: 100%; object-fit: contain;">
                            <source src="${data.video.url}" type="video/mp4">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>`;
                }
            } catch (error) {
                console.error('Error loading video:', error);
                currentVideoId = null;
            }
        }

        async function loadRunningTeks() {
            const contentEl = safeGetElementById('running-text-content');
            try {
                const response = await fetch('/api/running-teks');
                if (!response.ok) return;
                const data = await response.json();
                let texts = "<span>Selamat Datang di Layanan Kami</span>";
                if (data.running_teks && data.running_teks.length > 0) {
                    texts = data.running_teks.map(item => `<span>${item.text}</span>`).join('');
                }
                contentEl.innerHTML = texts + texts;
            } catch (error) {
                console.error("Failed to load running text:", error);
            }
        }

        // --- INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', () => {
            updateDateTime();
            fetchQueueData();
            loadVideo();
            loadRunningTeks();
            renderHistory();

            setInterval(updateDateTime, 1000);
            setInterval(fetchQueueData, 3000);
            setInterval(loadVideo, 5 * 60 * 1000);
            setInterval(loadRunningTeks, 5 * 60 * 1000);
        });
    </script>
</body>

</html>
