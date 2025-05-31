<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CharacterPredictionService
{
    public function predict(array $answers): ?string
    {
        $response = Http::post('http://192.168.1.111:8001/predict', [
            'answers' => $answers
        ]);

        return $response->successful()
            ? $response->json('label')
            : null;
    }
}
