<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware'=>'jwt.auth'], function(){
    Route::post('/logout', [LoginController::class, 'logout'])->name('api.logout');
    Route::get('/user', [UserController::class, 'userDetails'])->name('user.details');
});

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [LoginController::class, 'login'])->name('api.login');

// Route::middleware('jwt.auth')->get('/user', function(Request $request) {
//     return auth()->user();
// });




