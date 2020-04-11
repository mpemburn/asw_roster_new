<?php
namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $table = 'permissions';
    protected $fillable = [
        'name',
        'display_name','description'
    ];

    public static function getPermissionByName($name)
    {
        $permission = self::where('name', $name);
        return ($permission->exists()) ? $permission->first() : null;
    }
}