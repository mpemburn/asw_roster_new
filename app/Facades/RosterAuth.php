<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class RosterAuth
 * @package App\Facades
 *
 * @method getMemberName
 * @method getMemberId
 * @method userIsLeaderOrScribe
 */
class RosterAuth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'RosterAuthService';
    }
}
