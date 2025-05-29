<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Waiting Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;600;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/waiting_room.css') }}">
</head>
<body>
    <div class="container">
        <div class="game-card">
            <div class="room-header">
                <h1>Passcode</h1>
                <h2>Bagikan Kode Unik Berikut ke Temanmu!</h2>
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
                <h2>👥 Players ({{ count($participants) }})</h2>
                <div class="player-rows" id="player-list">
                    @foreach ($participants as $index => $player)
                        <div class="player-row">
                            <div class="player-card {{ $index === 0 || (isset($player->is_host) && $player->is_host) || (isset($player->role) && $player->role === 'host') ? 'host-card' : '' }}">
                                <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                                    <path d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                                </svg>
                                <div class="player-info">
                                    <span class="player-name">{{ $player->username }}</span>
                                    @if ($index === 0 || (isset($player->is_host) && $player->is_host) || (isset($player->role) && $player->role === 'host'))
                                        <span class="host-badge">HOST</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if (count($participants) < 2)
                        <div class="player-row">
                            <div class="player-card waiting-card">
                                <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,15.4L16.2,16.2Z"/>
                                </svg>
                                <span>Menunggu pemain...</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($startTime)
            <div class="countdown-section">
                <div class="countdown-card">
                    <div class="countdown-content">
                        <h3>Tantangan Puzzle dimulai dalam</h3>
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
        const playersListElement = document.getElementById('player-list');

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
                            // Update player list dynamically
                            const playerRows = playersListElement.querySelectorAll('.player-row');
                            playerRows.forEach(row => {
                                if (!row.querySelector('.waiting-card')) {
                                    const playerName = row.querySelector('.player-name').textContent;
                                    if (!currentPlayers.includes(playerName)) {
                                        row.remove();
                                    }
                                }
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