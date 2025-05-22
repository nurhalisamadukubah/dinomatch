<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ensiklopedia Dinosaurus</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        header p {
            margin: 0.5rem 0 0;
            font-size: 1.2rem;
        }

        main {
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Dinosaur List */
        #dino-list {
            display: grid;
            gap: 1.5rem;
        }

        .dino-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dino-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .dino-card h2 {
            margin: 0 0 1rem;
            font-size: 1.5rem;
            color: #16a34a;
        }

        .dino-card p {
            margin: 0 0 1.5rem;
            color: #666;
        }

        .btn-detail {
            background: #16a34a;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .btn-detail:hover {
            background: #15803d;
        }

        /* Dinosaur Detail */
        #dino-detail {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #dino-detail.hidden {
            display: none;
        }

        #detail-title {
            margin: 0 0 1rem;
            font-size: 2rem;
            color: #16a34a;
        }

        #detail-description {
            margin: 0 0 2rem;
            color: #666;
        }

        #btn-back {
            background: #666;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        #btn-back:hover {
            background: #555;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 1rem;
            background: #333;
            color: white;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <header>
        <h1>Ensiklopedia Dinosaurus</h1>
        <p>Jelajahi dunia dinosaurus yang menakjubkan!</p>
    </header>

    <main>
        <!-- Daftar Dinosaurus -->
        <section id="dino-list">
            <div class="dino-card" data-id="1">
                <h2>Tyrannosaurus Rex</h2>
                <p>Salah satu dinosaurus predator terbesar sepanjang sejarah.</p>
                <button class="btn-detail">Lihat Detail</button>
            </div>
            <div class="dino-card" data-id="2">
                <h2>Triceratops</h2>
                <p>Dinosaurus herbivora dengan tiga tanduk khas di kepalanya.</p>
                <button class="btn-detail">Lihat Detail</button>
            </div>
            <div class="dino-card" data-id="3">
                <h2>Velociraptor</h2>
                <p>Dinosaurus kecil yang cerdas dan sangat lincah.</p>
                <button class="btn-detail">Lihat Detail</button>
            </div>
        </section>

        <!-- Detail Dinosaurus -->
        <section id="dino-detail" class="hidden">
            <h2 id="detail-title"></h2>
            <p id="detail-description"></p>
            <button id="btn-back">Kembali ke Daftar</button>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Ensiklopedia Dinosaurus. All rights reserved.</p>
    </footer>

    <script>
        // Ambil elemen yang dibutuhkan
        const dinoList = document.getElementById("dino-list");
        const dinoDetail = document.getElementById("dino-detail");
        const detailTitle = document.getElementById("detail-title");
        const detailDescription = document.getElementById("detail-description");
        const btnBack = document.getElementById("btn-back");

        // Data ensiklopedia dinosaurus
        const dinosaurs = {
            1: {
                title: "Tyrannosaurus Rex",
                description: "Tyrannosaurus Rex adalah salah satu predator terbesar yang pernah hidup di Bumi. Panjang tubuhnya bisa mencapai 12 meter, dengan rahang yang sangat kuat untuk berburu mangsa."
            },
            2: {
                title: "Triceratops",
                description: "Triceratops adalah dinosaurus herbivora yang memiliki tiga tanduk di kepalanya. Dinosaurus ini hidup sekitar 68 juta tahun yang lalu di Amerika Utara."
            },
            3: {
                title: "Velociraptor",
                description: "Velociraptor adalah dinosaurus kecil yang terkenal karena kecepatannya dan kecerdasannya. Mereka berburu dalam kelompok dan memiliki cakar melengkung yang tajam."
            }
        };

        // Event listener untuk tombol detail
        document.querySelectorAll(".btn-detail").forEach(button => {
            button.addEventListener("click", () => {
                const id = button.closest(".dino-card").getAttribute("data-id");
                showDinoDetail(id);
            });
        });

        // Event listener untuk tombol kembali
        btnBack.addEventListener("click", () => {
            dinoDetail.classList.add("hidden");
            dinoList.classList.remove("hidden");
        });

        // Fungsi untuk menampilkan detail dinosaurus
        function showDinoDetail(id) {
            const dino = dinosaurs[id];
            if (dino) {
                detailTitle.textContent = dino.title;
                detailDescription.textContent = dino.description;
                dinoList.classList.add("hidden");
                dinoDetail.classList.remove("hidden");
            }
        }
    </script>
</body>

</html>
