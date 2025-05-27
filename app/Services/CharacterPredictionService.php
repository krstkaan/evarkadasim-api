<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CharacterPredictionService
{
    public function predict(array $answers): ?string
    {
        $response = Http::post('http://127.0.0.1:8000/predict', [
            'answers' => $answers
        ]);

        return $response->successful()
            ? $response->json('label')
            : null;
    }
}
