<?php
namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    public static function getRoleByName($name): SpatieRole
    {
        return self::findByName($name);
    }
}
