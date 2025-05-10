<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsHelios
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->is_helios) {
            return response()->json([
                'message' => 'Bu işlemi gerçekleştirmek için yetkiye sahip olmalısınız.'
            ], 403);
        }

        return $next($request);
    }
}
