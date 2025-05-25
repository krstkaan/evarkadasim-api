<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsHeilosAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Auth kontrolü ve is_helios kontrolü (integer 1 olarak)
        if (!Auth::check() || Auth::user()->is_helios != 1) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        return $next($request);
    }
}