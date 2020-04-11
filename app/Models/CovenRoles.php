<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LeadershipRole
 */
class CovenRoles extends Model
{
    protected $table = 'tblCovenRoles';

	public $timestamps = false;

    protected $fillable = [
        'Coven',
        'MemberID',
        'Role',
    ];

    protected $guarded = [];

        
}