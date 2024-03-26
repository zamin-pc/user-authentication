<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Exceptions\JWTException;

class WebController extends Controller
{
    public function registerForm()
    {
        return view('user.register');
    }

    public function loginForm()
    {
        return view('login');
    }

    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits:10',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.login');
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $user = User::Where('email', $request->email)->first();
            
            if (!empty($user->email)) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    return redirect()->route('user.dashboard')->with('success', 'You are registered successfully..!!');
                } else {
                    return redirect()->route('login.form')->with('error', 'Incorrect password..!!');
                }
            } else {
                // External API Call
                $appUrl = env('EXTERNAL_APP_URL');
                $endpoint = '/api/v1/user/login';
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($appUrl.$endpoint, [
                    'email' => $request->email,
                    'password' => $request->password,
                ]);
                
                // Handle response
                if ($response->successful()) {
                    // Request was successful, handle response
                    $data = $response->json();
                    return response()->json([
                        'data' => $data,
                    ]);
                } else {
                    // Request failed
                    $statusCode = $response->json();
                    return response()->json([
                        'statusCode' => $statusCode,
                    ]);
                }
            }
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login.form')->with('success', 'User log out..!!');
    }

}
