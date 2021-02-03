<?php


namespace App\Repositories\Backend;


use App\Models\Documents;
use Illuminate\Database\Eloquent\Collection;

class DocumentsRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @return Collection
     */
    public function getByIdUser($idUser)
    {
        return Documents::where('id_user', '=', $idUser)->get();
    }
    
    /**
     * 
     * @param int $id
     * @return string
     */
    public function getPathById($id)
    {
        return Documents::where('id', $id)->value('path');
    }


    public function getById($id)
    {
        return Documents::where('id', $id)->get();
    }


    public function create($params)
    {
        return Documents::create($params);
    }


    public function deleteById($id)
    {
        return Documents::where('id', $id)
            ->delete();
    }
}