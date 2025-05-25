<?php

namespace App\Services;

use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogService
{
    public static function log(Request $request, string $action, ?string $description = null): void
    {
        UserLog::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
    }
}
