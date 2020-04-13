<?php


namespace App\Http\Controllers;


use App\Models\Member;
use App\Services\MembershipService;

class TestController extends Controller
{
    public function show()
    {
        $membership = new MembershipService();
        dd($membership->getMemberFromEmail('mpemburn@gmail.com'));
    }
}
