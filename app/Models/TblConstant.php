<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblConstant
 */
class TblConstant extends Model
{
    protected $table = 'tblConstants';

    protected $primaryKey = 'ConstantID';

	public $timestamps = false;

    protected $fillable = [
        'Constant',
        'ConstantValue',
        'UpdatedDate',
        'UpdatedBy'
    ];

    protected $guarded = [];

        
}