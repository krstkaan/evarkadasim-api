<?php

namespace App\Services;

use App\Models\AgeRange;

class AgeRangeService
{
    public function all()
    {
        return AgeRange::all();
    }

    public function find($id)
    {
        return AgeRange::findOrFail($id);
    }

    public function create(array $data)
    {
        return AgeRange::create($data);
    }

    public function update($id, array $data)
    {
        $ageRange = AgeRange::findOrFail($id);
        $ageRange->update($data);
        return $ageRange;
    }

    public function delete($id)
    {
        return AgeRange::destroy($id);
    }
}