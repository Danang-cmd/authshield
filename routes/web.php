<?php

// routes/web.php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Tampilan Form (sesuaikan nama file blade-mu)
Route::view('/login', 'auth.login')->name('login.view')->middleware('guest');
Route::view('/register', 'auth.register')->name('register.view')->middleware('guest');

// Eksekusi Logika Proyek AuthShield
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Proteksi Halaman Dashboard
Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});