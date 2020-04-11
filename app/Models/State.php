<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class State
 */
class State extends Model
{
    protected $table = 'tblStates';

    public $timestamps = false;

    protected $fillable = [
        'StateID',
        'Abbrev',
        'State',
        'Local',
        'Country'
    ];

    protected $guarded = [];

        
}