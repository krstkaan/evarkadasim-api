<?php

namespace App\Http\Controllers;

use App\Services\CharacterTestService;
use Illuminate\Http\Request;
use App\Models\CharacterTestAnswer;
use App\Services\CharacterPredictionService;




class CharacterTestController extends Controller
{
    protected CharacterTestService $service;

    public function __construct(CharacterTestService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $questions = $this->service->getAllQuestions();
        return response()->json($questions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string|max:255',
            'options.*.value' => 'required|integer',
        ]);

        $question = $this->service->storeQuestion($request->all());

        return response()->json([
            'message' => 'Soru başarıyla eklendi.',
            'data' => $question
        ], 201);
    }

    public function submit(Request $request, CharacterPredictionService $predictor)
    {
        $request->validate([
            'answers' => 'required|array|size:22', // kesin 22 cevap bekleniyor
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.value' => 'required|integer|min:1|max:3',
        ]);

        $user = auth()->user();

        // 🔄 Önceki yanıtları temizle
        CharacterTestAnswer::where('user_id', $user->id)->delete();

        // 💾 Yeni yanıtları kaydet
        foreach ($request->answers as $answer) {
            CharacterTestAnswer::create([
                'user_id' => $user->id,
                'question_id' => $answer['question_id'],
                'selected_value' => $answer['value'],
            ]);
        }

        // ✅ Test tamamlandı olarak işaretle
        $user->character_test_done = true;

        // 📥 22 cevabı sırayla topla (question_id sırasına göre)
        $answers = CharacterTestAnswer::where('user_id', $user->id)
            ->join('questions', 'character_test_answers.question_id', '=', 'questions.id')
            ->orderBy('questions.id')
            ->pluck('selected_value')
            ->toArray();

        // 🔍 AI karakter etiketi tahmini
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

