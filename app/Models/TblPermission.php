<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblPermission
 */
class TblPermission extends Model
{
    protected $table = 'tblPermissions';

    protected $primaryKey = 'PermissionID';

	public $timestamps = false;

    protected $fillable = [
        'Task',
        'Realm',
        'GroupName',
        'UserLogon',
        'Create_rights',
        'Read_rights',
        'Update_rights',
        'Delete_rights',
        'ViewMenu'
    ];

    protected $guarded = [];

        
}