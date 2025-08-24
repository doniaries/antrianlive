<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian</title>
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
            font-size: 15vw;
            font-weight: 800;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1;
            animation: pulse 2s ease-in-out infinite;
        }

        .counter-label {
            font-size: 3vw;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .counter-number {
            font-size: 8vw;
            font-weight: 800;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1;
        }

        .counter-name {
            font-size: 2vw;
            font-weight: 500;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .header {
            position: absolute;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 10;
        }

        .header h1 {
            font-size: 4vw;
            font-weight: 700;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
        }

        .header p {
            font-size: 1.5vw;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .footer {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 10;
        }

        .footer p {
            font-size: 1.2vw;
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .time-display {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-size: 2vw;
            font-weight: 600;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .date-display {
            position: absolute;
            top: 4.5rem;
            right: 2rem;
            font-size: 1.2vw;
            color: rgba(255, 255, 255, 0.8);
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
            bottom: 1rem;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.3);
            color: white;
            font-size: 1.5vw;
            padding: 1rem;
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

        @media (max-width: 768px) {
            .display-container {
                grid-template-columns: 1fr;
                grid-template-rows: 1fr 1fr 1fr;
            }
            
            .main-display {
                grid-row: 1;
            }
            
            .current-number {
                font-size: 25vw;
            }
            
            .counter-label {
                font-size: 6vw;
            }
            
            .counter-number {
                font-size: 15vw;
            }
            
            .counter-name {
                font-size: 4vw;
            }
        }
    </style>
</head>
<body>
    <div class="display-container">
        <div class="main-display">
            <div class="header">
                <h1>Sistem Antrian</h1>
                <p>Nomor Antrian Saat Ini</p>
            </div>
            
            <div class="current-number" id="currentNumber">A001</div>
            <div class="counter-label">Loket 1</div>
            
            <div class="time-display" id="currentTime">14:30</div>
            <div class="date-display" id="currentDate">Senin, 25 Des 2024</div>
            
            <div class="marquee">
                <span class="marquee-text">Selamat datang di sistem antrian modern kami. Silakan mengambil nomor antrian dan menunggu dipanggil. Terima kasih atas kesabaran Anda.</span>
            </div>
        </div>
        
        <div class="counter-display">
            <div class="counter-number" id="counter1Number">A002</div>
            <div class="counter-name">Loket 1</div>
        </div>
        
        <div class="counter-display">
            <div class="counter-number" id="counter2Number">A003</div>
            <div class="counter-name">Loket 2</div>
        </div>
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

        // Simulate queue updates
        function updateQueue() {
            const services = ['A', 'B', 'C'];
            const numbers = Array.from({length: 999}, (_, i) => String(i + 1).padStart(3, '0'));
            
            // Generate new numbers
            const newMain = services[Math.floor(Math.random() * services.length)] + numbers[Math.floor(Math.random() * 999)];
            const newCounter1 = services[Math.floor(Math.random() * services.length)] + numbers[Math.floor(Math.random() * 999)];
            const newCounter2 = services[Math.floor(Math.random() * services.length)] + numbers[Math.floor(Math.random() * 999)];
            
            // Update display with animation
            const mainDisplay = document.getElementById('currentNumber');
            const counter1Display = document.getElementById('counter1Number');
            const counter2Display = document.getElementById('counter2Number');
            
            mainDisplay.classList.add('new-number');
            counter1Display.classList.add('new-number');
            counter2Display.classList.add('new-number');
            
            mainDisplay.textContent = newMain;
            counter1Display.textContent = newCounter1;
            counter2Display.textContent = newCounter2;
            
            setTimeout(() => {
                mainDisplay.classList.remove('new-number');
                counter1Display.classList.remove('new-number');
                counter2Display.classList.remove('new-number');
            }, 800);
        }

        // Initialize
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // Update queue every 30 seconds for demo
        setInterval(updateQueue, 30000);

        // Add sound notification
        function playNotification() {
            const audio = new Audio('/sounds/bell.mp3');
            audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // WebSocket connection for real-time updates (placeholder)
        // This would connect to your backend for real-time queue updates
        function connectWebSocket() {
            // Example WebSocket connection
            // const ws = new WebSocket('ws://localhost:8080');
            // ws.onmessage = function(event) {
            //     const data = JSON.parse(event.data);
            //     updateDisplay(data);
            //     playNotification();
            // };
        }

        // Call WebSocket connection
        // connectWebSocket();

        // Keyboard shortcuts for testing
        document.addEventListener('keydown', function(e) {
            if (e.key === ' ') {
                e.preventDefault();
                updateQueue();
                playNotification();
            }
        });
    </script>
</body>
</html>