<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CharacterPredictionService
{
    protected string $mlUrl;

    public function __construct()
    {
        $this->mlUrl = env('ML_URL', 'http://127.0.0.1:8001');
    }

    public function predict(array $answers): ?string
    {
        try {
            $response = Http::post($this->mlUrl . '/predict', [
                'answers' => $answers
            ]);

            if ($response->successful()) {
                return $response->json('label');
            } else {
                Log::warning('Karakter tahmini başarısız', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Karakter tahmini sırasında hata oluştu', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
}
