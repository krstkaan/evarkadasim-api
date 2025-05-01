<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;

class CharacterTestService
{
    public function getAllQuestions(): array
    {
        return Question::with('options')->get()->toArray();
    }

    public function storeQuestion(array $data): Question
    {
        return DB::transaction(function () use ($data) {
            $question = Question::create([
                'question' => $data['question'],
                'type' => 'single_choice',
            ]);

            foreach ($data['options'] as $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'text' => $option['text'],
                    'value' => $option['value'],
                ]);
            }

            return $question->load('options');
        });
    }


}
