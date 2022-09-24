<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function create()
    {
        return view('permission.index');
    }

    public function store(Request $request)
    {
        $permission = Permission::updateOrCreate([
            'name' => $request->module_parent,
            'display_name' => $request->module_parent,
            'parent_id' => 0
        ]);

        foreach ($request->module_childrent as $value) {
            Permission::updateOrCreate([
                'name' => $value,
                'display_name' => $value,
                'parent_id' => $permission->id,
                'key_code' =>\Str::slug($value),
            ]);
        }
        return redirect()->route('roles');
    }
}
