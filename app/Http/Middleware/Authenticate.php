<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
{
    if ($request->expectsJson()) {
        return null; // Return null to indicate that no redirection is needed
    }

    // If the request does not expect JSON, return the JSON response
    return response()->json(['error' => 'Unauthenticated'], 401);
}
}
