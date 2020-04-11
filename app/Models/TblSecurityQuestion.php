<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblSecurityQuestion
 */
class TblSecurityQuestion extends Model
{
    protected $table = 'tblSecurityQuestions';

    protected $primaryKey = 'Security_Question_ID';

	public $timestamps = false;

    protected $fillable = [
        'Security_Question'
    ];

    protected $guarded = [];

        
}