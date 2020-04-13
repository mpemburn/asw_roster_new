<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Membership
 * @package App\Facades
 *
 * @method getMemberById($member_id)
 * @method getGuildMembers($guildId)
 * @method getPrimaryPhone($memberId)
 */
class Membership extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MembershipService';
    }
}
