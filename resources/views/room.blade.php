<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Game Room</title>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <!-- Ganti link Font Awesome CDN dengan -->
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
</head>

<body>
    <div class="container">
        <div class="game-card">
            <div class="room-header">
                <div class="room-code">
                    <h1>Room Code</h1>
                    <div class="code-container">
                        <span class="code">{{ $room->code }}</span>
                        <button id="copy-code-btn" class="copy-btn">
                            <svg aria-hidden="true" focusable="false" style="width:1.5em;height:1.5em" viewBox="0 0 24 24">
                                <path d="M19 21H8V7h11m0-2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2m-3-4H4a2 2 0 0 0-2 2v14h2V3h12V1z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="section players-section">
                <h2><i class="fas fa-users"></i> Players ({{ count($participants) }})</h2>
                <div class="player-list">
                    @foreach ($participants as $player)
                        <div class="player-card">
                            <svg aria-hidden="true" focusable="false" style="width:1em;height:1em" viewBox="0 0 24 24">
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
                        <h3>Game Starting In</h3>
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

                timerElement.textContent = `${remaining} detik`;

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
                        playersListElement.innerHTML = ''; // Kosongkan daftar pemain
                        currentPlayers.forEach(player => {
                            const li = document.createElement('li');
                            li.textContent = player;
                            playersListElement.appendChild(li);
                        });
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
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.classList.add('copied');
                
                setTimeout(() => {
                    btn.innerHTML = '<i class="far fa-copy"></i>';
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
