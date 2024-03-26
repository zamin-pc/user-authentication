<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CheckBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request->header('X-Internal-Request'));
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized user..!!'], 401);
        }
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $decodedData = (array) $decoded;
            // dd($decodedData);
            $user = new User();
            $user->fill($decodedData);

            $authenticatedUser = Auth::guard('api')->setUser($user);

            if ($authenticatedUser) {
                return $next($request);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
}
}