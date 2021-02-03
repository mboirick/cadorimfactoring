<?php


namespace App\Repositories\Backend;


use App\Models\Subscribers;
use Illuminate\Support\Facades\DB;

class SubscribersRepository
{

    public function getByStatusAndKycGroupeBy($status, $kyc, array $params)
    {
      return  DB::table('abonnes as A')
        ->leftJoin('paiements as P', 'A.email', '=', 'P.payer_email')
        ->where('A.kyc', '=', $kyc)
        ->where('P.payment_status', '=', $status)
         ->where('email', 'like', '%' . $params['email'] . '%')
        ->where('phone', 'like', '%' . $params['phone'] . '%')
        ->where('username', 'like', '%' . $params['username'] . '%')
        ->select(array(
          DB::Raw('MAX(P.payment_date) as lastDate'),
          DB::Raw('count(*) as nbr'),
          DB::Raw('A.email'),
          DB::Raw('A.id'),
          DB::Raw('sum(P.payment_amount) as somme'),
        
        ))
        ->groupBy('A.email')
        ->groupBy('A.id')
        ->paginate(20);
    }

    public function update($id, array $params)
    {
        return Subscribers::where('id', $id)
              ->update($params);
    }


    public function getByStatusAndKyc($status, $kyc)
    {
        return DB::table('abonnes as A')
          ->leftJoin('paiements as P', 'A.email', '=', 'P.payer_email')
          ->where('A.kyc', '=', $kyc)
          ->where('P.payment_status', '=', $status)
          ->select('A.email')
          ->groupBy('A.email')
          ->get();
    }

    public function getByCriterion($constraints)
    {
      return  Subscribers::where('username', 'like', '%' . $constraints['username'] . '%')
        ->where('email', 'like', '%' . $constraints['email'] . '%')
        ->where('phone', 'like', '%' . $constraints['phone'] . '%')
        ->where('kyc', 'like', '%' . $constraints['kyc'] . '%')

        ->orderBy('confirmed_at', 'DESC')
        ->paginate(20);
    }

    public function getStatus()
    {
        return Subscribers::select(array(
            DB::Raw('sum(CASE WHEN (kyc = 3) THEN 1 ELSE 0 END) as nbr_check'),
            DB::Raw('sum(CASE WHEN (kyc = 1) THEN 1 ELSE 0 END) as nbr_approuved'),
            DB::Raw('sum(CASE WHEN (kyc = 2) THEN 1 ELSE 0 END) as nbr_rejeter'),
            DB::Raw('sum(CASE WHEN (kyc = 0) THEN 1 ELSE 0 END) as nbr_attente'),

          ))
          ->first();
    }

    public function getAllOrderBy()
    {
        return Subscribers::orderBy('confirmed_at', 'DESC')
            ->paginate(20);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return Subscribers::where('id', '=', $id)->get();
    }

    public function getByEmail($email)
    {
        return Subscribers::where('email', '=', $email)->value('id');
    }

    /**
     * @param $dateStart
     * @param $dateend
     * @param $numbre
     * @return mixed
     */
    public function searchByCriterion($dateStart, $dateend, $numbre)
    {
        $query = "CALL searchByCritere('{$dateStart}', '{$dateend}', '{$numbre}')";

        return DB::select($query);
    }

    /**
     * @param $month
     * @param $year
     * @return mixed
     */
    public function getCountByMonthAndYear($month, $year)
    {
        return Subscribers::whereMonth('confirmed_at', '=', date('m'))
            ->whereYear('confirmed_at', '=', date('Y'))
            ->count();
    }

    /**
     * @return mixed
     */
    public function getAllSponsoring()
    {
        return DB::table('abonnes as A')
            ->leftJoin('abonnes as B', 'A.id', '=', 'B.id_parrain')
            ->select(array(
                    DB::Raw('count(B.id_parrain) AS number'),
                    DB::Raw('A.id'),
                    DB::Raw('A.prenom'),
                    DB::Raw('A.email'),
                    DB::Raw('A.username'),
                    DB::Raw('A.pays_residence'),
                    DB::Raw('A.id_parrain'),
                )
            )
            ->groupBy('A.id')
            ->groupBy('A.prenom')
            ->groupBy('A.email')
            ->groupBy('A.username')
            ->groupBy('A.pays_residence')
            ->groupBy('A.id_parrain')
            ->orderBy('number', 'DESC')
            ->paginate(20);
    }

    /**
     * @param $name
     * @param $email
     * @param $nbr
     * @return mixed
     */
    public function getSearchSponsoringByCriterion($name, $email, $nbr)
    {
        return DB::table('abonnes as A')
            ->leftJoin('abonnes as B', 'A.id', '=', 'B.id_parrain')
            ->select(array(
                    DB::Raw('count(B.id_parrain) AS number'),
                    DB::Raw('A.id'),
                    DB::Raw('A.prenom'),
                    DB::Raw('A.email'),
                    DB::Raw('A.username'),
                    DB::Raw('A.pays_residence'),
                    DB::Raw('A.id_parrain'),
                )
            )
            ->where('A.username', 'like', '%' . $name . '%')
            ->where('A.email', 'like', '%' . $email . '%')
            ->groupBy('A.id')
            ->groupBy('A.prenom')
            ->groupBy('A.email')
            ->groupBy('A.username')
            ->groupBy('A.pays_residence')
            ->groupBy('A.id_parrain')
            ->havingRaw('count(B.id_parrain) >= ?',  [intval ($nbr)])
            ->orderBy('number', 'DESC')
            ->paginate(20);
    }

    public function getByEmailId($id, $email)
    {
        return Subscribers::select('id')
                    ->where('email', $email)
                    ->where('id', $id)->first();
    }
}