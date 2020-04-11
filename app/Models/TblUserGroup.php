<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblUserGroup
 */
class TblUserGroup extends Model
{
    protected $table = 'tblUserGroups';

    protected $primaryKey = 'GroupID';

	public $timestamps = false;

    protected $fillable = [
        'GroupName',
        'Manager'
    ];

    protected $guarded = [];

        
}