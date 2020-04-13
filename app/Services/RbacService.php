<?php
namespace App\Services;

use App\Facades\Membership;
use App\Facades\Roles;
use App\Models\Permission;
use App\Models\Role;
use App\User;

class RbacService {

    /**
     * Writes/rewrites relationship between Leadership-level users and their access roles
     *
     * @return void
     */
    public function setLeadershipRoles()
    {
        $count = 0;

        // Create Role object
        $admin = Role::getRoleByName('admin');
        $coven_leader = Role::getRoleByName('coven-leader');
        $coven_scribe = Role::getRoleByName('coven-scribe');

        // Get an array of the leadership types that can be assigned these roles
        $valid_roles = Roles::getLeadershipRoleArray();

        // Get list of all users
        $all_users = User::all();
        foreach ($all_users as $user) {
            // Get the Member record associated with this user
            $member = Membership::getMemberById($user->member_id);
            // ...and the leadership role associated with that member, if any
            $role = $member->LeadershipRole;
            // Detach all roles first; they will be recreated in the next steps
            if ($user->hasRole('coven-leader')) {
                $user->detachRole($coven_leader);
            }
            if ($user->hasRole('coven-scribe')) {
                $user->detachRole($coven_scribe);
            }
            // Attach coven leader roles (also applies to Elders)
            if (in_array($role, $valid_roles)) {
                if (!is_null($coven_leader)) {
                    $user->attachRole($coven_leader);
                    $count++;
                }
            }
            // Attach Scribe role
            if ($role == 'SCR') {
                if (!is_null($coven_scribe)) {
                    $user->attachRole($coven_scribe);
                    $count++;
                }
            }
            // Attach admin role to Elder and above
            if (in_array($role, ['ELDER', 'CRF', 'CRM'])) {
                if (!is_null($admin) && !$user->hasRole('admin')) {
                    $user->attachRole($admin);
                    $count++;
                }
            }
        }
        return $count;

    }

    /**
     * Attach permissions to existing roles
     *
     * @return void
     */
    public function setRolePermissions() {
        $coven_leader = Role::getRoleByName('coven-leader');
        $coven_scribe = Role::getRoleByName('coven-scribe');

        $this->attachPermission($coven_leader, 'create-user');
        $this->attachPermission($coven_leader, 'edit-user');
        $this->attachPermission($coven_scribe, 'create-user');
        $this->attachPermission($coven_scribe, 'edit-user');
    }

    /* Private Methods */

    /**
     * Wrapper for Role::attachPermission with safety check
     *
     * @param $role
     * @param $permission_name
     */
    private function attachPermission($role, $permission_name)
    {
        if (!$role->hasPermission($permission_name)) {
            $permission = Permission::getPermissionByName($permission_name);
            $role->attachPermission($permission);
        }
    }
}
