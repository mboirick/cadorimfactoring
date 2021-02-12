<?php

namespace App\Repositories\Backend;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function getAllByUserType($userType)
    {
        return User::select('id_client', 'firstname')
                    ->where('user_type', '=', $userType)
                    ->get();
    }

    public function delete($id)
    {
        return $this->getById($id)->delete();
    }

    public function restore($id)
    {
        return User::withTrashed()
                    ->findorfail($id)
                    ->restore();
    }

    public function update($id, $params)
    {
        return User::where('id', $id)
                    ->update($params);
    }

    public function getWithTrashedById($id)
    {
        return  User::where('id',$id)
                        ->withTrashed()
                        ->first();
    } 

    public function getAllByCriterion(array $params)
    {
        return  User::where('users.user_type','like',  '%' . $params['userType'] . '%')
                ->where('users.lastname','like',  '%' . $params['lastName'] . '%')
                ->where('users.firstname','like',  '%' . $params['firstName'] . '%')
                ->where('users.email','like',  '%' . $params['email'] . '%')
                ->withTrashed() 
                ->select('users.*')
                ->paginate(20);
    }

    public function getClientsByIndiceUserType($indice, $userType)
    {
        return  User::leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->select('users.*', 'solde_client.solde_euros', 'solde_client.solde_mru')
            ->where('solde_client.indice', '=', $indice)
            ->where('users.user_type', '=', $userType)
            ->whereNotNull('email_verified_at')
            ->orderBy('solde_client.created_at', 'DESC')
            ->paginate(20);
    }

    public function getClientsByCriterion($params)
    {
        return DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->select('users.*', 'solde_client.solde_euros', 'solde_client.solde_mru')
            ->where('solde_client.indice', '=', '1')
            ->where('users.user_type', '=', 'business')
            ->where('users.firstname', 'LIKE', '%' . $params['societe'] . '%')
            ->where('users.email', 'LIKE', '%' . $params['email'] . '%')
            ->where('users.phone', 'LIKE', '%' . $params['telephone'] . '%')
            ->whereNotNull('email_verified_at')
            ->orderBy('users.created_at', 'DESC')
            ->paginate(20);
    } 

    public function getByIdUser($id)
    {
        return User::
            leftJoin('adresse_agences', 'adresse_agences.id_agence', '=', 'users.id_client')
            ->select('users.*',  'adresse_agences.*')
            ->where('users.id', '=', $id)->first();
    }

    public function getUserById($id)
    {
        return User::where('users.id', '=', $id)->first();
    }

    /**
     * @param $id
     * @return User|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null
     */
    public function getById($id)
    {
        return User::
            leftJoin('adresse_agences', 'adresse_agences.id_agence', '=', 'users.id_client')
            ->select('users.*',  'adresse_agences.*')
            ->where('users.id_client', '=', $id)->first();
    }

    /**
     * @param $userType
     * @param $index
     * @param $columnSum
     * @return mixed
     */
    public function getSumByColumns($userType, $index, $columnSum)
    {
        return User::leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->where('solde_client.indice', '=', $index)
            ->where('users.user_type', '=', $userType)
            ->sum('solde_client.'.$columnSum);

            $solde_eur = DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->where('solde_client.indice', '=', '1')
            ->where('users.user_type', '=', 'business')
            ->sum('solde_client.solde_euros');
    }

    /**
     * @param $idClient
     * @return mixed
     */
    public function getValuesByIdClient($idClient)
    {
        return User::join('stat_agences', 'stat_agences.email_agence', '=', 'users.email')
            ->select('users.email',  'stat_agences.jours')
            ->where('users.id_client', '=', $idClient)
            ->orderBy('stat_agences.jours', 'DESC')->first();
    }

    /**
     * @param $idClient
     * @return mixed
     */
    public function getEmailByIdClient($idClient)
    {
        return User::where('id_client', '=', $idClient)->value('email');
    }

    /**
     * @param $id
     * @param $userType
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getExcludeById($id, $userType)
    {
        return User::where('id_client', '!=', $id)
            ->where('user_type', '=', $userType)
            ->get();
    }

    /**
     * @param $column
     * @param $value
     * @return User|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null
     */
    public function getByCriterion($column, $value)
    {
        return User::where($column, '=', $value)->get();
    }

    /**
     * @param $index
     * @param $userType
     * @return mixed
     */
    public function getByIndexAndUserType($index, $userType)
    {
        return User::leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->select('users.*')
            ->where('agences.indice', '=', $index)
            ->where('users.user_type', '=', $userType)
            ->orderBy('users.firstname', 'ASC')->get();
    }

    /**
     * @param $index
     * @param $userType
     * @return mixed
     */
    public function getClientBalanceByIndexAndUserType($index, $userType)
    {
        return User::leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->select('users.id_client', 'users.firstname')
            ->where('user_type', '=', $userType)
            ->where('solde_client.indice', '=', $index)
            ->orderBy('users.firstname', 'ASC')->get();
    }

    /**
     * @param $id
     * @param $newPassword
     * @return bool
     */
    public function updatePassword($id, $newPassword)
    {
        return User::where('id_client', '=', $id)
            ->update([
                'password' => Hash::make($newPassword)
            ]);
    }

    /**
     * @param $id
     * @param array $params
     * @return bool
     */
    public function updateById($id, $params)
    {
        return User::where('id_client', '=', $id)
            ->update([
                'firstname' => $params['agence'],
                'name' => $params['nom'],
                'lastname' => $params['prenom'],
                'phone' => $params['telephone']
            ]);
    }

    /**
     * @param array $params
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $params)
    {
        return User::create([
            'id_client' => $params['idClient'],
            'user_type' => $params['user_type'],
            'firstname' => $params['agence'],
            'name'      => $params['nom'],
            'lastname'  => $params['prenom'],
            'email' => $params['email'],
            'phone' => $params['telephone'],
            'password' => Hash::make($params['idClient']),
            'email_verified_at' => date("Y-m-d H:i:s")
        ]);
    }

    public function createUser(array $params)
    {
        return User::create($params);
    }

    /**
     * get return number of accounts
     * @return array()
     */
    public function getNumberOfAccounts($indice, $userType)
    {
        return User::leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->select('users.*',  'agences.solde_mru')
            ->where('agences.indice', '=', $indice)
            ->where('users.user_type', '=', $userType)
            ->whereNotNull('email_verified_at')->count();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getByCriteria(array $params)
    {
        return User::leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->leftJoin('adresse_agences', 'adresse_agences.id_agence', '=', 'agences.id_client')
            ->select('users.*', 'agences.solde_mru', 'adresse_agences.*')
            ->where('agences.indice', '=', $params['index'])
            ->where('users.user_type', '=', $params['user_type'])
            ->where('users.firstname', 'LIKE', '%' . $params['firstname'] . '%')
            ->where('users.email', 'LIKE', '%' . $params['email'] . '%')
            ->where('users.phone', 'LIKE', '%' . $params['phone'] . '%')
            ->whereNotNull('email_verified_at')
            ->orderBy('users.created_at', 'DESC')
            ->paginate(20);
    }

    public function getByTypeAndIndex($userType, $index)
    {
        return User::leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->leftJoin('adresse_agences', 'adresse_agences.id_agence', '=', 'agences.id_client')
            ->select('users.*', 'agences.solde_mru', 'adresse_agences.*')
            ->where('agences.indice', '=', $index)
            ->where('users.user_type', '=', $userType)
            ->whereNotNull('email_verified_at')
            ->orderBy('users.created_at', 'DESC')
            ->get();
    }

    /**
     * @param $index
     * @param $idClient
     * @return User|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null
     */
    public function getByIndexAndIdClient($index, $idClient)
    {
        return User::leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->select('users.*',  'agences.solde_mru', 'agences.montant_mru')
            ->where('agences.id_client', '=', $idClient)
            ->where('agences.indice', '=', $index)
            ->first();
    }

    /**
     * @param $userType
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getByUserType($userType)
    {
        return User::where('user_type', $userType)
            ->orderBy('firstname', 'asc')
            ->get();
    }

    public function getByUserTypes(array $types)
    {
        return User::whereIn('user_type', $types)
            ->orderBy('firstname', 'asc')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getUsersBusiness()
    {
        return User::where('user_type', '=', 'business')
            ->whereNotNull('email_verified_at')
            ->orderBy('firstname', 'asc')
            ->get();
    }

    /**
     * @param $idClient
     * @param $column
     * @return mixed
     */
    public function getValueByIdClient($idClient, $column)
    {
        return User::where('id_client',  $idClient)->value($column);
    }

    public function getClientsByUserType($id, $userType)
    {
        return User::where('id_client', '!=', $id)
                ->where('user_type', '=', $userType)
                ->get();
    }

    public function getByIdClient($idClient)
    {
        return User::leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->select('users.*', 'solde_client.solde_euros', 'solde_client.solde_mru')
            ->where('solde_client.id_client', '=', $idClient)
            ->where('solde_client.indice', '=', '1')
            ->first();
    }

    public function getUserByIdClient($idClient)
    {
        return User::where('id_client', '=', $idClient)
                    ->get();
    }
}
