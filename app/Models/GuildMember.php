<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GuildMember
 */
class GuildMember extends Model
{
    protected $table = 'tblGuildMembers';

    public $timestamps = false;

    protected $fillable = [
        'GuildID',
        'MemberID',
    ];

    protected $guarded = [];

}