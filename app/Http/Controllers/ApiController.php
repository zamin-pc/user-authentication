<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
// use JWTAuth;
// use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;


class ApiController extends Controller
{
    public function login(Request $request)
    {
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
                if (Auth::attempt($request->only('email', 'password'))) {

                    $authUser = Auth::user();
                    $accessToken = $authUser->createToken('MyAppToken')->accessToken;

                    $payload = [
                        'id' => $authUser->id,
                        'name' => $authUser->name,
                        'email' => $authUser->email,
                        'phone' => $authUser->phone,
                    ];

                    $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

                    return response()->json([
                        'message' => 'User logged in successfully..!!',
                        'token' => $token,
                        'accessToken' => $accessToken
                    ]);
                } else {
                    return response()->json(['error' => 'Wrong password..!!'], 401);
                }
            } else {
                $appUrl = env('EXTERNAL_APP_URL');
                $endpoint = '/api/v1/user/login';

                $response = Http::timeout(10)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($appUrl . $endpoint, [
                    'email' => $request->email,
                    'password' => $request->password,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return response()->json([
                        'data' => $data,
                    ]);
                } else {
                    return response()->json(['error' => 'User not found..!!'], 401);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function userDetails(Request $request)
    {
        $users = User::all();
        if ($users->isNotEmpty()) {
            return response()->json([
                'message' => 'success',
                'user' => $users
            ]);
        }
        return response()->json([
            'message' => 'No users found..!!'
        ], 200);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
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

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
}
