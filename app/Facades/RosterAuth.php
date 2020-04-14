<?php

namespace App\Facades;

use App\User;
use Illuminate\Support\Facades\Facade;

/**
 * Class RosterAuth
 * @package App\Facades
 *
 * @method getMemberId
 * @method getMemberName
 * @method getUserCoven
 * @method grantRoleToUser(User $user, string $roleName)
 * @method isAdmin
 * @method isCovenLeader($coven)
 * @method isCovenScribe($coven)
 * @method isElder
 * @method isGuildLeader
 * @method isMemberOf($roleName)
 * @method isThisMember($memberId)
 * @method revokeRoleFromUser($user, $roleName)
 * @method userIsLeaderOrScribe
 */
class RosterAuth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'RosterAuthService';
    }
}
