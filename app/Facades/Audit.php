<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Audit extends Facade {
    protected static function getFacadeAccessor() { return 'AuditService'; }
}
