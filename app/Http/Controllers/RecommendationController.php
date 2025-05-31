<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Http;

class RecommendationController extends Controller
{
    public function matchScore(Request $request)
    {
        $request->validate([
            'label1' => 'required|string',
            'label2' => 'required|string',
        ]);

        $label1 = $request->input('label1');
        $label2 = $request->input('label2');

        // FastAPI'den sonucu al
        $response = Http::post('http://192.168.1.111:8001/predict-score', [
            'label1' => $label1,
            'label2' => $label2,
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Skor hesaplanamadı'], 500);
        }
    }
}
