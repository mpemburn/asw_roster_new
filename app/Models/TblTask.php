<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblTask
 */
class TblTask extends Model
{
    protected $table = 'tblTasks';

    protected $primaryKey = 'TaskID';

	public $timestamps = false;

    protected $fillable = [
        'Task',
        'Description'
    ];

    protected $guarded = [];

        
}