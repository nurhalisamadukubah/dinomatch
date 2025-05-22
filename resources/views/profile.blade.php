<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Game Puzzle</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        /* Header */
        header {
            background-color: #6200ea;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 2rem;
        }

        /* Profil Pengguna */
        .profile {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }

        .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .profile h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .profile p {
            font-size: 1rem;
            color: #666;
        }

        .profile button {
            background-color: #6200ea;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .profile button:hover {
            background-color: #3700b3;
        }

        /* Riwayat Permainan */
        .game-history {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .game-history h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #6200ea;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <header>
        <h1>Profil Pengguna</h1>
    </header>

    <!-- Profil Pengguna -->
    <section class="profile">
        <img src="https://via.placeholder.com/100" alt="Avatar Pengguna">
        @if (session()->has('player'))
            <h2>{{ $player->player->username }}</h2>
            <p>Bergabung sejak:
                {{ \Carbon\Carbon::parse($player->player->created_at)->locale('id')->translatedFormat('l ,d F Y') }}</p>
        @else
            <h2>{{ $player->user->username }}</h2>
            <p>Bergabung sejak:
                {{ \Carbon\Carbon::parse($player->user->created_at)->locale('id')->translatedFormat('l, d F Y') }}</p>
        @endif
        <button onclick="editProfile()">Edit Profil</button>
    </section>

    <!-- Riwayat Permainan -->
    <section class="game-history">
        <h2>Riwayat Permainan</h2>
        <table>
            <thead>
                <tr>
                    <th>Menang</th>
                    <th>Babak</th>
                    <th>Kalah</th>
                    <th>Tingkat Kesulitan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultsPerRound as $round => $data)
                    @php
                        $winner = $data['winner']; // Ambil data pemenang
                        $losers = $data['losers']; // Ambil data yang kalah
                    @endphp
                    <tr>
                        <td>{{ $winner->player->username ?? $winner->user->username }}</td>
                        <td>Babak {{ $round }}</td>
                        <td>
                            @foreach ($losers as $loser)
                                <div>{{ $loser->player->username ?? $loser->user->username }}</div>
                            @endforeach
                        </td>
                        <td>{{ Str::ucfirst($winner->room->gallery->dificulty) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2023 Game Puzzle. Dibuat dengan ❤ oleh Tim Developer.</p>
    </footer>

    <script>
        // Fungsi untuk tombol Edit Profil
        function editProfile() {
            alert("Fitur Edit Profil akan segera hadir!");
            // window.location.href = "edit-profile.html"; // Uncomment untuk navigasi sebenarnya
        }
    </script>
</body>

</html>
