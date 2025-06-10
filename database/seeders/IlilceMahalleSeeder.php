<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IlIlceMahalleSeeder extends Seeder
{
    public function run()
    {
        $path = storage_path('seeders/data/ililcemahalle_202506110016.csv');
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle, 0, ',');

        $rows = [];
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        $idMap = [];

        foreach ($rows as $row) {
            $id = (int)$row[0];
            $name = $row[1];
            $ustID = (int)$row[2];

            $idMap[$id] = [
                'name' => $name,
                'ustID' => $ustID,
            ];
        }

        foreach ($idMap as $id => $entry) {
            if ($entry['ustID'] === 0) {
                continue;
            }

            $parent = $idMap[$entry['ustID']] ?? null;
            if (!$parent) continue;

            $grandparent = $idMap[$parent['ustID']] ?? null;

            if ($parent['ustID'] === 0) {
                $il = $parent['name'];
                $ilce = $entry['name'];
                $mahalle = null;
            } elseif ($grandparent) {
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
