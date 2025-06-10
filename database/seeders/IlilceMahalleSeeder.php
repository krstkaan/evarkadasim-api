<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IlIlceMahalleSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/ililcemahalle_202506110016.csv');
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle, 0, ',');

        $rows = [];
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        // ID → Ad eşlemesi
        $idMap = [];

        foreach ($rows as $row) {
            $id = (int) $row[0];
            $name = $row[1];
            $ustID = (int) $row[2];

            $idMap[$id] = [
                'name' => $name,
                'ustID' => $ustID,
            ];
        }

        foreach ($idMap as $id => $entry) {
            if ($entry['ustID'] === 0) {
                // Şehir → atla
                continue;
            }

            $parent = $idMap[$entry['ustID']] ?? null;
            if (!$parent) {
                continue;
            }

            $grandparent = $idMap[$parent['ustID']] ?? null;

            if ($parent['ustID'] === 0) {
                // İlçe
                $il = $parent['name'];
                $ilce = $entry['name'];
                $mahalle = null;
            } elseif ($grandparent) {
                // Mahalle
                $il = $grandparent['name'];
                $ilce = $parent['name'];
                $mahalle = $entry['name'];
            } else {
                continue;
            }

            DB::table('ililcemahalle')->insert([
                'il' => $il,
                'ilce' => $ilce,
                'mahalle' => $mahalle,
            ]);
        }
    }
}


