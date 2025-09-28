@extends('layouts.app')

@section('title', 'Sistem Antrian Digital')

@php
    // Ambil pengaturan profil dari database
    $profil = \App\Models\Profil::query()->first();
    $faviconUrl = $profil && $profil->favicon ? asset('storage/' . $profil->favicon) : '/favicon.ico';
    $logoUrl = $profil && $profil->logo ? asset('storage/' . $profil->logo) : null;
    $namaInstansi =
        $profil && $profil->nama_instansi ? $profil->nama_instansi : config('app.name', 'Sistem Antrian Digital');
    $appName = config('app.name', env('APP_NAME', 'Sistem Antrian'));
@endphp

@section('fonts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        :root {
            --bg-dark: #111827;
            --bg-panel: rgba(31, 41, 55, 0.75);
            --border-color: rgba(255, 255, 255, 0.1);
            --primary-accent: #38bdf8;
            --secondary-accent: #f59e0b;
            --text-light: #f9fafb;
            --text-muted: #9ca3af;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            background-image: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            color: var(--text-light);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
    </style>
@endsection

@section('content')

    <style>
        /* --- Header & Footer --- */
        .header,
        .footer {
            background-color: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            flex-shrink: 0;
            width: 100%;
            z-index: 20;
            border-bottom: 1px solid var(--border-color);
        }

        .header {
            height: var(--header-height);
        }

        .footer {
            border-top: 1px solid var(--border-color);
            border-bottom: none;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .header-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--text-light);
        }

        .datetime {
            text-align: right;
        }

        .time {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-accent);
        }

        .date {
            font-size: 1rem;
            color: var(--text-muted);
        }

        /* Running text container for better visibility */
        .running-text-container {
            width: 100%;
            overflow: hidden;
            background-color: #0a1a2f;
            /* Biru gelap */
            border: none;
            border-radius: 0;
            padding: 0;
            margin: 0;
            position: relative;
            left: 0;
            right: 0;
            z-index: 100;
            height: 80px;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.3);
        }

        .running-text-content {
            display: inline-block;
            font-size: 3.2rem;
            /* Diperbesar dari 2.8rem */
            font-weight: 700;
            /* Lebih tebal */
            padding-left: 100%;
            animation: marquee 50s linear infinite;
            white-space: nowrap;
            color: #ffffff;
            text-shadow: none;
            line-height: 90px;
            /* Disesuaikan dengan tinggi footer */
            letter-spacing: 1px;
            /* Sedikit jarak antar huruf */
        }

        .running-text-content span {
            margin: 0 3rem;
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
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 60% 40%;
            grid-template-areas:
                "calling video"
                "history history";
            gap: 1rem;
            padding: 1rem;
            overflow: hidden;
            max-height: calc(100vh - var(--header-height) - 60px);
        }

        /* --- Panel Styling --- */
        .panel {
            background: var(--bg-panel);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .panel-header {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            background-color: rgba(255, 255, 255, 0.05);
            flex-shrink: 0;
        }

        .panel-body {
            padding: 1.5rem;
            flex-grow: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* --- Calling Panel --- */
        .calling-panel {
            grid-area: calling;
            height: 100%;
        }

        .calling-panel .panel-body {
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1rem;
            /* slightly tighter to fit content */
        }

        .label {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        #current-number {
            font-size: clamp(3.5rem, 8vh, 6rem);
            /* Ukuran font dikurangi agar lebih proporsional */
            font-weight: 800;
            line-height: 1;
            margin: 0.75rem 0;
            color: var(--secondary-accent);
            /* Glow mengikuti warna layanan via CSS variable */
            text-shadow: 0 0 30px var(--glow-color, rgba(245, 158, 11, 0.8));
        }

        #current-counter {
            font-size: 2.5rem;
            /* Ukuran font dikurangi agar lebih proporsional */
            font-weight: 700;
            color: var(--text-light);
        }

        /* --- History Panel --- */
        .history-panel {
            grid-area: history;
            font-size: 1.2rem;
        }

        .history-panel .panel-body {
            padding: 0;
        }

        /* History grid per layanan */
        #history-list {
            padding: 1.5rem;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
            max-height: 100%;
        }

        .history-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        @media (max-width: 1280px) {
            .history-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .history-grid {
                grid-template-columns: 1fr;
            }
        }

        .service-card {
            background: rgba(31, 41, 55, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .service-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .service-title {
            font-weight: 700;
        }

        .service-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        .service-badge {
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 999px;
            border: 1px solid currentColor;
        }

        .service-card-body {
            padding: 0.5rem 0.75rem;
        }

        #history-list::-webkit-scrollbar {
            width: 5px;
        }

        #history-list::-webkit-scrollbar-thumb {
            background: var(--primary-accent);
            border-radius: 5px;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0.75rem;
            border-bottom: 1px dashed var(--border-color);
            transition: background-color 0.3s ease;
            font-size: 1.4rem;
            animation: blinkBg 3s ease-in-out infinite;
        }

        @keyframes blinkBg {

            0%,
            100% {
                background-color: transparent;
            }

            50% {
                background-color: rgba(59, 130, 246, 0.15);
            }
        }

        .history-number {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .history-counter {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* --- Video Panel --- */
        .video-panel {
            grid-area: video;
            height: 100%;
            /* Takes the full height of its grid cell */
        }

        .video-panel .panel-body {
            padding: 0;
            overflow: hidden;
            position: relative;
        }

        /* Styling untuk kontrol video */
        .youtube-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .video-controls {
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 10;
        }

        .control-btn {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.1);
        }

        .video-container {
            width: 100%;
            height: 100%;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-container iframe,
        .video-container video {
            width: 100%;
            height: 100%;
            border: none;
            object-fit: cover;
        }

        .file-video-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .file-video-container video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .video-placeholder {
            text-align: center;
            font-size: 1.25rem;
            color: var(--text-muted);
        }

        .video-placeholder i {
            font-size: 3rem;
            display: block;
            margin-bottom: 1rem;
        }

        /* --- Animations --- */
        .new-call-main {
            animation: highlight-main 1s ease;
        }

        @keyframes highlight-main {
            0% {
                transform: scale(0.9);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .new-call-item {
            background-color: rgba(56, 189, 248, 0.3) !important;
            animation: highlight-item 1.5s ease;
        }

        @keyframes highlight-item {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* --- Call Number Emphasis --- */
        .call-highlight {
            animation: pulse-glow 1.5s ease-out 2;
        }

        @keyframes pulse-glow {
            0% {
                transform: scale(1);
                text-shadow: 0 0 10px var(--glow-color, rgba(245, 158, 11, 0.6)), 0 0 0 rgba(0, 0, 0, 0);
                filter: drop-shadow(0 0 0 var(--glow-color, rgba(0, 0, 0, 0)));
            }

            40% {
                transform: scale(1.08);
                text-shadow: 0 0 30px var(--glow-color, rgba(245, 158, 11, 0.95)), 0 0 60px var(--glow-color, rgba(245, 158, 11, 0.7));
                filter: drop-shadow(0 0 10px var(--glow-color, rgba(245, 158, 11, 0.7)));
            }

            100% {
                transform: scale(1);
                text-shadow: 0 0 10px var(--glow-color, rgba(245, 158, 11, 0.6)), 0 0 0 rgba(0, 0, 0, 0);
                filter: drop-shadow(0 0 0 var(--glow-color, rgba(0, 0, 0, 0)));
            }
        }

        /* --- Fullscreen Button --- */
        #fullscreen-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background: var(--bg-panel);
            color: var(--text-light);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        #fullscreen-btn:hover {
            background: var(--primary-accent);
            transform: scale(1.1);
        }

        /* --- Responsive --- */
        @media (max-width: 1024px) {
            .main-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto;
                grid-template-areas:
                    "calling"
                    "video"
                    "history";
                overflow-y: auto;
            }

            .video-panel {
                min-height: 300px;
            }

            .calling-panel,
            .history-panel {
                height: auto;
            }

            .calling-panel {
                min-height: 350px;
            }

            .history-panel {
                min-height: 300px;
            }
        }
    </style>
    </head>

    <body>
        <header class="header">
            <div class="logo">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo"
                        style="height:40px;width:auto;border-radius:6px;object-fit:contain;">
                @else
                    <i class="fas fa-layer-group fa-2x"></i>
                @endif
                <span class="logo-text">{{ $namaInstansi }}</span>
            </div>
            <div class="header-center">{{ $appName }}</div>
            <div class="datetime">
                <div id="current-time" class="time">--:--:--</div>
                <div id="current-date" class="date">--</div>
            </div>
        </header>

        <main class="main-container">
            <div class="panel calling-panel">
                <div class="panel-body">
                    <div id="calling-content">
                        <span class="label">Nomor Antrian</span>
                        <div id="current-number">---</div>
                        <span class="label">Silakan Menuju</span>
                        <div id="current-counter">-</div>
                    </div>
                </div>
            </div>

            <div class="panel video-panel">
                <div class="panel-body">
                    <div id="video-container" class="video-container">
                        <div class="video-placeholder">
                            <i class="fas fa-spinner fa-spin"></i>
                            <div>Memuat video...</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel history-panel">
                <div class="panel-body">
                    <div id="history-list"></div>
                </div>
            </div>
        </main>

        <footer class="footer"
            style="width: 100vw; margin: 0; padding: 0; left: 0; right: 0; position: fixed; bottom: 0; height: 80px; display: block; z-index: 1000;">
            <div class="running-text-container" aria-label="Informasi Berjalan"
                style="display: block; height: 80px; line-height: 80px; visibility: visible; overflow: visible; width: 100%;">
                <div class="running-text-content" id="running-text-content" style="visibility: visible; font-size: 32px;">
                </div>
            </div>
        </footer>

        <button id="fullscreen-btn" title="Toggle Fullscreen">
            <i class="fas fa-expand"></i>
        </button>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            // --- State Management ---
            let lastCalledNumber = null;
            let callHistory = [];
            const MAX_HISTORY = 30; // Jumlah maksimum riwayat yang disimpan (ditingkatkan)
            let currentVideoId = null;
            let lastEventSignature = null; // to detect recalls/updates even when number is the same
            let fetchController = null; // Controller untuk abort fetch request
            let intervals = []; // Array untuk menyimpan interval IDs
            let isPageUnloading = false; // Flag untuk mendeteksi page unload

            // Auto-refresh halaman saat menerima event dari counter-manager
            window.addEventListener('storage', function(e) {
                if (e.key === 'counter_status_changed') {
                    console.log('Counter status changed, stopping intervals and reloading page...');
                    isPageUnloading = true;

                    // Hentikan semua interval sebelum reload
                    intervals.forEach(intervalId => clearInterval(intervalId));

                    // Abort ongoing fetch request
                    if (fetchController) {
                        fetchController.abort();
                    }

                    location.reload(true);
                }
            });

            // Detect page unload to prevent unnecessary requests
            window.addEventListener('beforeunload', function() {
                isPageUnloading = true;
                intervals.forEach(intervalId => clearInterval(intervalId));
                if (fetchController) {
                    fetchController.abort();
                }
            });

            window.addEventListener('pagehide', function() {
                isPageUnloading = true;
                intervals.forEach(intervalId => clearInterval(intervalId));
                if (fetchController) {
                    fetchController.abort();
                }
            });

            // --- UTILITY FUNCTIONS ---
            const safeGetElementById = (id) => document.getElementById(id);

            // --- CORE FUNCTIONS ---
            function extractServiceCode(num) {
                if (!num) return null;
                const match = String(num).match(/^[A-Za-z]+/);
                return match ? match[0].toUpperCase() : null;
            }

            function getServiceColor(code) {
                // Map service codes to consistent colors
                // PU: merah, PS: biru, PA: hijau; fallback: oranye
                const map = {
                    'PU': {
                        color: '#ef4444',
                        glow: 'rgba(239, 68, 68, 0.7)'
                    },
                    'PS': {
                        color: '#3b82f6',
                        glow: 'rgba(59, 130, 246, 0.7)'
                    },
                    'PA': {
                        color: '#22c55e',
                        glow: 'rgba(34, 197, 94, 0.7)'
                    },
                };
                if (code && map[code]) return map[code];
                return {
                    color: '#f59e0b',
                    glow: 'rgba(245, 158, 11, 0.7)'
                };
            }

            function applyServiceColorToCurrent(codeOrNumber) {
                const numberEl = safeGetElementById('current-number');
                const code = extractServiceCode(codeOrNumber) || codeOrNumber;
                const {
                    color,
                    glow
                } = getServiceColor(code);
                if (numberEl) {
                    numberEl.style.color = color;
                    // Set CSS variable for glow color so CSS/animation follows service color
                    numberEl.style.setProperty('--glow-color', glow);
                }
                return {
                    color
                };
            }

            function loadLastCalledFromStorage() {
                try {
                    const num = localStorage.getItem('lastCalledNumber');
                    const counter = localStorage.getItem('lastCalledCounter');
                    if (num) {
                        lastCalledNumber = num;
                        const numberEl = safeGetElementById('current-number');
                        const counterEl = safeGetElementById('current-counter');
                        if (numberEl) numberEl.textContent = num;
                        if (counterEl) counterEl.textContent = counter || 'Loket';
                        // Apply color based on service code from stored number
                        applyServiceColorToCurrent(num);
                    }
                } catch (e) {
                    console.warn('localStorage unavailable');
                }
            }

            function saveLastCalledToStorage(num, counter) {
                try {
                    localStorage.setItem('lastCalledNumber', num);
                    if (counter) localStorage.setItem('lastCalledCounter', counter);
                } catch (e) {}
            }

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

            async function fetchQueueData(retryCount = 0) {
                // Don't make requests if page is unloading
                if (isPageUnloading) {
                    return;
                }

                const maxRetries = 3;
                const retryDelay = 1000; // 1 second

                try {
                    // Abort previous request if still running
                    if (fetchController) {
                        fetchController.abort();
                    }

                    // Create new abort controller
                    fetchController = new AbortController();

                    const response = await fetch('/api/display-data?t=' + Date.now(), {
                        signal: fetchController.signal,
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                'content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        cache: 'no-cache',
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
                    }

                    const data = await response.json();
                    updateDisplay(data);

                    // Reset retry count on success
                    if (retryCount > 0) {
                        console.log('Queue data fetch recovered after', retryCount, 'retries');
                    }

                } catch (error) {
                    // Ignore abort errors (normal when page reloads)
                    if (error.name === 'AbortError') {
                        return;
                    }

                    // Retry on network errors
                    if (retryCount < maxRetries && (
                            error.name === 'TypeError' ||
                            error.message.includes('Failed to fetch') ||
                            error.message.includes('NetworkError')
                        )) {
                        console.warn(
                            `Fetch failed (attempt ${retryCount + 1}/${maxRetries + 1}), retrying in ${retryDelay}ms...`,
                            error.message);
                        setTimeout(() => fetchQueueData(retryCount + 1), retryDelay);
                        return;
                    }

                    // Log other errors
                    console.error("Failed to fetch queue data:", error);
                }
            }

            function buildEventSignature(item) {
                if (!item) return null;
                const candidates = [
                    item.updated_at,
                    item.called_at,
                    item.recalled_at,
                    item.updatedAt,
                    item.timestamp,
                    item.recall_count,
                    item.recalled_times,
                    item.status_updated_at,
                ];
                const sigExtra = candidates.filter(Boolean).join('|');
                return `${item.formatted_number || ''}#${sigExtra}`;
            }

            function updateDisplay(data) {
                const currentCall = data.currentCalled && data.currentCalled.length > 0 ? data.currentCalled[0] : null;

                // Load history from sessionStorage (jika ada setelah reset antrian) atau localStorage
                if (callHistory.length === 0) {
                    try {
                        // Cek apakah ada riwayat panggilan sementara dari reset antrian
                        const tempHistory = sessionStorage.getItem('tempCallHistory');
                        if (tempHistory) {
                            // Gunakan riwayat dari sessionStorage (prioritas)
                            callHistory = JSON.parse(tempHistory);
                            // Hapus data sementara setelah digunakan
                            sessionStorage.removeItem('tempCallHistory');
                            // Simpan kembali ke localStorage untuk persistensi
                            localStorage.setItem('callHistory', tempHistory);
                            renderHistory();
                        } else {
                            // Jika tidak ada data sementara, gunakan localStorage seperti biasa (dibaca dinamis)
                            const savedHistoryNow = localStorage.getItem('callHistory');
                            if (savedHistoryNow) {
                                try {
                                    callHistory = JSON.parse(savedHistoryNow);
                                } catch (e) {
                                    callHistory = [];
                                }
                                renderHistory();
                            }
                        }
                    } catch (e) {
                        console.warn('Error loading call history from localStorage:', e);
                    }
                }

                if (currentCall) {
                    const newSignature = buildEventSignature(currentCall);
                    const isRecallFlag = !!(currentCall.is_recall || currentCall.recall || currentCall.recalled || (currentCall
                        .recall_count && currentCall.recall_count > 0));
                    const isNewNumber = currentCall.formatted_number !== lastCalledNumber;
                    const isRecallBySignature = (!!newSignature && newSignature !== lastEventSignature && !isNewNumber);
                    const shouldProcess = isNewNumber || isRecallFlag || isRecallBySignature;

                    if (shouldProcess) {
                        // Hanya update lastCalledNumber jika nomor baru
                        if (isNewNumber) lastCalledNumber = currentCall.formatted_number;
                        if (newSignature) lastEventSignature = newSignature;

                        const callingContent = safeGetElementById('calling-content');
                        const numberEl = safeGetElementById('current-number');
                        numberEl.textContent = currentCall.formatted_number;
                        safeGetElementById('current-counter').textContent = currentCall.counter_name || 'Loket';

                        // Determine service code and apply color to the big number
                        const code = (currentCall.service_code || extractServiceCode(currentCall.formatted_number));
                        const {
                            color
                        } = applyServiceColorToCurrent(code);

                        callingContent.classList.remove('new-call-main');
                        void callingContent.offsetWidth; // Trigger reflow
                        callingContent.classList.add('new-call-main');

                        // Emphasize the current number with a pulse/glow effect
                        numberEl.classList.remove('call-highlight');
                        void numberEl.offsetWidth; // Trigger reflow for restarting animation
                        numberEl.classList.add('call-highlight');

                        // Persist to storage so it survives refresh
                        saveLastCalledToStorage(currentCall.formatted_number, currentCall.counter_name || 'Loket');
                        lastCalledNumber = currentCall.formatted_number;

                        // Tambahkan ke riwayat dengan flag recall yang tepat
                        callHistory.unshift({
                            number: currentCall.formatted_number,
                            counter: currentCall.counter_name || 'Loket',
                            serviceCode: code,
                            serviceName: currentCall.service_name || (currentCall.service && currentCall.service
                                .name) || code || 'Layanan',
                            color,
                            recall: isRecallFlag || isRecallBySignature // Tandai sebagai recall jika memang recall
                        });
                        if (callHistory.length > MAX_HISTORY) callHistory.pop();

                        // Save history to localStorage
                        try {
                            localStorage.setItem('callHistory', JSON.stringify(callHistory));
                        } catch (e) {
                            console.warn('Error saving call history to localStorage:', e);
                        }

                        renderHistory();
                    }
                } else {
                    // Do not clear the display; keep showing the last called number (from memory/storage)
                }
            }

            function renderHistory() {
                const historyListEl = safeGetElementById('history-list');
                if (!historyListEl) return;

                // Group by serviceCode
                const groups = {};
                for (const item of callHistory) {
                    const code = item.serviceCode || extractServiceCode(item.number) || 'OTHER';
                    if (!groups[code]) groups[code] = {
                        items: [],
                        name: item.serviceName || code
                    };
                    groups[code].items.push(item);
                }

                // Build cards
                const cardsHtml = Object.entries(groups).map(([code, group]) => {
                    const {
                        color
                    } = getServiceColor(code);
                    const badgeStyle = `color:${color}`;
                    const recallCount = group.items.filter(it => it.recall).length;
                    const itemsHtml = group.items.slice(0, 4).map((call, idx) => {
                        const itemColor = (call.color) ? call.color : getServiceColor(code).color;
                        return `
                        <div class="history-item ${idx === 0 ? 'new-call-item' : ''}">
                            <span class="history-number" style="color:${itemColor}">${call.number}</span>
                            <span class="history-counter">${call.counter}${call.recall ? ' • <span style="color:#f59e0b;font-weight:600;">Panggilan Ulang</span>' : ''}</span>
                            <span class="history-service-visible" style="display:none;">${code}</span>
                        </div>`;
                    }).join('');

                    return `
                    <div class="service-card">
                        <div class="service-card-header">
                            <div>
                                <div class="service-title">${group.name}</div>
                                ${recallCount > 0 ? `<div class=\"service-subtitle\" style=\"color:#f59e0b\">Panggilan ulang: ${recallCount}</div>` : ''}
                            </div>
                            <div class="service-badge" style="${badgeStyle}">${code}</div>
                        </div>
                        <div class="service-card-body">
                            ${itemsHtml || '<div class="history-item"><span class="history-counter">Belum ada panggilan</span></div>'}
                        </div>
                    </div>`;
                }).join('');

                historyListEl.innerHTML = `<div class="history-grid">${cardsHtml}</div>`;

                // Remove highlight class after animation time
                const newItems = historyListEl.querySelectorAll('.new-call-item');
                if (newItems && newItems.length) {
                    setTimeout(() => newItems.forEach(el => el.classList.remove('new-call-item')), 2000);
                }
            }

            async function loadVideo() {
                const videoContainer = safeGetElementById('video-container');
                if (!videoContainer) return;
                try {
                    const response = await fetch('/api/video');
                    if (!response.ok) throw new Error('API request failed');
                    const data = await response.json();

                    if (!data?.success || !data?.video?.url) {
                        videoContainer.innerHTML = `
                            <div class="video-placeholder" style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem;">
                                <i class="fas fa-video-slash" style="font-size: 4rem; margin-bottom: 1rem; color: rgba(255, 255, 255, 0.2);"></i>
                                <div style="font-size: 1.5rem; font-weight: 500; margin-bottom: 0.5rem; color: rgba(255, 255, 255, 0.7);">Video Belum Tersedia</div>
                                <div style="font-size: 1rem; color: rgba(255, 255, 255, 0.5); text-align: center;">Silakan tambahkan video melalui halaman admin untuk menampilkan konten di sini</div>
                            </div>
                        `;
                        currentVideoId = null;
                        return;
                    }
                    if (currentVideoId === data.video.id) return;
                    currentVideoId = data.video.id;

                    if (data.video.type === 'youtube') {
                        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
                        const match = data.video.url.match(regExp);
                        const videoId = (match && match[2].length === 11) ? match[2] : null;
                        if (videoId) {
                            // Tambahkan controls=1 dan enablejsapi=1 untuk kontrol volume dan API
                            const embedUrl =
                                `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=0&loop=1&playlist=${videoId}&controls=1&rel=0&enablejsapi=1`;

                            // Tambahkan div container untuk kontrol volume tambahan
                            videoContainer.innerHTML = `
                            <div class="youtube-container">
                                <iframe id="youtube-player-${videoId}"
                                    src="${embedUrl}"
                                    title="YouTube video"
                                    frameborder="0"
                                    allow="autoplay; encrypted-media; fullscreen; picture-in-picture"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    loading="lazy"
                                    allowfullscreen></iframe>
                                <div class="video-controls">
                                    <button id="toggle-mute" class="control-btn">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                </div>
                            </div>`;

                            // Tambahkan event listener untuk tombol mute/unmute setelah DOM dirender
                            setTimeout(() => {
                                const toggleBtn = document.getElementById('toggle-mute');
                                const iframe = document.getElementById('youtube-player-' + videoId);
                                const icon = toggleBtn?.querySelector('i');

                                if (toggleBtn && iframe && icon) {
                                    toggleBtn.addEventListener('click', function() {
                                        // Kirim pesan ke iframe YouTube untuk toggle mute
                                        const isMuted = icon.classList.contains('fa-volume-mute');

                                        if (iframe.contentWindow) {
                                            if (isMuted) {
                                                iframe.contentWindow.postMessage(
                                                    '{"event":"command","func":"unMute","args":""}', '*'
                                                );
                                                icon.classList.remove('fa-volume-mute');
                                                icon.classList.add('fa-volume-up');
                                            } else {
                                                iframe.contentWindow.postMessage(
                                                    '{"event":"command","func":"mute","args":""}', '*');
                                                icon.classList.remove('fa-volume-up');
                                                icon.classList.add('fa-volume-mute');
                                            }
                                        }
                                    });
                                }
                            }, 1000);
                        } else {
                            videoContainer.innerHTML =
                                '<div class="video-placeholder"><i class="fas fa-exclamation-triangle"></i><div>URL YouTube tidak valid</div></div>';
                        }
                    } else if (data.video.type === 'file') {
                        videoContainer.innerHTML =
                            '<div class="file-video-container"><video autoplay loop muted playsinline preload="auto" controls src="' +
                            data.video.url + '"></video></div>';
                    }
                } catch (error) {
                    console.error('Error loading video:', error);
                    videoContainer.innerHTML =
                        '<div class="video-placeholder" style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%; padding: 2rem;">' +
                        '<i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #ef4444; margin-bottom: 1rem;"></i>' +
                        '<div style="font-size: 1.25rem; margin-bottom: 0.5rem; font-weight: 500;">Gagal Memuat Video</div>' +
                        '<div style="text-align: center; color: rgba(255, 255, 255, 0.7);">Terjadi kesalahan saat memuat video. Silakan coba lagi nanti.</div>' +
                        '</div>';
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
                    if (data.running_teks?.length > 0) {
                        texts = data.running_teks.map(item => `<span>${item.text}</span>`).join('');
                    }
                    contentEl.innerHTML = texts + texts;
                } catch (error) {
                    console.error("Failed to load running text:", error);
                }
            }

            function setupFullscreen() {
                const btn = safeGetElementById('fullscreen-btn');
                const icon = btn.querySelector('i');

                function updateIcon() {
                    icon.className = document.fullscreenElement ? 'fas fa-compress' : 'fas fa-expand';
                }
                btn.addEventListener('click', () => {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                    } else {
                        document.exitFullscreen();
                    }
                });
                document.addEventListener('fullscreenchange', updateIcon);
            }

            // Fungsi untuk menampilkan notifikasi
            function showNotification(message, type = 'info') {
                // Buat elemen notifikasi
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                    type === 'success' ? 'bg-green-500' :
                    type === 'error' ? 'bg-red-500' :
                    type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
                } text-white`;
                notification.innerHTML = message;

                // Tambahkan ke body
                document.body.appendChild(notification);

                // Hilangkan setelah 3 detik
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 500);
                }, 3000);
            }



            // --- INITIALIZATION ---
            document.addEventListener('DOMContentLoaded', () => {
                // Restore last called number/counter before any API calls
                loadLastCalledFromStorage();
                updateDateTime();
                fetchQueueData();
                loadVideo();
                loadRunningTeks();
                renderHistory();
                setupFullscreen();

                // Setup intervals dan simpan ID-nya untuk cleanup
                intervals.push(setInterval(updateDateTime, 1000));
                intervals.push(setInterval(fetchQueueData, 3000));
                intervals.push(setInterval(loadVideo, 5 * 60 * 1000));
                intervals.push(setInterval(loadRunningTeks, 5 * 60 * 1000));

                // Setup Livewire event listeners (v3 compatible)
                document.addEventListener('livewire:init', () => {
                    Livewire.on('antrian-reset', () => {
                        console.log('Antrian telah direset, menghapus riwayat panggilan...');

                        // Hapus semua data riwayat dari localStorage dan sessionStorage
                        localStorage.clear(); // Hapus semua data localStorage
                        sessionStorage.clear(); // Hapus semua data sessionStorage

                        // Reset array callHistory
                        callHistory = [];

                        // Kosongkan tampilan riwayat
                        const historyListEl = safeGetElementById('history-list');
                        if (historyListEl) {
                            historyListEl.innerHTML = '<div class="history-grid"></div>';
                        }

                        // Reset calling content animation
                        if (callingContent) {
                            callingContent.classList.remove('new-call-main');
                        }

                        // Tambahkan pemanggilan renderHistory untuk memastikan tampilan benar-benar kosong
                        renderHistory();

                        console.log('Riwayat berhasil dibersihkan sepenuhnya!');
                        // Trigger localStorage event untuk memastikan sinkronisasi
                        localStorage.setItem('counter_status_changed', Date.now());
                    });

                    // Event listener untuk panggilan antrian
                    Livewire.on('antrian-called', function(data) {
                        console.log('Antrian dipanggil:', data);
                        // Refresh data antrian jika diperlukan
                        if (typeof loadAntrian === 'function') {
                            loadAntrian();
                        }
                    });

                    // Event listener untuk panggilan queue
                    Livewire.on('queue-called', function(data) {
                        console.log('Queue dipanggil:', data);
                        // Refresh data antrian jika diperlukan
                        if (typeof loadAntrian === 'function') {
                            loadAntrian();
                        }
                    });

                    // Event listener untuk membersihkan riwayat panggilan
                    Livewire.on('clear-call-history-event', (data) => {
                        console.log('Membersihkan riwayat panggilan dengan Livewire event...', data);

                        // Hapus SEMUA data dari localStorage dan sessionStorage
                        const keysToRemove = [
                            'callHistory',
                            'lastCalledNumber',
                            'lastCalledCounter',
                            'tempCallHistory',
                            'counter_status_changed'
                        ];
                        // Pastikan kunci dihapus dari kedua storage agar tidak re-populate lagi
                        keysToRemove.forEach(key => {
                            try {
                                localStorage.removeItem(key);
                            } catch (e) {
                                /* noop */
                            }
                            try {
                                sessionStorage.removeItem(key);
                            } catch (e) {
                                /* noop */
                            }
                        });

                        // Reset semua variabel state
                        callHistory = [];
                        lastCalledNumber = null;
                        lastEventSignature = null;

                        // Reset tampilan nomor yang sedang dipanggil
                        const currentNumberEl = safeGetElementById('current-number');
                        if (currentNumberEl) {
                            currentNumberEl.textContent = '---';
                            currentNumberEl.style.color = '';
                            currentNumberEl.style.removeProperty('--glow-color');
                            currentNumberEl.classList.remove('call-highlight');
                        }

                        const currentCounterEl = safeGetElementById('current-counter');
                        if (currentCounterEl) {
                            currentCounterEl.textContent = '-';
                        }

                        // Reset tampilan nomor terakhir dipanggil (jika ada elemen terpisah)
                        const lastCalledEl = safeGetElementById('last-called-number');
                        if (lastCalledEl) {
                            lastCalledEl.textContent = '-';
                        }

                        const lastCalledCounterEl = safeGetElementById('last-called-counter');
                        if (lastCalledCounterEl) {
                            lastCalledCounterEl.textContent = '-';
                        }

                        // Kosongkan tampilan riwayat
                        const historyListEl = safeGetElementById('history-list');
                        if (historyListEl) {
                            historyListEl.innerHTML = '<div class="history-grid"></div>';
                        }

                        // Reset calling content animation
                        const callingContent = safeGetElementById('calling-content');
                        if (callingContent) {
                            callingContent.classList.remove('new-call-main');
                        }

                        // Tambahkan pemanggilan renderHistory untuk memastikan tampilan benar-benar kosong
                        renderHistory();

                        console.log('Riwayat berhasil dibersihkan sepenuhnya!');

                        // Trigger localStorage event untuk memastikan sinkronisasi
                        try {
                            localStorage.setItem('counter_status_changed', Date.now());
                        } catch (e) {
                            /* noop */
                        }
                    });
                });
            });
        </script>
    @endsection

    @section('body_class', 'font-sans antialiased')
    @section('container_class', '')
