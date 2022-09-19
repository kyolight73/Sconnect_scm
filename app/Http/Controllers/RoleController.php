<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $role;
    private $permission;
    public function __construct(Role $role, Permission $permission)
    {
        $this -> role = $role;
        $this -> permission = $permission;
    }
    public function index(){
        $roles = $this->role->paginate(10);
        return view('roles.index', compact('roles'));
    }
    public function edit($id)
    {
        $permissionsParent = $this->permission->where('parent_id',0)->get();
        $role = $this->role->find($id);
        $permissionsChecked = $role->permissions;
        return view('roles.edit', compact('permissionsParent','role','permissionsChecked'));
    }
    public function update($id, Request $request)
    {
        $role= $this->role->find($id);
        $role->update([
            'name'=>$request->name,
            'display_name'=>$request->display_name,
        ]);

        $role->permissions()->sync($request->permission_id);
        return redirect()->route('roles');
    }
}
