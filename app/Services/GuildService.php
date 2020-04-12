<?php
namespace App\Services;

use App\Models\Guild;
use Illuminate\Support\Facades\Auth;
use App\Models\GuildMember;
use Request;
use Route;
use URL;

class GuildService {
    protected $user;

    public function isMember($guild_id, $member_id)
    {
        $guild_member = GuildMember::where('GuildID', $guild_id)
            ->where('MemberID', $member_id)
            ->get()
            ->first();

        return ($guild_member !== null);
    }

    public function isLeader($guild_id, $member_id)
    {
        $guild_leader = Guild::where('GuildID', $guild_id)
            ->where('LeaderMemberID', $member_id)
            ->get()
            ->first();

        return ($guild_leader !== null);
    }

    public function getGuilds(): array
    {
        return Guild::all()->toArray();
    }

    public function getGuildName()
    {
        if(Request::is('guild/*')) {
            $guild_id = Request::segment(3);

            $guild = Guild::where('GuildID', $guild_id)->first();

            return $guild->GuildName;

        }

        return null;
    }
}

