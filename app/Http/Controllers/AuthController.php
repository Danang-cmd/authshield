<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Menghitung nilai entropi dari password untuk mengukur kekuatannya.
     */
    private function calculatePasswordEntropy(string $password): float
    {
        $length = strlen($password);
        if ($length === 0) return 0;

        $poolSize = 0;
        if (preg_match('/[a-z]/', $password)) $poolSize += 26; // Huruf kecil
        if (preg_match('/[A-Z]/', $password)) $poolSize += 26; // Huruf kapital
        if (preg_match('/[0-9]/', $password)) $poolSize += 10; // Angka
        if (preg_match('/[^a-zA-Z0-9]/', $password)) $poolSize += 33; // Simbol/Karakter khusus

        // Rumus: E = L * log2(R)
        return $length * log($poolSize, 2);
    }

    /**
     * Alur Kerja Registrasi
     */
    public function register(Request $request)
    {
        // 1. Input Data & Validasi Awal Struktur Form
        $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Pengecekan Kekuatan Password dengan Rumus Entropi
        $entropy = $this->calculatePasswordEntropy($request->password);
        
        // Batas minimal entropi standar aman adalah sekitar 50-60 bit
        if ($entropy < 50) {
            return back()->withErrors([
                'password' => "Password terlalu lemah (Entropi: " . round($entropy, 2) . " bit). Kombinasikan huruf besar, angka, dan simbol."
            ])->withInput();
        }

        // 3. Generasi Salt Unik
        $salt = Str::random(16);

        // 4. Penggabungan (Password + Salt + Secret Key)
        $secretKey = env('AUTH_SECRET_KEY');
        $combinedPassword = $request->password . $salt . $secretKey;

        // 5. Proses Hashing menggunakan Bcrypt & Penyimpanan ke Database
        User::create([
            'username' => $request->username,
            'salt'     => $salt,
            'password' => Hash::make($combinedPassword), // Laravel menggunakan Bcrypt secara default
        ]);

        return redirect()->route('login.view')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * Alur Kerja Login
     */
    public function login(Request $request)
    {
        // 1. Input Data
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Pencarian Pengguna berdasarkan Username
        $user = User::where('username', $credentials['username'])->first();

        // Pesan error generik demi keamanan (mencegah user enumeration)
        $loginError = 'Username atau Password salah.';

        if (!$user) {
            return back()->withErrors(['username' => $loginError])->withInput();
        }

        // 3. Rekonstruksi & Hashing Ulang (Password Login + Salt DB + Secret Key Server)
        $secretKey = env('AUTH_SECRET_KEY');
        $combinedPassword = $credentials['password'] . $user->salt . $secretKey;

        // 4. Verifikasi / Pencocokan Hash Bcrypt
        if (Hash::check($combinedPassword, $user->password)) {
            // Jika Cocok: Buat session login
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        // Jika Berbeda
        return back()->withErrors(['username' => $loginError])->withInput();
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.view');
    }
}