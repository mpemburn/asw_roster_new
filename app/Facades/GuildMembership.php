<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class GuildMembership
 * @package App\Facades
 *
 * @method getGuilds
 * @method isLeader($guildId, $memberId)
 */
class GuildMembership extends Facade {

    protected static function getFacadeAccessor(): string
    {
        return 'GuildService';
    }
}
