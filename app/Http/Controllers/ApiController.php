<?php

namespace App\Http\Controllers;

use App\Models\SharedUser;
use App\Models\User;
use App\Rules\UniqueEmail;
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
use Illuminate\Support\Facades\DB;


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
            $user = SharedUser::Where('email', $request->email)->first();
            if (!empty($user->email)) {
                if (Auth::guard('shared')->attempt($request->only('email', 'password'))) {

                    $authUser = Auth::guard('shared')->user();
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
        // $authUser = Auth::guard('shared')->user();
        $users = SharedUser::all();
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
            // 'email' => 'required|email|unique:users|unique:shared_users,email',
            'email' => ['required', 'email', new UniqueEmail],
            'phone' => 'required|digits:10',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            );

            $user = User::create($data);
            $sharedUser = SharedUser::create($data);

            DB::commit();

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $sharedUser
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function profileUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits:10',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    }
}
