<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Digital</title>
    @php
        $faviconUrl = $profil && $profil->favicon ? asset('storage/' . $profil->favicon) : '/favicon.ico';
        use App\Models\Profil;
        $profil = Profil::first();
        $logoUrl = $profil && $profil->logo ? asset('storage/' . $profil->logo) : null;
        $namaInstansi = $profil && $profil->nama_instansi ? $profil->nama_instansi : 'Sistem Antrian Digital';
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            height: 60px !important;
            flex-shrink: 0;
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            width: 100%;
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
            height: calc(100vh - 60px);
            padding: 1rem;
            padding-top: 70px;
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
            grid-row: 1 / 3;
        }

        .video-card {
            grid-column: 2 / 3;
            grid-row: 1 / 2;
        }


        .services-card {
            grid-column: 2;
            grid-row: 2 / 3;
            min-height: 300px;
            display: flex;
            flex-direction: column;
        }

        .running-teks-card {
            grid-column: 1 / 3;
            grid-row: 3 / 4;
            min-height: 80px;
            display: flex;
            align-items: center;
            overflow: hidden;
            position: relative;
            margin-top: 0.5rem;
            background-color: #1e293b;
            border-radius: 12px;
            padding: 1.5rem 1rem;
            border: 1px solid #334155;
        }

        .running-teks-container {
            position: absolute;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 2rem;
            font-size: 1.25rem;
            font-weight: 600;
            width: 100%;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .running-teks-fade {
            animation: fadeInOut 3s ease-in-out infinite;
        }

        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
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
            height: 100%;
            position: relative;
        }

        .video-placeholder {
            text-align: center;
            color: #64748b;
        }

        .video-player {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-player iframe,
        .video-player video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            border-radius: 8px;
        }

        .video-placeholder i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            display: block;
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            flex: 1;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .service-item {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 16px;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #475569;
            position: relative;
            overflow: hidden;
            min-height: 220px;
            justify-content: center;
        }

        /* Warna berbeda untuk tiap layanan */
        .service-item[data-service-id="1"] {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border-color: #3b82f6;
        }

        .service-item[data-service-id="2"] {
            background: linear-gradient(135deg, #166534 0%, #22c55e 100%);
            border-color: #22c55e;
        }

        .service-item[data-service-id="3"] {
            background: linear-gradient(135deg, #9a3412 0%, #f97316 100%);
            border-color: #f97316;
        }

        .service-item[data-service-id="4"] {
            background: linear-gradient(135deg, #86198f 0%, #a855f7 100%);
            border-color: #a855f7;
        }

        .service-item[data-service-id="5"] {
            background: linear-gradient(135deg, #a16207 0%, #eab308 100%);
            border-color: #eab308;
        }

        .service-item[data-service-id="6"] {
            background: linear-gradient(135deg, #be185d 0%, #ec4899 100%);
            border-color: #ec4899;
        }

        .service-item[data-service-id="7"] {
            background: linear-gradient(135deg, #0e7490 0%, #06b6d4 100%);
            border-color: #06b6d4;
        }

        .service-item[data-service-id="8"] {
            background: linear-gradient(135deg, #7c2d12 0%, #d97706 100%);
            border-color: #d97706;
        }

        .service-item[data-service-id="9"] {
            background: linear-gradient(135deg, #4c1d95 0%, #8b5cf6 100%);
            border-color: #8b5cf6;
        }

        .service-item[data-service-id="10"] {
            background: linear-gradient(135deg, #881337 0%, #f43f5e 100%);
            border-color: #f43f5e;
            color: white;
        }

        /* Enhanced Fullscreen Button Styles */
        #fullscreen-btn {
            position: fixed !important;
            bottom: 20px !important;
            right: 20px !important;
            width: 60px !important;
            height: 60px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(139, 92, 246, 0.25)) !important;
            color: white !important;
            border: 2px solid rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15) !important;
            cursor: pointer !important;
            z-index: 9999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(2px);
        }

        #fullscreen-btn:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 6px 25px rgba(59, 130, 246, 0.6) !important;
        }

        #fullscreen-btn:active {
            transform: scale(0.95) !important;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .service-item[data-service-id="1"] {
            color: white;
        }

        .service-item[data-service-id="2"] {
            color: white;
        }

        .service-item[data-service-id="3"] {
            color: white;
        }

        .service-item[data-service-id="4"] {
            color: white;
        }

        .service-item[data-service-id="5"] {
            color: white;
        }

        .service-item[data-service-id="6"] {
            color: white;
        }

        .service-item[data-service-id="7"] {
            color: white;
        }

        .service-item[data-service-id="8"] {
            color: white;
        }

        .service-item[data-service-id="9"] {
            color: white;
        }

        .service-item .service-current {
            font-size: 3.5rem !important;
            font-weight: bold !important;
            letter-spacing: 2px !important;
            margin: 0.3rem 0 !important;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .service-item .service-next {
            font-size: 2.8rem !important;
            font-weight: bold !important;
            letter-spacing: 1px !important;
            margin: 0.3rem 0 !important;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
        }

        // ... existing code ...
        .service-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .service-item.active {
            border-color: #3b82f6;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
            }

            50% {
                opacity: 0.7;
                box-shadow: 0 0 25px rgba(59, 130, 246, 0.6);
            }
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
            font-size: 1rem;
            font-weight: 700;
            color: white !important;
            text-align: center;
            line-height: 1.2;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .service-info {
            text-align: center;
            width: 100%;
        }

        .service-current {
            font-size: 3rem;
            font-weight: 800;
            color: #ffffff !important;
            margin: 0.75rem 0;
            line-height: 1;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
            background: rgba(255, 255, 255, 0.25);
            padding: 0.75rem;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        .service-next {
            font-size: 2.8rem;
            font-weight: 700;
            color: #f0f9ff !important;
            margin-bottom: 0.75rem;
            line-height: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.15);
            padding: 0.75rem;
            border-radius: 8px;
        }

        .service-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9) !important;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .service-counter {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.95) !important;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .service-range {
            font-size: 0.8rem;
            color: white !important;
            font-family: 'Courier New', monospace;
            padding: 0.25rem 0.5rem;
            background: rgba(255, 255, 255, 0.25) !important;
            border-radius: 4px;
            display: inline-block;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Running Text Fade Animation */
        .running-text-fade {
            background: rgba(59, 130, 246, 0.1);
            border-top: 1px solid rgba(59, 130, 246, 0.2);
            backdrop-filter: blur(10px);
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .running-text-content {
            animation: fadeInOut 8s ease-in-out infinite;
            color: #374151;
            font-weight: 500;
            font-size: 0.875rem;
        }

        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 0;
                transform: translateY(5px);
            }

            10%,
            90% {
                opacity: 1;
                transform: translateY(0);
            }
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

        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
                padding: 0.75rem;
                height: calc(100vh - 60px);
                padding-top: 70px;
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
                height: calc(100vh - 60px);
                padding-top: 70px;
            }

            .calling-card,
            .video-card,
            .services-card {
                grid-column: 1 / 2;
                grid-row: auto;
            }

            .header {
                padding: 0.25rem 0.5rem !important;
                height: 50px !important;
                position: fixed !important;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                width: 100%;
            }

            .logo-text {
                font-size: 0.875rem !important;
            }

            .time {
                font-size: 1rem !important;
            }

            .date {
                font-size: 0.7rem !important;
            }

            .logo-icon {
                width: 25px !important;
                height: 25px !important;
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
                grid-template-columns: 1fr !important;
                grid-template-rows: auto auto auto auto !important;
                height: calc(100vh - 50px) !important;
                padding-top: 60px;
            }

            .calling-card,
            .video-card,
            .services-card {
                grid-column: 1 / 2 !important;
                min-height: 150px !important;
                max-height: none !important;
            }

            .calling-card {
                grid-row: 1 / 2 !important;
            }

            .video-card {
                grid-row: 2 / 3 !important;
            }

            .services-card {
                grid-row: 3 / 4 !important;
                max-height: 180px !important;
            }

            #running-text {
                grid-column: 1 / 2 !important;
                grid-row: 4 / 5 !important;
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


        .services-grid::-webkit-scrollbar-track,


        .services-grid::-webkit-scrollbar-thumb,


        .services-grid::-webkit-scrollbar-thumb:hover,


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

        /* Layout untuk mode 1920x1080 */
        @media screen and (min-width: 1920px) {
            .main-container {
                grid-template-columns: 1fr 1fr !important;
                grid-template-rows: 1.5fr 1fr auto !important;
                gap: 1rem !important;
                height: calc(100vh - 60px) !important;
                padding: 0.75rem !important;
                padding-top: 70px !important;
            }

            .calling-card {
                grid-column: 1 / 2 !important;
                grid-row: 1 / 2 !important;
                height: 100% !important;
                min-height: 300px !important;
            }

            .video-card {
                grid-column: 2 / 3 !important;
                grid-row: 1 / 2 !important;
                height: 100% !important;
                min-height: 300px !important;
            }

            .services-card {
                grid-column: 1 / 3 !important;
                grid-row: 2 / 3 !important;
                height: 100% !important;
                max-height: 200px !important;
                min-height: 150px !important;
            }
        }

        /* Layout untuk fullscreen - header tetap terlihat */
        :fullscreen .main-container,
        :-webkit-full-screen .main-container,
        :-moz-full-screen .main-container {
            grid-template-columns: 1fr 1fr !important;
            grid-template-rows: 1.5fr 1fr auto !important;
            height: calc(100vh - 60px) !important;
            gap: 0.75rem !important;
            padding: 0.5rem !important;
            padding-top: 70px !important;
        }

        /* Running teks dengan tabel */
        .running-text-table {
            display: flex;
            align-items: center;
            gap: 2rem;
            white-space: nowrap;
            animation: scroll-left 30s linear infinite;
            padding: 10px 0;
        }

        .running-text-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .running-text-item i {
            color: #ffd700;
            font-size: 1.2rem;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* Enhanced running text for 1920x1080 */
        @media screen and (min-width: 1920px) {
            .running-text {
                height: 120px !important;
                padding: 15px 0 !important;
            }

            .running-text-table {
                gap: 3rem;
                padding: 10px 0;
            }

            .running-text-item {
                font-size: 1.2rem;
                padding: 12px 20px;
                background: rgba(59, 130, 246, 0.15);
                border: 1px solid rgba(59, 130, 246, 0.4);
            }

            .running-text-item i {
                color: #ffd700;
                margin-right: 10px;
            }
        }

        /* Fullscreen optimizations */
        :fullscreen .running-text,
        :-webkit-full-screen .running-text,
        :-moz-full-screen .running-text {
            height: 100px !important;
        }

        :fullscreen .running-text-table,
        :-webkit-full-screen .running-text-table,
        :-moz-full-screen .running-text-table {
            gap: 2.5rem;
        }

        :fullscreen .running-text-item,
        :-webkit-full-screen .running-text-item,
        :-moz-full-screen .running-text-item {
            font-size: 1.1rem;
            padding: 10px 18px;
        }

        /* Running Text Fade Animation Styles */
        .running-text-fade {
            transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: opacity, transform;
        }

        .running-text-fade.fade-out {
            opacity: 0;
            transform: translateY(-10px);
        }

        .running-text-fade.fade-in {
            opacity: 1;
            transform: translateY(0);
        }

        .running-text-content {
            transition: opacity 1.2s ease-in-out, transform 1.2s ease-in-out;
            will-change: opacity, transform;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo Instansi"
                    style="height: 40px; width: auto; border-radius: 8px; background: none;">
            @else
                <div class="logo-icon">
                    <i class="fas fa-list-ol text-white text-xl"></i>
                </div>
            @endif
            <div class="logo-text">{{ $namaInstansi }}</div>
        </div>

        <div class="text-center">
            <div class="text-xl font-bold text-white">{{ env('APP_NAME', 'Sistem Antrian Digital') }}</div>
        </div>

        <div class="datetime">
            <div id="current-time" class="time">--:--:--</div>
            <div id="current-date" class="date">--</div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container"
        style="grid-template-columns: 1fr 1fr; grid-template-rows: 1.2fr 0.8fr auto; gap: 0.75rem; height: calc(100vh - 70px); padding: 0.5rem; overflow: hidden; min-height: 0;">
        <!-- Sedang Dipanggil Card -->
        <div class="card calling-card" style="grid-column: 1 / 2; grid-row: 1 / 2; height: 100%; min-height: 200px;">
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
        </div>

        <!-- Video Informasi Card -->
        <div class="card video-card" style="grid-column: 2 / 3; grid-row: 1 / 2; height: 100%; min-height: 200px;">
            <h2 class="card-title">
                <div class="card-icon" style="background-color: #ef4444;">
                    <i class="fab fa-youtube text-white"></i>
                </div>
                Video Informasi
            </h2>
            <div class="video-container" style="min-height: 120px; height: 100%; position: relative;">
                <div id="video-player" class="video-player" style="width: 100%; height: 100%;">
                    <!-- Video akan dimuat dari API -->
                    <div class="video-placeholder">
                        <i class="fas fa-play-circle"></i>
                        <div>Memuat video...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Layanan Card -->
        <div class="card services-card"
            style="grid-column: 1 / 3; grid-row: 2 / 3; height: 100%; min-height: 150px; max-height: 200px; overflow-y: auto;">
            <h2 class="card-title">
                <div class="card-icon" style="background-color: #10b981;">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                Dalam Antrian
            </h2>
            <div class="services-grid" id="services-container">
                <div class="no-data">Memuat data layanan...</div>
            </div>
        </div>

        <!-- Running Text -->
        <div class="running-text-fade" id="running-text"
            style="grid-column: 1 / 3; grid-row: 3 / 4; padding: 0.5rem 1rem; text-align: center; margin-top: 0.25rem;">
            <div class="running-text-content">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                <span>Memuat running text...</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Sistem Antrian Digital. All rights reserved.</p>
    </footer>

    <!-- Floating Fullscreen Toggle Button - Super Visible -->
    <button id="fullscreen-btn"
        class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-full p-4 shadow-2xl transition-all duration-300 ease-in-out transform hover:scale-110 z-[99999] border-4 border-white/30 backdrop-blur-sm"
        title="Toggle Fullscreen"
        style="display: flex !important; align-items: center; justify-content: center; width: 70px !important; height: 70px !important; min-width: 70px !important; min-height: 70px !important; position: fixed !important; bottom: 30px !important; right: 30px !important;">
        <i class="fas fa-expand-arrows-alt text-2xl"></i>
    </button>

    <!-- Audio Element -->
    {{-- <audio id="callSound" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Safe DOM element selection with null checks
        function safeQuerySelector(selector) {
            const element = document.querySelector(selector);
            if (!element) {
                console.warn(`Element not found: ${selector}`);
            }
            return element;
        }

        function safeGetElementById(id) {
            const element = document.getElementById(id);
            if (!element) {
                console.warn(`Element not found: ${id}`);
            }
            return element;
        }

        // Update waktu dan tanggal
        function updateDateTime() {
            const now = new Date();
            const timeElement = safeGetElementById('current-time');
            const dateElement = safeGetElementById('current-date');

            if (!timeElement || !dateElement) return;

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
            // Safe event listeners with null checks
            const currentNumberEl = safeGetElementById('current-number');
            const currentCounterEl = safeGetElementById('current-counter');
            const currentServiceEl = safeGetElementById('current-service');
            const servicesContainer = safeGetElementById('services-container');
            const runningText = safeGetElementById('running-text');

            // Listen for antrian-called event
            window.addEventListener('antrian-called', function(event) {
                const data = event.detail;
                console.log('Received antrian-called event:', data);

                // Update display elements safely
                if (currentNumberEl && data.number) {
                    currentNumberEl.textContent = data.number;
                }
                if (currentCounterEl && data.counter) {
                    currentCounterEl.textContent = data.counter;
                }
                if (currentServiceEl && data.service) {
                    currentServiceEl.textContent = data.service;
                }

                // Update running text safely
                if (runningText) {
                    const serviceName = data.service || 'layanan';
                    runningText.innerHTML =
                        `<i class="fas fa-info-circle"></i> Sedang dipanggil: Nomor ${data.number} untuk ${serviceName} di ${data.counter}.`;
                }

                // Refresh data from API
                fetchQueueData();
            });

            // Start polling when page loads
            if (typeof fetchQueueData === 'function') {
                fetchQueueData();
                loadVideo(); // Load video saat halaman dimuat
                loadRunningTeks(); // Load running teks

                // Set up polling with error handling
                setInterval(() => {
                    fetchQueueData().catch(console.error);
                    loadVideo(); // Reload video setiap 30 detik
                    loadRunningTeks(); // Reload running teks setiap 30 detik
                }, 30000); // Poll every 30 seconds
            }
        });

        // Inisialisasi
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Real-time polling configuration
        let lastUpdateTime = Date.now();
        let pollingInterval = 3000; // Poll every 3 seconds
        let pollingActive = true;

        // Video player functionality
        let currentVideoId = null;
        let videoLoadAttempts = 0;
        const MAX_RETRIES = 3;
        const RETRY_DELAY = 5000; // 5 seconds

        async function loadVideo() {
            const videoPlayer = document.getElementById('video-player');
            
            try {
                // Show loading state
                videoPlayer.innerHTML = `
                    <div class="video-placeholder">
                        <div class="loading"></div>
                        <div>Memuat video...</div>
                    </div>
                `;

                const response = await fetch('/api/video', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Gagal memuat video');
                }

                // Reset retry counter on successful response
                videoLoadAttempts = 0;

                // If no active video
                if (!data.video) {
                    videoPlayer.innerHTML = `
                        <div class="video-placeholder">
                            <i class="fas fa-video-slash text-4xl mb-2"></i>
                            <div class="text-lg font-medium">Tidak ada video aktif</div>
                            <div class="text-sm opacity-75 mt-1">Silakan tambahkan video melalui panel admin</div>
                        </div>
                    `;
                    return;
                }

                // Skip if same video is already playing
                if (currentVideoId === data.video.id) {
                    return;
                }

                currentVideoId = data.video.id;

                // Handle YouTube videos
                if (data.video.type === 'youtube') {
                    videoPlayer.innerHTML = `
                        <div class="youtube-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%;">
                            <iframe 
                                src="${data.video.url}"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    `;
                } 
                // Handle local video files
                else if (data.video.type === 'file') {
                    videoPlayer.innerHTML = `
                        <video 
                            autoplay 
                            muted 
                            loop 
                            playsinline
                            style="width: 100%; height: 100%; object-fit: contain; background: #000;"
                            onerror="handleVideoError()"
                        >
                            <source src="${data.video.url}" type="video/mp4">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>
                    `;
                }
            } catch (error) {
                console.error('Error loading video:', error);
                videoLoadAttempts++;
                
                if (videoLoadAttempts <= MAX_RETRIES) {
                    videoPlayer.innerHTML = `
                        <div class="video-placeholder">
                            <i class="fas fa-sync-alt fa-spin text-4xl mb-2"></i>
                            <div>Mencoba memuat ulang video (${videoLoadAttempts}/${MAX_RETRIES})...</div>
                        </div>
                    `;
                    // Retry after delay
                    setTimeout(loadVideo, RETRY_DELAY);
                } else {
                    videoPlayer.innerHTML = `
                        <div class="video-placeholder">
                            <i class="fas fa-exclamation-triangle text-4xl mb-2 text-yellow-500"></i>
                            <div class="text-lg font-medium">Gagal memuat video</div>
                            <div class="text-sm opacity-75 mt-1">${error.message || 'Terjadi kesalahan'}</div>
                            <button onclick="loadVideo()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                            </button>
                        </div>
                    `;
                }
            }
        }

        // Handle video playback errors
        function handleVideoError() {
            const videoPlayer = document.getElementById('video-player');
            if (!videoPlayer) return;
            
            videoPlayer.innerHTML = `
                <div class="video-placeholder">
                    <i class="fas fa-exclamation-triangle text-4xl mb-2 text-red-500"></i>
                    <div class="text-lg font-medium">Gagal memutar video</div>
                    <div class="text-sm opacity-75 mt-1">Format tidak didukung atau file rusak</div>
                    <button onclick="loadVideo()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                    </button>
                </div>
            `;
        }

        // Initialize video player
        function initVideoPlayer() {
            // Load video when page loads
            loadVideo();
            
            // Refresh video every 5 minutes to catch any updates
            setInterval(loadVideo, 5 * 60 * 1000);
        }

        // Running teks functionality dengan fade animation yang lebih mulus
        async function loadRunningTeks() {
            try {
                const response = await fetch('/api/running-teks', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!response.ok) {
                    return; // Silent fail - tidak tampilkan error
                }

                const data = await response.json();
                const runningTextContainer = document.getElementById('running-text');

                if (!data.running_teks || data.running_teks.length === 0) {
                    runningTextContainer.innerHTML = `
                        <div class="running-text-content">
                            <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                            <span>Sistem Antrian Digital - Selamat datang di layanan kami</span>
                        </div>
                    `;
                    return;
                }

                const activeItems = data.running_teks;

                if (activeItems.length === 0) {
                    runningTextContainer.innerHTML = `
                        <div class="running-text-content">
                            <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                            <span>Sistem Antrian Digital - Selamat datang di layanan kami</span>
                        </div>
                    `;
                    return;
                }

                // Setup CSS untuk transisi yang lebih mulus
                runningTextContainer.style.transition = 'opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1)';
                runningTextContainer.style.willChange = 'opacity';
                runningTextContainer.style.opacity = '1';

                let currentIndex = 0;
                let isTransitioning = false;

                const createContent = (text) => `
                    <div class="running-text-content" style="display: flex; align-items: center; justify-content: center; width: 100%; min-height: 2.5rem;">
                        <i class="fas fa-info-circle text-blue-400 mr-3" style="font-size: 1.1rem;"></i>
                        <span style="font-size: 1.25rem; font-weight: 600;">${text}</span>
                    </div>
                `;

                const smoothTransition = (newText) => {
                    if (isTransitioning) return;

                    isTransitioning = true;

                    // Fade out dengan easing yang lebih halus
                    runningTextContainer.style.opacity = '0';

                    setTimeout(() => {
                        runningTextContainer.innerHTML = createContent(newText);

                        // Force reflow untuk transisi yang smooth
                        runningTextContainer.offsetHeight;

                        // Fade in dengan easing yang lebih halus
                        runningTextContainer.style.opacity = '1';

                        setTimeout(() => {
                            isTransitioning = false;
                        }, 1200);
                    }, 1200);
                };

                // Tampilkan teks pertama
                runningTextContainer.innerHTML = createContent(activeItems[0].text);

                // Rotasi teks dengan timing yang lebih baik
                const updateText = () => {
                    currentIndex = (currentIndex + 1) % activeItems.length;
                    smoothTransition(activeItems[currentIndex].text);
                };

                // Mulai rotasi setelah delay awal
                setTimeout(() => {
                    setInterval(updateText, 8000); // 8 detik per teks (1.2s fade + 6.8s tampil)
                }, 2000);

            } catch (error) {
                // Silent fail - tampilkan default text tanpa error
                const runningTextContainer = document.getElementById('running-text');
                runningTextContainer.innerHTML = `
                    <div class="running-text-content">
                        <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                        <span>Sistem Antrian Digital - Selamat datang di layanan kami</span>
                    </div>
                `;
            }
        }

        // Enhanced fetch function with comprehensive error handling
        async function fetchQueueData() {
            if (!pollingActive) return;

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout

            const url = new URL('/api/display-data', window.location.origin);
            url.searchParams.set('t', Date.now());

            try {
                const response = await fetch(url.toString(), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    },
                    signal: controller.signal,
                    mode: 'cors',
                    credentials: 'omit'
                });

                clearTimeout(timeoutId);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Invalid response format - expected JSON');
                }

                const data = await response.json();

                if (typeof data !== 'object' || data === null) {
                    throw new Error('Invalid data structure');
                }

                updateDisplay(data);
                lastUpdateTime = Date.now();
                hideOfflineIndicator();

                if (window.debugMode) {
                    console.log('✅ Queue data fetched successfully:', data);
                }

            } catch (error) {
                clearTimeout(timeoutId);

                if (error.name === 'AbortError') {
                    console.warn('⏰ Request timeout - server may be slow');
                    showOfflineIndicator('Request timeout - checking connection...');
                } else if (error.name === 'TypeError') {
                    console.error('🔌 Network error:', error.message);
                    showOfflineIndicator('Network connection issue - retrying...');
                } else {
                    console.error('❌ Fetch error:', error.message);
                    showOfflineIndicator(`Error: ${error.message}`);
                }

                // Retry with exponential backoff
                const retryDelay = Math.min(5000 * Math.pow(1.5, fetchQueueData.retryCount || 0), 30000);
                fetchQueueData.retryCount = (fetchQueueData.retryCount || 0) + 1;

                setTimeout(() => {
                    fetchQueueData.retryCount = 0;
                    fetchQueueData();
                }, retryDelay);
            }
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

            const normalizedName = serviceName?.toLowerCase() || '';
            for (const [key, icon] of Object.entries(icons)) {
                if (normalizedName.includes(key.toLowerCase())) {
                    return icon;
                }
            }
            return 'fas fa-list-ol';
        }

        // Update display with real data
        function updateDisplay(data) {
            const currentNumberEl = safeGetElementById('current-number');
            const currentCounterEl = safeGetElementById('current-counter');
            const currentServiceEl = safeGetElementById('current-service');
            const servicesContainer = safeGetElementById('services-container');
            const runningText = safeGetElementById('running-text');

            if (!data) return;

            // Update current calling queue - only show if it's a recent call
            let validCurrentCall = null;

            if (data.currentCalled && data.currentCalled.length > 0) {
                validCurrentCall = data.currentCalled[0];
            }

            if (validCurrentCall) {
                if (currentNumberEl) currentNumberEl.textContent = validCurrentCall.formatted_number || '---';
                if (currentCounterEl) currentCounterEl.textContent = validCurrentCall.counter_name || 'Loket';
                if (currentServiceEl) currentServiceEl.textContent = validCurrentCall.service_name || '-';
            } else {
                if (currentNumberEl) currentNumberEl.textContent = '---';
                if (currentCounterEl) currentCounterEl.textContent = '-';
                if (currentServiceEl) currentServiceEl.textContent = '-';
            }



            // Update services info
            if (servicesContainer) {
                if (data.services && data.services.length > 0) {
                    servicesContainer.innerHTML = '';
                    data.services.forEach(service => {
                        const serviceCurrentCalled = validCurrentCall && validCurrentCall.service_id === service
                            .id ? validCurrentCall : null;
                        const nextQueue = data.nextQueues?.find(q => q.service_id === service.id);

                        const serviceItem = document.createElement('div');
                        serviceItem.className = 'service-item';
                        serviceItem.setAttribute('data-service-id', service.id);
                        if (serviceCurrentCalled) {
                            serviceItem.classList.add('active');
                        }

                        const iconClass = getServiceIcon(service.name);

                        serviceItem.innerHTML = `
                            <div class="service-header">
                                <div class="service-icon"
                                    style="background: ${serviceCurrentCalled ? '#3b82f6' : '#475569'}">
                                    <i class="${iconClass} text-white"></i>
                                </div>
                                <div class="service-name">${service.name}</div>
                            </div>
                            <div class="service-info">
                                <div class="service-current">${serviceCurrentCalled ? serviceCurrentCalled.formatted_number : '---'}</div>
                                <div class="service-next">${nextQueue ? nextQueue.formatted_number : '-'}</div>
                                <div class="service-counter">${serviceCurrentCalled ? (serviceCurrentCalled.counter_name || 'Loket') : ''}</div>
                                <div class="service-range">${service.range || ''}</div>
                            </div>
                        `;
                        servicesContainer.appendChild(serviceItem);
                    });
                } else {
                    servicesContainer.innerHTML = '<div class="no-data">Tidak ada layanan aktif</div>';
                }
            }

            // Update running text safely
            if (runningText) {
                if (validCurrentCall) {
                    runningText.innerHTML = `
                        <i class="fas fa-info-circle"></i>
                        Nomor ${validCurrentCall.formatted_number} untuk ${validCurrentCall.service_name} di ${validCurrentCall.counter_name || 'Loket'}. Silakan menunggu jika nomor Anda belum dipanggil.
                    `;
                } else {
                    runningText.innerHTML = `
                        <i class="fas fa-info-circle"></i>
                        Sistem Antrian Digital - Selamat datang di layanan kami
                    `;
                }
            }
        }

        // Enhanced offline indicator
        function showOfflineIndicator(message = 'Connection lost') {
            let indicator = document.getElementById('offline-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'offline-indicator';
                indicator.style.cssText = `
                    position: fixed;
                    top: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #ef4444;
                    color: white;
                    padding: 12px 24px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    z-index: 9999;
                    font-weight: bold;
                    animation: pulse 2s infinite;
                `;
                document.body.appendChild(indicator);
            }
            indicator.textContent = message;
            indicator.style.display = 'block';
        }

        function hideOfflineIndicator() {
            const indicator = document.getElementById('offline-indicator');
            if (indicator) {
                indicator.style.display = 'none';
            }
        }

        // Enhanced polling with visibility API
        let pollingIntervalId;
        let isPageVisible = true;

        function startPolling() {
            if (typeof fetchQueueData === 'function') {
                fetchQueueData(); // Initial load

                // Clear any existing interval
                if (pollingIntervalId) {
                    clearInterval(pollingIntervalId);
                }

                pollingIntervalId = setInterval(() => {
                    if (isPageVisible && navigator.onLine !== false) {
                        fetchQueueData();
                    }
                }, pollingInterval);
            }
        }

        // Handle page visibility
        document.addEventListener('visibilitychange', () => {
            isPageVisible = !document.hidden;
            if (isPageVisible) {
                fetchQueueData(); // Immediate fetch when page becomes visible
            }
        });

        // Handle online/offline events
        window.addEventListener('online', () => {
            console.log('🟢 Connection restored');
            hideOfflineIndicator();
            fetchQueueData();
        });

        window.addEventListener('offline', () => {
            console.log('🔴 Connection lost');
            showOfflineIndicator('No internet connection');
        });

        // Fullscreen toggle functionality
        // Handle page visibility changes
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Reload video when page becomes visible again
                if (typeof loadVideo === 'function') {
                    loadVideo();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize video player
            if (typeof initVideoPlayer === 'function') {
                initVideoPlayer();
            }
            
            const fullscreenBtn = safeGetElementById('fullscreen-btn');
            if (!fullscreenBtn) return;

            const icon = fullscreenBtn.querySelector('i');

            // Check if fullscreen is supported
            function isFullscreenSupported() {
                return document.fullscreenEnabled ||
                    document.webkitFullscreenEnabled ||
                    document.mozFullScreenEnabled ||
                    document.msFullscreenEnabled;
            }

            // Check if currently in fullscreen
            function isInFullscreen() {
                return !!(document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.mozFullScreenElement ||
                    document.msFullscreenElement);
            }

            // Enter fullscreen
            function enterFullscreen() {
                const element = document.documentElement;
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                }
            }

            // Exit fullscreen
            function exitFullscreen() {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }

            // Toggle fullscreen
            function toggleFullscreen() {
                if (isInFullscreen()) {
                    exitFullscreen();
                } else {
                    enterFullscreen();
                }
            }

            // Update button icon based on fullscreen state
            function updateFullscreenIcon() {
                if (!icon) return;
                if (isInFullscreen()) {
                    icon.className = 'fas fa-compress';
                    fullscreenBtn.title = 'Keluar Fullscreen';
                } else {
                    icon.className = 'fas fa-expand-arrows-alt';
                    fullscreenBtn.title = 'Masuk Fullscreen';
                }
            }

            // Add click event listener
            fullscreenBtn.addEventListener('click', toggleFullscreen);

            // Listen for fullscreen changes
            ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange'].forEach(
                event => {
                    document.addEventListener(event, updateFullscreenIcon);
                });

            // Keyboard shortcut (F11 or F key)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'F11' || e.key === 'f' || e.key === 'F') {
                    e.preventDefault();
                    toggleFullscreen();
                }
            });

            // Tambahkan efek pulse pada tombol fullscreen saat pertama kali load
            setTimeout(() => {
                const btn = safeGetElementById('fullscreen-btn');
                if (btn) {
                    btn.style.animation = 'pulse 2s infinite';
                    setTimeout(() => {
                        btn.style.animation = '';
                    }, 6000);
                }
            }, 2000);

            // Always show fullscreen button regardless of support
            // Modern browsers all support fullscreen API
            fullscreenBtn.style.display = 'flex';
            fullscreenBtn.style.alignItems = 'center';
            fullscreenBtn.style.justifyContent = 'center';

            // Initial icon update
            updateFullscreenIcon();
        });
    </script>
</body>

</html>
