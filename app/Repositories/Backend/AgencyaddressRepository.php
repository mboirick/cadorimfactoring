<?php


namespace App\Repositories\Backend;


use App\Models\Agencyaddress;
use Illuminate\Http\Request;

class AgencyaddressRepository
{
    /**
     * @param array $params
     * @return Agencyaddress|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $params)
    {
        return Agencyaddress::create([
            "id_agence" => $params['idClient'],
            "ville" => $params['ville'],
            "quartier" => $params['quartier']
        ]);
    }

    /**
     * @param $idClient
     * @param $params
     * @return bool
     */
    public function updateByIdClient($idClient, $params)
    {
        return Agencyaddress::where('id_agence', '=', $idClient)
            ->update([
                "ville" => $params['ville'],
                "quartier" => $params['quartier']
            ]);
    }
}