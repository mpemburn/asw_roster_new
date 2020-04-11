<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BoardRole
 */
class BoardRole extends Model
{
    protected $table = 'tblBoardRoles';

    protected $primaryKey = 'RoleID';

	public $timestamps = false;

    protected $fillable = [
        'BoardRole'
    ];

    protected $guarded = [];

        
}