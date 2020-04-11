<?php
namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    public static function getRoleByName($name)
    {
        $role = self::where('name', $name);
        return ($role->exists()) ? $role->get()->first() : null;
    }
}
