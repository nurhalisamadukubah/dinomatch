<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;600;700&family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
    <title>Tentang</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('room.index') }}" style="text-decoration: none;">
                <button class="back-button">←</button>
            </a>
            <h1 class="page-title">TENTANG</h1>
        </div>

        <div class="main-content">
            <!-- Hero Section -->
            <div class="hero-section">
                <div class="game-logo">DINOMATCH</div>
                <div class="game-subtitle">Tantangan Puzzle Menantimu!</div>
                <div class="game-description">
                    Mulailah eksplorasi seru menyusun puzzle dan ungkap fakta-fakta
                    menarik tentang dinosaurus — hewan dari masa lalu yang selalu seru
                    untuk dikenali! DinoMatch menghadirkan perpaduan unik antara bermain
                    dan belajar, dengan tema eksplorasi dinosaurus yang dikemas dalam
                    gameplay kompetitif. Cocokkan kepingan, tantang teman, dan kumpulkan
                    berbagai informasi seru tentang beragam spesies dinosaurus dalam
                    pengalaman yang edukatif dan penuh tantangan!
                </div>
            </div>

            <!-- Game Features -->
            <div class="content-section">
                <h2 class="section-title">🎮 GAME FEATURES</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <span class="feature-icon">🧩</span>
                        <div class="feature-title">Selesaikan Puzzle</div>
                        <div class="feature-description">
                            Susun kepingan puzzle acak yang diacak dengan algoritma Salsa20.
                            Uji kemampuanmu dan taklukkan setiap tingkatan puzzle!
                        </div>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">⚔️</span>
                        <div class="feature-title">Pertandingan Seru</div>
                        <div class="feature-description">
                            Ajak temanmu bertanding! Tantang mereka untuk menyelesaikan
                            puzzle lebih cepat dan raih hadiah kemenangan!.
                        </div>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">⏱️</span>
                        <div class="feature-title">Time Attack</div>
                        <div class="feature-description">
                            Berpacu dengan waktu! Tunjukkan kecepatan dan ketepatanmu dalam
                            menyusun puzzle sebelum waktu habis.
                        </div>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">🦖</span>
                        <div class="feature-title">Kumpulkan Dinosaurus</div>
                        <div class="feature-description">
                            Temukan dan kumpulkan 10 spesies dinosaurus unik yang
                            masing-masing dengan karakteristik dan kemampuan spesial!
                        </div>
                    </div>
                </div>
            </div>

            <!-- About the Game -->
            <div class="content-section">
                <h2 class="section-title">🌍 THE STORY</h2>
                <div class="section-content">
                    <p>
                        Selamat datang di DinoMatch, sebuah permainan seru di mana
                        menyusun puzzle bukan hanya menyenangkan, tapi juga mengungkap
                        berbagai fakta menarik tentang dinosaurus — hewan purba yang
                        selalu seru untuk dikenali! Dalam game ini, kamu akan menyusun
                        potongan-potongan puzzle untuk membuka informasi unik tentang
                        dinosaurus.
                    </p>
                    <p>
                        Bangun pengetahuanmu tentang beragam spesies dinosaurus dengan
                        tantangan 10 puzzle seru yang harus kamu selesaikan. Setiap
                        tantangan membawa kamu lebih dekat untuk mengenal dinosaurus
                        dengan berbagai keunikan, mulai dari yang paling terkenal hingga
                        yang jarang diketahui!
                    </p>
                    <p>
                        Dengan konsep bermain sambil belajar, DinoMatch menggabungkan
                        keseruan mencocokkan puzzle dengan kompetisi yang seru. Kamu bisa
                        bertanding dengan teman, mengasah kecepatan dan ketepatan, serta
                        mengumpulkan informasi seru dari setiap spesies yang kamu temukan.
                    </p>
                    <p>
                        Ayo ajak temanmu, selesaikan semua tantangan, mulai permainan
                        edukatifmu di DinoMatch!
                    </p>
                </div>
            </div>

            <!-- Developer Section -->
            <div class="content-section developer-section">
                <div class="developer-avatar">👩‍💻</div>
                <div class="developer-info">
                    <h2 class="section-title">💡 MEET THE GAME MASTER</h2>
                    <div class="developer-name">Nurhalisa Madukubah</div>
                    <div class="developer-role">E1E1 19 035</div>
                    <div class="section-content">
                        <p>
                            "Pada dasarnya, DinoMatch yang merupakan permainan puzzle
                            berbasis website yang mengkolaborasikan tema dinosaurus dan
                            mekanisme permainan puzzle ini dirancang dan dibangun karena
                            saya ingin lulus 🙏"
                        </p>
                    </div>
                </div>
            </div>

            <!-- Credits -->
            <div class="content-section credits-section">
                <h2 class="section-title">🐎 EXPLORATION CREDITS</h2>
                <div class="credits-list">
                    <div class="credit-item">
                        <div class="credit-role">💵 Grant Fund Foundation 👑</div>
                        <div class="credit-name">Kakalan Bapaknya Shaquille</div>
                    </div>
                    <div class="credit-item">
                        <div class="credit-role">💻 BE Developer</div>
                        <div class="credit-name">Rex Leo, C.Gpt</div>
                    </div>
                    <div class="credit-item">
                        <div class="credit-role">🎨 Game Designer</div>
                        <div class="credit-name">Claude Sonnet, M.Ai</div>
                    </div>
                    <div class="credit-item">
                        <div class="credit-role">🖌️ Art Director</div>
                        <div class="credit-name">Google, S.Eng</div>
                    </div>
                    <div class="credit-item">
                        <div class="credit-role">🔊 Sound Engineer</div>
                        <div class="credit-name">Pixabay de Khinsider, D.Load</div>
                    </div>
                    <div class="credit-item">
                        <div class="credit-role">🦖 Paleontology Consultant</div>
                        <div class="credit-name">
                            Natural History Museum and Jurassic World Evolution Wiki
                        </div>
                    </div>
                </div>

                <div class="section-content" style="margin-top: 30px; text-align: center">
                    <p>
                        <strong>Special Thanks: </strong>Para Hamba Allah yang Mendo'akan Saya.
                    </p>
                    <p style="margin-top: 20px; font-style: italic">
                        <strong>"Sesungguhnya Kemampuan Manusia adalah Kehendak Allah." </strong>
                        - Nur, Mahasiswi yang hanya ingin lulus.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Feature cards hover effects
        document.querySelectorAll('.feature-card').forEach(card => {
            const icon = card.querySelector('.feature-icon');
            
            card.addEventListener('mouseenter', function() {
                icon.style.transform = 'scale(1.2) rotate(10deg)';
            });

            card.addEventListener('mouseleave', function() {
                icon.style.transform = 'scale(1) rotate(0deg)';
            });
        });

        // Developer avatar click effect
        document.querySelector('.developer-avatar').addEventListener('click', function() {
            this.style.transform = 'scale(1.1) rotate(360deg)';
            setTimeout(() => {
                this.style.transform = 'scale(1) rotate(0deg)';
            }, 600);
        });

        // Credits animation on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const creditItems = entry.target.querySelectorAll('.credit-item');
                    creditItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.transform = 'translateY(0) scale(1)';
                            item.style.opacity = '1';
                        }, index * 100);
                    });
                }
            });
        }, {
            threshold: 0.3,
            rootMargin: '0px 0px -50px 0px'
        });

        // Initialize credit items as hidden
        document.querySelectorAll('.credit-item').forEach(item => {
            item.style.transform = 'translateY(30px) scale(0.8)';
            item.style.opacity = '0';
        });

        // Observe credits section
        const creditsSection = document.querySelector('.credits-section');
        if (creditsSection) {
            observer.observe(creditsSection);
        }
    </script>
</body>
</html>