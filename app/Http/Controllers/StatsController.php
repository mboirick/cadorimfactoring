<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use Excel;
use Auth;
use PDF;
use App\Country;
use App\Department;
use App\Division;
use App\Agence;


class StatsController extends Controller
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
    public function index()
    {
 
          echo "ici stats";
    }


   //// export excel

   public function exportExcel(Request $request) {
    $this->prepareExportingData($request)->export('xlsx');
    redirect()->intended('atlpay-mgmt/index');
}

public function exportPDF(Request $request) {
    $constraints = [
       'from' => $request['from'],
       'to' => $request['to']
   ];
   $employees = $this->getExportingData($constraints);
   $pdf = PDF::loadView('atlpay-mgmt/pdf', ['employees' => $employees, 'searchingVals' => $constraints]);
   return $pdf->download('report_from_'. $request['from'].'_to_'.$request['to'].'pdf');
   redirect()->intended('atlpay-mgmt/index');
}

private function prepareExportingData($request) {
    $author = Auth::user()->username;
    $employees = $this->getExportingData(['from'=> $request['from'], 'to' => $request['to']]);
    return Excel::create('report_from_'. $request['from'].'_to_'.$request['to'], function($excel) use($employees, $request, $author) {

    // Set the title
    $excel->setTitle('Liste de transaction chez Atlpay from '. $request['from'].' to '. $request['to']);

    // Chain the setters
    $excel->setCreator($author)
        ->setCompany('HoaDang');

    // Call them separately
    $excel->setDescription('Atlpay rapport');

    $excel->sheet('ATLPAY Rapport', function($sheet) use($employees) {

    $sheet->fromArray($employees);
        });
    });
}



private function getExportingData($constraints) {
    return DB::table('coordonnes_commandes')
    ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
    ->select('coordonnes_commandes.date_commande', 'paiements.*')
   -> where('date_commande', '>=', $constraints['from'])
    ->where('date_commande', '<=', $constraints['to'])
    ->Where('paiements.payment_status', 'like', '%omplet%')
    ->Where('paiements.payment_type', 'like', 'cart')
    ->get()
    ->map(function ($item, $key) {
    return (array) $item;
    })
    ->all();

   

    
}




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        return view('agence-mgmt/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function retrait()
    {
 
        $countries = Country::all();
        $departments = Department::all();
        $divisions = Division::all();
        return view('cache-mgmt/retrait', ['countries' => $countries,
        'departments' => $departments, 'divisions' => $divisions]);
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
        $keys = ['nom_agence', 'nom_responsable', 'phone', 
        'adresse','heure_ouverture','heure_fermeture','jours_ouvrable', 'latitude',  'longitude' ];
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
    public function edit($id)
    {
        $agence = Agence::find($id);
        // Redirect to state list if updating state wasn't existed
        if ($agence == null || empty($agence)) {
            return redirect()->intended('/agence-management');
        }
       
        return view('agence-mgmt/edit', ['agence' => $agence]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $agence = Agence::findOrFail($id);
        $this->validateInput($request);
        // Upload image
        $keys = ['nom_agence', 'nom_responsable', 'phone',  'adresse','heure_ouverture','heure_fermeture','jours_ouvrable', 'latitude',  'longitude' ];
        $input = $this->createQueryInput($keys, $request);

        //dd($request->hasFile('picture'));
        if ($request->file('picture')) {
            $path = $request->file('picture')->store('avatars');
            $input['picture'] = $path;
           
        }

        Agence::where('id', $id)
            ->update($input);

        return redirect()->intended('/agence-management');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    public function search(Request $request) {
        
       // dd($request-> all());
        $constraints = [
            'from' => $request['from'],
            'to' => $request['to']
            
          ];

        $atlpay = $this->getCashs($constraints);
                
        //dd($constraints);
        return view('atlpay-mgmt/index', ['atlpay' => $atlpay, 'searchingVals' => $constraints]);
        
    }


    private function getCashs($constraints)
    
   
    {
      $cashs =  DB::table('coordonnes_commandes')
        ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
        ->select('coordonnes_commandes.date_commande', 'paiements.*')
       -> where('date_commande', '>=', $constraints['from'])
        ->where('date_commande', '<=', $constraints['to'])
        ->Where('paiements.payment_status', 'like', '%omplet%')
        ->Where('paiements.payment_type', 'like', 'cart')
        ->orderBy('date_commande', 'DESC')
        ->paginate(20);

     $nombretransaction =  DB::table('coordonnes_commandes')
     ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
     ->select('coordonnes_commandes.date_commande', 'paiements.*')
    -> where('date_commande', '>=', $constraints['from'])
     ->where('date_commande', '<=', $constraints['to'])
     ->Where('paiements.payment_status', 'like', '%omplet%')
     ->Where('paiements.payment_type', 'like', 'cart')
        ->count();
    $soldeatlpay =  DB::table('coordonnes_commandes')
    ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
    ->select('coordonnes_commandes.date_commande', 'paiements.*')
   -> where('date_commande', '>=', $constraints['from'])
    ->where('date_commande', '<=', $constraints['to'])
    ->Where('paiements.payment_status', 'like', '%omplet%')
    ->Where('paiements.payment_type', 'like', 'cart')
        ->sum('paiements.payment_amount');   

     $frais_atl=  floor(  $soldeatlpay*0.015 + $nombretransaction *0.4 );
     

     $solde_net= floor($soldeatlpay -$frais_atl);
     $soldeatlpay= floor($soldeatlpay);
      return [$cashs, $nombretransaction, $soldeatlpay,  $frais_atl,  $solde_net];
    }



    private function doSearchingQuery($constraints) {
        $query = DB::table('agences')
    
        ->select('agences.*');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where($fields[$index], 'like', '%'.$constraint.'%');
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
    public function load($name) {
         $path = storage_path().'/app/avatars/'.$name;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    private function validateInput($request) {
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

    private function createQueryInput($keys, $request) {
        $queryInput = [];
        for($i = 0; $i < sizeof($keys); $i++) {
            $key = $keys[$i];
            $queryInput[$key] = $request[$key];
        }

        return $queryInput;
    }
}
