<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - {{ $profil->nama_instansi ?? 'Sistem Antrian' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            grid-template-columns: 1fr 400px;
            grid-template-rows: 80px 1fr 60px;
            height: 100vh;
            gap: 0;
            background: rgba(0, 0, 0, 0.1);
        }

        .header-bar {
            grid-column: 1 / -1;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-content h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .header-content .instansi {
            font-size: 1rem;
            opacity: 0.8;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .time-display {
            font-size: 1.5rem;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        .date-display {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 2fr 1fr;
            gap: 1rem;
            padding: 1rem;
            overflow: hidden;
        }

        .queue-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            display: flex;
            flex-direction: column;
        }

        .queue-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            opacity: 0.9;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            flex: 1;
            align-content: start;
        }

        .service-item {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .service-item:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        .service-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .current-queue {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffd700;
            margin: 0.5rem 0;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .counter-info {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 0.5rem;
        }

        .next-queue {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .video-section {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
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
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
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
            backdrop-filter: blur(20px);
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
            top: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
        }

        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        @keyframes pulse-glow {
            0%, 100% { text-shadow: 0 0 10px rgba(255, 215, 0, 0.5); }
            50% { text-shadow: 0 0 20px rgba(255, 215, 0, 0.8), 0 0 30px rgba(255, 215, 0, 0.6); }
        }

        .current-queue {
            animation: pulse-glow 2s ease-in-out infinite;
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
            <div class="header-content">
                <h1>Sistem Antrian</h1>
                <div class="instansi">{{ $profil->nama_instansi ?? 'Nama Instansi' }}</div>
            </div>
            <div class="header-info">
                <div class="date-display" id="currentDate">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</div>
                <div class="time-display" id="currentTime">{{ now()->format('H:i:s') }}</div>
            </div>
        </div>

        <div class="main-content">
            <div class="queue-section">
                <div class="queue-title">Nomor Antrian Sedang Dilayani</div>
                <div class="services-grid">
                    @foreach($currentQueues as $serviceCode => $queue)
                        <div class="service-item">
                            <div class="service-name">{{ $queue['service_name'] }}</div>
                            <div class="current-queue" id="current-{{ $serviceCode }}">{{ $queue['number'] }}</div>
                            <div class="counter-info" id="counter-{{ $serviceCode }}">Loket: {{ $queue['counter'] }}</div>
                            <div class="next-queue">Berikutnya: <span id="next-{{ $serviceCode }}">{{ $nextQueues[$serviceCode] ?? '-' }}</span></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="video-section">
                <div class="video-placeholder">
                    <i class="fas fa-play-circle"></i>
                    <div>Video YouTube</div>
                    <div style="font-size: 0.8rem; opacity: 0.6;">Akan ditampilkan di sini</div>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <div class="counters-section">
                <div class="counters-title">Status Loket</div>
                @foreach($counters as $counter)
                    <div class="counter-card">
                        <div class="counter-number-display" id="counter{{ $counter->id }}Number">-</div>
                        <div class="counter-name-display">{{ $counter->name }}</div>
                    </div>
                @endforeach
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
            fetch('{{ route("display.data") }}')
                .then(response => response.json())
                .then(data => {
                    let hasNewNumber = false;
                    
                    // Update current serving numbers
                    Object.keys(data).forEach(serviceCode => {
                        const serviceData = data[serviceCode];
                        const currentElement = document.getElementById(`current-${serviceCode}`);
                        const counterElement = document.getElementById(`counter-${serviceCode}`);
                        const nextElement = document.getElementById(`next-${serviceCode}`);
                        
                        if (currentElement && currentElement.textContent !== serviceData.current) {
                            currentElement.classList.add('new-number');
                            setTimeout(() => currentElement.classList.remove('new-number'), 800);
                            hasNewNumber = true;
                        }
                        
                        if (currentElement) currentElement.textContent = serviceData.current;
                        if (counterElement) counterElement.textContent = serviceData.current_counter;
                        if (nextElement) nextElement.textContent = serviceData.next;
                    });
                    
                    // Update counter displays
                    const counters = @json($counters);
                    counters.forEach(counter => {
                        const counterElement = document.getElementById(`counter${counter.id}Number`);
                        if (counterElement) {
                            // Find the current serving number for this counter
                            let currentForCounter = '-';
                            Object.keys(data).forEach(serviceCode => {
                                if (data[serviceCode].current_counter === counter.name) {
                                    currentForCounter = data[serviceCode].current;
                                }
                            });
                            if (counterElement.textContent !== currentForCounter) {
                                counterElement.classList.add('new-number');
                                setTimeout(() => counterElement.classList.remove('new-number'), 800);
                            }
                            counterElement.textContent = currentForCounter;
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
                animation: newNumber 0.8s ease-out;
            }
            
            @keyframes newNumber {
                0% { transform: scale(0.8); opacity: 0; }
                50% { transform: scale(1.2); opacity: 1; }
                100% { transform: scale(1); opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        // Initialize
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // Fetch queue data every 3 seconds
        fetchQueueData();
        setInterval(fetchQueueData, 3000);

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