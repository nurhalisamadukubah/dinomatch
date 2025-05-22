<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Puzzle Prototype</title>
    @if ($gallery->dificulty == 'hard')
    <link rel="stylesheet" href="{{ asset('main/puzzle.css') }}">
    @elseif ($gallery->dificulty == 'medium')
    <link rel="stylesheet" href="{{ asset('main/3x3.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('main/2x2.css') }}">
    @endif
</head>

<body>
    <div class="main-container">
        <!-- HEADER -->
        <div class="header">
            <div>BABAK : <span id="round">1</span></div>
            <div>WAKTU : <span id="time">0:25</span></div>
            <div>SCORE : <span id="score">0</span></div>
        </div>
        
        <!-- Game Board -->
        <div class="container">
            <div id="board"></div>
            <div id="pieces"></div>
        </div>
    </div>

    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content">
            <h2 id="modal-title">Selamat!</h2>
            <p id="description">deskripsi</p>
            <button id="btn-finish">Tutup</button>
        </div>
    </div>

{{-- Winner --}}
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close-btn" id="closePopup">&times;</span>
        <div class="trophy-container">
            <div class="trophy">🏆</div>
        </div>
        <h1 id="modal-title">Congratulations!</h1>
        {{-- <h2 id="winnerDisplay">Menunggu hasil...</h2> --}}
        <p id="modal-description">Ronde berikutnya akan dimulai dalam <span id="winnerCountdown">5</span> detik...</p>
        <div id="confetti-container"></div>
    </div>
</div>

{{-- Loser --}}
<div id="losePopup" class="lose-popup">
    <div class="lose-popup-content">
        <span class="close-btn" id="closeLosePopup">&times;</span>
        <div class="broken-heart">
            <div id="smoke-container"></div>
            <div id="blood-container"></div>💔
        </div>
        <h1 style="color: #ff4444;" id="modal-title">Game Over!</h1>
        {{-- <h2 id="loserDisplay">Menunggu hasil...</h2> --}}
        <p id="modal-description">Ronde berikutnya akan dimulai dalam <span id="loserCountdown">5</span> detik...</p>
    </div>
</div>
    
    <footer>
        <script>
            const playerId = "{{ $player_id }}";
            const roomId = "{{ $room_id }}";
        </script>
        <script src="{{ asset('main/salsa20.js') }}"></script>
        @if ($gallery->dificulty == 'hard')
        <script src="{{ asset('main/game.js') }}"></script>
        @elseif ($gallery->dificulty == 'medium')
        <script src="{{ asset('main/3x3.js') }}"></script>
        @else
        <script src="{{ asset('main/2x2.js') }}"></script>
        @endif
    </footer>
</body>
</html>
