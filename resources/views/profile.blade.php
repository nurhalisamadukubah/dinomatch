<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cabin :wght@400;600;700&family=Bubblegum+Sans&display=swap"
        rel="stylesheet">
    <title>Profil Pengguna - Game Puzzle</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cabin', sans-serif;
            background-color: #e6d2b5;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(218, 165, 32, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(58, 77, 57, 0.1) 0%, transparent 50%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="%23734f30" opacity="0.1"/><circle cx="80" cy="40" r="1.5" fill="%23daa520" opacity="0.1"/><circle cx="60" cy="80" r="1" fill="%233a4d39" opacity="0.1"/></svg>');
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/api/placeholder/1920/1080') center/cover;
            opacity: 0.03;
            pointer-events: none;
            z-index: -2;
        }

        /* Animated background elements */
        .bg-leaves {
            position: fixed;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .leaf {
            position: absolute;
            width: 30px;
            height: 30px;
            background: url('/api/placeholder/30/30') no-repeat center;
            background-size: contain;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            25% {
                transform: translateY(-10px) rotate(5deg);
            }

            50% {
                transform: translateY(-20px) rotate(-5deg);
            }

            75% {
                transform: translateY(-10px) rotate(3deg);
            }
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        /* Header section */
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            background-color: #3a4d39;
            border-radius: 25px;
            padding: 15px 20px;
            border: 5px solid #daa520;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .back-button {
            background: linear-gradient(135deg, #734f30, #8b5a2b);
            color: #fff;
            border: 3px solid #daa520;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            font-size: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
            box-shadow: 0 6px 0 #5a3e24, 0 10px 20px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
            font-family: 'Bubblegum Sans', cursive;
        }

        .back-button:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 11px 0 #5a3e24, 0 15px 25px rgba(0, 0, 0, 0.4);
        }

        .page-title {
            flex-grow: 1;
            text-align: center;
            font-family: 'Bubblegum Sans', cursive;
            font-size: 48px;
            font-weight: 700;
            color: #daa520;
            text-shadow: 3px 3px 0 #331a00, -1px -1px 0 #331a00, 1px -1px 0 #331a00, -1px 1px 0 #331a00;
            position: relative;
            z-index: 2;
            letter-spacing: 2px;
        }

        /* Main content layout */
        .main-content {
            display: flex;
            gap: 40px;
            flex: 1;
            min-height: 0;
        }

        /* Profile section */
        .profile-section {
            flex: 0 0 380px;
        }

        .profile-card {
            background: linear-gradient(135deg, rgba(248, 244, 230, 0.95), rgba(230, 210, 181, 0.95));
            border-radius: 30px;
            padding: 35px;
            border: 6px solid #734f30;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: visible;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
            height: fit-content;
            transform: rotate(-1deg);
        }

        .avatar-container {
            width: 220px;
            height: 220px;
            border-radius: 20px;
            overflow: hidden;
            border: 5px solid #734f30;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            position: relative;
            background: linear-gradient(135deg, #f8f4e6, #e6d2b5);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-img {
            width: 90%;
            height: 90%;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .profile-info {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            position: relative;
        }

        .profile-value {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 28px;
            color: #3a4d39;
            background: linear-gradient(135deg, #f8f4e6, #fff);
            padding: 15px 25px;
            border-radius: 20px;
            border: 4px dashed #daa520;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .edit-button {
            background: linear-gradient(135deg, #daa520, #b8941c);
            color: #fff;
            border: 3px solid #734f30;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 0 #996d14, 0 8px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            position: relative;
        }

        .edit-button:hover {
            transform: translateY(-3px) scale(1.1) rotate(15deg);
            box-shadow: 0 9px 0 #996d14, 0 12px 20px rgba(0, 0, 0, 0.3);
        }

        /* Stats section */
        .stats-section {
            margin-top: 25px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .stat-card {
            background: linear-gradient(135deg, #3a8559, #2d6b44);
            color: white;
            padding: 15px 20px;
            border-radius: 20px;
            border: 4px solid #daa520;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            text-align: center;
            min-width: 120px;
            position: relative;
            overflow: hidden;
        }

        .stat-number {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 28px;
            font-weight: 700;
            display: block;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stat-label {
            font-family: 'Cabin', sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Game history section */
        .history-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .section-title {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 36px;
            font-weight: 700;
            color: #3a4d39;
            text-shadow: 3px 3px 0 #daa520, -1px -1px 0 #daa520, 1px -1px 0 #daa520, -1px 1px 0 #daa520;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
            display: block;
            padding: 20px 40px;
            background: linear-gradient(135deg, rgba(248, 244, 230, 0.9), rgba(230, 210, 181, 0.9));
            border-radius: 25px;
            border: 5px solid #734f30;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            transform: rotate(1deg);
            letter-spacing: 2px;
        }

        .history-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            flex: 1;
            background: linear-gradient(135deg, rgba(248, 244, 230, 0.95), rgba(230, 210, 181, 0.95));
            border-radius: 25px;
            padding: 25px;
            border: 5px solid #734f30;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .history-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 244, 230, 0.9));
            border-radius: 20px;
            overflow: hidden;
            border: 4px solid #734f30;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: stretch;
            transition: all 0.3s ease;
            position: relative;
        }

        .result-badge {
            width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Bubblegum Sans', cursive;
            font-size: 20px;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            letter-spacing: 1px;
        }

        .result-win {
            background: linear-gradient(135deg, #3a8559, #2d6b44);
        }

        .result-lose {
            background: linear-gradient(135deg, #a55638, #8b4530);
        }

        .result-draw {
            background: linear-gradient(135deg, #daa520, #b8941c);
        }

        .history-details {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 25px;
            position: relative;
        }

        .match-players {
            font-family: 'Cabin', sans-serif;
            font-size: 20px;
            color: #3a4d39;
            display: flex;
            align-items: center;
            gap: 20px;
            font-weight: 600;
        }

        .player-name {
            font-weight: 700;
            color: #734f30;
        }

        .vs-text {
            font-family: 'Bubblegum Sans', cursive;
            color: #daa520;
            font-size: 18px;
            font-weight: 700;
        }

        .match-score {
            font-family: 'Bubblegum Sans', cursive;
            font-size: 24px;
            font-weight: 700;
            color: #3a4d39;
            background: linear-gradient(135deg, #daa520, #b8941c);
            color: white;
            padding: 10px 20px;
            border-radius: 15px;
            border: 3px solid #734f30;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        /* Decorative elements */
        .floating-fossil {
            position: absolute;
            z-index: 0;
            opacity: 0.1;
            pointer-events: none;
            animation: float 6s ease-in-out infinite;
        }

        /* Media Queries */
        @media (max-width: 1024px) {
            .main-content {
                flex-direction: column;
                gap: 30px;
            }

            .profile-section {
                flex: none;
            }

            .profile-card {
                flex-direction: row;
                align-items: center;
                gap: 25px;
                transform: rotate(0deg);
            }

            .avatar-container {
                width: 180px;
                height: 180px;
            }

            .profile-info {
                flex: 1;
            }

            .section-title {
                font-size: 32px;
                transform: rotate(0deg);
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 36px;
            }

            .back-button {
                width: 60px;
                height: 60px;
                font-size: 28px;
            }

            .profile-card {
                flex-direction: column;
                align-items: center;
            }

            .avatar-container {
                width: 160px;
                height: 160px;
            }

            .profile-value {
                font-size: 24px;
            }

            .history-card {
                flex-direction: column;
            }

            .result-badge {
                width: 100%;
                padding: 15px;
                justify-content: space-between;
            }

            .match-players {
                font-size: 18px;
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .stats-section {
                gap: 10px;
            }

            .stat-card {
                min-width: 100px;
                padding: 12px 15px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }

            .page-title {
                font-size: 28px;
                padding: 15px 20px;
            }

            .profile-card {
                padding: 25px;
            }

            .avatar-container {
                width: 140px;
                height: 140px;
            }

            .profile-value {
                font-size: 20px;
                padding: 12px 20px;
            }

            .history-details {
                flex-direction: column;
                gap: 15px;
                align-items: center;
                padding: 15px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-leaves">
        <div class="leaf"></div>
        <div class="leaf"></div>
        <div class="leaf"></div>
        <div class="leaf"></div>
        <div class="leaf"></div>
    </div>

    <img src="/api/placeholder/180/180" alt="Floating fossil" class="floating-fossil fossil-1">
    <img src="/api/placeholder/200/160" alt="Floating fossil" class="floating-fossil fossil-2">
    <img src="/api/placeholder/140/140" alt="Floating fossil" class="floating-fossil fossil-3">

    <div class="container">
        <div class="header">
            <a href="{{ route('room.index') }}" style="text-decoration: none"><button class="back-button">←</button></a>
            <h1 class="page-title">PROFIL EXPLORATOR</h1>
        </div>

        <div class="main-content">
            <!-- Profile Section -->
            <div class="profile-section">
                <div class="profile-card">
                    <div class="avatar-container">
                        <img src="https://via.placeholder.com/220 " alt="Dinosaur avatar" class="avatar-img">
                    </div>

                    <div class="profile-info">
                        <div class="profile-item">
                            @if (session()->has('player'))
                                <div class="profile-value">{{ $player->player->username ?? $user->username }}</div>
                            @else
                                <div class="profile-value">{{ $player->user->username ?? $user->username }}</div>
                            @endif
                            <button class="edit-button">✏️</button>
                        </div>
                    </div>

                    <div class="stats-section">
                        <div class="stat-card">
                            <span class="stat-number">12</span>
                            <span class="stat-label">Menang</span>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(135deg, #a55638, #8b4530);">
                            <span class="stat-number">8</span>
                            <span class="stat-label">Kalah</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="history-section">
                <h2 class="section-title">RIWAYAT PERMAINAN</h2>
                <div class="history-container">
                    @if ($resultsPerRound->isEmpty())
                        <p>Belum ada riwayat permainan untuk ditampilkan.</p>
                    @else
                        @foreach ($resultsPerRound as $round => $data)
                            @php
                                $winner = $data['winner'];
                                $losers = $data['losers'];
                            @endphp

                            <div class="history-card">
                                <div class="result-badge result-win">VICTORY!</div>
                                <div class="history-details">
                                    <div class="match-players">
                                        @if (session()->has('player'))
                                            <span class="player-name">{{ $player->player->username ?? '-' }}</span>
                                        @else
                                            <span class="player-name">{{ $player->user->username ?? '-' }}</span>
                                        @endif
                                        <span class="vs-text">VS</span>
                                        <span
                                            class="player-name">{{ $winner->player->username ?? ($winner->user->username ?? '-') }}</span>
                                    </div>
                                    <div class="match-score">3 - 0</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <script>
                // Edit button functionality
                document.querySelector('.edit-button').addEventListener('click', function() {
                    const profileValue = document.querySelector('.profile-value');
                    const currentName = profileValue.textContent;

                    this.style.transform = 'translateY(-3px) scale(1.1) rotate(360deg)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 300);

                    const newName = prompt('Masukkan nama baru:', currentName);
                    if (newName && newName.trim() !== '' && newName !== currentName) {
                        profileValue.textContent = newName.trim();
                        profileValue.style.background = 'linear-gradient(135deg, #daa520, #b8941c)';
                        profileValue.style.color = 'white';
                        setTimeout(() => {
                            profileValue.style.background = 'linear-gradient(135deg, #f8f4e6, #fff)';
                            profileValue.style.color = '#3a4d39';
                        }, 500);
                    }
                });

                // Add floating animation to profile card
                const profileCard = document.querySelector('.profile-card');
                let floatDirection = 1;
                setInterval(() => {
                    const currentTransform = profileCard.style.transform || 'rotate(-1deg)';
                    const rotation = floatDirection > 0 ? 'rotate(-0.5deg)' : 'rotate(-1.5deg)';
                    profileCard.style.transform = rotation;
                    floatDirection *= -1;
                }, 3000);

                // Animate stats on page load
                window.addEventListener('load', function() {
                    const statNumbers = document.querySelectorAll('.stat-number');
                    statNumbers.forEach((stat, index) => {
                        const finalValue = parseInt(stat.textContent);
                        stat.textContent = '0';
                        setTimeout(() => {
                            let current = 0;
                            const increment = finalValue / 20;
                            const timer = setInterval(() => {
                                current += increment;
                                if (current >= finalValue) {
                                    current = finalValue;
                                    clearInterval(timer);
                                }
                                stat.textContent = Math.floor(current);
                            }, 50);
                        }, index * 200);
                    });
                });
            </script>
</body>

</html>
