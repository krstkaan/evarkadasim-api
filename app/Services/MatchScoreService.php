<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MatchScoreService
{
    protected string $mlUrl;

    public function __construct()
    {
        // .env dosyasına PREDICT_SCORE_URL olarak örnek: http://127.0.0.1:8000/predict-score
        $this->mlUrl = env('PREDICT_SCORE_URL', 'http://192.168.1.111:8001/predict-score');
    }

    public function getScore(string $label1, string $label2): ?float
    {
        $response = Http::post($this->mlUrl, [
            'label1' => $label1,
            'label2' => $label2,
        ]);

        if ($response->successful()) {
            return $response->json()['score'] ?? null;
        }

        return null;
    }
}
