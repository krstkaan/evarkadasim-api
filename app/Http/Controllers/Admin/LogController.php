<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\LogService;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        if (!$type) {
            return response()->json(['message' => 'Log türü (type) parametresi gereklidir'], 400);
        }

        try {
            $logs = LogService::getLogs($type, $request->all(), $request->get('per_page', 15));
            return response()->json($logs);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}

