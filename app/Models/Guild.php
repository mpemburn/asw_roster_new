<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Guild
 */
class Guild extends Model
{
    protected $table = 'tblGuilds';

    protected $primaryKey = 'GuildID';

	public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'GuildName',
        'Description',
        'LeaderMemberID',
    ];

    protected $guarded = [];

}