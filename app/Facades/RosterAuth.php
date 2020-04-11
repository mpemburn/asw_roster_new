<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RosterAuth extends Facade {
    protected static function getFacadeAccessor() { return 'RosterAuthService'; }
}
