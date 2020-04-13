<?php
namespace App\Services;

use App\Facades\Membership;
use App\Models\BoardRole;
use App\Models\CovenRoles;
use App\Models\LeadershipRole;
use App\Models\Guild;

class RolesService {

    public function changeCovenRole($coven, $member_id, $role, $status)
    {
        if ($this->doesCovenRoleExist($coven, $role)) {
            if ($status == 1) {
                $this->updateCovenRole($coven, $member_id, $role);
            } else {
                $this->deleteCovenRole($coven, $role);
            }
        } else {
            $this->createCovenRole($coven, $member_id, $role);
        }
    }

    public function changePurseWardenRole($coven, $member_id, $status)
    {
        $this->changeCovenRole($coven, $member_id, 'PW', $status);
    }

    public function changeScribeRole($coven, $member_id, $status)
    {
        $this->changeCovenRole($coven, $member_id, 'SCR', $status);
    }

    public function doesCovenRoleExist($coven, $role)
    {
        $covenRole = CovenRoles::where('Coven', $coven)
            ->where('Role', $role)
            ->get()
            ->first();

        return ($covenRole !== null);
    }

    public function getAllRoles($member)
    {
        $member_id = $member->MemberID;
        $coven = $member->Coven;
        $leader_roles = $this->getLeadershipRoleArray();
        $leader = (in_array($member->LeadershipRole, $leader_roles)) ? $member->LeadershipRole : null;
        $pw = ($this->isPurseWarden($member_id, $coven)) ? 'PW' : null;
        $scribe = ($this->isScribe($member_id, $coven)) ? 'SCR' : null;
        $all_roles = [$leader, $pw, $scribe];
        $all_roles = array_filter($all_roles);

        return implode('/', $all_roles);
    }

    /**
     * Retrieve array of leadership roles at the coven level
     *
     * @return array
     */
    public function getCovenRoleArray()
    {
        $roles = [];
        $leadership = LeadershipRole::where('LeadershipLevel', 'Coven')->get();
        foreach ($leadership as $leader) {
            $roles[] = $leader->Role;
        }
        return $roles;
    }

    /**
     * Retrieve array of leadership roles of the "Leadership" type (not Scribes or Purse Wardens)
     *
     * @return array
     */
    public function getLeadershipRoleArray()
    {
        $roles = [];
        $leadership = LeadershipRole::where('GroupName', 'Leadership')->get();
        foreach ($leadership as $leader) {
            $roles[] = $leader->Role;
        }
        return $roles;
    }

    public function hasCovenRole($member_id, $coven, $role)
    {
        $covenRole = CovenRoles::where('Coven', $coven)
            ->where('MemberID', $member_id)
            ->where('Role', $role)
            ->get()
            ->first();

        return ($covenRole !== null);
    }

    public function isLeader($member_id, $coven)
    {
        $valid_roles = $this->getLeadershipRoleArray();

        $is_leader = false;
        foreach ($valid_roles as $role) {
            if ($this->hasCovenRole($member_id, $coven, $role)) {
                $is_leader = true;
                break;
            }
        }

        return $is_leader;
    }

    public function isPurseWarden($member_id, $coven)
    {
        return $this->hasCovenRole($member_id, $coven, 'PW');
    }

    public function isScribe($member_id, $coven)
    {
        return $this->hasCovenRole($member_id, $coven, 'SCR');
    }

    /**
     * Standard dropdown for board roles
     *
     * @return array
     */
    public function boardDropdown()
    {
        return BoardRole::pluck('BoardRole', 'BoardRole')->prepend('None', '');
    }

    /**
     * Standard dropdown for leadership roles
     *
     * @return array
     */
    public function leadershipDropdown()
    {
        return LeadershipRole::where('GroupName', 'Leadership')
            ->where('LeadershipLevel', 'Coven')
            ->pluck('Description', 'Role')
            ->prepend('None', '');
    }

    /* CovenRole C_UD methods */

    public function createCovenRole($coven, $member_id, $role)
    {
        $covenRole = new CovenRoles();
        $covenRole->Coven = $coven;
        $covenRole->MemberID = $member_id;
        $covenRole->Role = $role;

        return $covenRole->save();
    }

    public function deleteCovenRole($coven, $role)
    {
        $covenRole = CovenRoles::where('Coven', $coven)
            ->where('Role', $role);

        return $covenRole->delete();

    }

    public function updateCovenRole($coven, $member_id, $role)
    {
        return CovenRoles::where('Coven', $coven)
            ->where('Role', $role)
            ->update(['MemberID' => $member_id]);

    }

    public function userIsLeaderOrScribe($user_id, $coven)
    {
        $member_id = Membership::getMemberIdFromUserId($user_id);
        $is_leader = $this->isLeader($member_id, $coven);
        $is_scribe = $this->isScribe($member_id, $coven);

        return $is_leader || $is_scribe;
    }
}
