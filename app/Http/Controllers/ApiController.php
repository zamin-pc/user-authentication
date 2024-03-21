<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Http;

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
            if ($user->email) {
                // if (JWTAuth::attempt($request->only('email', 'password'))) {

                //     $user = JWTAuth::user();
                //     $additionalPayload = [
                //         'id' => $user->id,
                //         'name' => $user->name,
                //         'email' => $user->email,
                //         'phone' => $user->phone,
                //     ];

                //     $tokenWithPayload = JWTAuth::claims($additionalPayload)->attempt($request->only('email', 'password'));

                //     return response()->json([
                //         'message' => 'user logged in successfully..!!',
                //         'token' => $tokenWithPayload
                //     ]);
                // } else {
                //     return response()->json(['error' => 'Please fill correct password..!!'], 401);
                // }
                return response()->json([
                    // 'message' => 'user logged in successfully..!!',
                    'user' => $user
                ], 200);
            } else {

                // $appUrl = env('EXTERNAL_APP_URL');
                // $email = env('USER_EMAIL');
                // $password = env('USER_PASSWORD');
                // $email = $request->email;
                // $password = $request->password;

                // $response = Http::withHeaders([
                //     'Content-Type' => 'application/json',
                // ])->post($appUrl . '/api/v1/user/login', [
                //     'email' => $email,
                //     'password' => $password
                // ]);

                // if ($response->successful()) {
                //     $json_decode = json_decode($response, true);

                //     return response()->json([
                //         'message' => 'user logged in successfully..!!',
                //         'token' => $json_decode['userData']
                //     ], 200);
                // }

                return response()->json(['error' => 'Email not found..!!'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        JWTAuth::invalidate($token);
        return response()->json([
            'message' => 'User Log out successfully...!!'
        ], 200);
    }

    public function userDetails()
    {
        return response()->json([
            'user' => JWTAuth::user()
        ]);
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

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
}
