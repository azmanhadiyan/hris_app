<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManajemenUser\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', function () {
    return view('/auth/login');
});
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');


Route::middleware(['auth'])->group(function () {
    Route::resource('manajemen_users/users', UserController::class)
        ->names('manajemen_users.users');
});


require __DIR__.'/auth.php';
