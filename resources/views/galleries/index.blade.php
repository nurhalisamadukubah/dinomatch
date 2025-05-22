<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/gallery.css') }}">
    <title>Galeri Dinosaurus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .gallery {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            border: 2px solid black;
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }

        .card img {
            width: 100px;
            height: 100px;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 2px solid black;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .popup img {
            width: 200px;
            height: 200px;
        }

        .close {
            cursor: pointer;
            color: red;
            font-size: 18px;
        }

        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            border-radius: 5px;
            position: relative;
            width: 300px;
            text-align: center;
        }

        .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: black;
        }
    </style>
</head>

<body>
    <h1>GALERI</h1>
    @if (Route::currentRouteName() === 'galleries.show')
    <a href="{{ route('galleries.create') }}" class="btn btn-primary">Buat Galeri Baru</a>
    @endif
    <div class="gallery">
        @foreach ($galleries as $gallery)
            <a href="{{ route('galleries.detail', $gallery->id) }}" style="text-decoration: none; color:black;"><div class="card">
                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->name }}">
                <p>{{ $gallery->name }}</p>
            </div></a>
        @endforeach
        
        </div>
        <div id="popup" class="popup">
            <span class="close" onclick="hidePopup()">&times;</span>
            <h2 id="popup-title"></h2>
            <img id="popup-image" src="" alt="">
            <p id="popup-desc"></p>
        </div>
        <script>
            function showPopup(title, description, image) {
                document.getElementById('popup-title').textContent = title;
                document.getElementById('popup-desc').textContent = description;
                document.getElementById('popup-image').src = image;
                document.getElementById('popup').style.display = 'block';
            }

            function hidePopup() {
                document.getElementById('popup').style.display = 'none';
            }
        </script>
    </body>

    </html>
