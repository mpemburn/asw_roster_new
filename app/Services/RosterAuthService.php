<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Facades\Membership;
use App\Models\Role;

class RosterAuthService
{
    /** @var User */
    protected $user;
    protected $member;

    public function __construct()
    {
        $this->user = Auth::user();
        if ($this->user !== null) {
            $this->member = Membership::getMemberById($this->user->member_id);
        }
    }

    public function getMemberId()
    {
        return $this->member ? $this->member->MemberID : null;
    }

    public function getMemberName(): ?string
    {
        return $this->member ? $this->member->First_Name . ' ' . $this->member->Last_Name : null;
    }

    public function getUserCoven(): ?string
    {
        return $this->member ? $this->member->Coven : null;
    }

    public function grantRoleToUser($user, $roleName): void
    {
        if ($user && $roleName) {
            $role = Role::getRoleByName($roleName);
            $user->attachRole($role);
        }
    }

    public function revokeRoleFromUser($user, $roleName): void
    {
        if ($user && $roleName) {
            $role = Role::getRoleByName($roleName);
            $user->detachRole($role);
        }
    }

    public function isAdmin(): bool
    {
        return $this->user ? $this->user->hasRole('admin') : false;
    }

    public function isCovenLeader($coven): bool
    {
        return $this->member && $this->user
            ? ($this->member->Coven === $coven && $this->user->hasRole('coven-leader'))
            : false;
    }

    public function isCovenScribe($coven): bool
    {
        return $this->member && $this->user
            ? ($this->member->Coven === $coven && $this->user->hasRole('coven-scribe'))
            : false;
    }

    public function isElder()
    {
        return $this->member ? (in_array($this->member->LeadershipRole, ['ELDER', 'CRF', 'CRM'])) : false;
    }

    public function isGuildLeader(): bool
    {
        return $this->user ? $this->user->hasRole('guild-leader') : false;
    }

    public function isMemberOf($roleName): bool
    {
        return $this->user && $roleName ? $this->user->hasRole($roleName) : false;
    }

    public function isThisMember($memberId): bool
    {
        return $this->user && $memberId ? ($this->user->member_id === $memberId) : false;
    }

    public function userIsLeaderOrScribe(): bool
    {
        $isLeader = $this->isMemberOf('coven-leader');
        $isScribe = $this->isMemberOf('coven-scribe');

        return $isLeader || $isScribe;

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

}
