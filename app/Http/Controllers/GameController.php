<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{
    // Menyimpan hasil game ke database
    public function store(Request $request)
    {
        if (Session::has('user')) {
            $gameResult = GameResult::create([
                'user_id' => $request->player_id,
                'room_id' => $request->room_id,
                'correct_pieces' => $request->correct_pieces,
                'time_taken' => $request->time_taken,
                'round' => $request->round,
                'score' => 0, // Default score 0, akan diupdate saat menentukan pemenang
            ]);
        } else {
            $gameResult = GameResult::create([
                'player_id' => $request->player_id,
                'room_id' => $request->room_id,
                'correct_pieces' => $request->correct_pieces,
                'time_taken' => $request->time_taken,
                'round' => $request->round,
                'score' => 0, // Default score 0, akan diupdate saat menentukan pemenang
            ]);
        }

        return response()->json(['message' => 'Game result saved', 'data' => $gameResult]);
    }

    // Membandingkan hasil dua pemain dan menentukan pemenang
    public function determineWinner($room_id, $round)
    {
        // Ambil hasil game untuk ronde tertentu
        $results = GameResult::with(['user', 'player'])
            ->where('room_id', $room_id)
            ->where('round', $round)
            ->get();

        if ($results->count() < 2) {
            return response()->json([
                'message' => 'Menunggu pemain lain',
                'pemenang' => $results->count()
            ]);
        }

        // Urutkan berdasarkan kriteria: correct_pieces DESC, time_taken ASC
        $sortedResults = $results->sortBy([
            fn($a, $b) => $b->correct_pieces - $a->correct_pieces, // Jumlah benar tertinggi
            fn($a, $b) => $a->time_taken - $b->time_taken // Waktu tercepat
        ]);

        $winner = $sortedResults->first();

        // Update score pemenang menjadi 1
        // $winner->update(['score' => 1]);

        if (Session::has('user')) {
            $this->updateUserLevel($winner->user_id);
        } else {
            $this->updateUserLevel($winner->player_id);
        }

        return response()->json([
            'message' => 'Pemenang ronde ' . $round . ' ditentukan',
            'winner' => $winner->user ? $winner->user->username : $winner->player->username,
            'totalWin' => $winner->user ? $winner->user->wins : $winner->player->wins,
            'id' => $winner->user ? $winner->user->id : $winner->player->id,
            'details' => [
                'correct_pieces' => $winner->correct_pieces,
                'time_taken' => $winner->time_taken,
                'player_type' => $winner->user_id ? 'Registered User' : 'Guest Player'
            ]
        ]);
    }

    public function checkAllPlayersFinished($room_id, $round)
    {
        // Hitung jumlah pemain yang sudah menyelesaikan ronde ini
        $finishedPlayers = GameResult::where('room_id', $room_id)->where('round', $round)->count();

        // Total pemain di room (misalnya, 2 pemain)
        $totalPlayers = 2; // Ganti dengan logika untuk mendapatkan total pemain di room

        if ($finishedPlayers >= $totalPlayers) {
            return response()->json(['all_finished' => true]);
        } else {
            return response()->json(['all_finished' => false]);
        }
    }

    public function updateUserLevel($userId)
    {
        try {
            // Cari user baik registered maupun guest
            $user = Session::has('user')
                ? User::find($userId)
                : Player::find($userId);

            if (!$user) {
                throw new \Exception("User tidak ditemukan");
            }

            // Increment wins instead of resetting to 1
            $user->update([
                'wins' => $user->wins + 1
            ]);

            // Naik level jika wins mencapai 2
            if ($user->wins >= 2) {
                $user->update([
                    'level' => $user->level + 1,
                    'wins' => 0
                ]);
            }

            return response()->json([
                'success' => true,
                'level' => $user->level,
                'wins' => $user->wins
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserData($userId)
    {
        try {
            // Check if it's a registered user or guest
            $user = Session::has('user')
                ? User::find($userId)
                : Player::find($userId);

            if (!$user) {
                throw new \Exception("User tidak ditemukan");
            }

            return response()->json([
                'success' => true,
                'level' => $user->level,
                'wins' => $user->wins
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Function untuk reset wins ketika game berakhir (ronde 3)
    public function resetWins($userId)
    {
        try {
            // Cari user baik registered maupun guest
            $user = Session::has('user')
                ? User::find($userId)
                : Player::find($userId);

            if (!$user) {
                throw new \Exception("User tidak ditemukan");
            }

            // Reset wins menjadi 0
            $user->update([
                'wins' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wins telah direset',
                'level' => $user->level,
                'wins' => $user->wins
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
