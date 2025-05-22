<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Puzzle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .header a {
            text-decoration: none;
            font-size: 1.5rem;
            color: #000;
        }

        .header h1 {
            flex: 1;
            text-align: center;
            font-size: 1.5rem;
            margin: 0;
            color: #000;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        .stars {
            display: flex;
            justify-content: center;
            gap: 2px;
        }

        .stars span {
            font-size: 1.2rem;
            color: #ffa500;
        }

        .stars span.inactive {
            color: #ddd;
        }

        /* Modal Styles */
        .modal {
            display: none;
            /* Awalnya disembunyikan */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            margin: 15% auto;
            text-align: center;
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
            /* Animasi muncul */
        }

        /* Animasi Muncul */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Input */
        .modal-content input[type="text"] {
            width: 90%;
            padding: 10px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Tombol Submit */
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        /* Tombol Tutup */
        .btn-close {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }

        .btn-close:hover {
            background-color: #d32f2f;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('room.index') }}">&#x2190;</a>
            <h1>Pilih Puzzle</h1>
            <span></span>
        </div>
        <div class="grid">
            @foreach ($galleries as $gallery)
                <div class="card" onclick="openModal({{ $gallery->id }})">
                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->name }}">
                    <div class="stars">
                        @if ($gallery->dificulty == 'easy')
                            <span>&#9733;</span>
                            <span>&#9734;</span>
                            <span>&#9734;</span>
                        @elseif ($gallery->dificulty == 'medium')
                            <span>&#9733;</span>
                            <span>&#9733;</span>
                            <span>&#9734;</span>
                        @else
                            <span>&#9733;</span>
                            <span>&#9733;</span>
                            <span>&#9733;</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <form action="{{ route('room.create') }}" method="POST">
                @csrf
                <h2>Buat Room</h2>
                @if (Session::has('user'))
                    <input type="hidden" name="galleryId" id="galleryId">
                    <button type="submit" class="btn-submit">Buat Room</button>
                @else
                    <input type="hidden" name="galleryId" id="galleryId">
                    <input type="text" name="username" placeholder="Nama Anda" required
                        value="{{ session('player.username') }}">
                    <button type="submit" class="btn-submit">Buat Room</button>
                @endif
            </form>
            <button class="btn-close" onclick="closeModal()">Tutup</button>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('galleryId').value = id;
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
