<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class UserController extends Controller
{
    public function registerForm() {
        return view('user.registerForm');
    }

    public function dashboard() {
        return view('user.dashboard');
    }

    public function userDetails() {

        return response()->json([
            'user' => JWTAuth::user()
        ]);
    }
}
