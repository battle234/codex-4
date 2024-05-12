<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsUser
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}

