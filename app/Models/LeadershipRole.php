<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LeadershipRole
 */
class LeadershipRole extends Model
{
    protected $table = 'tblLeadershipRoles';

    protected $primaryKey = 'RoleID';

	public $timestamps = false;

    protected $fillable = [
        'Role',
        'Description',
        'GroupName',
        'LeadershipLevel'
    ];

    protected $guarded = [];

        
}