<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): ?Renderable
    {
        return view('home', [
            'cssUrl' => URL::to('/public/css'),
            'jsUrl' => URL::to('/js'),
            'authUser' => true, //Auth::check(),
            'guestUser' => Auth::guest(),
            'memberStatusIs' => (new Request())->is('member'),
            'guildStatusIs' => (new Request())->is('guild'),
            'memberName' => 'MarkyP', // RosterAuth::getMemberName()
            'memberId' => '6', // Auth::user()->member_id
            'userIsLeaderOrScribe' => true, // RosterAuth::userIsLeaderOrScribe()
            'guildList' => ['GRY' => 'Order of the Gryphons', 'FAL' => 'Order of the Falcon'], // GuildMembership::getGuilds()
        ]);
    }
}
