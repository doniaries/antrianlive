<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - {{ $profil->nama_instansi ?? 'Sistem Antrian' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            overflow: hidden;
            height: 100vh;
        }

        .display-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: 1fr 1fr;
            height: 100vh;
            gap: 2px;
            background: rgba(0, 0, 0, 0.1);
        }

        .main-display {
            grid-row: 1 / -1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .main-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .counter-display {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .counter-display:nth-child(3) {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
        }

        .counter-display:nth-child(4) {
            background: linear-gradient(135deg, #45b7d1 0%, #96c93d 100%);
        }

        .counter-display:nth-child(5) {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .counter-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            opacity: 0.2;
        }

        .current-number {
            font-size: 12vw;
            font-weight: 800;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1;
            animation: pulse 2s ease-in-out infinite;
        }

        .counter-label {
            font-size: 2.5vw;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .counter-number {
            font-size: 6vw;
            font-weight: 800;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1;
        }

        .counter-name {
            font-size: 1.8vw;
            font-weight: 500;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .header {
            position: absolute;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 10;
            width: 100%;
            padding: 0 2rem;
        }

        .header h1 {
            font-size: 3vw;
            font-weight: 700;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
        }

        .header .instansi {
            font-size: 2vw;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
        }

        .header .subtitle {
            font-size: 1.5vw;
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .footer {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 10;
        }

        .footer p {
            font-size: 1vw;
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .time-display {
            position: absolute;
            top: 1rem;
            right: 2rem;
            font-size: 1.8vw;
            font-weight: 600;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .date-display {
            position: absolute;
            top: 3.5rem;
            right: 2rem;
            font-size: 1vw;
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .fullscreen-btn {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-size: 1.5vw;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3vw;
            height: 3vw;
            min-width: 40px;
            min-height: 40px;
        }

        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.05);
        }

        .fullscreen-btn:active {
            transform: scale(0.95);
        }

        .next-queue {
            position: absolute;
            bottom: 1rem;
            left: 2rem;
            font-size: 1.2vw;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .new-number {
            animation: newNumber 0.8s ease-out;
        }

        @keyframes newNumber {
            0% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

        .marquee {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.3);
            color: white;
            font-size: 1.2vw;
            padding: 0.5rem;
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee-text {
            display: inline-block;
            animation: marquee 20s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .service-counter {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .service-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .service-name {
            font-size: 1.5vw;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .service-current {
            font-size: 3vw;
            font-weight: 800;
            color: white;
            margin: 0.5rem 0;
        }

        .service-counter-name {
            font-size: 1vw;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
        }

        .service-next {
            font-size: 1vw;
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width: 768px) {
            .display-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto;
                height: auto;
                min-height: 100vh;
            }
            
            .main-display {
                grid-row: 1;
                min-height: 50vh;
            }
            
            .counter-display {
                min-height: 25vh;
            }
            
            .current-number {
                font-size: 20vw;
            }
            
            .counter-label {
                font-size: 5vw;
            }
            
            .counter-number {
                font-size: 12vw;
            }
            
            .counter-name {
                font-size: 3vw;
            }
            
            .header h1 {
                font-size: 5vw;
            }
            
            .header .instansi {
                font-size: 3vw;
            }
            
            .header .subtitle {
                font-size: 2.5vw;
            }
        }
    </style>
</head>
<body>
    <div class="display-container">
        <div class="main-display">
            <div class="header">
                <h1>Sistem Antrian</h1>
                <div class="instansi">{{ $profil->nama_instansi ?? 'Nama Instansi' }}</div>
                <div class="subtitle">Nomor Antrian Sedang Dilayani</div>
            </div>
            
            <div class="service-counter">
                @foreach($currentQueues as $serviceCode => $queue)
                    <div class="service-card">
                        <div class="service-name">{{ $queue['service_name'] }}</div>
                        <div class="service-current" id="current-{{ $serviceCode }}">{{ $queue['number'] }}</div>
                        <div class="service-counter-name" id="counter-{{ $serviceCode }}">{{ $queue['counter'] }}</div>
                        <div class="service-next">Berikutnya: <span id="next-{{ $serviceCode }}">{{ $nextQueues[$serviceCode] ?? '-' }}</span></div>
                    </div>
                @endforeach
            </div>
            
            <div class="time-display" id="currentTime">{{ now()->format('H:i') }}</div>
            <div class="date-display" id="currentDate">{{ now()->locale('id')->isoFormat('dddd, D MMM YYYY') }}</div>
            <button class="fullscreen-btn" id="fullscreenBtn" onclick="toggleFullscreen()" title="Fullscreen">
            <span id="fullscreenIcon">⛶</span>
        </button>
            
            <div class="marquee">
                <span class="marquee-text">Selamat datang di {{ $profil->nama_instansi ?? 'sistem antrian kami' }}. Silakan mengambil nomor antrian dan menunggu dipanggil sesuai layanan yang tersedia. Terima kasih atas kesabaran Anda.</span>
            </div>
        </div>
        
        @foreach($counters as $index => $counter)
            @if($index < 4)
                <div class="counter-display">
                    <div class="counter-number" id="counter{{ $counter->id }}Number">-</div>
                    <div class="counter-name">{{ $counter->name }}</div>
                </div>
            @endif
        @endforeach
    </div>

    <script>
        // Update time and date
        function updateDateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: false 
            });
            const dateString = now.toLocaleDateString('id-ID', { 
                weekday: 'long', 
                day: 'numeric', 
                month: 'short', 
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
                    // Update current serving numbers
                    Object.keys(data).forEach(serviceCode => {
                        const serviceData = data[serviceCode];
                        const currentElement = document.getElementById(`current-${serviceCode}`);
                        const counterElement = document.getElementById(`counter-${serviceCode}`);
                        const nextElement = document.getElementById(`next-${serviceCode}`);
                        
                        if (currentElement && currentElement.textContent !== serviceData.current) {
                            currentElement.classList.add('new-number');
                            setTimeout(() => currentElement.classList.remove('new-number'), 800);
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
                            counterElement.textContent = currentForCounter;
                        }
                    });
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
            if (document.hidden) {
                // Page is hidden, you might want to slow down updates
            } else {
                // Page is visible, resume normal updates
                fetchQueueData();
            }
        });
    </script>
</body>
</html>