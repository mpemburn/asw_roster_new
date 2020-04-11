<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Membership extends Facade {
    protected static function getFacadeAccessor() { return 'MembershipService'; }
}
