<?php

namespace App\Http\Controllers;

use App\Services\CharacterTestService;
use Illuminate\Http\Request;
use App\Models\CharacterTestAnswer;



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

    public function submit(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.value' => 'required|integer',
        ]);

        $user = auth()->user();

        // Eski yanıtları temizle (varsa)
        CharacterTestAnswer::where('user_id', $user->id)->delete();

        foreach ($request->answers as $answer) {
            CharacterTestAnswer::create([
                'user_id' => $user->id,
                'question_id' => $answer['question_id'],
                'selected_value' => $answer['value'],
            ]);
        }

        // Kullanıcı testini tamamladı
        $user->character_test_done = true;
        $user->save();

        return response()->json(['message' => 'Test başarıyla kaydedildi.']);
    }
}

