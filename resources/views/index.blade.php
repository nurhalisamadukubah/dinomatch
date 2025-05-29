<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;600;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/mainMenu.css') }}">
    <title>Game Room</title>
</head>

<body>
    {{-- Error Handling --}}
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
        <button class="close" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    {{-- Main Container --}}
    <div class="container">
        {{-- Header --}}
        <header>
            <div class="logo">
                <!-- <img src="/api/placeholder/70/70" alt="DinoMatch Logo"> -->
                <h1>DINOMATCH</h1>
            </div>
            <nav>
                <ul>
                    @if (Session::has('user'))
                        <li><a href="{{ route('profile.index') }}">Profile</a></li>
                    @else
                        <li><a href="{{ route('login.profile') }}">Profile</a></li>
                    @endif
                    <li><a href="{{ route('galleries.index', ['id' => session('player.id') ?? 0]) }}">Dinopedia</a></li>
                    <li><a href="{{ route('tutorial.index') }}">Cara Bermain</a></li>
                    <li><a href="{{ route('about.index') }}">Tentang</a></li>
                </ul>
            </nav>
        </header>

        {{-- Main Content --}}
        <main>
            {{-- Hero Section --}}
            <div class="hero">
                <h2>DINOMATCH</h2>
                <p>Susun dan Selesaikan Tantangan Puzzle Dinosaurus!</p>
            </div>

            {{-- Game Buttons --}}
            <div class="game-buttons">
                @if (Session::has('user'))
                    <a href="{{ route('gallery.index') }}">
                        <button class="play-btn">PLAY NOW!</button>
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        <button class="play-btn">PLAY NOW!</button>
                    </a>
                @endif
                {{-- Join Section --}}
                <div class="join-section">
                    <p>atau Bergabung Menggunakan Passcode Teman:</p>
                    @if (Session::has('user'))
                        <form action="{{ route('join.post') }}" method="POST" class="join-form">
                    @else
                        <form action="{{ route('login.join') }}" method="POST" class="join-form">
                    @endif
                        @csrf
                        <input type="text" name="code" class="passcode-input" placeholder="Masukkan Passcode" required>
                        <button type="submit" class="join-btn">GABUNG</button>
                    </form>
                </div>
            </div>

            {{-- Feature Cards --}}
            <div class="features">
                {{-- Repeat for each feature --}}
                <div class="feature-card">
                    <div class="feature-icon"><img src="/api/placeholder/45/45" alt="Icon"></div>
                    <h3 class="feature-title">Fun Puzzles</h3>
                    <p class="feature-text">Piece together amazing dinosaur fossils!</p>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer>
            <p>© 2025 DinoMatch - Uncovering Prehistoric Mysteries!</p>
        </footer>
    </div>

    {{-- Modal Handling --}}
    {{-- @if (!Session::has('user'))
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h2>Gabung Room</h2>
            <input type="text" name="username" placeholder="Nama Anda" required value="{{ session('player.username') }}">
            <button type="submit" form="form-join">Gabung</button>
            <button class="btn-close" onclick="closeModal()">Tutup</button>
        </div>
    </div>
    @endif --}}

    {{-- JavaScript --}}
    <script>
        // Keep existing JS functionality
        // document.getElementById('form-join')?.addEventListener('submit', function() {
        //     this.querySelector('button[type="submit"]').disabled = true;
        // });

        // function openModal() {
        //     document.getElementById('myModal').style.display = 'block';
        // }

        // function closeModal() {
        //     document.getElementById('myModal').style.display = 'none';
        // }

        // window.onclick = function(event) {
        //     const modal = document.getElementById('myModal');
        //     if (event.target === modal) closeModal();
        // }
    </script>
</body>

</html>
