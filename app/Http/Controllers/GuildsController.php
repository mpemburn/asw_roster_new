<?php

namespace App\Http\Controllers;

use App\Models\GuildMember;
use Illuminate\Http\Request;
use App\Http\Requests;
// Facades
use GuildMembership;
use Membership;
use RosterAuth;

class GuildsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Add or remove members
     */
    public function manage($guild_id)
    {
        $active = Membership::getGuildMembers($guild_id);
        $has_leader_role = RosterAuth::isGuildLeader();
        $member_id = RosterAuth::getMemberId();
        $is_admin = RosterAuth::isAdmin();
        $is_leader = GuildMembership::isLeader($guild_id, $member_id);
        $can_edit = ($is_admin || ($has_leader_role && $is_leader));

        return view('guild_manage', ['members' => $active, 'can_edit' => $can_edit]);
    }

    public function add(Request $request) {
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
        $guild_member = GuildMember::where('GuildID', $request->guild_id)
            ->where('MemberID', $request->member_id);

        $guild_member->delete();

        return ['success' => true];
    }

}
