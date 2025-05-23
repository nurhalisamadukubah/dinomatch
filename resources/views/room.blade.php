<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DinoMatch - Dig Site Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;600;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #e6d2b5;
            background-image: url('/api/placeholder/800/600');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            font-family: 'Cabin', sans-serif;
            position: relative;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/api/placeholder/500/500') repeat;
            opacity: 0.05;
            pointer-events: none;
            z-index: -1;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        
        /* Game Card */
        .game-card {
            background-color: rgba(58, 77, 57, 0.95);
            border-radius: 25px;
            border: 5px solid #daa520;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            padding: 40px;
            max-width: 800px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .game-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/api/placeholder/800/600') no-repeat center;
            background-size: cover;
            opacity: 0.1;
            pointer-events: none;
            z-index: -1;
        }
        
        /* Decorative fossils */
        .game-card::after {
            content: "";
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background: url('/api/placeholder/80/80') no-repeat center;
            background-size: contain;
            opacity: 0.4;
            transform: rotate(15deg);
        }
        
        /* Room Header */
        .room-header {
            text-align: center;
            margin-bottom: 35px;
            position: relative;
        }
        
        .room-header h1 {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 36px;
            color: #daa520;
            text-shadow: 3px 3px 0 #331a00;
            margin-bottom: 20px;
            position: relative;
        }
        
        .room-header h1::after {
            content: "🦖";
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
        }
        
        .room-header h1::before {
            content: "🦕";
            position: absolute;
            left: -30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
        }
        
        /* Code Container */
        .code-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            background-color: #734f30;
            padding: 20px 30px;
            border-radius: 15px;
            border: 3px solid #daa520;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
            max-width: 300px;
        }
        
        .code {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 32px;
            font-weight: 700;
            color: #f5f5dc;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
            letter-spacing: 3px;
        }
        
        .copy-btn {
            background-color: #3a4d39;
            border: 2px solid #daa520;
            border-radius: 10px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 0 #2c3c2c;
        }
        
        .copy-btn svg {
            width: 20px;
            height: 20px;
            fill: #f5f5dc;
        }
        
        .copy-btn:hover {
            background-color: #4a6349;
            transform: translateY(-2px);
            box-shadow: 0 6px 0 #2c3c2c;
        }
        
        .copy-btn:active {
            transform: translateY(2px);
            box-shadow: 0 2px 0 #2c3c2c;
        }
        
        .copy-btn.copied {
            background-color: #2d6a2d;
        }
        
        /* Section Styles */
        .section {
            margin-bottom: 30px;
        }
        
        .section h2 {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 28px;
            color: #daa520;
            text-shadow: 2px 2px 0 #331a00;
            margin-bottom: 20px;
            text-align: center;
            position: relative;
        }
        
        .section h2::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background-color: #daa520;
            border-radius: 10px;
        }
        
        /* Players Section */
        .players-section {
            background-color: rgba(115, 79, 48, 0.8);
            padding: 25px;
            border-radius: 20px;
            border: 3px solid #daa520;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .players-section::before {
            content: "";
            position: absolute;
            top: -15px;
            left: 30px;
            width: 50px;
            height: 50px;
            background: url('/api/placeholder/50/50') no-repeat center;
            background-size: contain;
            transform: rotate(-20deg);
            opacity: 0.6;
        }
        
        .player-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .player-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: #e6d2b5;
            padding: 15px 20px;
            border-radius: 12px;
            border: 2px solid #3a4d39;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .player-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(218, 165, 32, 0.2), transparent);
            transition: 0.5s;
        }
        
        .player-card:hover::before {
            left: 100%;
        }
        
        .player-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            border-color: #daa520;
        }
        
        .player-card svg {
            width: 24px;
            height: 24px;
            fill: #3a4d39;
            flex-shrink: 0;
        }
        
        .player-card span {
            font-family: 'Cabin', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: #3a4d39;
        }
        
        /* Countdown Section */
        .countdown-section {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .countdown-card {
            background-color: #734f30;
            padding: 30px 40px;
            border-radius: 20px;
            border: 4px solid #daa520;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            text-align: center;
            position: relative;
            transform: rotate(-1deg);
            animation: pulse 2s infinite alternate;
        }
        
        @keyframes pulse {
            0% { transform: rotate(-1deg) scale(1); }
            100% { transform: rotate(1deg) scale(1.02); }
        }
        
        .countdown-card::before, .countdown-card::after {
            content: "";
            position: absolute;
            width: 40px;
            height: 40px;
            background: url('/api/placeholder/40/40') no-repeat center;
            background-size: contain;
        }
        
        .countdown-card::before {
            top: -15px;
            left: -15px;
            transform: rotate(-25deg);
        }
        
        .countdown-card::after {
            bottom: -15px;
            right: -15px;
            transform: rotate(25deg);
        }
        
        .countdown-card i {
            font-size: 32px;
            color: #daa520;
            margin-bottom: 15px;
            display: block;
        }
        
        .countdown-content h3 {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 24px;
            color: #f5f5dc;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
            margin-bottom: 15px;
        }
        
        .countdown-timer {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 48px;
            font-weight: 700;
            color: #daa520;
            text-shadow: 3px 3px 0 #331a00;
            background-color: rgba(58, 77, 57, 0.8);
            padding: 15px 25px;
            border-radius: 15px;
            border: 3px solid #daa520;
            display: inline-block;
            min-width: 120px;
        }
        
        /* Decorative footprints */
        .dino-footprint {
            position: absolute;
            width: 30px;
            height: 40px;
            background: url('/api/placeholder/30/40') no-repeat center;
            background-size: contain;
            opacity: 0.2;
            z-index: 0;
        }
        
        .footprint1 { top: 15%; left: 5%; transform: rotate(20deg); }
        .footprint2 { top: 25%; right: 8%; transform: rotate(-15deg); }
        .footprint3 { bottom: 20%; left: 10%; transform: rotate(10deg); }
        .footprint4 { bottom: 15%; right: 5%; transform: rotate(-20deg); }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .game-card {
                padding: 25px;
                margin-top: 60px;
            }
            
            .site-header h1 {
                font-size: 24px;
            }
            
            .site-header img {
                height: 40px;
            }
            
            .room-header h1 {
                font-size: 28px;
            }
            
            .room-header h1::before,
            .room-header h1::after {
                display: none;
            }
            
            .code-container {
                max-width: 250px;
                padding: 15px 20px;
            }
            
            .code {
                font-size: 24px;
            }
            
            .section h2 {
                font-size: 24px;
            }
            
            .player-list {
                grid-template-columns: 1fr;
            }
            
            .countdown-timer {
                font-size: 36px;
                padding: 10px 20px;
            }
        }
        
        @media (max-width: 480px) {
            .game-card {
                padding: 20px;
            }
            
            .room-header h1 {
                font-size: 24px;
            }
            
            .code {
                font-size: 20px;
                letter-spacing: 2px;
            }
            
            .countdown-card {
                padding: 20px 25px;
            }
            
            .countdown-content h3 {
                font-size: 20px;
            }
            
            .countdown-timer {
                font-size: 28px;
                padding: 8px 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Decorative footprints -->
    <div class="dino-footprint footprint1"></div>
    <div class="dino-footprint footprint2"></div>
    <div class="dino-footprint footprint3"></div>
    <div class="dino-footprint footprint4"></div>
    
    <div class="container">
        <div class="game-card">
            <div class="room-header">
                <h1>PassCode</h1>
                <div class="code-container">
                    <span class="code">{{ $room->code }}</span>
                    <button id="copy-code-btn" class="copy-btn">
                        <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                            <path d="M19 21H8V7h11m0-2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2m-3-4H4a2 2 0 0 0-2 2v14h2V3h12V1z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="section players-section">
                <h2>🦴 Player ({{ count($participants) }})</h2>
                <div class="player-list">
                    @foreach ($participants as $player)
                        <div class="player-card">
                            <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                                <path d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                            </svg>
                            <span>{{ $player->username }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($startTime)
            <div class="countdown-section">
                <div class="countdown-card">
                    <i class="fas fa-hourglass-start"></i>
                    <div class="countdown-content">
                        <h3>Excavation Starting In</h3>
                        <div id="timer" class="countdown-timer">00</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        const startTime = "{{ $startTime }}"; // Waktu mulai permainan
        const roomCode = "{{ $room->code }}"; // Kode room
        const roomId = "{{ $room->id }}"; // Kode room
        const playerId = '{{ $player_id }}';
        const galleryId = '{{ $gallery_id }}';
        const playersListElement = document.getElementById('players-list');

        // Fungsi untuk memulai countdown
        function startCountdown(startTime) {
            const timerElement = document.getElementById('timer');
            const countdownInterval = setInterval(() => {
                const now = new Date().getTime(); // Waktu saat ini dalam milidetik
                const start = new Date(startTime).getTime(); // Waktu mulai dalam milidetik
                const remaining = Math.max(0, Math.floor((start - now) / 1000)); // Sisa waktu dalam detik

                timerElement.textContent = `${remaining}`;

                if (remaining <= 0) {
                    clearInterval(countdownInterval); // Hentikan interval saat waktu habis
                    window.location.replace(
                    `/puzzle/${playerId}/${roomId}/${galleryId}`); // Alihkan ke halaman lain jika diperlukan
                }
            }, 1000);
        }

        // Fungsi untuk mendapatkan waktu mulai dari server (polling)
        function fetchStartTime(roomCode) {
            fetch(`/room/${roomCode}/start-time`)
                .then(response => response.json())
                .then(data => {
                    if (data.start_time) {
                        startCountdown(data.start_time);
                    }
                })
                .catch(error => console.error('Error fetching start time:', error));
        }

        if (startTime) {
            // Langsung mulai countdown jika waktu mulai sudah tersedia
            startCountdown(startTime);
        } else {
            // Polling untuk mendapatkan waktu mulai jika belum tersedia
            setInterval(() => fetchStartTime(roomCode), 2000); // Polling setiap 2 detik
        }

        // Fungsi untuk memperbarui daftar pemain
        function updatePlayersList() {
            fetch(`/room/${roomCode}/players`)
                .then(response => response.json())
                .then(data => {
                    const currentPlayers = data.map(player => player.username); // Daftar pemain saat ini
                    const previousPlayers = JSON.parse(localStorage.getItem(`players-${roomCode}`)) ||
                []; // Daftar pemain sebelumnya

                    // Cek apakah ada pemain baru yang masuk
                    const newPlayers = currentPlayers.filter(player => !previousPlayers.includes(player));

                    if (newPlayers.length > 0) {
                        // Jika ada pemain baru, simpan daftar pemain terbaru dan refresh halaman
                        localStorage.setItem(`players-${roomCode}`, JSON.stringify(currentPlayers));
                        location.reload();
                    } else {
                        // Jika tidak ada pemain baru, perbarui daftar pemain tanpa refresh
                        if (playersListElement) {
                            playersListElement.innerHTML = ''; // Kosongkan daftar pemain
                            currentPlayers.forEach(player => {
                                const li = document.createElement('li');
                                li.textContent = player;
                                playersListElement.appendChild(li);
                            });
                        }
                    }
                })
                .catch(error => console.error('Error fetching players:', error));
        }

        // Polling untuk memperbarui daftar pemain setiap 2 detik
        setInterval(updatePlayersList, 2000);

        // Inisialisasi daftar pemain saat pertama kali load
        updatePlayersList();

        // Fungsi untuk menangani salin kode room
        document.getElementById('copy-code-btn').addEventListener('click', () => {
            navigator.clipboard.writeText(roomCode).then(() => {
                const btn = document.getElementById('copy-code-btn');
                btn.innerHTML = '<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/></svg>';
                btn.classList.add('copied');
                
                setTimeout(() => {
                    btn.innerHTML = '<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M19 21H8V7h11m0-2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2m-3-4H4a2 2 0 0 0-2 2v14h2V3h12V1z"/></svg>';
                    btn.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Gagal menyalin kode:', err);
                alert('Gagal menyalin kode. Silahkan salin secara manual.');
            });
        });
    </script>
</body>

</html>