<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MatchScoreService
{
    protected string $mlUrl;

    public function __construct()
    {
        $this->mlUrl = env('ML_URL') . '/predict-score';
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
