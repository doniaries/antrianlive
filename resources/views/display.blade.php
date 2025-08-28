<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - {{ $profil->nama_instansi ?? 'Sistem Antrian' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
            height: 100vh;
            color: white;
        }

        .modern-display {
            display: grid;
            grid-template-columns: 1fr 650px;
            grid-template-rows: 110px 1fr 60px;
            height: 100vh;
            gap: 0;
            background: rgba(0, 0, 0, 0.1);
        }

        .header-bar {
            grid-column: 1 / -1;
            background: rgba(0, 0, 0, 0.2);
            display: grid;
            grid-template-columns: 200px 2fr 300px;
            align-items: flex-start;
            padding: 0.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            min-height: 100px;
        }

        .header-logo {
            grid-column: 1;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0.5rem 0;
            height: 90px;
        }

        .header-logo img {
            max-height: 80px;
            max-width: 180px;
            object-fit: contain;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem;
        }

        .header-content {
            text-align: center;
            grid-column: 2;
        }

        .header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .header-content .instansi {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        .header-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-start;
            grid-column: 3;
            align-self: flex-start;
            padding-top: 0.5rem;
            text-align: right;
        }

        .time-display {
            font-size: 1.8rem;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            line-height: 1.2;
            margin-bottom: 0.2rem;
        }

        .date-display {
            font-size: 1.1rem;
            opacity: 0.8;
            line-height: 1.2;
            margin-top: 0;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 2fr 1fr;
            gap: 1.5rem;
            padding: 1.5rem 1rem 1rem;
            overflow: hidden;
        }

        .queue-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .queue-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffd700;
            margin-bottom: 1rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
            background: linear-gradient(45deg, #ffd700, #ffed4e, #ffd700);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .queue-status {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-align: center;
            opacity: 0.8;
        }

        .services-grid {
            display: flex;
            flex-wrap: nowrap;
            gap: 0.5rem;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 1rem;
            width: 100%;
        }

        .service-item {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            padding: 1.5rem 1rem;
            flex: 1;
            min-width: 0;
            height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .service-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-radius: 1rem;
            z-index: 1;
        }
        
        .service-item > * {
            position: relative;
            z-index: 2;
        }

        .service-item:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        .service-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .current-queue {
            font-size: 2.5rem;
            font-weight: 900;
            color: #ffffff;
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.8);
            line-height: 1;
            margin-bottom: 0.25rem;
            text-align: center;
        }

        .next-queue {
            display: none;
        }
        
        @keyframes pulse-glow {
            from {
                text-shadow: 0 0 20px rgba(255, 255, 255, 0.8), 0 0 40px rgba(255, 255, 255, 0.4);
            }
            to {
                text-shadow: 0 0 30px rgba(255, 255, 255, 1), 0 0 60px rgba(255, 255, 255, 0.6);
            }
        }

        .current-queue {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }

        .next-queue {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .counter-status-section {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .counter-status-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
            opacity: 0.9;
            color: #ffd700;
        }

        .counter-status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.75rem;
            justify-items: center;
            align-items: start;
            max-width: 800px;
            margin: 0 auto;
        }

        .counter-status-item {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 0.75rem;
            text-align: center;
            transition: all 0.3s ease;
            min-height: 80px;
        }

        .counter-status-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .counter-status-status {
            font-size: 0.8rem;
            color: #00ff88;
            font-weight: 600;
            line-height: 1.2;
        }

        .video-section {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 100%;
            min-height: 400px;
            aspect-ratio: 16/9;
        }

        .video-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 1rem;
        }

        .video-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .sidebar {
            background: rgba(0, 0, 0, 0.2);
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            padding: 1rem;
            gap: 1rem;
        }

        .counters-section {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
        }

        .counters-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
            opacity: 0.9;
        }

        .counter-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .counter-card:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .counter-number-display {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.25rem;
        }

        .counter-name-display {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .footer-bar {
            grid-column: 1 / -1;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .marquee-container {
            overflow: hidden;
            width: 100%;
            padding: 0 2rem;
        }

        .marquee-text {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 30s linear infinite;
            font-size: 1rem;
            opacity: 0.9;
        }

        .fullscreen-btn {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            opacity: 0;
            visibility: hidden;
        }

        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        body:hover .fullscreen-btn {
            opacity: 1;
            visibility: visible;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        @keyframes gentle-pulse {
            0% {
                transform: scale(1);
                text-shadow: 0 0 8px rgba(255, 215, 0, 0.4);
            }
            50% {
                transform: scale(1.05);
                text-shadow: 0 0 15px rgba(255, 215, 0, 0.7), 0 0 25px rgba(255, 215, 0, 0.3);
            }
            100% {
                transform: scale(1);
                text-shadow: 0 0 8px rgba(255, 215, 0, 0.4);
            }
        }

        .current-queue {
            animation: gentle-pulse 3s ease-in-out infinite;
        }

        @media (max-width: 1024px) {
            .modern-display {
                grid-template-columns: 1fr;
                grid-template-rows: 80px 1fr 300px 60px;
            }

            .sidebar {
                border-left: none;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .counters-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.5rem;
                align-content: start;
            }

            .counter-card {
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .header-content h1 {
                font-size: 1.3rem;
            }

            .header-content .instansi {
                font-size: 0.8rem;
            }

            .services-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .current-queue {
                font-size: 2rem;
            }

            .service-name {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="modern-display">
        <div class="header-bar">
            <div class="header-logo">
                @if($profil && $profil->logo)
                    <img src="{{ $profil->logo_url }}" alt="Logo {{ $profil->nama_instansi ?? 'Instansi' }}">
                @else
                    <img src="{{ asset('favicon.svg') }}" alt="Logo Default">
                @endif
            </div>
            <div class="header-content">
                <h1>Sistem Antrian</h1>
                <div class="instansi">{{ $profil->nama_instansi ?? 'Nama Instansi' }}</div>
            </div>
            <div class="header-info">
                <div class="time-display" id="currentTime">{{ now()->format('H:i:s') }}</div>
                <div class="date-display" id="currentDate">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="queue-section">
                <div class="queue-title">NOMOR ANTRIAN SEDANG DIPANGGIL</div>
                <div class="services-grid">
                    @foreach ($currentQueues as $serviceCode => $queue)
                        <div class="service-item">
                            <div class="service-name">{{ $queue['service_name'] }}</div>
                            <div class="current-queue" id="current-{{ $serviceCode }}">{{ $queue['number'] }}</div>
                            <div class="next-queue" id="next-{{ $serviceCode }}">Berikutnya: <span
                                    id="next-{{ $serviceCode }}">{{ $nextQueues[$serviceCode] ?? '-' }}</span></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="counter-status-section">
                <div class="counter-status-title">ANTREAN AKAN DIPANGGIL</div>
                <div class="counter-status-grid">
                    @foreach ($counters as $counter)
                        <div class="counter-status-item">
                            <div class="counter-status-name">{{ $counter->name }}</div>
                            <div class="counter-status-status" id="status-{{ $counter->id }}">Menunggu antrean</div>
                            <div style="font-size: 1rem; font-weight: 700; color: #ffd700; margin-top: 0.25rem;"
                                id="upcoming-{{ $counter->id }}">-</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="sidebar">
            <div class="video-section" style="height: 100%; margin-bottom: 1rem;">
                <div class="video-placeholder">
                    <i class="fas fa-play-circle"></i>
                    <div>Video YouTube</div>
                    <div style="font-size: 0.8rem; opacity: 0.6;">Akan ditampilkan di sini</div>
                </div>
            </div>
        </div>

        <div class="footer-bar">
            <div class="marquee-container">
                <div class="marquee-text">
                    Selamat datang di {{ $profil->nama_instansi ?? 'sistem antrian kami' }}.
                    Silakan mengambil nomor antrian dan menunggu dipanggil sesuai layanan yang tersedia.
                    Terima kasih atas kesabaran Anda.
                </div>
            </div>
        </div>

        <button class="fullscreen-btn" onclick="toggleFullscreen()" title="Fullscreen">
            <i class="fas fa-expand"></i>
        </button>
    </div>

    <script>
        // Update time and date
        function updateDateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('currentDate').textContent = dateString;
        }

        // Fetch real-time queue data
        function fetchQueueData() {
            fetch('{{ route('display.data') }}')
                .then(response => response.json())
                .then(data => {
                    let hasNewNumber = false;

                    // Update current serving numbers with enhanced highlight
                Object.keys(data).forEach(serviceCode => {
                    const serviceData = data[serviceCode];
                    const currentElement = document.getElementById(`current-${serviceCode}`);
                    const counterElement = document.getElementById(`counter-${serviceCode}`);
                    const nextElement = document.getElementById(`next-${serviceCode}`);

                    if (currentElement && currentElement.textContent !== serviceData.current) {
                        const oldValue = currentElement.textContent;
                        currentElement.textContent = serviceData.current;
                        
                        // Enhanced highlight effect
                        currentElement.classList.add('new-number');
                        currentElement.style.animation = 'none';
                        setTimeout(() => {
                            currentElement.style.animation = 'pulse-glow 2s ease-in-out infinite alternate';
                            currentElement.classList.remove('new-number');
                        }, 2000);
                        
                        if (oldValue !== '-' && serviceData.current !== '-') {
                            hasNewNumber = true;
                        }
                    }

                    if (counterElement) counterElement.textContent = serviceData.current_counter;
                    if (nextElement) nextElement.textContent = serviceData.next;
                });

                    // Update counter status displays with upcoming queues
                    const counters = @json($counters);
                    counters.forEach(counter => {
                        const statusElement = document.getElementById(`status-${counter.id}`);
                        const upcomingElement = document.getElementById(`upcoming-${counter.id}`);

                        if (statusElement && upcomingElement) {
                            // Find the service assigned to this counter
                            let assignedService = null;
                            let upcomingNumber = '-';

                            Object.keys(data).forEach(serviceCode => {
                                if (data[serviceCode].current_counter === counter.name) {
                                    assignedService = serviceCode;
                                    upcomingNumber = data[serviceCode].next || '-';
                                }
                            });

                            if (assignedService) {
                                statusElement.textContent = 'Akan dipanggil';
                                statusElement.style.color = '#00ff88';
                                upcomingElement.textContent = upcomingNumber !== '-' ? `A-${upcomingNumber}` :
                                    '-';
                            } else {
                                statusElement.textContent = 'Menunggu antrean';
                                statusElement.style.color = '#ffffff';
                                upcomingElement.textContent = '-';
                            }
                        }
                    });

                    // Play notification if there's a new number
                    if (hasNewNumber) {
                        playNotification();
                    }
                })
                .catch(error => console.error('Error fetching queue data:', error));
        }

        // Add sound notification
        function playNotification() {
            const audio = new Audio('/sounds/bell.mp3');
            audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // Fullscreen toggle functionality
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable fullscreen: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        }

        // Add animation class for new numbers
        const style = document.createElement('style');
        style.textContent = `
            .new-number {
            background: rgba(255, 215, 0, 0.3) !important;
            transform: scale(1.08);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6), 0 0 60px rgba(255, 215, 0, 0.3);
            animation: soft-highlight 2.5s ease-in-out;
        }
        
        @keyframes soft-highlight {
            0% {
                transform: scale(1);
                opacity: 0.9;
                text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            }
            50% {
                transform: scale(1.08);
                opacity: 1;
                text-shadow: 0 0 20px rgba(255, 215, 0, 0.8), 0 0 35px rgba(255, 215, 0, 0.4);
            }
            100% {
                transform: scale(1);
                opacity: 1;
                text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            }
        }
        `;
        document.head.appendChild(style);

        // Initialize
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Fetch queue data every 3 seconds
        fetchQueueData();
        setInterval(fetchQueueData, 3000);

        // Listen for Livewire events for real-time updates
        if (typeof Livewire !== 'undefined') {
            Livewire.on('ticket-created', (data) => {
                console.log('New ticket created:', data);
                // Immediately refresh display data
                fetchQueueData();
                // Optional: play notification for new ticket
                setTimeout(playNotification, 500);
            });
        }

        // Listen for Laravel Echo broadcasting events
        if (typeof Echo !== 'undefined') {
            Echo.channel('ticket-updates')
                .listen('.ticket.created', (data) => {
                    console.log('Broadcast event received:', data);
                    fetchQueueData();
                    setTimeout(playNotification, 500);
                });
        }

        // Listen for localStorage events (cross-tab communication)
        window.addEventListener('storage', (event) => {
            if (event.key === 'ticket-created') {
                const data = JSON.parse(event.newValue);
                console.log('Display received ticket via localStorage:', data);
                fetchQueueData();
                setTimeout(playNotification, 500);
                
                // Clean up the storage event
                setTimeout(() => {
                    localStorage.removeItem('ticket-created');
                }, 1000);
            }
        });

        // Fallback: Listen for custom events via document
        document.addEventListener('ticket-created', (event) => {
            console.log('Display received ticket-created event:', event.detail);
            fetchQueueData();
            setTimeout(playNotification, 500);
        });

        // Keyboard shortcuts for testing
        document.addEventListener('keydown', function(e) {
            if (e.key === ' ') {
                e.preventDefault();
                fetchQueueData();
                playNotification();
            }
        });

        // Handle visibility change to pause/resume updates
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Page is visible, resume normal updates
                fetchQueueData();
            }
        });

        // Add smooth transitions for video section
        function loadVideo() {
            const videoSection = document.querySelector('.video-section');
            videoSection.innerHTML = `
                <iframe
                    width="100%"
                    height="100%"
                    src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1&mute=1&loop=1&playlist=dQw4w9WgXcQ"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            `;
        }

        // Optional: Load video after page load
        // setTimeout(loadVideo, 2000);
    </script>
</body>

</html>
