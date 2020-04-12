<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Membership
 * @package App\Facades
 *
 * @method getMemberById($member_id)
 */
class Membership extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MembershipService';
    }
}
