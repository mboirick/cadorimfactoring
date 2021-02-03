<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    protected function setPermission()
    {
        $this->middleware('permission:admin-user', ['only' => ['index','restore', 'delete', 'edit','update', 'add', 'store']]);
    }

    public function index(Request $request)
    {
        $params = [
            'userType' => isset($request->user_type)?$request->user_type:'',
            'lastName' => isset($request->lastName)?$request->lastName:'',
            'firstName' => isset($request->firstName)?$request->firstName:'',
            'email' => isset($request->email)?$request->email:'' 
        ];
    	$users = $this->userRepository->getAllByCriterion($params); 
        //var_dump($users);die;
		return view('backend.user.index', 
					['users' => $users,
					'lastName' => isset($request->lastName)?$request->lastName:'',
					'firstName' => isset($request->firstName)?$request->firstName:'',
					'email' => isset($request->email)?$request->email:'']);
    }

    public function add()
    {
        $roles = Role::pluck('name','name')->all();

    	return view('backend.user.add', compact('roles'));
    }

    public function store(Request $request)
    {
        $status = Password::sendResetLink( $request->only('email'));
        
        $this->validate($request, [
            'firstname' => 'required|min:2',
            'lastname' => 'required|min:2',
            'user_type' => 'required|min:2',
            'email' => 'required|email',
            'roles' => 'required',
        ]);
        
        $password = $this->randomPassword();

        $params = [
            'username'  => 'avatars/HehSQ4MxiPMd5di8EtV9GcwBj6TZV6PXKfmi06Q1.jpg',
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'user_type' => $request->user_type,
            'email'     => $request->email,
            'password'  => bcrypt($password)
        ];
       

        $idUser = $this->userRepository->create($params);

        $user = $this->userRepository->getById($idUser);
        $user->assignRole($request->input('roles'));

        Password::sendResetLink($request->only('email'));
        
        return redirect()->intended('/token/store/' . $idUser);
    }

    public function randomPassword() 
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    /**
     * 
     */
    public function profile()
    {   
    	return view('backend.user.profile');
    }

    public function updateProfile(Request $request)
    {   
        $constraints = [
            'firstname' => 'required|min:2',
            'lastname' => 'required|min:2',
        ];
        $input = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname
        ];
        
        if ($request['password'] != null && strlen($request['password']) > 0) {
            $constraints['password'] = 'required|min:6|confirmed';
            $input['password'] =  bcrypt($request['password']);
        }
        
        $this->validate($request, $constraints);
        $this->userRepository->update(Auth::user()->id, $input);
        
        Session::flash('message', 'les modifications ont bien été effectuées.');

        return view('backend.user.edit');
    }

    public function edit($id)
    {   
        $user = $this->userRepository->getWithTrashedById($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

    	return view('backend.user.edit', ['user' => $user, 'roles' =>$roles, 'userRole' => $userRole]);
    }

    public function update(Request $request)
    {   
        if($request['idUser'] != null && isset($request['idUser'])){
            $constraints = [
                'firstname' => 'required|min:2',
                'lastname' => 'required|min:2',
            ];
            $input = [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname
            ];
    
            if ($request['pwdRadios'] != null && $request['pwdRadios'] == 1) {
                $password = $this->randomPassword();
                $input['password'] =  bcrypt($password);
            }
            
            $this->validate($request, $constraints);
            $this->userRepository->update($request['idUser'], $input);

            $user = $this->userRepository->getUserById($request['idUser']);
            $this->roleRepository->deleteModelHasRoles($request['idUser']);
            $user->assignRole($request->input('roles'));


            if ($request['pwdRadios'] != null && $request['pwdRadios'] == 1) {
                Password::sendResetLink($request->only('email'));
            }
            
            Session::flash('message', 'les modifications ont bien été effectuées.'); 
        }
        
        return redirect()->intended('/users');
    }   

    public function delete($id)
    {           
        $user = $this->userRepository->getById($id);

    	$user->tokens->each(function($token, $key) {
	        $token->delete();
	    });

        $this->tokensRepository->deleteById($id);
        
        $this->userRepository->delete($id);

       
        Session::flash('message', 'le compte a été désactivé.');

        return redirect()->intended('users');
    }

    public function restore($id)
    {           
        $this->userRepository->restore($id);

        Session::flash('message', 'le compte a été activé.');

        return redirect()->intended('users');
    }
}

