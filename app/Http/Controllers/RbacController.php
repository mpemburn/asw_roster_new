<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Role;
use App\Models\Permission;
use App\User;
use App\Facades\Rbac;

class RbacController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('member_id', '6')->first();
        $admin = Role::where('name', 'admin')->get()->first();

//        if ($user->hasRole('admin')) {
//            $createPost = new Permission();
//            $createPost->name         = 'create-user';
//            $createPost->display_name = 'Create User'; // optional
//// Allow a user to...
//            $createPost->description  = 'Create new user'; // optional
//            $createPost->save();

//            $editUser = new Permission();
//            $editUser->name         = 'edit-user';
//            $editUser->display_name = 'Edit Users'; // optional
//// Allow a user to...
//            $editUser->description  = 'edit existing users'; // optional
//            $editUser->save();

        $editUser = Permission::where('name', 'edit-user')->first();
        $admin->attachPermission($editUser);
// equivalent to $admin->perms()->sync(array($createPost->id));

//
// role attach alias
        //$user->attachRole($admin); // parameter can be an Role object, array, or id

    }

    /**
     * Set the roles for leaders
     *
     * @return void
     */
    public function setLeadershipRoles()
    {
        // Use Rbac (facade for Services\RbacService) to write leadership roles
        Rbac::setLeadershipRoles();
    }

    /**
     * Attach permissions to existing roles
     *
     * @return void
     */
    public function setRolePermissions() {
        // Use Rbac (facade for Services\RbacService) to attach permissions
        Rbac::setRolePermissions();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
