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
        $room = Room::where('code', $code)->with(['users', 'players'])->first();

        $participant = $room->users->count();

        return response()->json($participant);
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
                return redirect()->route('room.index')->with('error', 'Player tidak ditemukan atau password salah.');
            }

            // Set session jika login berhasil
            Session::put('user', $user);
        }

        $player = GameResult::with(['user', 'player', 'room'])->where('user_id', $user->id)->first();

        $results = collect();
        $resultsPerRound = collect();

        if ($player && $player->room_id) {
            $results = GameResult::with(['player', 'user', 'room'])
                ->where('room_id', $player->room_id)
                ->latest()
                ->get();

            $groupedByRound = $results->groupBy('round');

            $resultsPerRound = $groupedByRound->map(function ($roundGroup) {
                $sorted = $roundGroup->sortBy([
                    ['correct_pieces', 'desc'],
                    ['time_taken', 'asc']
                ]);

                $winner = $sorted->first();
                $losers = $sorted->slice(1)->values();

                return [
                    'winner' => $winner,
                    'losers' => $losers,
                ];
            });
        }

        return view('profile', compact(['results', 'player', 'user', 'resultsPerRound']));
    }
}
