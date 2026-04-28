<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     * Jika user sudah login, redirect ke dashboard.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Menampilkan form register.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Memproses register.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'client',
        ]);

        Auth::login($user);

        return redirect()->route('client.dashboard');
    }

    /**
     * Memproses login menggunakan username & password.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'], // Menggunakan username
            'password' => ['required', 'string'],
        ]);

        // 2. Coba login (Attempt)
        if (Auth::attempt($credentials)) {
            // Jika berhasil:
            $request->session()->regenerate();

            // Redirect based on role
            if (Auth::user()->role === 'client') {
                return redirect()->route('client.dashboard');
            }

            return redirect()->intended('/');
        }

        // 3. Jika gagal:
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logout user

        $request->session()->invalidate(); // Invalidasi session saat ini
        $request->session()->regenerateToken(); // Regenerasi CSRF token

        return redirect('/login'); // Kembali ke halaman login
    }
}