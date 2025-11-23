<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk fitur Login otomatis
use Illuminate\Support\Facades\Hash; // Untuk enkripsi password
use App\Models\User; // Panggil Model User

class AuthController extends Controller
{
    // 1. Tampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ... (kode atas sama)

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // === LOGIKA REDIRECT YANG BENAR ===

            // Jika role ADMIN -> Ke Dashboard
            if ($user->role === 'admin') {
                return redirect()->intended('/dashboard');
            }

            // Jika role USER BIASA -> Ke Home (JANGAN KE DASHBOARD)
            return redirect()->intended('/');
        }

        // Jika Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ... (kode bawah sama)

    // 3. Tampilkan Form Register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 4. Proses Register
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // confirmed artinya harus ada input password_confirmation
        ]);

        // Buat User Baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => 'user', // Default user biasa
        ]);

        // Langsung login setelah register (Opsional)
        // Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // 5. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
