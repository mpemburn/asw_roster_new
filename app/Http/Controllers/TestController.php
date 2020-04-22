<?php


namespace App\Http\Controllers;


use App\Facades\RosterAuth;
use App\Models\Member;
use App\Services\MembershipService;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $perms = $user->getAllPermissions();
        !d($perms);
        $roles = $user->with('roles')->where('id',21)->first();

//        !d($roles);

        $membership = new MembershipService();
//        dd($membership->getMemberFromEmail('mpemburn@gmail.com'));
//        $name = RosterAuth::getMemberName();
//        $id = RosterAuth::getMemberId();
//        $userIsLeaderOrScribe = RosterAuth::userIsLeaderOrScribe();

//        echo $userIsLeaderOrScribe;

    }
}
