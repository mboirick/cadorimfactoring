<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Facture;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Coordonnes_commande;
use App\Paiement;
use App\Commande;
use App\User;
use App\Solde_client;
use App\Cache_table;
use Excel;
use Auth;
use App\Client;

class PayementController extends Controller
{
    //


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    }

    /*public function attente()
    {

        $paiements = DB::table('cadorimpays')
            ->leftJoin('users', 'cadorimpays.id_client', '=', 'users.id_client')
            ->select('cadorimpays.*', 'users.firstname')
            ->where('cadorimpays.statut', '=', '0')
            ->orderBy('cadorimpays.created_at', 'DESC')
            ->paginate(20);



        return view('paiement-mgmt/paiementcourant', [
            'paiements' => $paiements

        ]);
    }*/


   /* public function story()
    {
        $paiements = DB::table('cadorimpays')
            ->leftJoin('users', 'cadorimpays.id_client', '=', 'users.id_client')
            ->select('cadorimpays.*', 'users.firstname')
            ->where('cadorimpays.statut', '!=', '0')
            ->orderBy('cadorimpays.created_at', 'DESC')
            ->paginate(20);

        $documents = DB::table('factures')->get();

        return view('paiement-mgmt/paiementstory', [
            'paiements' => $paiements,
            'documents' => $documents

        ]);
    }*/


    /*public function client(Request $request)
    {
        $nbr_compte = DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->where('solde_client.indice', '=', '1')
            ->where('users.user_type', '=', 'business')
            ->whereNotNull('email_verified_at')->count();

        $solde_eur = DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->where('solde_client.indice', '=', '1')
            ->where('users.user_type', '=', 'business')
            ->sum('solde_client.solde_euros');

        $solde_mru = DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->where('solde_client.indice', '=', '1')
            ->where('users.user_type', '=', 'business')
            ->sum('solde_client.solde_mru');

        $clients = DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->select('users.*', 'solde_client.solde_euros', 'solde_client.solde_mru')
            ->where('solde_client.indice', '=', '1')
            ->where('users.user_type', '=', 'business')
            ->whereNotNull('email_verified_at')
            ->orderBy('solde_client.created_at', 'DESC')
            ->paginate(20);
        $idmax = Cache_table::where('id_client','!=', '99')->max('id');
        $solde_dispo = Cache_table::where('id',  $idmax)->value('solde');

 
     $infos =  ['nbr_compte' => $nbr_compte, 'solde_eur' => floor($solde_eur), 'solde_mru' => floor($solde_mru), 'solde_dispo' =>  floor($solde_dispo)];

        return view('paiement-mgmt/client', [
            'clients' => $clients,
            'infos' => $infos,

        ]);
    }*/


    /*public function editClient($id)
    {

        $clientedit = DB::table('users')
            ->where('id_client', '=', $id)->get();
        $solde = DB::table('solde_client')->where('id_client', '=', $id)->latest()->first();

        return view('paiement-mgmt/editclient', ['clientedit' => $clientedit, 'solde' => $solde]);
    }*/

    /*public function creatclient(Request $request)
    {

        $id_client = uniqid();

        User::create([
            'id_client' => $id_client,
            'user_type' => 'business',
            'firstname' => $request['societe'],
            'name' => $request['nom'],
            'lastname' => $request['prenom'],
            'email' => $request['email'],
            'phone' => $request['telephone'],
            'password' => Hash::make($id_client),
            'email_verified_at' => date("Y-m-d H:i:s")
        ]);
        Solde_client::create([
            'id_client' => $id_client,
            'id_client_debiteur' => 'creation',
            'solde_avant_euros' => 0,
            'solde_avant_mru' => 0,
            'solde_euros' => $request['solde_eur'],
            'solde_mru' => $request['solde_mru'],
            'montant_euros' => $request['solde_eur'],
            'taux' => 0,
            'montant_mru' => $request['solde_mru']

        ]);
        return redirect()->intended('/paiement-management/clients');
    }*/

    /*public function updateClient($id, Request $request)
    {


        $inputcash['nom_benef'] = $request['societe'];

        DB::table('users')->where('id_client', '=', $id)
            ->update([
                'firstname' => $request['societe'],
                'name' => $request['nom'],
                'lastname' => $request['prenom'],
                'phone' => $request['telephone']
            ]);

        DB::table('solde_client')
            ->where('indice', '=', '1')
            ->where('id_client', '=', $id)
            ->update([
                'solde_euros' => $request['solde_eur'],
                'solde_mru' => $request['solde_mru']
            ]);

        return $this->client($request);
    }*/

    public function clientdemande($id)
    {
        $paiements = DB::table('cadorimpays')
            ->leftJoin('users', 'cadorimpays.id_client', '=', 'users.id_client')
            ->where('statut', '=', '0')
            ->where('cadorimpays.id_client', '=', $id)
            ->orderBy('cadorimpays.created_at', 'DESC')
            ->paginate(20);

        $documents = DB::table('factures')->where('id_client', '=', $id)->get();



        return view('paiement-mgmt/paiementcourant', [
            'paiements' => $paiements

        ]);
    }

    public function clientstory($id)
    {
        $paiements = DB::table('solde_client')
            ->where('solde_client.id_client', '=', $id)
            ->orderBy('solde_client.created_at', 'DESC')
            ->paginate(20);

        $comptes = DB::table('users')->select('id_client', 'firstname')->where('user_type', '=', 'business')->get();


        return view('paiement-mgmt/comptestory', [
            'paiements' => $paiements,
            'comptes' => $comptes,

        ]);
    }

    /*public function detail($id_paiement)
    {

        $paiements = DB::table('cadorimpays')
            ->leftJoin('users', 'cadorimpays.id_client', '=', 'users.id_client')
            ->select('cadorimpays.*', 'users.firstname', 'users.email')
            ->where('id_paiement', '=', '' . $id_paiement . '')
            ->where('statut', '=', '0')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        $id_client =  $paiements[0]->id_client;

        $documents = DB::table('factures')->where('id_paiement', '=', $id_paiement)->get();

        $soldes = DB::table('solde_client')
            ->where('id_client', '=', $id_client)
            ->where('indice', '=', '1')
            ->first();

        return view('paiement-mgmt/detail', [
            'paiements' => $paiements[0],
            'documents' => $documents,
            'soldes' => $soldes

        ]);
    }*/

    public function decision(Request $request)
    {


        $id_paiement = $request['paiement'];
        $reponse = $request['remarque'];
        $id_client = $request['id_client'];


        $infos = DB::table('cadorimpays')->where('id_paiement', '=', $id_paiement)->first();



        if ($request['operation'] == 'approuver'  && $infos->statut == 0) {

            $data_avant =  DB::table('solde_client')
                ->where('id_client', '=', $id_client)
                ->where('indice', '=', 1)->first();


            $files = $request->file('document');
            if ($request->hasFile('document')) {
                foreach ($files as $file) {
                    $path = $file->store('factures');
                    $input['type'] = "recu";
                    $input['id_client'] = $id_client;
                    $input['id_paiement'] = $id_paiement;
                    $input['path'] = $path;
                    $input['numero_facture'] = $request['reference'];

                    Facture::create($input);
                }
            }

            if ($request['type_demande'] == 'credit') {
                $input = [
                    'id_client' =>  $id_client,
                    'id_client_debiteur' => $request['type_demande'],
                    'solde_avant_euros' => $data_avant->solde_euros,
                    'solde_avant_mru' => $data_avant->solde_mru,
                    'solde_euros' => $data_avant->solde_euros + $request['montant_euros'],
                    'solde_mru' => $data_avant->solde_mru +  $request['montant_mru'],
                    'montant_euros' => $request['montant_euros'],
                    'taux' => $request['taux_echange'],
                    'montant_mru' => $request['montant_mru'],
                    'indice' => 1,
                    'motif' =>  $reponse,
                    'type_opperation' => $request['type_demande']

                ];
            } else {
                $input = [
                    'id_client' =>  $id_client,
                    'id_client_debiteur' => $infos->entreprise,
                    'solde_avant_euros' => $data_avant->solde_euros,
                    'solde_avant_mru' => $data_avant->solde_mru,
                    'solde_euros' => $data_avant->solde_euros - $request['montant_euros'],
                    'solde_mru' => $data_avant->solde_mru -  $request['montant_mru'],
                    'montant_euros' => $request['montant_euros'],
                    'taux' => $request['taux_echange'],
                    'montant_mru' => $request['montant_mru'],
                    'indice' => 1,
                    'motif' =>  $reponse,
                    'type_opperation' => $request['type_demande']

                ];
            }



            DB::table('solde_client')->where('id_client', '=', $id_client)->update(['indice' => 0]);
            Solde_client::create($input);
            DB::table('cadorimpays')->where('id_paiement', '=', $id_paiement)->update(['statut' => 1, 'reponses' =>  $reponse]);

            return redirect()->intended('/paiement-management/clients');
        }

        if ($request['operation'] == 'rejeter'  && $infos->statut == 0) {

            DB::table('cadorimpays')->where('id_paiement', '=', $id_paiement)->update(['statut' => 2, 'reponses' =>  $reponse]);

            //send notification
            return redirect()->intended('/paiement-management/clients');
        }
    }

    public function crediter($id)
    {

        $clients = DB::table('users')
            ->where('id_client', '!=', $id)->where('user_type', '=', 'business')->get();

        $debiteur = DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->select('users.*', 'solde_client.solde_euros', 'solde_client.solde_mru')
            ->where('solde_client.id_client', '=', $id)
            ->where('solde_client.indice', '=', '1')
            ->first();


        return view('paiement-mgmt/crediter',  ['clients' => $clients, 'debiteur' => $debiteur]);
    }

    /*public function addmontant(Request $request)
    {

        //dd($request);
        $montant_euro = $request['montant_euro'];
        $montant_mru = $request['montant'];


        $solde_debiteur = DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['debiteur'])->first();

        $solde_crediteur = DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['benef'])->first();

        DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['debiteur'])->update([
            'indice' => 0
        ]);

        DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['benef'])->update([
            'indice' => 0
        ]);

        Solde_client::create([
            'id_client' => $request['debiteur'],
            'id_client_debiteur' => $solde_crediteur->id_client,
            'solde_avant_euros' => $solde_debiteur->solde_euros,
            'solde_avant_mru' => $solde_debiteur->solde_mru,
            'solde_euros' => $solde_debiteur->solde_euros + $montant_euro,
            'solde_mru' => $solde_debiteur->solde_mru +  $montant_mru,
            'montant_euros' =>  $montant_euro,
            'taux' => $request['taux'],
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'credit'
        ]);

        Solde_client::create([
            'id_client' => $request['benef'],
            'id_client_debiteur' => $solde_debiteur->id_client,
            'solde_avant_euros' => $solde_crediteur->solde_euros,
            'solde_avant_mru' => $solde_crediteur->solde_mru,
            'solde_euros' => $solde_crediteur->solde_euros - $montant_euro,
            'solde_mru' => $solde_crediteur->solde_mru -  $montant_mru,
            'montant_euros' =>  $montant_euro,
            'taux' => $request['taux'],
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'debit'
        ]);

        return redirect()->intended('/paiement-management/clients');
    }*/


    /*public function debiter($id)
    {

        $clients = DB::table('users')
            ->where('id_client', '!=', $id)->where('user_type', '=', 'business')->get();

        $debiteur = DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->select('users.*', 'solde_client.solde_euros', 'solde_client.solde_mru')
            ->where('solde_client.id_client', '=', $id)
            ->where('solde_client.indice', '=', '1')
            ->first();

        return view('paiement-mgmt/debiter', ['clients' => $clients, 'debiteur' => $debiteur]);
    }*/


    /*public function retirermontant(Request $request)
    {


        $montant_euro = $request['montant_euro'];
        $montant_mru = $request['montant'];


        $solde_debiteur = DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['debiteur'])->first();

        $solde_crediteur = DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['benef'])->first();

        DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['debiteur'])->update([
            'indice' => 0
        ]);

        DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['benef'])->update([
            'indice' => 0
        ]);

        Solde_client::create([
            'id_client' => $request['debiteur'],
            'id_client_debiteur' => $solde_crediteur->id_client,
            'solde_avant_euros' => $solde_debiteur->solde_euros,
            'solde_avant_mru' => $solde_debiteur->solde_mru,
            'solde_euros' => $solde_debiteur->solde_euros - $montant_euro,
            'solde_mru' => $solde_debiteur->solde_mru -  $montant_mru,
            'montant_euros' =>  $montant_euro,
            'taux' => $request['taux'],
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'debit'
        ]);

        Solde_client::create([
            'id_client' => $request['benef'],
            'id_client_debiteur' => $solde_debiteur->id_client,
            'solde_avant_euros' => $solde_crediteur->solde_euros,
            'solde_avant_mru' => $solde_crediteur->solde_mru,
            'solde_euros' => $solde_crediteur->solde_euros + $montant_euro,
            'solde_mru' => $solde_crediteur->solde_mru +  $montant_mru,
            'montant_euros' =>  $montant_euro,
            'taux' => $request['taux'],
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'credit'
        ]);


        return redirect()->intended('/paiement-management/clients');
    }*/

    private function createQueryInput($keys, $request)
    {
        $queryInput = [];
        for ($i = 0; $i < sizeof($keys); $i++) {
            $key = $keys[$i];
            $queryInput[$key] = $request[$key];
        }

        return $queryInput;
    }

    /*public function searchcompte(Request $request)
    {

        //dd($request);
        $clients = DB::table('users')
            ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
            ->select('users.*', 'solde_client.solde_euros', 'solde_client.solde_mru')
            ->where('solde_client.indice', '=', '1')
            ->where('users.user_type', '=', 'business')
            ->where('users.firstname', 'LIKE', '%' . $request['societe'] . '%')
            ->where('users.email', 'LIKE', '%' . $request['email'] . '%')
            ->where('users.phone', 'LIKE', '%' . $request['telephone'] . '%')
            ->whereNotNull('email_verified_at')
            ->orderBy('users.created_at', 'DESC')
            ->paginate(20);


        return view('paiement-mgmt/client', [
            'clients' => $clients,
        ]);
    }*/

    public function depotview()
    {

        $clients = DB::table('users')
            ->where('user_type', '=', 'business')->get();

        return view('paiement-mgmt/depot', ['clients' => $clients]);
    }



    public function retraitview()
    {

        $clients = DB::table('users')
            ->where('user_type', '=', 'business')->get();

        return view('paiement-mgmt/retrait', ['clients' => $clients]);
    }

    public function depotretrait(Request $request)
    {
 

        if ($request['operation'] == 'retrait') {

            $solde = DB::table('solde_client')->where('id_client', '=', $request['id_client'])->where('indice', '=', '1')->first();
            

            if ($solde) {
                

                $input = [
                    'id_client' =>  $request['id_client'],
                    'id_client_debiteur' => $request['expediteur'],
                    'solde_avant_euros' => $solde->solde_euros,
                    'solde_avant_mru' => $solde->solde_mru,
                    'solde_euros' => 0,
                    'solde_mru' => $solde->solde_mru -  $request['montant_mru'],
                    'montant_euros' => 0,
                    'taux' => 0,
                    'montant_mru' => $request['montant_mru'],
                    'motif' =>  $request['message'],
                    'type_opperation' => 'Retrait'

                ];

                $this->soldeupdate($request);

                DB::table('solde_client')->where('id_client', '=', $request['id_client'])->where('indice', '=', '1')->update(['indice' => 0]);
                Solde_client::create($input);
                Session::flash('message', ' Modification effectuée avec success!   تم التعديل بنجاح!'); 

                return redirect()->intended('/cache-management');
            }
        }

        if ($request['operation'] == 'depot') {

            $solde = DB::table('solde_client')->where('id_client', '=', $request['id_client'])->where('indice', '=', '1')->first();

            if ($solde) {
                DB::table('solde_client')->where('id_client', '=', $request['id_client'])->where('indice', '=', '1')->update(['indice' => 0]);

                $input = [
                    'id_client' =>  $request['id_client'],
                    'id_client_debiteur' => $request['expediteur'],
                    'solde_avant_euros' => 0,
                    'solde_avant_mru' => $solde->solde_mru,
                    'solde_euros' => 0,
                    'solde_mru' => $solde->solde_mru +  $request['montant_mru'],
                    'montant_euros' => 0,
                    'taux' => 0,
                    'montant_mru' => $request['montant_mru'],
                    'motif' =>  $request['message'],
                    'type_opperation' => 'Dépot'

                ];

                Solde_client::create($input);

                Session::flash('message', ' Modification effectuée avec success!  تم التعديل بنجاح!'); 

                return redirect()->intended('/cache-management');
            }
        }
    }

    public function search(Request $request)
    {


        if ($request['search'] == 'excel') {

            $this->prepareExportingData()->export('xlsx');
        } elseif ($request['search'] == 'recherche') {

            return redirect()->intended('/paiement-management/attente');
        }
    }


    private function prepareExportingData()
    {
        $author = Auth::user()->username;
        $employees = $this->getExportingData();
        return Excel::create('Rapport', function ($excel) use ($employees, $author) {

            // Set the title
            $excel->setTitle('Excel ');

            // Chain the setters
            $excel->setCreator($author)
                ->setCompany('Cadorim');

            // Call them separately
            $excel->setDescription('rapport');

            $excel->sheet('Rapport', function ($sheet) use ($employees) {

                $sheet->fromArray($employees);
            });
        });
    }


    private function getExportingData()
    {
        return DB::table('cadorimpays')
            ->leftJoin('users', 'cadorimpays.id_client', '=', 'users.id_client')
            ->select('cadorimpays.*', 'users.firstname')
            ->where('cadorimpays.statut', '=', '0')
            ->get()
            ->map(function ($item, $key) {
                return (array) $item;
            })
            ->all();
    }


    private function soldeupdate($request)
    {

    

                $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
                $solde = Cache_table::where('id',  $idmax)->value('solde');
                $client=User::where('id_client',  $request['id_client'])->value('firstname');
                $charge = $request['montant_mru'];

                //$solde = $solde - $charge;
                $input['id_client'] = $request['id_client'];
                $input['nom_benef'] =$request['operation'].'__'.$client;
                $input['expediteur'] = Auth::user()->email;
                $input['montant_euro'] = 0;
                $input['montant'] = $request['montant_mru'];
                $input['solde_avant'] = $solde;
                $input['phone_benef'] = $request['message'];
                $input['operation'] = $request['operation'];
                $input['solde'] =  $solde - $charge;
                $input['solde_apres'] = $solde -  $charge;

                Cache_table::create($input);
            
        
    }
}
