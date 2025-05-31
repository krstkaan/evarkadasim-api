<?php

namespace App\Http\Controllers;

use App\Services\CharacterTestService;
use Illuminate\Http\Request;
use App\Models\CharacterTestAnswer;
use App\Services\CharacterPredictionService;
use Illuminate\Support\Facades\DB; // DB Facade'ını ekleyin
use Illuminate\Http\JsonResponse;   // JsonResponse tipini ekleyin
// use Illuminate\Support\Facades\Log; // Eğer loglama yapacaksanız

class CharacterTestController extends Controller
{
    protected CharacterTestService $service;

    public function __construct(CharacterTestService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $questions = $this->service->getAllQuestions();
        return response()->json($questions);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->all();
        $isBulk = false;
        $questionsToProcess = [];
        $validatedData = [];

        // Gelen verinin yapısını kontrol et: Eğer bir dizi ve ilk elemanı 'question' içeriyorsa çoklu işlemdir.
        if (is_array($payload) && !empty($payload) && isset($payload[0]) && is_array($payload[0]) && array_key_exists('question', $payload[0])) {
            $isBulk = true;
            $validatedData = $request->validate([
                '*' => 'required|array',
                '*.question' => 'required|string|max:255',
                '*.options' => 'required|array|min:2',
                '*.options.*.text' => 'required|string|max:255',
                '*.options.*.value' => 'required|integer',
            ]);
            $questionsToProcess = $validatedData;
        } else {
            // Tekil işlem
            $isBulk = false;
            $validatedData = $request->validate([
                'question' => 'required|string|max:255',
                'options' => 'required|array|min:2',
                'options.*.text' => 'required|string|max:255',
                'options.*.value' => 'required|integer',
            ]);
            $questionsToProcess = [$validatedData]; // Tek elemanlı diziye çevir
        }

        $createdItems = [];
        DB::beginTransaction();

        try {
            foreach ($questionsToProcess as $questionData) {
                // Servis metodu tek bir soru verisi (array) alacak şekilde olmalı.
                // Mevcut $this->service->storeQuestion($request->all()) çağrısı zaten tüm isteği alıyordu.
                // $questionData, doğrulanmış tek bir soruya ait veriyi içerir.
                $createdQuestion = $this->service->storeQuestion($questionData);
                $createdItems[] = $createdQuestion;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Karakter testi sorusu eklenirken hata: ' . $e->getMessage(), ['payload' => $payload, 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Sorular eklenirken bir hata oluştu.',
                'error' => $e->getMessage() // Geliştirme ortamı için
            ], 500);
        }

        if ($isBulk) {
            return response()->json([
                'message' => count($createdItems) . ' adet soru başarıyla eklendi.',
                'data' => $createdItems
            ], 201);
        } else {
            return response()->json([
                'message' => 'Soru başarıyla eklendi.',
                'data' => $createdItems[0] ?? null
            ], 201);
        }
    }

    public function submit(Request $request, CharacterPredictionService $predictor): JsonResponse
    {
        $request->validate([
            'answers' => 'required|array|size:22',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.value' => 'required|integer|min:1|max:3',
        ]);

        $user = auth()->user();

        CharacterTestAnswer::where('user_id', $user->id)->delete();

        foreach ($request->answers as $answer) {
            CharacterTestAnswer::create([
                'user_id' => $user->id,
                'question_id' => $answer['question_id'],
                'selected_value' => $answer['value'],
            ]);
        }

        $user->character_test_done = true;

        $answers = CharacterTestAnswer::where('user_id', $user->id)
            ->join('questions', 'character_test_answers.question_id', '=', 'questions.id')
            ->orderBy('questions.id')
            ->pluck('selected_value')
            ->toArray();

        if (count($answers) === 22) {
            $label = $predictor->predict($answers);
            if ($label) {
                $user->character_label = $label;
            }
        }

        $user->save();

        return response()->json([
            'message' => 'Test başarıyla kaydedildi.',
            'label' => $user->character_label,
        ]);
    }
}