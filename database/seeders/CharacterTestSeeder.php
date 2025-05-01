<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;

class CharacterTestSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Evde yalnız kalmaktan hoşlanır mısın?',
                'options' => [
                    ['text' => 'Evet, yalnızlık bana iyi gelir', 'value' => 3],
                    ['text' => 'Bazen', 'value' => 2],
                    ['text' => 'Hayır, kalabalık severim', 'value' => 1],
                    ['text' => 'Fark etmez', 'value' => 2],
                ],
            ],
            [
                'question' => 'Düzen senin için ne kadar önemli?',
                'options' => [
                    ['text' => 'Aşırı önemli, her şey yerli yerinde olmalı', 'value' => 3],
                    ['text' => 'Genelde dikkat ederim', 'value' => 2],
                    ['text' => 'Dağınıklık beni rahatsız etmez', 'value' => 1],
                    ['text' => 'Duruma göre değişir', 'value' => 2],
                ],
            ],
            [
                'question' => 'Gece mi gündüz mü daha aktifsindir?',
                'options' => [
                    ['text' => 'Gece kuşuyum', 'value' => 1],
                    ['text' => 'Gündüz insanıyım', 'value' => 3],
                    ['text' => 'İkisi de olabilir', 'value' => 2],
                    ['text' => 'Fark etmez', 'value' => 2],
                ],
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'question' => $q['question'],
                'type' => 'single_choice',
            ]);

            foreach ($q['options'] as $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'text' => $option['text'],
                    'value' => $option['value'],
                ]);
            }
        }
    }
}

