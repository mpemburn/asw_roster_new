<?php


namespace App\Http\Controllers;


use App\Models\Member;

class TestController extends Controller
{
    public function show()
    {
        $member = new Member();
        dd($member->where('Active', '=', 1)->get()->toArray());
        //return 'Howdy!';
    }
}
