<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupTablesSeeder extends Seeder
{
    public function run(): void
    {
        $files = [
            ['file' => 'age_ranges_202506110158.csv', 'table' => 'age_ranges'],
            ['file' => 'building_ages_202506110159.csv', 'table' => 'building_ages'],
            ['file' => 'furniture_statuses_202506110159.csv', 'table' => 'furniture_statuses'],
            ['file' => 'heating_types_202506110159.csv', 'table' => 'heating_types'],
            ['file' => 'house_types_202506110159.csv', 'table' => 'house_types'],
            ['file' => 'roommate_genders_202506110159.csv', 'table' => 'roommate_genders'],
        ];

        foreach ($files as $item) {
            $path = base_path("database/seeders/data/{$item['file']}");
            $handle = fopen($path, 'r');

            if (!$handle) {
                throw new \Exception("CSV dosyası açılamadı: $path");
            }

            $header = fgetcsv($handle, 0, ',');
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                $data = array_combine($header, $row);

                DB::table($item['table'])->insert([
                    'id' => (int) $data['id'],
                    'label' => $data['label'],
                    'created_at' => $data['created_at'] ?? now(),
                    'updated_at' => $data['updated_at'] ?? now(),
                ]);
            }

            fclose($handle);
        }
    }
}
