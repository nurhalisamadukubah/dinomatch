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
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|integer',
            'correct_pieces' => 'required|integer',
            'time_taken' => 'required|numeric',
            'round' => 'required|integer',
        ]);

        // Simpan data dengan user_id
        $gameResult = GameResult::create($validated);
        
        return response()->json(['message' => 'Game result saved', 'data' => $gameResult], 201);
    }

    // Membandingkan hasil dua pemain dan menentukan pemenang
    public function determineWinner($room_id, $round)
    {
        $results = GameResult::with('user')
            ->where('room_id', $room_id)
            ->where('round', $round)
            ->get();

        if ($results->count() < 2) {
            return response()->json([
                'message' => 'Menunggu pemain lain',
                'pemenang' => $results->count()
            ]);
        }

        // Urutkan berdasarkan correct_pieces dan time_taken
        $sortedResults = $results->sortBy([
            fn($a, $b) => $b->correct_pieces - $a->correct_pieces,
            fn($a, $b) => $a->time_taken - $b->time_taken
        ]);

        $winner = $sortedResults->first();

        $this->updateUserLevel($winner->user_id);

        return response()->json([
            'message' => 'Pemenang ronde ' . $round . ' ditentukan',
            'winner' => $winner->user->username,
            'totalWin' => $winner->user->wins,
            'id' => $winner->user->id,
            'pemenang' => 1, // Tambahkan ini untuk konsistensi dengan frontend
            'details' => [
                'correct_pieces' => $winner->correct_pieces,
                'time_taken' => $winner->time_taken,
                'player_type' => 'Registered User'
            ]
        ]);
    }

    // Method baru untuk mendeteksi match winner berdasarkan round wins
    public function getMatchWinner($room_id)
    {
        try {
            // Get semua hasil game untuk room ini, urutkan berdasarkan round
            $allResults = GameResult::with('user')
                ->where('room_id', $room_id)
                ->orderBy('round', 'asc')
                ->get();

            if ($allResults->isEmpty()) {
                return response()->json([
                    'match_finished' => false,
                    'winner_id' => null,
                    'message' => 'Belum ada hasil game'
                ]);
            }

            // Group hasil berdasarkan round
            $roundResults = $allResults->groupBy('round');
            
            // Hitung kemenangan per user
            $userWins = [];
            
            foreach ($roundResults as $round => $results) {
                if ($results->count() >= 2) {
                    // Tentukan pemenang round ini
                    $sortedResults = $results->sortBy([
                        fn($a, $b) => $b->correct_pieces - $a->correct_pieces,
                        fn($a, $b) => $a->time_taken - $b->time_taken
                    ]);
                    
                    $roundWinner = $sortedResults->first();
                    $userId = $roundWinner->user_id;
                    
                    if (!isset($userWins[$userId])) {
                        $userWins[$userId] = 0;
                    }
                    $userWins[$userId]++;
                    
                    // Cek apakah ada yang sudah menang 2 round (match winner)
                    if ($userWins[$userId] >= 2) {
                        return response()->json([
                            'match_finished' => true,
                            'winner_id' => $userId,
                            'winner_name' => $roundWinner->user->username,
                            'total_round_wins' => $userWins[$userId],
                            'message' => 'Match selesai'
                        ]);
                    }
                }
            }

            // Cek apakah sudah ada 3 round yang selesai
            $completedRounds = count($roundResults->filter(function($results) {
                return $results->count() >= 2;
            }));

            if ($completedRounds >= 3) {
                // Tentukan pemenang berdasarkan total kemenangan round
                $maxWins = max($userWins);
                $matchWinnerId = array_search($maxWins, $userWins);
                
                $matchWinner = User::find($matchWinnerId);
                
                return response()->json([
                    'match_finished' => true,
                    'winner_id' => $matchWinnerId,
                    'winner_name' => $matchWinner ? $matchWinner->username : 'Unknown',
                    'total_round_wins' => $maxWins,
                    'message' => 'Match selesai setelah 3 round'
                ]);
            }

            return response()->json([
                'match_finished' => false,
                'winner_id' => null,
                'current_round_wins' => $userWins,
                'completed_rounds' => $completedRounds,
                'message' => 'Match masih berlangsung'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'match_finished' => false,
                'winner_id' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Method untuk menyimpan hasil match (menggunakan tabel GameResult dengan flag khusus)
    public function saveMatchResult(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required',
                'room_id' => 'required|integer',
                'is_winner' => 'required|boolean',
                'match_type' => 'required|string',
                'round' => 'required|integer',
                'total_wins' => 'required|integer',
                'total_losses' => 'required|integer'
            ]);

            // Simpan dengan correct_pieces sebagai flag untuk match result
            // correct_pieces = 999 menandakan ini adalah match result, bukan round result
            $matchResult = GameResult::create([
                'user_id' => $validated['user_id'],
                'room_id' => $validated['room_id'],
                'correct_pieces' => $validated['is_winner'] ? 999 : 998, // 999 = match winner, 998 = match loser
                'time_taken' => $validated['total_wins'], // Gunakan time_taken untuk menyimpan total wins
                'round' => $validated['round'] + 100 // Tambah 100 untuk membedakan dengan round biasa
            ]);

            return response()->json([
                'success' => true,
                'data' => $matchResult,
                'message' => 'Match result saved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkAllPlayersFinished($room_id, $round)
    {
        // Hitung jumlah pemain yang sudah menyelesaikan ronde ini
        $finishedPlayers = GameResult::where('room_id', $room_id)
            ->where('round', $round)
            ->where('correct_pieces', '<', 900) // Exclude match results
            ->count();

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
            $user = User::find($userId);
            if (!$user) throw new \Exception("User tidak ditemukan");

            $newWins = $user->wins + 1;

            // Update wins
            $user->update([
                'wins' => $newWins
            ]);

            // Cek apakah sudah mencapai target wins untuk naik level
            if ($newWins >= 2) {
                $user->update([
                    'level' => $user->level + 1,
                    'wins' => 0
                ]);
            }

            return response()->json([
                'success' => true,
                'level' => $user->fresh()->level,
                'wins' => $user->fresh()->wins
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
            $user = User::find($userId);
            if (!$user) throw new \Exception("User tidak ditemukan");

            $user->update(['wins' => 0]);
            return response()->json(['success' => true, 'message' => 'Wins direset', 'level' => $user->level, 'wins' => $user->wins]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Method untuk mendapatkan pemenang round (untuk backward compatibility)
    public function getWinner($room_id, $round)
    {
        return $this->determineWinner($room_id, $round);
    }
}