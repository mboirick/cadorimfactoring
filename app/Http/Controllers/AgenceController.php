<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Employee;
use App\City;
use App\User;
use App\Country;
use App\Department;
use App\Division;
use App\Cache_table;
use App\Adresse_agence;
use App\Agence;
use Illuminate\Support\Facades\Hash;
use Auth;

class AgenceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function index(Request $request)
    {
        /*$nbr_compte = DB::table('users')
            ->leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->select('users.*',  'agences.solde_mru')
            ->where('agences.indice', '=', '1')
            ->where('users.user_type', '=', 'operateur')
            ->whereNotNull('email_verified_at')->count();

        $solde_mru = DB::table('users')
            ->leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->where('agences.indice', '=', '1')
            ->where('users.user_type', '=', 'operateur')
            ->sum('agences.solde_mru');

        $clients = DB::table('users')
            ->leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->leftJoin('adresse_agences', 'adresse_agences.id_agence', '=', 'agences.id_client')
            ->select('users.*', 'agences.solde_mru', 'adresse_agences.*')
            ->where('agences.indice', '=', '1')
            ->where('users.user_type', '=', 'operateur')
            ->whereNotNull('email_verified_at')
            ->orderBy('users.created_at', 'DESC')
            ->paginate(20);
        $idmax = Cache_table::max('id');
        $solde_dispo = Cache_table::where('id',  $idmax)->value('solde');


        $request->session()->put('solde', ['nbr_compte' => $nbr_compte, 'solde_mru' => round($solde_mru), 'solde_dispo' =>  round($solde_dispo)]);

        return view('agence-mgmt/index', [
            'clients' => $clients

        ]);*/
    /*}*/

    /*public function addagence()
    {

        $villes = DB::table('city')->select('id', 'name')->orderBy('id')->get();

        return view('agence-mgmt/addagence', ['villes'  => $villes]);
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create(Request $request)
    {


        $id_agence = uniqid();

        $this->validate($request, [
            'email' => 'required|unique:users|max:255',
        ]);

        User::create([
            'id_client' => $id_agence,
            'user_type' => 'operateur',
            'firstname' => $request['agence'],
            'name' => $request['nom'],
            'lastname' => $request['prenom'],
            'email' => $request['email'],
            'phone' => $request['telephone'],
            'password' => Hash::make($id_agence),
            'email_verified_at' => date("Y-m-d H:i:s")
        ]);

        Agence::create([
            "id_client" => $id_agence,
            "id_client_debiteur" => Auth::user()->email,
            "motif" => 'Creation compte',
            "type_opperation" => 'Creation'

        ]);
        Adresse_agence::create(["id_agence" => $id_agence, "ville" => $request['ville'], "quartier" => $request['quartier']]);

        return redirect()->intended('/agence-management');
    }*/

    /*public function editagence($id)
    {

        $clientedit = DB::table('users')
            ->leftJoin('adresse_agences', 'adresse_agences.id_agence', '=', 'users.id_client')
            ->select('users.*',  'adresse_agences.*')
            ->where('users.id_client', '=', $id)->first();
        $villes = DB::table('city')->select('id', 'name')->orderBy('id')->get();

        return view('agence-mgmt/editagence', ['clientedit' => $clientedit, 'villes' => $villes]);
    }*/

    public function retrait()
    {

        $countries = Country::all();
        $departments = Department::all();
        $divisions = Division::all();
        return view('cache-mgmt/retrait', [
            'countries' => $countries,
            'departments' => $departments, 'divisions' => $divisions
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validateInput($request);
        // Upload image
        $path = $request->file('picture')->store('avatars');
        $keys = [
            'nom_agence', 'nom_responsable', 'phone',
            'adresse', 'heure_ouverture', 'heure_fermeture', 'jours_ouvrable', 'latitude',  'longitude'
        ];
        $input = $this->createQueryInput($keys, $request);
        $input['picture'] = $path;
        // Not implement yet
        // $input['company_id'] = 0;
        Agence::create($input);

        return redirect()->intended('/agence-management');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function edit($id)
    {
        $agence = Agence::find($id);
        // Redirect to state list if updating state wasn't existed
        if ($agence == null || empty($agence)) {
            return redirect()->intended('/agence-management');
        }

        return view('agence-mgmt/edit', ['agence' => $agence]);
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request, $id)
    {

        if ($request['password']) {
            $this->validate($request, [

                'password' => 'required|min:6|confirmed',
            ]);

            DB::table('users')->where('id_client', '=', $id)
                ->update([
                    'firstname' => $request['agence'],
                    'name' => $request['nom'],
                    'lastname' => $request['prenom'],
                    'phone' => $request['telephone'],
                    'password' => Hash::make($request['password'])
                ]);
        } else {

            DB::table('users')->where('id_client', '=', $id)
                ->update([
                    'firstname' => $request['agence'],
                    'name' => $request['nom'],
                    'lastname' => $request['prenom'],
                    'phone' => $request['telephone']

                ]);
        }




        DB::table('adresse_agences')->where('id_agence', '=', $id)
            ->update([
                "ville" => $request['ville'],
                "quartier" => $request['quartier']
            ]);

        return redirect()->intended('/agence-management');
    }*/
/*
    public function crediter($id)
    {

        $clients = DB::table('users')
            ->where('id_client', '!=', $id)->where('user_type', '=', 'operateur')->get();

        $debiteur = DB::table('users')
            ->leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->select('users.*',  'agences.solde_mru')
            ->where('agences.id_client', '=', $id)
            ->where('agences.indice', '=', '1')
            ->first();


        return view('agence-mgmt/crediteragence',  ['clients' => $clients, 'debiteur' => $debiteur]);
    }*/

/*
    public function debiter($id)
    {

        $clients = DB::table('users')
            ->where('id_client', '!=', $id)->where('user_type', '=', 'operateur')->get();

        $debiteur = DB::table('users')
            ->leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->select('users.*',  'agences.solde_mru')
            ->where('agences.id_client', '=', $id)
            ->where('agences.indice', '=', '1')
            ->first();

        return view('agence-mgmt/debiteragence', ['clients' => $clients, 'debiteur' => $debiteur]);
    }*/


    public function destroy($id)
    {
        Agence::where('id', $id)->delete();
        return redirect()->intended('/agence-management');
    }

    /**
     * Search state from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    /*public function search(Request $request)
    {

        //dd($request-> all());
        $constraints = [
            'nom_agence' => $request['agence'],
            'nom_responsable' => $request['responsable'],
            'phone' => $request['telephone']
        ];
        $agences = $this->doSearchingQuery($constraints);

        //dd($constraints);

        return view('agence-mgmt/index', ['agences' => $agences, 'searchingVals' => $constraints]);
    }*/

    private function doSearchingQuery($constraints)
    {
        $query = DB::table('agences')

            ->select('agences.*');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where($fields[$index], 'like', '%' . $constraint . '%');
            }

            $index++;
        }
        return $query->paginate(8);
    }

    /**
     * Load image resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function load($name)
    {
        $path = storage_path() . '/app/avatars/' . $name;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    private function validateInput($request)
    {
        $this->validate($request, [

            'nom_agence' => 'required',
            'nom_responsable' => 'required',
            'phone' => 'required|max:15',
            'adresse' => 'required',
            'heure_ouverture' => 'required',
            'heure_fermeture' => 'required',
            'jours_ouvrable' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',




        ]);
    }

    private function createQueryInput($keys, $request)
    {
        $queryInput = [];
        for ($i = 0; $i < sizeof($keys); $i++) {
            $key = $keys[$i];
            $queryInput[$key] = $request[$key];
        }

        return $queryInput;
    }

/*
    public function depotagenceview()
    {

        $clients = DB::table('users')
            ->where('user_type', '=', 'operateur')->get();

        return view('agence-mgmt/depotagence', ['clients' => $clients]);
    }*/



    /*public function retraitagenceview()
    {var_dump('gggg');die;

        $clients = DB::table('users')
            ->where('user_type', '=', 'operateur')->get();

        return view('agence-mgmt/retraitagence', ['clients' => $clients]);
    }*/


    /*public function depotretraitagence(Request $request)
    {


        if ($request['operation'] == 'retrait') {

            $solde = DB::table('agences')->where('id_client', '=', $request['id_client'])->where('indice', '=', '1')->first();

            if ($solde) {
                DB::table('agences')->where('id_client', '=', $request['id_client'])->where('indice', '=', '1')->update(['indice' => 0]);

               $input = [
                    'id_client' =>  $request['id_client'],
                    'id_client_debiteur' =>  Auth::user()->email,
                    'solde_avant_mru' => $solde->solde_mru,
                    'solde_mru' => $solde->solde_mru -  $request['montant_mru'],
                    'montant_mru' => $request['montant_mru'],
                    'motif' =>  $request['message'],
                    'type_opperation' => 'retrait'

                ];

                Agence::create($input);

                return redirect()->intended('/agence-management');
            }
        }

        /*if ($request['operation'] == 'depot') {

            $solde = DB::table('agences')->where('id_client', '=', $request['id_client'])->where('indice', '=', '1')->first();

            if ($solde) {
                DB::table('agences')->where('id_client', '=', $request['id_client'])->where('indice', '=', '1')->update(['indice' => 0]);

                $input = [
                    'id_client' =>  $request['id_client'],
                    'id_client_debiteur' =>  Auth::user()->email,
                    'solde_avant_mru' => $solde->solde_mru,
                    'solde_mru' => $solde->solde_mru +  $request['montant_mru'],

                    'montant_mru' => $request['montant_mru'],
                    'motif' =>  $request['message'],
                    'type_opperation' => 'Dépot'

                ];

            
                Agence::create($input);

                //eneleve du cash disponilble globale
                
                $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
                $soldedispo = Cache_table::where('id',  $idmax)->value('solde');
                $nomagence= User::where('id_client',$request['id_client'])->value('firstname');
               
                $input = [
                    'id_client' =>  $request['id_client'],
                    'expediteur' => $request['expediteur'],
                    'nom_benef' => 'Agent:'.$nomagence,
                    'phone_benef' => 'Depot Cash agence',
                    'montant_euro' => 0,
                    'montant' => $request['montant_mru'],
                    'operation' =>  'retrait',
                    'solde_avant' => $soldedispo,
                    'solde_apres' =>  $soldedispo -$request['montant_mru'],
                    'solde' => $soldedispo -$request['montant_mru']

                ];

                Cache_table::create($input);
                

                return redirect()->intended('/agence-management');
            }
        }
    }*/

    public function retirermontant(Request $request)
    {



        $montant_mru = $request['montant'];


        $solde_debiteur = DB::table('agences')->where('indice', '=', '1')->where('id_client', '=', $request['debiteur'])->first();

        $solde_crediteur = DB::table('agences')->where('indice', '=', '1')->where('id_client', '=', $request['benef'])->first();

        DB::table('agences')->where('indice', '=', '1')->where('id_client', '=', $request['debiteur'])->update([
            'indice' => 0
        ]);

        DB::table('agences')->where('indice', '=', '1')->where('id_client', '=', $request['benef'])->update([
            'indice' => 0
        ]);

        Agence::create([
            'id_client' => $request['debiteur'],
            'id_client_debiteur' => $solde_crediteur->id_client,
            'solde_avant_mru' => $solde_debiteur->solde_mru,
            'solde_mru' => $solde_debiteur->solde_mru -  $montant_mru,

            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'debit'
        ]);

        Agence::create([
            'id_client' => $request['benef'],
            'id_client_debiteur' => $solde_debiteur->id_client,
            'solde_avant_mru' => $solde_crediteur->solde_mru,
            'solde_mru' => $solde_crediteur->solde_mru +  $montant_mru,

            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'credit'
        ]);


        return redirect()->intended('/agence-management');
    }

    public function addmontant(Request $request)
    {
        //dd($request);

        $montant_mru = $request['montant'];


        $solde_debiteur = DB::table('agences')->where('indice', '=', '1')->where('id_client', '=', $request['debiteur'])->first();

        $solde_crediteur = DB::table('agences')->where('indice', '=', '1')->where('id_client', '=', $request['benef'])->first();

        DB::table('agences')->where('indice', '=', '1')->where('id_client', '=', $request['debiteur'])->update([
            'indice' => 0
        ]);

        DB::table('agences')->where('indice', '=', '1')->where('id_client', '=', $request['benef'])->update([
            'indice' => 0
        ]);

        Agence::create([
            'id_client' => $request['debiteur'],
            'id_client_debiteur' => $solde_crediteur->id_client,
            'solde_avant_mru' => $solde_debiteur->solde_mru,
            'solde_mru' => $solde_debiteur->solde_mru +  $montant_mru,
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'credit'
        ]);

        Agence::create([
            'id_client' => $request['benef'],
            'id_client_debiteur' => $solde_debiteur->id_client,
            'solde_avant_mru' => $solde_crediteur->solde_mru,
            'solde_mru' => $solde_crediteur->solde_mru -  $montant_mru,
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'debit'
        ]);

        return redirect()->intended('/agence-management');
    }
/*
    public function agencestory($id)
    {
        $paiements = DB::table('agences')
            ->leftJoin('users', 'agences.id_client', '=', 'users.id_client')
            ->select('agences.*', 'users.firstname')
            ->where('agences.id_client', '=', $id)
            ->orderBy('agences.created_at', 'DESC')
            ->paginate(20);

        $agences= User::select('id_client','firstname')->where('user_type', 'operateur') ->get();

       
        return view('agence-mgmt/agencestory', [
            'paiements' => $paiements,
            'agences'=> $agences,


        ]);
    }*/

    /*public function operationstory($id, $jour)
    {
        $email = DB::table('users')
            ->where('id_client', '=', $id)->value('email');

        if ($email) {
            $caches =  DB::table('cache_tables as w')
                ->leftJoin('coordonnes_commandes as c', 'w.code_confirmation', '=', 'c.id_commande')
                ->select('w.*', 'c.frais_gaza'retraitagenceview, 'c.agence_gaza')
                ->where('expediteur', '=', $email)
                ->whereDate('w.created_at', '=', $jour)
                ->orderBy('created_at', 'DESC')
                ->paginate(20);

            $agent = DB::table('stat_agences')
                ->where('jours', '=', $jour)
                ->where('email_agence', '=', $email)
                ->first();



            return view('agence-mgmt/details', [
                'caches' => $caches,
                'agent' => $agent


            ]);
        }
    }*/
}
