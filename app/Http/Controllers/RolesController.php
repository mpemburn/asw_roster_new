<?php
namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->middleware('permission:manage-users-roles-and-permissions');
    }

    public function __invoke(Request $request)
    {
        return "Welcome to our homepage";
    }

    public function getIndex(Request $request)
    {
        $roles = Role::with('perms')->get();
        return view('roles.index', ['roles' => $roles]);
    }

    public function getCreate()
    {
        return view('roles.create');
    }

    public function postCreate(Request $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);
        return redirect()->to('admin/roles/index');
    }

    public function getUpdate($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.update', ['role' => $role]);
    }

    public function postUpdate(Request $request)
    {
        $role = Role::findOrFail($request->get('id'));
        $role->name = $request->get('name');
        $role->display_name = $request->get('display_name');
        $role->description = $request->get('description');
        $role->save();
        return redirect();
    }

    public function getDelete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect();
    }

    public function getAttach(Request $request)
    {
        $role = Role::where('id', '=', $request->id)->with('perms')->first();
        $permissions_id = $role->perms->pluck('id')->toArray();
        $permissionsNotAttached = Permission::whereNotIn('id', $permissions_id)->get();
        return view('roles.attach', compact('role', 'permissionsNotAttached'));
    }

    public function postAttach(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $permission = Permission::findOrFail($request->permission);
        $role->attachPermission($permission);
        return redirect();
    }

    public function getDetach(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);
        $role->detachPermission($permission);
        return redirect()->to('/admin/roles/index');

    }
}
