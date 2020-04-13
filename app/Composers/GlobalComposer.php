<?php

namespace App\Composers;

use App\Facades\RosterAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use App\Facades\GuildMembership;

class GlobalComposer
{
    public function compose(View $view): void
    {
        $view->with('authUser', Auth::check())
        ->with('guestUser', Auth::guest())
        ->with('memberStatusIs', (new Request())->is('member'))
        ->with('guildStatusIs', (new Request())->is('guild'))
        ->with('memberName', RosterAuth::getMemberName())
        ->with('memberId', RosterAuth::getMemberId())
        ->with('userIsLeaderOrScribe', RosterAuth::userIsLeaderOrScribe())
        ->with('guildList', GuildMembership::getGuilds());
    }

}
