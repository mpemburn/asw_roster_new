<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblMenuContext
 */
class TblMenuContext extends Model
{
    protected $table = 'tblMenuContext';

    protected $primaryKey = 'MenuContextID';

	public $timestamps = false;

    protected $fillable = [
        'MenuID',
        'MenuContext',
        'AvailableTo',
        'Task',
        'Precedence'
    ];

    protected $guarded = [];

        
}