<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblZipCode
 */
class TblZipCode extends Model
{
    protected $table = 'tblZipCodes';

    public $timestamps = false;

    protected $fillable = [
        'Zip',
        'City',
        'State',
        'Lat',
        'Lon',
        'TimeZone',
        'Unit',
        'Country'
    ];

    protected $guarded = [];

        
}