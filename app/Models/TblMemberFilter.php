<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblMemberFilter
 */
class TblMemberFilter extends Model
{
    protected $table = 'tblMemberFilters';

    protected $primaryKey = 'FilterID';

	public $timestamps = false;

    protected $fillable = [
        'Filter',
        'FilterIDSQL',
        'Description',
        'INExpression',
        'UseConstant',
        'Override',
        'OverrideDefaultSort'
    ];

    protected $guarded = [];

        
}