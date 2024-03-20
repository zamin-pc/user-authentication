<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use JWTAuth;

class UserController extends Controller
{
    public function registerForm()
    {
        return view('user.registerForm');
    }

    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function userDetails()
    {

        return response()->json([
            'user' => JWTAuth::user()
        ]);
    }

    public function create(Request $request)
    {
        // dd($request->all());
        // $response = Http::post(route('api.register'), [
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'phone' => $request->phone,
        //     'password' => Hash::make($request->password),
        // ]);

        // dd($response);

        // if ($response->successful()) {
        //     $data = $response->json();
        //     return response()->json([
        //         'message' => 'user logged in successfully..!!',
        //         'data' => $data
        //     ], 200);
        // } 

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ];

        $dataString = json_encode($data);

        $ch = curl_init(route('api.register')); // Assuming 'api.register' is the route for registration
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Handle response
            dd($response);
        }

        // Close cURL session
        curl_close($ch);
    }
}
