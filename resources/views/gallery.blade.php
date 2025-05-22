<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;600;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <title>DinoMatch - Discover Prehistoric Puzzles!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Chalkboard SE', 'Bubblegum Sans', sans-serif;
        }
        
        body {
            background-color: #e6d2b5;
            background-image: url('/api/placeholder/1920/1080');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
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
            margin-bottom: 20px;
            position: relative;
        }
        
        .back-button {
            background-color: #734f30;
            color: #fff;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
            text-decoration: none;
        }
        
        .back-button::before {
            content: "";
            position: absolute;
            width: 70px;
            height: 70px;
            background: url('/api/placeholder/70/70') no-repeat center;
            background-size: contain;
            opacity: 0.2;
            z-index: -1;
        }
        
        .back-button:hover {
            background-color: #8b5a2b;
            transform: scale(1.1);
        }
        
        .page-title {
            flex-grow: 1;
            text-align: center;
            font-size: 42px;
            font-weight: bold;
            color: #3a4d39;
            text-shadow: 3px 3px 0 #fff, -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 15px 25px;
            border-radius: 20px;
            border: 4px dashed #734f30;
            position: relative;
            overflow: hidden;
        }
        
        .page-title::after {
            content: "";
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 80px;
            height: 80px;
            background: url('/api/placeholder/80/80') no-repeat center;
            background-size: contain;
            transform: rotate(15deg);
            opacity: 0.2;
        }
        
        /* Category filter */
        .category-filter {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .category-button {
            background-color: #3a4d39;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            border: 2px solid #fff;
        }
        
        .category-button:hover,
        .category-button.active {
            background-color: #daa520;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        
        /* Puzzle grid */
        .puzzle-grid {
            display: grid;
            grid-template-columns: repeat(4, 280px);
            grid-template-rows: repeat(3, 320px);
            gap: 25px;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: auto;
            overflow-y: auto;
            max-height: calc(100vh - 200px);
            width: 100%;
        }
        
        .puzzle-card {
            background-color: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 4px solid #734f30;
            position: relative;
            transform-style: preserve-3d;
            width: 280px;
            height: 320px;
        }
        
        .puzzle-card:hover {
            transform: translateY(-15px) rotateY(5deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
            border-color: #daa520;
        }
        
        .puzzle-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 50%);
            z-index: 2;
            pointer-events: none;
        }
        
        .puzzle-image {
            width: 100%;
            height: 70%;
            overflow: hidden;
            position: relative;
            border-bottom: 4px solid #734f30;
            background-color: #f8f4e6;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .puzzle-image img {
            width: 85%;
            height: 85%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }
        
        .puzzle-card:hover .puzzle-image img {
            transform: scale(1.1);
        }
        
        .puzzle-stars {
            height: 30%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            background-color: #f8f4e6;
            padding: 10px;
            position: relative;
        }
        
        .puzzle-stars::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/api/placeholder/100/50') no-repeat center;
            background-size: 50%;
            opacity: 0.1;
        }
        
        .star {
            font-size: 32px;
            color: #daa520;
            filter: drop-shadow(0 2px 3px rgba(0,0,0,0.2));
            transition: all 0.3s ease;
        }
        
        .star.inactive {
            color: #ccc;
        }
        
        /* Difficulty indicator */
        .difficulty {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #3a4d39;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 20px;
            z-index: 3;
            box-shadow: 0 3px 6px rgba(0,0,0,0.3);
            border: 2px solid #fff;
            transition: all 0.3s ease;
        }
        
        .puzzle-card:hover .difficulty {
            background-color: #daa520;
        }
        
        /* Dino facts */
        .dino-fact {
            position: absolute;
            bottom: 10px;
            left: 10px;
            font-size: 12px;
            color: #3a4d39;
            max-width: 90%;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-style: italic;
            text-shadow: 1px 1px 0 #fff;
        }
        
        .puzzle-card:hover .dino-fact {
            opacity: 1;
        }
        
        /* Fossil decorations */
        .fossil-decoration {
            position: absolute;
            z-index: 0;
            opacity: 0.15;
            pointer-events: none;
        }
        
        .fossil-1 {
            top: 10%;
            left: 5%;
            width: 200px;
            height: 200px;
            transform: rotate(-15deg);
        }
        
        .fossil-2 {
            bottom: 10%;
            right: 5%;
            width: 250px;
            height: 200px;
            transform: rotate(30deg);
        }
        
        .fossil-3 {
            top: 40%;
            right: 15%;
            width: 150px;
            height: 150px;
            transform: rotate(-10deg);
        }
        
        .fossil-4 {
            bottom: 30%;
            left: 15%;
            width: 180px;
            height: 180px;
            transform: rotate(20deg);
        }
        
        /* Scrollbar styling for the grid */
        .puzzle-grid::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }
        
        .puzzle-grid::-webkit-scrollbar-track {
            background: rgba(115, 79, 48, 0.2);
            border-radius: 10px;
        }
        
        .puzzle-grid::-webkit-scrollbar-thumb {
            background: #734f30;
            border-radius: 10px;
            border: 2px solid rgba(115, 79, 48, 0.2);
        }
        
        .puzzle-grid::-webkit-scrollbar-thumb:hover {
            background: #8b5a2b;
        }
        
        /* Grid container wrapper */
        .grid-container {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            border: 3px dashed #734f30;
            padding: 10px;
            backdrop-filter: blur(5px);
            box-shadow: inset 0 0 20px rgba(115, 79, 48, 0.1);
        }
        .footprint {
            position: absolute;
            width: 40px;
            height: 60px;
            background: url('/api/placeholder/40/60') no-repeat center;
            background-size: contain;
            opacity: 0.1;
            z-index: 0;
        }
        
        .footprint:nth-child(1) { top: 10%; left: 20%; transform: rotate(20deg); }
        .footprint:nth-child(2) { top: 15%; left: 25%; transform: rotate(20deg); }
        .footprint:nth-child(3) { top: 20%; left: 30%; transform: rotate(20deg); }
        .footprint:nth-child(4) { top: 25%; left: 35%; transform: rotate(20deg); }
        .footprint:nth-child(5) { top: 30%; left: 40%; transform: rotate(20deg); }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            margin: 10% auto;
            text-align: center;
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
            border: 4px solid #734f30;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .modal-content h2 {
            color: #3a4d39;
            font-size: 28px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 0 #fff;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-content input[type="text"] {
            width: 90%;
            padding: 15px;
            margin: 15px 0;
            border: 3px solid #734f30;
            border-radius: 15px;
            font-size: 18px;
            background-color: #f8f4e6;
            color: #3a4d39;
            font-weight: bold;
        }

        .modal-content input[type="text"]:focus {
            outline: none;
            border-color: #daa520;
            box-shadow: 0 0 10px rgba(218, 165, 32, 0.3);
        }

        .btn-submit {
            background-color: #3a4d39;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-submit:hover {
            background-color: #daa520;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-close {
            background-color: #734f30;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-close:hover {
            background-color: #8b5a2b;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
        
        /* Media Queries */
        @media (max-width: 1400px) {
            .puzzle-grid {
                grid-template-columns: repeat(3, 280px);
                grid-template-rows: repeat(4, 320px);
            }
        }
        
        @media (max-width: 1024px) {
            .puzzle-grid {
                grid-template-columns: repeat(2, 280px);
                grid-template-rows: repeat(6, 320px);
            }
            
            .fossil-decoration {
                opacity: 0.1;
            }
            
            .page-title {
                font-size: 32px;
            }
        }
        
        @media (max-width: 768px) {
            .puzzle-grid {
                grid-template-columns: repeat(2, 250px);
                grid-template-rows: repeat(6, 300px);
                gap: 15px;
            }
            
            .puzzle-card {
                width: 250px;
                height: 300px;
            }
            
            .category-filter {
                flex-wrap: wrap;
            }
            
            .page-title {
                font-size: 28px;
                padding: 10px 15px;
            }
            
            .back-button {
                width: 50px;
                height: 50px;
                font-size: 22px;
            }
            
            .star {
                font-size: 26px;
            }
            
            .difficulty {
                font-size: 14px;
                padding: 5px 10px;
            }
        }
        
        @media (max-width: 600px) {
            .puzzle-grid {
                grid-template-columns: 220px;
                grid-template-rows: repeat(12, 280px);
                gap: 15px;
                justify-content: center;
            }
            
            .puzzle-card {
                width: 220px;
                height: 280px;
            }
            
            .page-title {
                font-size: 24px;
            }
            
            .fossil-decoration {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Background decorations -->
        <img src="/api/placeholder/200/200" alt="Fossil decoration" class="fossil-decoration fossil-1">
        <img src="/api/placeholder/250/200" alt="Fossil decoration" class="fossil-decoration fossil-2">
        <img src="/api/placeholder/150/150" alt="Fossil decoration" class="fossil-decoration fossil-3">
        <img src="/api/placeholder/180/180" alt="Fossil decoration" class="fossil-decoration fossil-4">
        
        <div class="footprint"></div>
        <div class="footprint"></div>
        <div class="footprint"></div>
        <div class="footprint"></div>
        <div class="footprint"></div>
        
        <div class="header">
            <a href="{{ route('room.index') }}" class="back-button">←</a>
            <h1 class="page-title">PILIH PUZZLE DINO!</h1>
        </div>
        
        <div class="category-filter">
            <button class="category-button active" onclick="filterPuzzles('all')">SEMUA</button>
            <button class="category-button" onclick="filterPuzzles('easy')">MUDAH</button>
            <button class="category-button" onclick="filterPuzzles('medium')">SEDANG</button>
            <button class="category-button" onclick="filterPuzzles('hard')">SULIT</button>
        </div>
        
        <div class="grid-container">
            <div class="puzzle-grid">
                @foreach ($galleries as $gallery)
                    <div class="puzzle-card" data-difficulty="{{ $gallery->dificulty }}" onclick="openModal({{ $gallery->id }})">
                        <div class="puzzle-image">
                            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->name }}">
                            <div class="difficulty">
                                @if($gallery->dificulty == 'easy')
                                    Mudah
                                @elseif($gallery->dificulty == 'medium')
                                    Sedang
                                @else
                                    Sulit
                                @endif
                            </div>
                        </div>
                        <div class="puzzle-stars">
                            @if ($gallery->dificulty == 'easy')
                                <span class="star">★</span>
                                <span class="star inactive">☆</span>
                                <span class="star inactive">☆</span>
                            @elseif ($gallery->dificulty == 'medium')
                                <span class="star">★</span>
                                <span class="star">★</span>
                                <span class="star inactive">☆</span>
                            @else
                                <span class="star">★</span>
                                <span class="star">★</span>
                                <span class="star">★</span>
                            @endif
                            <div class="dino-fact">{{ $gallery->name }} - Siap untuk petualangan!</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <form action="{{ route('room.create') }}" method="POST">
                @csrf
                <h2>🦕 Buat Room Dino! 🦖</h2>
                @if (Session::has('user'))
                    <input type="hidden" name="galleryId" id="galleryId">
                    <button type="submit" class="btn-submit">🎮 Mulai Petualangan!</button>
                @else
                    <input type="hidden" name="galleryId" id="galleryId">
                    <input type="text" name="username" placeholder="Siapa nama penjelajah dino?" required
                        value="{{ session('player.username') }}">
                    <button type="submit" class="btn-submit">🎮 Mulai Petualangan!</button>
                @endif
            </form>
            <button class="btn-close" onclick="closeModal()">❌ Tutup</button>
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

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Filter puzzles by difficulty
        function filterPuzzles(difficulty) {
            const cards = document.querySelectorAll('.puzzle-card');
            const buttons = document.querySelectorAll('.category-button');
            
            // Remove active class from all buttons
            buttons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            cards.forEach(card => {
                if (difficulty === 'all' || card.dataset.difficulty === difficulty) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.puzzle-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.animationPlayState = 'paused';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.animationPlayState = 'running';
                });
            });
        });
    </script>
</body>
</html>