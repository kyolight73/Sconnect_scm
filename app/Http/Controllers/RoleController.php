<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isNull;

class RoleController extends Controller
{
    private $role;
    private $permission;

    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    public function index()
    {
        $roles = $this->role->paginate(15);
        return view('roles.index', compact('roles'));
    }

    public function add(Request $request)
    {
        try {
            $messages = array(
                'name.required' => 'Chưa nhập tên nhóm',
                'display_name.required' => 'Chưa nhập mô tả tên nhóm',
            );

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100',
                'display_name' => 'required|max:100',
            ], $messages);

            $message = 'Thêm mới nhóm thành công';
            $body = '';
            $status = 'success';

            if ($validator->fails()) {
                $status = 'failure';
                $message = '';
                $messages = $validator->messages();
                foreach ($messages->all() as $msg) {
                    $message .= $msg . '<br/>';
                }

            } else {

                $role = new Role();
                $role->name = $request->input('name');
                $role->display_name = $request->input('display_name');
                $role->save();


            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json(['status' => $status, 'message' => $message, 'body' => $body]);
    }

    public function change(Request $request) {
        $status = "success";
        $body = '';
        $message = 'Cập nhật thành công';
        try {
            $id = $request->input('id');
            if (!is_numeric($id)) $id = 0;
            $name = $request->input('name');
            $display_name = $request->input('display_name');

                $dept = Role::find($id);
                $dept->name = $name;
                $dept->display_name = $display_name;
                $dept->save();

        } catch (\Exception $e) {
            $message = "Cập nhật không thành công! \nLỗi: ".$e->getMessage();
        }

        return response()->json(['status'=>$status, 'message'=>$message, 'body'=>$body]);
    }

    public function edit($id)
    {
        $permissionsParent = $this->permission->where('parent_id',0)->get();
        $role = $this->role->find($id);
        $permissionsChecked = $role->permissions;
        return view('roles.edit', compact('permissionsParent', 'role', 'permissionsChecked'));
    }

    public function update($id, Request $request)
    {
        $role = $this->role->find($id);
        $role->permissions()->sync($request->permission_id);
        return redirect()->route('roles');
    }

    public function delete($id = 0)
    {
        $status = "success";
        $message = "Xoá thành công!";
        $body = '';
        try {
            $role = Role::find($id);
            $role->delete();
        } catch (\Exception $e) {
            $status = "failure";
            $message = "Xoá không thành công! \nLỗi: " . $e->getMessage();
        }
        return response()->json(['status' => $status, 'message' => $message, 'body' => $body]);
    }
}
