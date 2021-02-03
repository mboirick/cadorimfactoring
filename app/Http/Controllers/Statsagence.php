<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cache_table;
use App\Stat_agence;


class Statsagence extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index($id)
    {

        $email = DB::table('users')
            ->where('id_client', '=', $id)->value('email');

        $day =  DB::table('stat_agences')
            ->where('email_agence', '=', $email)
            ->latest('jours')
            ->value('jours');

        if ($email) {

            if ($day) {
            } else {

                $day = '2008-01-01';
            }



            $operations =  DB::table('cache_tables as w')
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

            $update =  DB::table('cache_tables as w')
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


            foreach ($operations as $operation) {
                $input = [
                    'id_agence' => $id,
                    'email_agence' => $email,
                    'jours' => $operation->day,
                    'nbr_operation' =>  $operation->nbr,
                    'total' =>  $operation->somme,
                    'total_gaza' =>  $operation->somme_gaza,
                    'total_euro' =>  $operation->somme_euro
                ];
                Stat_agence::create($input);
            }

            foreach ($update as $operation) {
                DB::table('stat_agences')
                    ->where('jours', '=', $day)
                    ->where('email_agence', '=', $email)
                    ->update([
                        'id_agence' => $id,
                        'email_agence' => $email,
                        'jours' => $operation->day,
                        'nbr_operation' =>  $operation->nbr,
                        'total' =>  $operation->somme,
                        'total_gaza' =>  $operation->somme_gaza,
                        'total_euro' =>  $operation->somme_euro

                    ]);
            }

            $operations =  DB::table('stat_agences')
                ->where('email_agence', '=', $email)
                ->orderBy('jours', 'DESC')
                ->paginate(20);

            return view('agence-mgmt/operationstory', [
                'operations' => $operations,


            ]);
        }
    }
}
