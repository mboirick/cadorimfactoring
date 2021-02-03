<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\City;
use App\State;
use App\Document;
use App\Abonne;

class KycController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(["create", "store", "edit", "search", "destroy"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $email)
    {
        
       $user = DB::table('abonnes')

      ->where('id', '=', $id)
      ->where('email', '=', $email)
      ->where('email', '=', $email)
      ->where('kyc', '!=', '1')
      ->get();
             
 
      if ($user->isNotEmpty())
      {
        return view('kyc-mgmt/index', ['user' => $user]);
      }
      else{

     echo ' <div style="margin-top: 50px;" align="center">
     <h1>Désolé, ce contenu n\'est plus disponible. </h1>
     <p>
       <a href="https://cadorim.com/" style="color: #fff; text-decoration: none">
         <div style="display: inline-block;background-color: #FFC107; padding: 7px 30px;border-radius: 20px;color: #f7f8fb">
           RETOUR AU SITE</div>
       </a>
     </p>

  </div>'  ;

     die;
      }
        
    }


    public function revenu($id, $email)
    {
        
       $user = DB::table('abonnes')

      ->where('id', '=', $id)
      ->where('email', '=', $email)
      ->where('email', '=', $email)
      ->where('kyc', '!=', '1')
      ->get();
             
 
      if ($user->isNotEmpty())
      {
        return view('kyc-mgmt/revenu', ['user' => $user]);
      }
      else{

     echo ' <div style="margin-top: 50px;" align="center">
     <h1>Désolé, ce contenu n\'est plus disponible. </h1>
     <p>
       <a href="https://cadorim.com/" style="color: #fff; text-decoration: none">
         <div style="display: inline-block;background-color: #FFC107; padding: 7px 30px;border-radius: 20px;color: #f7f8fb">
           RETOUR AU SITE</div>
       </a>
     </p>

  </div>'  ;

     die;
      }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::all();
        return view('system-mgmt/city/create', ['states' => $states]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        State::findOrFail($request['state_id']);
        $this->validateInput($request);
         city::create([
            'name' => $request['name'],
            'state_id' => $request['state_id']
        ]);

        return redirect()->intended('system-management/city');
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
        $city = city::find($id);
        // Redirect to city list if updating city wasn't existed
        if ($city == null || count($city) == 0) {
            return redirect()->intended('/system-management/city');
        }

        $states = State::all();
        return view('system-mgmt/city/edit', ['city' => $city, 'states' => $states]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
   
        $id = $request['idUser'];
       
        $files = $request->file('document');
    
        if($request->hasFile('document'))
        {
            foreach ($files as $file) 
            {
              $path = $file->store('avatars');
              $input['id_user'] = $id;
              $input['path'] = $path;
              Document::create($input);
            }
        }
    
      
        $input = [
            'genre' => $request['genre'],
            'username' => $request['username'],
            'prenom' => $request['prenom'],
            'phone' => $request['phone'],
            'adress' => $request['adresse'],
            'ville' => $request['Ville'],
            'code_postal' => $request['code_postal'],
            'pays_residence' => $request['pays_residence'],
            'date_naissance' => $request['date_naissance'],
            'type_doc' => $request['type_doc'],
            'numero_doc' => $request['numero_doc'],
            'date_emission' => $request['date_emission'],
            'date_expiration' => $request['date_expiration'],
            'kyc' => '3'
           
        ];
    
        //dd($input);
        
        //$this->validate($request, $constraints);
        Abonne::where('id', $id)
            ->update($input);
        $this->sendemail($id);
        return   view('kyc-mgmt/message');
    }


    public function updaterevenu(Request $request)
    {
   
        $id = $request['idUser'];
       
        $files = $request->file('document');
    
        if($request->hasFile('document'))
        {
            foreach ($files as $file) 
            {
              $path = $file->store('avatars');
              $input['id_user'] = $id;
              $input['path'] = $path;
              Document::create($input);
            }
        }
          
         $this->sendemail($id);
        return   view('kyc-mgmt/message');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        City::where('id', $id)->delete();
         return redirect()->intended('system-management/city');
    }

    public function loadCities($stateId) {
        $cities = City::where('state_id', '=', $stateId)->get(['id', 'name']);

        return response()->json($cities);
    }

    /**
     * Search city from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'name' => $request['name']
            ];

       $cities = $this->doSearchingQuery($constraints);
       return view('system-mgmt/city/index', ['cities' => $cities, 'searchingVals' => $constraints]);
    }
    
    private function doSearchingQuery($constraints) {
        $query = City::query();
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where( $fields[$index], 'like', '%'.$constraint.'%');
            }

            $index++;
        }
        return $query->paginate(5);
    }
    private function validateInput($request) {
        $this->validate($request, [
        'name' => 'required|max:60|unique:city'
    ]);
    }

    private function sendemail($id)
  { 

    $email = DB::table('abonnes')
    ->where('id', '=', $id)->value('email');

     $to = 'compliance@cadorim.com';


      $email_subject = "CADORIM :Formulaire KYC";
      $email_body = '
      L\'utilisater '.$email.' vient de remplir le formulaire KYC, Verifier dans les abonnes avec l\'adresse email si les informations sont corrects.

      
      ';

      $headers = "From: \"CADORIM\"<noreply@cadorim.com>\n";
      $headers .= "Reply-To: noreply@cadorim.com\n";
  
      $headers .= 'Bcc:  cadorimc@gmail.com, bmneine@gmail.com, cadorim.stat@gmail.com' . "\r\n";
      //$headers .= 'Bcc:  cadorim.stat@gmail.com' . "\r\n";
      $headers .= "Content-Type: text/html; charset=\"utf8\"";
  
  
  
      mail($to, $email_subject, $email_body, $headers);
    } 


}
