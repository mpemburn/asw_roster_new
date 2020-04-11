<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblUserSession
 */
class TblUserSession extends Model
{
    protected $table = 'tblUserSession';

    protected $primaryKey = 'SessionID';

	public $timestamps = false;

    protected $fillable = [
        'UserID',
        'UserLogon',
        'SessionDateTime',
        'RemoteAddress',
        'HostName',
        'Browser'
    ];

    protected $guarded = [];

        
}