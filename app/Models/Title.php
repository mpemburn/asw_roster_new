<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Title
 */
class Title extends Model
{
    protected $table = 'tblTitles';

    public $timestamps = false;

    protected $fillable = [
        'TitleID',
        'Title'
    ];

    protected $guarded = [];

        
}