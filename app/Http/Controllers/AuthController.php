<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed', // password harus dikonfirmasi
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Enkripsi password
            'level' => 0
        ]);

        return redirect('/login')->with('success', 'Register berhasil, silakan login!');
    }

    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showJoin(Request $request)
    {
        $code = $request->code;
        return view('auth.login', compact(['code']));
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Set session
            Session::put('user', $user);

            return redirect('/gallery');
        }

        return back()->withErrors(['username' => 'Username atau password salah!']);
    }

    // Logout
    public function logout()
    {
        Session::flush(); // Hapus semua session
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}

