<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Degree
 */
class Degree extends Model
{
    protected $table = 'tblDegrees';

    protected $primaryKey = 'Degree';

	public $timestamps = false;

    protected $fillable = [
        'Degree_Name',
        'Description'
    ];

    protected $guarded = [];

        
}