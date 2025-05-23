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
        // Jika sudah ada session player, gunakan data player yang sudah ada
        if ($request->session()->has('player')) {
            $player = Player::find(session('player.id'));

            // Cek apakah player sudah berada di room lain
            // if ($player->room_id) {
            //     return back()->withErrors(['error' => 'Anda sudah berada di room lain']);
            // }

            // Buat room baru
            $code = strtoupper(Str::random(6));
            $room = Room::create(['code' => $code, 'gallery_id' => $request->galleryId]);

            // Update player untuk bergabung ke room baru
            $player->update(['room_id' => $room->id]);

            // Mulai countdown jika room penuh
            $total = $room->players()->count() + $room->users()->count();
            if ($total >= 2) {
                $room->update(['start_time' => now()->addSeconds(10)]);
            }

            return redirect()->route('room.show', [
                'code' => $room->code,
                'username' => $player->username,
                'player_id' => $player->id,
                'gallery_id' => $request->galleryId
            ]);
        }

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

        // Jika guest, buat player baru
        $request->validate(['username' => 'required|string|max:255']);

        $player = Player::create([
            'username' => $request->username,
            'room_id' => $room->id,
            'level' => 0
        ]);

        // Mulai countdown jika room penuh
        $total = $room->players()->count() + $room->users()->count();
        if ($total >= 2) {
            $room->update(['start_time' => now()->addSeconds(10)]);
        }

        // Set session player untuk guest
        session(['player' => [
            'id' => $player->id,
            'username' => $player->username
        ]]);

        return redirect()->route('room.show', [
            'code' => $room->code,
            'username' => $player->username,
            'player_id' => $player->id,
            'gallery_id' => $request->galleryId
        ]);
    }

    // Bergabung ke room
    public function joinRoom(Request $request)
    {
        $room = Room::where('code', $request->code)->first();

        // Jika sudah ada session player, gunakan data player yang sudah ada
        if (Session::has('player')) {
            $player = Player::find(session('player.id'));

            // Cek apakah player sudah berada di room ini
            if ($player->room_id === $room->id) {
                return redirect()->route('room.show', [
                    'code' => $room->code,
                    'username' => $player->username,
                    'player_id' => $player->id,
                    'gallery_id' => $room->gallery_id
                ]);
            }

            // Cek apakah player sudah berada di room lain
            // if ($player->room_id) {
            //     return back()->withErrors(['error' => 'Anda sudah berada di room lain']);
            // }

            // Update player untuk bergabung ke room baru
            $player->update(['room_id' => $room->id]);

            // Mulai countdown jika room penuh
            $total = $room->players()->count() + $room->users()->count();
            if ($total >= 2) {
                $room->update(['start_time' => now()->addSeconds(10)]);
            }

            return redirect()->route('room.show', [
                'code' => $room->code,
                'username' => $player->username,
                'player_id' => $player->id,
                'gallery_id' => $room->gallery_id
            ]);
        }

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
        $total = $room->players()->count() + $room->users()->count();
        if ($total >= 2) {
            return back()->withErrors(['error' => 'Room sudah penuh']);
        }

        // Cek username unik di room
        if (
            Player::where('room_id', $room->id)->where('username', $username)->exists() ||
            User::where('room_id', $room->id)->where('username', $username)->exists()
        ) {
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

        // Logika untuk guest (hanya jika belum ada session player)
        $player = Player::create([
            'username' => $username,
            'room_id' => $room->id,
            'level' => 0
        ]);

        session(['player' => [
            'id' => $player->id,
            'username' => $player->username
        ]]);

        // Mulai countdown jika room penuh
        if (($room->players()->count() + $room->users()->count()) === 2) {
            $room->update(['start_time' => now()->addSeconds(10)]);
        }

        return redirect()->route('room.show', [
            'code' => $room->code,
            'username' => $player->username,
            'player_id' => $player->id,
            'gallery_id' => $room->gallery_id
        ]);
    }

    public function roomLogin(Request $request)
    {
        $room = Room::where('code', $request->code)->first();
        $user = User::where('username', $request->username)->first();

        $total = $room->players()->count() + $room->users()->count();
        if ($total >= 2) {
            return back()->withErrors(['error' => 'Room sudah penuh']);
        }

        if ($user && Hash::check($request->password, $user->password)) {
            // Set session
            Session::put('user', $user);

            $userId = Session::get('user')->id;
            User::find($userId)->update([
                'room_id' => $room->id,
                'level' => 0
            ]);

            return redirect()->route('room.show', [
                'code' => $room->code,
                'username' => Session::get('user')->username,
                'player_id' => $userId,
                'gallery_id' => $room->gallery_id
            ]);
        }

        return back()->withErrors(['username' => 'Username atau password salah!']);
    }

    // Menampilkan room
    public function showRoom($code, Request $request)
    {
        $room = Room::where('code', $code)->with(['users', 'players'])->first();

        $participants = $room->users->concat($room->players);

        if (!$room) {
            abort(404);
        }

        $startTime = null;
        $total = $room->players()->count() + $room->users()->count();

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
        $room = Room::where('code', $code)->with(['users', 'players'])->first();

        $participant = $room->users->concat($room->players);

        return response()->json($participant);
    }

    public function getRoomData($code)
    {
        $room = Room::where('code', $code)->with(['users', 'players'])->first();

        $participant = $room->users->concat($room->players);
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

    public function profile($id)
    {
        if (session()->has('player')) {
            $player = GameResult::with(['player', 'user', 'room'])->where('player_id', $id)->first();
        } else {
            $player = GameResult::with(['player', 'user', 'room'])->where('user_id', $id)->first();
        }
        
        if (!$player) {
            // Handle kasus ketika $player tidak ditemukan
            return redirect()->route('room.index')->with('error', 'Player not found.');
        }

        // Ambil data hasil permainan
        $results = GameResult::with(['player', 'user', 'room'])
            ->where('room_id', $player->room_id) // Ganti $roomId dengan ID yang sesuai
            ->latest()
            ->get();

        // Kelompokkan data berdasarkan ronde
        $groupedByRound = $results->groupBy('round');

        // Tentukan pemenang untuk setiap ronde
        $resultsPerRound = $groupedByRound->map(function ($roundGroup) {
            // Urutkan berdasarkan correct_pieces (desc) dan time_taken (asc)
            $sorted = $roundGroup->sortBy([
                ['correct_pieces', 'desc'],
                ['time_taken', 'asc']
            ]);
    
            // Ambil entri pertama sebagai pemenang
            $winner = $sorted->first();
    
            // Ambil entri selanjutnya sebagai yang kalah
            $losers = $sorted->slice(1)->values();
    
            return [
                'winner' => $winner,
                'losers' => $losers,
            ];
        });
        return view('profile', compact(['results', 'player', 'resultsPerRound']));
    }
}
