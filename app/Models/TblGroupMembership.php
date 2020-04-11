<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblGroupMembership
 */
class TblGroupMembership extends Model
{
    protected $table = 'tblGroupMembership';

    protected $primaryKey = 'GroupMembershipID';

	public $timestamps = false;

    protected $fillable = [
        'GroupName',
        'UserName'
    ];

    protected $guarded = [];

        
}