<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GameResult;
use App\Models\Player;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    // Membuat room
    public function createRoom(Request $request)
    {
        // Jika tidak ada session player, buat room baru dan player baru (untuk guest)
        $code = strtoupper(Str::random(6));
        $room = Room::create(['code' => $code, 'gallery_id' => $request->galleryId]);

        // Jika user terdaftar, gunakan data user
        if ($request->session()->has('user')) {
            $userId = $request->session()->get('user')->id;
            $user = User::find($userId);

            // Update user untuk bergabung ke room baru
            $user->update([
                'room_id' => $room->id,
                'level' => 0
            ]);

            // Mulai countdown jika room penuh
            $total = $room->players()->count() + $room->users()->count();
            if ($total >= 2) {
                $room->update(['start_time' => now()->addSeconds(10)]);
            }

            // Set session player untuk user terdaftar
            session(['player' => [
                'id' => $user->id,
                'username' => $user->username
            ]]);

            return redirect()->route('room.show', [
                'code' => $room->code,
                'username' => $user->username,
                'player_id' => $user->id,
                'gallery_id' => $request->galleryId
            ]);
        }
    }

    // Bergabung ke room
    public function joinRoom(Request $request)
    {
        $room = Room::where('code', $request->code)->first();

        // Validasi berbeda untuk user terdaftar vs guest
        if (Session::has('user')) {
            $request->validate(['code' => 'required|string|exists:rooms,code']);
            $username = Session::get('user')->username;
        } else {
            $request->validate([
                'code' => 'required|string|exists:rooms,code',
                'username' => 'required|string|max:255',
            ]);
            $username = $request->username;
        }

        // Cek kapasitas room
        $total = $room->users()->count();
        if ($total >= 2) {
            return back()->withErrors(['error' => 'Room sudah penuh']);
        }

        // Cek username unik di room
        if (User::where('room_id', $room->id)->where('username', $username)->exists()) {
            return back()->withErrors(['error' => 'Username sudah digunakan di room ini']);
        }

        // Logika join untuk user terdaftar
        if (Session::has('user')) {
            $userId = Session::get('user')->id;
            User::find($userId)->update([
                'room_id' => $room->id,
                'level' => 0
            ]);

            return redirect()->route('room.login', [
                'code' => $room->code
            ]);
        }
    }

    public function roomLogin(Request $request)
    {
        $room = Room::where('code', $request->code)->first();

        if (!$room) {
            return back()->withErrors(['error' => 'Room tidak ditemukan']);
        }

        // Hitung jumlah player di room
        $total = $room->users()->count();
        if ($total >= 2) {
            return back()->withErrors(['error' => 'Room sudah penuh']);
        }

        // Ambil user dari session jika sudah login
        if (Session::has('user')) {
            $user = Session::get('user');
        } else {
            // Validasi login jika belum login
            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withErrors(['username' => 'Username atau password salah!']);
            }

            // Simpan ke session
            Session::put('user', $user);
        }

        // Update user dengan room_id dan level
        $userId = $user->id;
        User::find($userId)->update([
            'room_id' => $room->id,
            'level' => 0
        ]);

        return redirect()->route('room.show', [
            'code' => $room->code,
            'username' => $user->username,
            'player_id' => $userId,
            'gallery_id' => $room->gallery_id
        ]);
    }

    // Menampilkan room
    public function showRoom($code, Request $request)
    {
        $room = Room::where('code', $code)->with(['users', 'players'])->first();

        $participants = User::where('room_id', $room->id)->get();

        if (!$room) {
            abort(404);
        }

        $startTime = null;
        $total = $room->users()->count();

        if ($total == 2) {
            $startTime = now()->addSeconds(10);
        }

        return view('room', [
            'room' => $room,
            'startTime' => $startTime ? $startTime->toIso8601String() : null,
            'participants' => $participants,
            'player_id' => $request->player_id,
            'gallery_id' => $request->gallery_id
        ]);
    }

    // API untuk mendapatkan daftar pemain
    public function getPlayers($code)
    {
        $room = Room::where('code', $code)->with('users')->first();

        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        // Return the list of users as JSON
        return response()->json($room->users);
    }

    public function getRoomData($code)
    {
        $room = Room::where('code', $code)->with(['users', 'players'])->first();

        $participant = $room->users->count();
        if (!$room) {
            return response()->json(['error' => 'Room tidak ditemukan'], 404);
        }

        return response()->json([
            'players' => $participant,
            'start_time' => $room->start_time,
        ]);
    }

    public function getStartTime($code)
    {
        $room = Room::where('code', $code)->first();

        if (!$room) {
            return response()->json(['error' => 'Room tidak ditemukan'], 404);
        }

        return response()->json([
            'start_time' => $room->start_time ? $room->start_time->toIso8601String() : null,
        ]);
    }

    public function puzzleMain($id, $code, $galleryId)
    {
        $gallery = Gallery::find($galleryId);
        $player_id = $id;
        $room_id = $code;
        return view('main', compact(['player_id', 'room_id', 'gallery']));
    }

    public function gallery()
    {
        $galleries = Gallery::all();
        return view('gallery', compact('galleries'));
    }

    public function tutorial()
    {
        return view('tutorial');
    }

    public function about()
    {
        return view('about');
    }
    
    public function profile(Request $request)
    {
        // Jika user sudah login (ada di session), ambil dari session
        if (Session::has('user')) {
            $user = Session::get('user');
        } else {
            // Validasi input jika belum login
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return redirect()->route('room.index')
                    ->with('error', 'Player tidak ditemukan atau password salah.');
            }

            // Set session jika login berhasil
            Session::put('user', $user);
        }

        // Ambil semua hasil game dari user ini
        $user_results = GameResult::where('user_id', $user->id)->get();

        // Jika tidak ada hasil game sama sekali
        if ($user_results->isEmpty()) {
            return view('profile', [
                'roomStats'  => [],
                'totalWins'  => 0,
                'totalLoses' => 0,
            ]);
        }

        // Ambil room IDs yang unik
        $roomIds = $user_results->pluck('room_id')->unique();

        // Inisialisasi counter keseluruhan untuk menang dan kalah (berdasarkan room)
        $totalWins  = 0;
        $totalLoses = 0;

        // Array untuk menyimpan detail tiap room, termasuk pemenang per ronde
        $roomStats = [];

        foreach ($roomIds as $roomId) {
            // Ambil semua GameResult di room ini, beserta relasi user, 
            // dan urutkan berdasarkan ronde lalu waktu (untuk keperluan sorting)
            $resultsInRoom = GameResult::with('user')
                ->where('room_id', $roomId)
                ->orderBy('round', 'asc')
                ->orderBy('time_taken', 'asc')
                ->get();

            if ($resultsInRoom->isEmpty()) {
                continue;
            }

            // Jika tiap ronde pasti ada tepat 2 GameResult, kita bisa grouping berdasarkan kolom 'round'
            $groupedByRound = $resultsInRoom->groupBy('round');

            // Hitung berapa ronde yang dimenangkan user dan berapa yang dimenangkan lawan
            $userRoundWins     = 0;
            $opponentRoundWins = 0;

            // Koleksi nama lawan (unik) untuk ditampilkan di view
            $allPlayerUsernames = $resultsInRoom->pluck('user.username')->unique()->filter()->toArray();
            // Hapus username user saat ini untuk mendapatkan username lawan
            $opponentNames = array_filter($allPlayerUsernames, function($name) use ($user) {
                return $name !== $user->username;
            });
            if (empty($opponentNames)) {
                // fallback jika tidak terdeteksi
                $opponentNames = ['Unknown'];
            }

            // Array detail per ronde (bisa digunakan jika ingin menampilkan breakdown per ronde)
            $roundDetails = [];

            foreach ($groupedByRound as $roundNumber => $roundResults) {
                // Pastikan ada 2 record di ronde ini
                if ($roundResults->count() < 2) {
                    // Jika data satu saja, skip (tidak valid sebagai ronde lengkap)
                    continue;
                }

                // Ambil kedua peserta di ronde ini, misal $r1 dan $r2
                $r1 = $roundResults->values()[0];
                $r2 = $roundResults->values()[1];

                // Tentukan pemenang ronde: yang punya skor lebih tinggi; jika seri, bandingkan time_taken
                if ($r1->correct_pieces > $r2->correct_pieces) {
                    $winnerThisRound = $r1;
                    $loserThisRound  = $r2;
                } elseif ($r1->correct_pieces < $r2->correct_pieces) {
                    $winnerThisRound = $r2;
                    $loserThisRound  = $r1;
                } else {
                    // Jika skor sama, pakai waktu (time_taken) terkecil => pemenang
                    if ($r1->time_taken <= $r2->time_taken) {
                        $winnerThisRound = $r1;
                        $loserThisRound  = $r2;
                    } else {
                        $winnerThisRound = $r2;
                        $loserThisRound  = $r1;
                    }
                }

                // Tambahkan detail ronde untuk keperluan debugging atau tampilan
                $roundDetails[$roundNumber] = [
                    'player1' => [
                        'username' => $r1->user->username,
                        'correct_pieces' => $r1->correct_pieces,
                        'time_taken' => $r1->time_taken,
                    ],
                    'player2' => [
                        'username' => $r2->user->username,
                        'correct_pieces' => $r2->correct_pieces,
                        'time_taken' => $r2->time_taken,
                    ],
                    'winner_username' => $winnerThisRound->user->username,
                ];

                // Hitung ronde menang untuk user saat ini atau lawan
                if ($winnerThisRound->user_id === $user->id) {
                    $userRoundWins++;
                } else {
                    $opponentRoundWins++;
                }
            }

            // Tentukan siapa overall pemenang di room ini (banyak ronde menang)
            if ($userRoundWins > $opponentRoundWins) {
                $roomWinnerId = $user->id;
                $totalWins++;
            } elseif ($userRoundWins < $opponentRoundWins) {
                $roomWinnerId = null; // lawan menang
                $totalLoses++;
            } else {
                // Jika seri (misal 1–1 di 2 ronde), kita bisa tentukan aturan tie-break (misal pemenang di ronde terakhir)
                // Untuk kesederhanaan, anggap tie => tidak dihitung win/loss secara keseluruhan.
                $roomWinnerId = null;
            }

            // Simpan detail room
            $roomStats[$roomId] = [
                'user_round_wins'     => $userRoundWins,
                'opponent_round_wins' => $opponentRoundWins,
                'opponent_names'      => $opponentNames,
                'round_details'       => $roundDetails,
                'room_winner_id'      => $roomWinnerId,
            ];
        }

        // Kirim data ke view 'profile'
        return view('profile', compact('roomStats', 'totalWins', 'totalLoses'));
    }
}
