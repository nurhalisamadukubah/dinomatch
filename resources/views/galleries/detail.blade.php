<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Dinosaurus - Tyrannosaurus Rex</title>
  <style>
    /* Reset CSS */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f4;
      color: #333;
      line-height: 1.6;
    }

    header {
      background-color: #4CAF50;
      color: white;
      padding: 20px;
      text-align: center;
    }

    header h1 {
      font-size: 2.5rem;
    }

    .back-button {
      background-color: #ff9800;
      color: white;
      border: none;
      padding: 10px 20px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 10px;
    }

    .back-button:hover {
      background-color: #e68900;
    }

    .hero {
      text-align: center;
      padding: 20px;
      background-color: #fff;
      margin: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .hero img {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
    }

    .hero h2 {
      font-size: 2rem;
      margin: 10px 0;
    }

    .sound-button {
      background-color: #2196F3;
      color: white;
      border: none;
      padding: 10px 20px;
      font-size: 1rem;
      cursor: pointer;
      border-radius: 5px;
    }

    .sound-button:hover {
      background-color: #1976D2;
    }

    .info {
      padding: 20px;
      background-color: #fff;
      margin: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .info p {
      font-size: 1.2rem;
      margin-bottom: 20px;
    }

    .facts {
      list-style: none;
    }

    .facts li {
      font-size: 1.1rem;
      margin: 10px 0;
    }

    .facts li::before {
      content: "🦖";
      margin-right: 10px;
    }

    .gallery {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      padding: 20px;
      background-color: #fff;
      margin: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .gallery img {
      max-width: 45%;
      height: auto;
      border-radius: 10px;
      margin: 10px 0;
    }

    footer {
      text-align: center;
      padding: 20px;
      background-color: #4CAF50;
      color: white;
      margin-top: 20px;
    }

    footer a {
      color: #ff9800;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <header>
    <h1>Mengenal {{ $gallery->name }}</h1>
  </header>

  <section class="hero">
    <img src="{{ asset('storage/'.$gallery->image) }}" alt="Tyrannosaurus Rex">
    <h2>{{ $gallery->name }}</h2>
    <button class="sound-button" onclick="playSound()">🔊 Putar Suara</button>
  </section>

  <section class="info">
    <p>{{ $gallery->description }}</p>
    <ul class="facts">
      <li>📏 Tinggi: 5 meter</li>
      <li>🍖 Makanan: Karnivora</li>
      <li>🌍 Habitat: Hutan dan dataran rendah</li>
    </ul>
  </section>

  <section class="gallery">
    <img src="https://via.placeholder.com/300x200" alt="T-Rex 1">
    <img src="https://via.placeholder.com/300x200" alt="T-Rex 2">
  </section>

  <footer>
    <p>© 2023 DinoWorld. Dibuat dengan ❤ oleh <a href="#">Tim DinoWorld</a>.</p>
  </footer>

  <script>
    // Fungsi untuk tombol suara
    function playSound() {
      const audio = new Audio("https://www.soundjay.com/button/sounds/button-3.mp3"); // Ganti dengan suara dinosaurus
      audio.play();
    }
  </script>
</body>
</html>