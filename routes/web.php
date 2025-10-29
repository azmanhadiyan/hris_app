<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ManajemenUser\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route utama aplikasi kamu terdaftar di sini.
| Route yang butuh autentikasi dikelompokkan dalam middleware 'auth'.
|
*/

// 🔹 Default route → arahkan ke halaman login
Route::get('/', function () {
    return view('auth.login');
});

// 🔹 Register route (jika kamu pakai register manual)
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// 🔹 Dashboard/Home
Route::get('/home', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

// 🔹 Route yang butuh login
Route::middleware(['auth'])->group(function () {

    // Manajemen User (admin)
    Route::resource('manajemen_users/users', UserController::class)
        ->names('manajemen_users.users');

    // CRUD Data Karyawan
    Route::resource('karyawan', KaryawanController::class);
});

require __DIR__ . '/auth.php';
