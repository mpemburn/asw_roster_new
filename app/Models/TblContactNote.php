<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblContactNote
 */
class TblContactNote extends Model
{
    protected $table = 'tblContactNotes';

    public $timestamps = false;

    protected $fillable = [
        'NoteID',
        'MemberID',
        'PurchaserID',
        'ContactDate',
        'ReturnCall',
        'ReturnCallDate',
        'ReturnStatus',
        'ForwardCall',
        'NoteText',
        'AttachmentPath',
        'AgentRelated',
        'UserID',
        'AppCreatedBy',
        'Deleted',
        'DeletedBy',
        'DeletedDate'
    ];

    protected $guarded = [];

        
}