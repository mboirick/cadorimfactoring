<?php


namespace App\Repositories\Backend;


use App\Models\Cash;
use Illuminate\Support\Facades\DB;

class CashRepository
{
    public function getByCodeConfirmation($idCommand)
    {
        return Cash::where('code_confirmation', $idCommand)->value('code_confirmation');
    }

    /**
     * Get all of the tasks for a given user.
     *
     * @return Collection
     */
    public function getBalance()
    {
        return Cash::orderBy('id', 'desc')
            ->value('solde');
    }

    /**
     * @return mixed
     */
    public function getBalanceLatest()
    {
        return Cash::where('id_client', '!=', '99')
            -> latest()->value('solde');
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getByLastBalanceByDate($date)
    {
        $balanceEnd =  Cash::where('id_client', '!=', '99')
            ->select('solde', 'solde_avant', 'id')
            ->whereDate('created_at', 'like', '%' . $date . '%')
            ->orderByDesc('id')
            ->first();

        $balanceStart =  DB::table('cache_tables')
            ->where('id_client', '!=', '99')
            ->whereDate('created_at', 'like', '%' . $date . '%')
            ->select('solde_avant', 'id')->orderBy('id')->first();

        if($balanceEnd)
            $balanceEnd->start = isset($balanceStart)?$balanceStart->solde_avant:0;

        return $balanceEnd;
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getBalanceClientByDate($date)
    {
        $query = "CALL searchBalanceClientByDate('{$date}')";

        return DB::select($query);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return Cash::orderBy('id', 'desc')
            ->where('id', '=', $id)->first();
    }

    /**
     * @return mixed
     */
    public function getAvailableBalance()
    {
        $idMax = Cash::where('id_client', '!=', '99')->max('id');

        return Cash::where('id',  $idMax)->value('solde');
    }
    

    public function getAvailableBalanceByIdMax()
    {
        $idMax = Cash::max('id');

        return Cash::select('*')->where('id',  $idMax)->first();
    }

    /**
     * @param array $params
     * @return Cash|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $params)
    {
        return Cash::create([
            'id_client'     =>  $params['id_client'],
            'expediteur'    => $params['expediteur'],
            'nom_benef'     => $params['nom_benef'],
            'phone_benef'   => $params['phone_benef'],
            'montant_euro'  => $params['montant_euro'],
            'montant'       => $params['montant'],
            'operation'     =>  $params['operation'],
            'solde_avant'   => $params['solde_avant'],
            'solde_apres'   =>  $params['solde_apres'],
            'solde'         => $params['solde']

        ]);
    }

    /**
     * @param $email
     * @param $day
     * @return mixed
     */
    public function getOperationsByEmailAndDAy($email, $day)
    {
        return DB::table('cache_tables as w')
            ->leftJoin('coordonnes_commandes', 'w.code_confirmation', '=', 'coordonnes_commandes.id_commande')
            ->where('expediteur', '=', $email)
            ->whereDate('created_at', '>', $day)
            ->select(array(
                DB::Raw('count(*) as nbr'),
                DB::Raw('sum(w.montant_euro) as somme_euro'),
                DB::Raw('sum(w.montant) as somme'),
                DB::Raw('sum(coordonnes_commandes.frais_gaza) as somme_gaza'),
                DB::Raw('DATE(w.created_at) day')
            ))
            ->groupBy('day')
            ->get();
    }

    /**
     * @param $email
     * @param $day
     * @return mixed
     */
    public function getDetailOperationsByEmailAndDAy($email, $day)
    {
        return DB::table('cache_tables as w')
            ->leftJoin('coordonnes_commandes as c', 'w.code_confirmation', '=', 'c.id_commande')
            ->select('w.*', 'c.frais_gaza', 'c.agence_gaza')
            ->where('expediteur', '=', $email)
            ->whereDate('w.created_at', '=', $day)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
    }

    /**
     * @param $email
     * @param $day
     * @return mixed
     */
    public function getOperationsUpdateByEmailAndDAy($email, $day)
    {
        return  DB::table('cache_tables as w')
            ->leftJoin('coordonnes_commandes', 'w.code_confirmation', '=', 'coordonnes_commandes.id_commande')
            ->where('expediteur', '=', $email)
            ->whereDate('created_at', '=', $day)
            ->select(array(
                DB::Raw('count(*) as nbr'),
                DB::Raw('sum(w.montant_euro) as somme_euro'),
                DB::Raw('sum(w.montant) as somme'),
                DB::Raw('sum(coordonnes_commandes.frais_gaza) as somme_gaza'),
                DB::Raw('DATE(w.created_at) day')
            ))
            ->groupBy('day')
            ->get();
    }

    /**
     * @param $createDate
     * @param $updateDate
     * @return mixed
     */
    public function getCashByCreateDateUpdateDate($createDate, $updateDate)
    {
        return Cash::select('cache_tables.*')
            ->where('created_at', '>=', $createDate)
            ->where('created_at', '<=', $updateDate)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
    }

    /**
     * @return mixed
     */
    public function getMaxId()
    {
        return Cash::where('id_client', '!=', '99')->max('id');
    }

    /**
     * @param $constraints
     * @return mixed
     */
    public function getCashByCriterion($constraints)
    {
        return Cash::select('cache_tables.*')
            ->where('created_at', '>=', $constraints['dateStart'])
            ->where('created_at', '<=', $constraints['dateEnd'])
            ->where('operation', 'like', '%' . $constraints['type'] . '%')
            ->where('id_client',  $constraints['idClient'])
            ->orWhere('nom_benef', 'like', '%' . $constraints['idClient'] . '%')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        return $cashs;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateBalance(array $params)
    {
        return Cash::where('id', '=', $params['id'])->update([
            'montant' => $params['amount'],
            'solde_apres' => $params['newBalance'],
            'solde' => $params['balance'],
            'phone_benef' => $params['phone']
        ]);
    }

    public function getByOperationAndDate($date, $operation)
    {
        return Cash::where('operation', '=', $operation)
            ->whereDate('created_at', 'like', '%' . $date . '%')
            ->select('*')
            ->get();
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getCashOutByDate($date)
    {
        return DB::table('cache_tables as w')
            ->leftJoin('coordonnes_commandes as c', 'w.code_confirmation', '=', 'c.id_commande')
            ->where('operation', '=', 'retrait')
            ->whereDate('created_at', 'like', '%' . $date . '%')
            ->select(array(
                'w.expediteur',
                DB::Raw('count(*) as nbr'),
                DB::Raw('sum(w.montant) as somme'),
                DB::Raw('sum(c.frais_gaza) as somme_gaza'),
                DB::Raw('sum(CASE WHEN (frais_gaza = 0) THEN w.montant ELSE 0 END) as somme_local'),
            ))
            ->groupBy('w.expediteur')
            ->get();
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getCashOutTotalByDate($date)
    {
        return DB::table('cache_tables as w')
            ->leftJoin('coordonnes_commandes as c', 'w.code_confirmation', '=', 'c.id_commande')
            ->where('operation', '=', 'retrait')
            ->whereDate('created_at', 'like', '%' . $date . '%')
            ->select(array(
                DB::Raw('count(*) as nbr'),
                DB::Raw('sum(w.montant) as somme'),
                DB::Raw('sum(c.frais_gaza) as somme_gaza'),
                DB::Raw('sum(CASE WHEN (frais_gaza = 0) THEN w.montant ELSE 0 END) as somme_local'),
            ))->get();
    }

    /**
     * @param $index
     * @param $date
     * @return mixed
     */
    public function getBalanceBusinessByIndex($index, $date)
    {
        $query = "CALL searchBalanceBusinessByIndex('{$index}', '{$date}')";

        return DB::select($query);
    }

    /**
     * @param $id
     * @param $invoices
     * @return mixed
     */
    public function updateInvoicesById($id, $invoices)
    {
        $cash = $this->getById($id);
        if(!empty($cash) && isset($cash->invoices)){
            $invoices = $invoices . ',' . $cash->invoices;
        }

        return Cash::where('id', '=', $id)
            ->update([
                'invoices' => $invoices
            ]);
    }


    public function addfilesInvonces()
    {
        $all =  Cash::all();

            
        foreach ($all as $cash) {
            $documents = DB::table('cashdocuments')
            ->where('id_operation', '=', $cash->id_operation)
            ->get();

            $path = '';
             foreach ($documents as $document) {
                if(empty($path))
                    $path  = str_replace('cashdocuments/', '', $document->path);
                else
                $path  .= ',' . str_replace('cashdocuments/', '', $document->path);
                
            }

             if(!empty($path)){

                DB::table('cache_tables')
                ->where('id_operation', '=', $cash->id_operation)
                ->update([
                        'invoices' => $path
                    ]);
             }
        }
    }
}