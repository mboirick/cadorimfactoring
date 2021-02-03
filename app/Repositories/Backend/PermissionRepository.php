<?php

namespace App\Repositories\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function getById($id)
    {
        return Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$id)
        ->get();
    } 

    public function create($name)
    {
        return  Role::create(['name' => $name]);
    } 

    public function getAll()
    {
        return  Permission::get();
    } 
}
