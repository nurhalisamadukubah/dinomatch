<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/mainMenu.css') }}">
    <title>Game Room</title>
</head>

<body>
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
        <button class="close" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
@endif

    <div id="scene-container"></div>

    <div class="menu">
        <nav class="nav-buttons">
            @if (session()->has('player'))
            <a href="{{ route('profile.index', ['id' => session('player.id')]) }}" style="text-decoration: none; color: black;"><button>Profil</button></a>
            <a href="{{ route('galleries.index', ['id' => session('player.id')]) }}" style="text-decoration: none; color: black;"><button>Galeri</button></a>
            @else
            <a href="{{ route('profile.index', ['id' => 0]) }}" style="text-decoration: none; color: black;"><button>Profil</button></a>
            <a href="{{ route('galleries.index', ['id' => 0]) }}" style="text-decoration: none; color: black;"><button>Galeri</button></a>
            @endif
            <a href="{{ route('tutorial.index') }}" style="text-decoration: none; color: black;"><button>Tutorial</button></a>
            <a href="{{ route('about.index') }}" style="text-decoration: none; color: black;"><button>Tentang</button></a>
        </nav>
        <h1 class="game-title">DINO PUZZLE</h1>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('gallery.index') }}"><button class="play-button">PLAY</button></a>
        <p style="color: black">atau</p>
        <form action="{{ route('room.join') }}" method="POST" id="form-join">
            @csrf
            <div class="join">
                <input type="text" name="code" placeholder="Masukkan passcode" required>
                @if (Session::has('user'))
                    <button class="join-button" type="submit" id="submitButton">Gabung dalam permainan</button>
                @else
                    <button class="join-button" onclick="openModal()">Gabung dalam permainan</button>

                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <h2>Gabung Room</h2>
                            <input type="text" name="username" placeholder="Nama Anda" required value="{{ session('player.username') }}">
                            <button type="submit" id="submitButton">Gabung</button>
                            <button class="btn-close" onclick="closeModal()">Tutup</button>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <script>
        document.getElementById('form-join').addEventListener('submit', function(event) {
            document.getElementById('submitButton').disabled = true;
        }, {
            once: true
        });

        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }
        // Tutup modal jika klik di luar area modal
        window.onclick = function(event) {
            let modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>
