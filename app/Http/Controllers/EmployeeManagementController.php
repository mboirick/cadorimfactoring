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

class EmployeeManagementController extends Controller
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
       
        $employees = DB::table('employees')
        
        ->leftJoin('department', 'employees.department_id', '=', 'department.id')
        
        ->leftJoin('division', 'employees.division_id', '=', 'division.id')
        ->select('employees.*', 'department.name as department_name', 'department.id as department_id', 'division.name as division_name', 'division.id as division_id')
        ->orderBy('created_at','DESC')
        ->paginate(8);

        return view('employees-mgmt/index', ['employees' => $employees]);
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
        return view('employees-mgmt/create', ['countries' => $countries,
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
        $password=bcrypt($request['password']);
        $keys = ['lastname', 'firstname', 'address', 
        'email', 'phone',  'department_id','division_id' ];
        $input = $this->createQueryInput($keys, $request);
        $input['picture'] = $path;

        $input['password'] = $password;
        Employee::create($input);

        return redirect()->intended('/employee-management');
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

        echo "test";
        die; 
        
        $employee = Employee::findOrFail($id);
        $this->validateInput($request);
        // Upload image
        $keys = ['lastname', 'firstname', 'address', 'email', 'phone', 'department_id', 'division_id'];
        $input = $this->createQueryInput($keys, $request);
        //dd($request->file('picture'));
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
            'firstname' => $request['nom'],
            'department.name' => $request['ville']
            ];
        $employees = $this->doSearchingQuery($constraints);
        $constraints['department_name'] = $request['ville'];
        return view('employees-mgmt/index', ['employees' => $employees, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = DB::table('employees')
       
        ->leftJoin('department', 'employees.department_id', '=', 'department.id')

        ->leftJoin('division', 'employees.division_id', '=', 'division.id')
        ->select('employees.firstname as employee_name', 'employees.*','department.name as department_name', 'department.id as department_id', 'division.name as division_name', 'division.id as division_id');
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
            'lastname' => 'required|max:60',
            'firstname' => 'required|max:60',
            
            'address' => 'required|max:120',
            
            'email' => 'required|max:60|unique:employees',
            'password' => 'required',
            'phone' => 'required|max:60|unique:employees',
            
            'department_id' => 'required',
            
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
