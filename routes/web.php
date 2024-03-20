<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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

Route::get('/register', [UserController::class, 'registerForm'])->name('register.form');
Route::post('/user-create', [UserController::class, 'create'])->name('user.create');
Route::get('/login', [LoginController::class, 'loginForm'])->name('user.login-form');


// Route::group(['middleware'=>'admin'], function(){
//     Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
// });

