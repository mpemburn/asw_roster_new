<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Coven
 */
class Coven extends Model
{
    protected $table = 'tblCovens';

    protected $primaryKey = 'Coven';

	public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'CovenFullName',
        'Wheel',
        'Element',
        'Tool'
    ];

    protected $guarded = [];

        
}