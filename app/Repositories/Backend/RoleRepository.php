<?php

namespace App\Repositories\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;

class RoleRepository
{
    public function getAll()
    {
        return Role::where('name', 'like', '%%')
        ->orderBy('id','DESC')->paginate(5);
    } 

    public function getById($id)
    {
        return Role::find($id);
    } 

    public function create($name)
    {
        return  Role::create(['name' => $name]);
    } 

    public function update($id, $name)
    {
        $role = $this->getById($id);
        $role->name = $name;
        $role->save();
    
        return $role;
    } 

    public function deleteModelHasRoles($id)
    {
        return DB::table('model_has_roles')
                    ->where('model_id',$id)
                    ->delete();
    } 

    public function getRolePermissionById($id)
    {
        return DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();
    }

    public function destroy($id)
    {
        return DB::table("roles")->where('id',$id)->delete();
    }
}
