<?php

namespace App\Http\Controllers;

use App\Models\GuildMember;
use Illuminate\Http\Request;
// Facades
use App\Facades\GuildMembership;
use App\Facades\Membership;
use App\Facades\RosterAuth;

class GuildsController extends Controller
{
    /**
     * Add or remove members
     */
    public function manage($guildId)
    {
        $active = Membership::getGuildMembers($guildId);
        $hasLeaderRole = RosterAuth::isGuildLeader();
        $memberId = RosterAuth::getMemberId();
        $isAdmin = RosterAuth::isAdmin();
        $isLeader = GuildMembership::isLeader($guildId, $memberId);
        $canEdit = ($isAdmin || ($hasLeaderRole && $isLeader));

        return view('guild_manage', ['members' => $active, 'can_edit' => $canEdit]);
    }

    public function add(Request $request): array
    {
        $guild_member = new GuildMember();
        $guild_member->GuildID = $request->guild_id;
        $guild_member->MemberID = $request->member_id;
        $guild_member->save();

        $member = Membership::getMemberById($request->member_id);
        $data = [
            'member_id' => $member->MemberID,
            'name' => $member->First_Name . ' ' . $member->Last_Name,
            'phone' => Membership::getPrimaryPhone($member->MemberID),
            'email' => $member->Email_Address,
            'coven' => $member->Coven
        ];

        return ['success' => true, 'data' => $data];
    }

    public function remove(Request $request) {
        $guildMember = GuildMember::where('GuildID', $request->guild_id)
            ->where('MemberID', $request->member_id);

        $guildMember->delete();

        return ['success' => true];
    }

}
