<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Menghitung nilai entropi dari password untuk mengukur kekuatannya.
    private function calculatePasswordEntropy(string $password): float
    {
        $length = strlen($password);
        if ($length === 0) return 0;

        $poolSize = 0;
        if (preg_match('/[a-z]/', $password)) $poolSize += 26; 
        if (preg_match('/[A-Z]/', $password)) $poolSize += 26; 
        if (preg_match('/[0-9]/', $password)) $poolSize += 10; 
        if (preg_match('/[^a-zA-Z0-9]/', $password)) $poolSize += 33; 

        return $length * log($poolSize, 2);
    }

    
    //Alur Kerja Registrasi
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $entropy = $this->calculatePasswordEntropy($request->password);
        
        if ($entropy < 50) {
            return back()->withErrors([
                'password' => "Password terlalu lemah (Entropi: " . round($entropy, 2) . " bit). Kombinasikan huruf besar, angka, dan simbol."
            ])->withInput();
        }

        $salt = bin2hex(random_bytes(16)); 
        $secretKey = env('AUTH_SECRET_KEY');
        $combinedPassword = $request->password . $salt . $secretKey;

        $customCost = 12; 

        $options = [
            'cost' => $customCost
        ];
        
        // Eksekusi hash manual
        $manualHashedPassword = password_hash($combinedPassword, PASSWORD_BCRYPT, $options);

        User::create([
            'username' => $request->username,
            'salt'     => $salt,
            'password' => $manualHashedPassword, 
        ]);

        return redirect()->route('login.view')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    //Alur Kerja Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();
        $loginError = 'Username atau Password salah.';

        if (!$user) {
            return back()->withErrors(['username' => $loginError])->withInput();
        }

        $secretKey = env('AUTH_SECRET_KEY');
        $combinedPassword = $credentials['password'] . $user->salt . $secretKey;

        // Verifikasi otomatis membaca cost factor dari hash di database
        if (password_verify($combinedPassword, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['username' => $loginError])->withInput();
    }

    //Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.view');
    }
    
    //Menampilkan Halaman Dashboard & Kalkulasi Benchmark
    public function dashboard()
    {
        $user = Auth::user();
        $userHash = $user->password;
        
        // Mengekstrak cost factor dari database untuk ditampilkan
        $hashParts = explode('$', $userHash);
        $costFactor = isset($hashParts[2]) ? (int) $hashParts[2] : 12;

        // Menghitung total iterasi riil
        $totalIterations = pow(2, $costFactor);

        // Menjalankan Live Benchmark
        $startTime = microtime(true);
        password_hash('simulasi_benchmark_kecepatan', PASSWORD_BCRYPT, ['cost' => $costFactor]); 
        $endTime = microtime(true);
        
        $executionTimeMs = round(($endTime - $startTime) * 1000, 2); 

        return view('dashboard', compact('userHash', 'costFactor', 'totalIterations', 'executionTimeMs'));
    }
}