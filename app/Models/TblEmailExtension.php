<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblEmailExtension
 */
class TblEmailExtension extends Model
{
    protected $table = 'tblEmailExtensions';

    public $timestamps = false;

    protected $fillable = [
        'Extension',
        'Description',
        'Type'
    ];

    protected $guarded = [];

        
}