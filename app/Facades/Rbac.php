<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Rbac
 * @package App\Facades
 *
 * @method setLeadershipRoles()
 */
class Rbac extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'RbacService';
    }
}
