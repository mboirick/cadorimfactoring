<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Employee;
use App\City;
use App\State;
use App\Country;
use App\Department;
use App\Division;
use App\Cache_table;
use App\Transfert_table;

class TransfertController extends Controller
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
        $caches = DB::table('transfert_tables')
        
        //->leftJoin('department', 'employees.department_id', '=', 'department.id')
        
        //->leftJoin('division', 'employees.division_id', '=', 'division.id')
        //->select('employees.*', 'department.name as department_name', 'department.id as department_id', 'division.name as division_name', 'division.id as division_id')
        ->select('transfert_tables.*')
        ->orderBy('created_at','DESC')
        ->paginate(10);

        return view('transfert-mgmt/index', ['caches' => $caches]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $cities = City::all();
        // $states = State::all();
        $countries = Country::all();
        $departments = Department::all();
        $divisions = Division::all();
        return view('cache-mgmt/create', ['countries' => $countries,
        'departments' => $departments, 'divisions' => $divisions]);
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
          
        $code_confirmation=str_shuffle(hexdec(uniqid()));

        $keys = ['expediteur', 'nom_benef', 'phone_benef', 'montant' ,'operation'];
       
        $input = $this->createQueryInput($keys, $request);
        // Not implement yet
        $input['code_confirmation'] =  $code_confirmation;

        //dd($request);
        Cache_table::create($input);

        return redirect()->intended('/cache-management');
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
        $employee = Employee::find($id);
        // Redirect to state list if updating state wasn't existed
        if ($employee == null || empty($employee)) {
            return redirect()->intended('/employee-management');
        }
        $cities = City::all();
        $states = State::all();
        $countries = Country::all();
        $departments = Department::all();
        $divisions = Division::all();
        return view('employees-mgmt/edit', ['employee' => $employee, 'cities' => $cities, 'states' => $states, 'countries' => $countries,
        'departments' => $departments, 'divisions' => $divisions]);
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
        $employee = Employee::findOrFail($id);
        $this->validateInput($request);
        // Upload image
        $keys = ['lastname', 'firstname', 'address', 'email', 'phone', 'department_id', 'division_id'];
        $input = $this->createQueryInput($keys, $request);
        if ($request->file('picture')) {
            $path = $request->file('picture')->store('avatars');
            $input['picture'] = $path;
        }

        Employee::where('id', $id)
            ->update($input);

        return redirect()->intended('/employee-management');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Employee::where('id', $id)->delete();
         return redirect()->intended('/employee-management');
    }

    /**
     * Search state from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'expediteur' => $request['expediteur'],
            'nom_benef' => $request['beneficiaire'],
            'phone_benef' => $request['telephone']
            ];
        $caches = $this->doSearchingQuery($constraints);
        //dd($constraints);
        $constraints['nom_benef'] = $request['nom_benef'];
        $constraints['phone_benef'] = $request['phone_benef'];
        return view('cache-mgmt/index', ['caches' => $caches, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = DB::table('cache_tables')
    
        ->select('cache_tables.*');
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

            'expediteur' => 'required',
            'nom_benef' => 'required',
            'phone_benef' => 'required|max:15',
            'montant' => 'required',          
          
            
            
            
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
