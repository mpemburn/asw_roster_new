<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Roles
 * @package App\Facades
 *
 * @method changePurseWardenRole($coven, $memberId, $status);
 * @method changeScribeRole($coven, $memberId, $status);
 *
 */
class Roles extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'RolesService';
    }
}
