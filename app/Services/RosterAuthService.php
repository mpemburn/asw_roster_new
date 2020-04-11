<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Facades\Membership;
use App\Models\Role;

class RosterAuthService {
    protected $user;
    protected $member;

    /**
     * init
     *
     * Initialize properties with data pulled from the logged-in user
     *
     * @return void
     */
    public function init()
    {
        $success = false;
        $this->user = Auth::user();
        if (!is_null($this->user)) {
            $this->member = Membership::getMemberById($this->user->member_id);
            $success = true;
        }
        return $success;
    }

    public function getMemberId()
    {
        return ($this->init()) ? $this->member->MemberID : null;
    }

    public function getMemberName()
    {
        return ($this->init()) ? $this->member->First_Name . ' ' . $this->member->Last_Name : null;
    }

    public function getUserCoven()
    {
        return ($this->init()) ? $this->member->Coven : null;
    }

    public function grantRoleToUser($user, $role_name)
    {
        if ($this->init()) {
            $role = Role::getRoleByName($role_name);
            $user->attachRole($role);
        }
    }

    public function revokeRoleFromUser($user, $role_name)
    {
        if ($this->init()) {
            $role = Role::getRoleByName($role_name);
            $user->detachRole($role);
        }
    }

    public function isAdmin()
    {
        return ($this->init()) ? $this->user->hasRole('admin') : false;
    }

    public function isCovenLeader($coven)
    {
        return ($this->init()) ? ($this->member->Coven == $coven && $this->user->hasRole('coven-leader')) : false;
    }

    public function isCovenScribe($coven)
    {
        return ($this->init()) ? ($this->member->Coven == $coven && $this->user->hasRole('coven-scribe')) : false;
    }

    public function isElder()
    {
        return ($this->init()) ? (in_array($this->member->LeadershipRole, ['ELDER', 'CRF', 'CRM'])) : false;
    }

    public function isGuildLeader()
    {
        return ($this->init()) ? $this->user->hasRole('guild-leader') : false;
    }

    public function isMemberOf($role_name)
    {
        return ($this->init()) ? $this->user->hasRole($role_name) : false;
    }

    public function isThisMember($member_id)
    {
        return ($this->init()) ? ($this->user->member_id == $member_id) : false;
    }

    public function userIsLeaderOrScribe()
    {
        if ($this->init()) {
            $is_leader = $this->isMemberOf('coven-leader');
            $is_scribe = $this->isMemberOf('coven-scribe');

            return $is_leader || $is_scribe;

//            $leadershipRoleService = new RolesService();
//            $valid_roles = $leadershipRoleService->getLeadershipRoleArray();
//            $valid_roles[] = 'SCR';
//
//            $has_role = false;
//            foreach ($valid_roles as $role) {
//                if ($this->isMemberOf($role)) {
//                    $has_role = true;
//                    break;
//                }
//            }
//
//            return $has_role; //(in_array($this->member->LeadershipRole, $valid_roles));
        }
        return false;
    }

}