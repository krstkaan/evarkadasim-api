<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecommendationController extends Controller
{
    protected $mlUrl;

    public function __construct()
    {
        $this->mlUrl = env('ML_URL', 'http://127.0.0.1:8001');
    }

    public function matchScore(Request $request)
    {
        $request->validate([
            'label1' => 'required|string',
            'label2' => 'required|string',
        ]);

        $label1 = $request->input('label1');
        $label2 = $request->input('label2');

        try {
            $response = Http::post($this->mlUrl . '/predict-score', [
                'label1' => $label1,
                'label2' => $label2,
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json([
                    'error' => 'ML servisi başarısız yanıt verdi.',
                    'details' => $response->body(),
                ], 502);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'ML servisine bağlanılamadı.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
