<?php


namespace App\Repositories\Backend;


use App\Models\City;

class CityRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @return Collection
     */
    public function getAll()
    {
        return City::select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}