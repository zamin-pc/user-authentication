<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [WebController::class, 'registerForm'])->name('register.form');
Route::post('/register', [WebController::class, 'register'])->name('user.register');
Route::get('/login', [WebController::class, 'loginForm'])->name('login.form');
Route::post('/login', [WebController::class, 'login'])->name('user.login');

Route::get('/dashboard', [WebController::class, 'dashboard'])->name('user.dashboard');


