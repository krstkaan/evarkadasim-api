<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IlIlceMahalleImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('seeders/data/ililcemahalle_202506110016.csv');
        $handle = fopen($path, 'r');
        if (!$handle) {
            throw new \Exception("CSV dosyası açılamadı: $path");
        }

        $header = fgetcsv($handle, 0, ',');

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            DB::table('ililcemahalle')->insert([
                'id' => (int) $row[0],
                'SehirIlceMahalleAdi' => $row[1],
                'UstID' => (int) $row[2],
                'minlongitude' => $row[3] !== '' ? (float) $row[3] : null,
                'minlatitude' => $row[4] !== '' ? (float) $row[4] : null,
                'maxlongitude' => $row[5] !== '' ? (float) $row[5] : null,
                'maxlatitude' => $row[6] !== '' ? (float) $row[6] : null,
                'MahalleID' => $row[7] !== '' ? (int) $row[7] : null,
            ]);
        }

        fclose($handle);
    }
}
