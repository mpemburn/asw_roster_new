<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Suffix
 */
class Suffix extends Model
{
    protected $table = 'tblSuffixes';

    public $timestamps = false;

    protected $fillable = [
        'SuffixID',
        'Suffix'
    ];

    protected $guarded = [];

        
}