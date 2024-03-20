<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function loginForm() {
        return view('login');
    }

    public function login(Request $request)  {

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            // dd($request->only('email', 'password'));
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                
                $response = Http::post('http://192.168.1.129:5000/api/v1/user/login', [
                    'email' => 'vishal1234@gmail.com',
                    'password' => 'Test@123'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return response()->json([
                        'message' => 'user logged in successfully..!!'
                    ], 500);
                } 

                return response()->json(['error' => 'Please fill correct credentials..!!'], 401);
            } else {
                return response()->json([
                    'message' => 'user logged in successfully..!!',
                    'token' => $token
                ]);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'Something went wrong..!!'], 500);
        }
        
    }

    public function logout(Request $request) {
        $token = $request->user()->tokens();
        // dd($token);
        $token->delete();
        return response()->json([
            'message' => 'User Log out successfully...!!'
        ], 200);
    }
}
