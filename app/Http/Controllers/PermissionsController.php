<?php
namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Http\Requests;


class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->middleware('permission:manage-users-roles-and-permissions');
    }

    public function getIndex()
    {
        $permissions = Permission::all();
        return view('permissions.index', ['permissions' => $permissions]);
    }

    public function getCreate()
    {
        return view('permissions.create');
    }

    public function postCreate(Request $request)
    {
        $permission = Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);

        return redirect();
    }

    public function getUpdate($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.update', ['permission' => $permission]);
    }

    public function postUpdate(Request $request)
    {
        $permission = Permission::findOrFail($request->get('id'));
        $permission->name = $request->get('name');
        $permission->display_name = $request->get('display_name');
        $permission->description = $request->get('description');
        $permission->save();
        return redirect();
    }

    public function getDelete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect();
    }
}

