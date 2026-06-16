<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama otomatis diarahkan ke Login
Route::redirect('/', '/login');

// 2. Rute untuk tamu (Guest) - Hanya bisa diakses jika BELUM login
Route::middleware('guest')->group(function () {
    // Tampilan Form
    Route::view('/register', 'auth.register')->name('register.view');
    Route::view('/login', 'auth.login')->name('login.view');

    // Eksekusi Logika ke Controller
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// 3. Rute Terproteksi (Auth) - Hanya bisa diakses jika SUDAH login
// 3. Rute Terproteksi (Auth) - Hanya bisa diakses jika SUDAH login
Route::middleware('auth')->group(function () {
    
    // UBAH BARIS INI: Panggil method dashboard dari AuthController
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Eksekusi Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});