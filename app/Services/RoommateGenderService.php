<?php

namespace App\Services;

use App\Models\RoommateGender;

class RoommateGenderService
{
    public function all()
    {
        return RoommateGender::all();
    }

    public function find($id)
    {
        return RoommateGender::findOrFail($id);
    }

    public function create(array $data)
    {
        return RoommateGender::create($data);
    }

    public function update($id, array $data)
    {
        $gender = RoommateGender::findOrFail($id);
        $gender->update($data);
        return $gender;
    }

    public function delete($id)
    {
        return RoommateGender::destroy($id);
    }
}
