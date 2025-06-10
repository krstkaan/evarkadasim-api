<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionAndOptionSeeder extends Seeder
{
    public function run(): void
    {
        // Questions CSV
        $questionPath = base_path('database/seeders/data/questions.csv');
        $questionHandle = fopen($questionPath, 'r');

        if (!$questionHandle) {
            throw new \Exception("Questions CSV dosyası açılamadı: $questionPath");
        }

        $questionHeader = fgetcsv($questionHandle, 0, ','); // başlık satırı
        while (($row = fgetcsv($questionHandle, 0, ',')) !== false) {
            $data = array_combine($questionHeader, $row);
            DB::table('questions')->insert([
                'id' => (int) $data['id'],
                'question' => $data['question'],
                'category' => $data['category'] !== '' ? $data['category'] : null,
                'type' => $data['type'] ?? 'single_choice',
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);
        }

        fclose($questionHandle);

        // Question Options CSV
        $optionPath = base_path('database/seeders/data/question_options.csv');
        $optionHandle = fopen($optionPath, 'r');

        if (!$optionHandle) {
            throw new \Exception("Question Options CSV dosyası açılamadı: $optionPath");
        }

        $optionHeader = fgetcsv($optionHandle, 0, ','); // başlık satırı
        while (($row = fgetcsv($optionHandle, 0, ',')) !== false) {
            $data = array_combine($optionHeader, $row);
            DB::table('question_options')->insert([
                'id' => (int) $data['id'],
                'question_id' => (int) $data['question_id'],
                'text' => $data['text'],
                'value' => (int) $data['value'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);
        }

        fclose($optionHandle);
    }
}
