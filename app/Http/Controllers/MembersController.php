<?php

namespace App\Http\Controllers;

use App\Models\BoardRole;
use App\Models\Coven;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Member;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use GuildMembership;
use Auth;

class MembersController extends Controller
{
    protected $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Display a listing of Members.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $active = $this->member->getActiveMembers();

        return view('members_list',
            [
                'members' => $active['members']
            ]);
    }

    /**
     * List of covens
     *
     * @return JSON
     */
    public function listCovens(Request $request)
    {
        $success = false;
        $covens = null;
        $covens_array = null;
        $original_values = (is_array($request->values)) ? $request->values : null;

        if (!is_null($original_values)) {
            $covens = Coven::whereIn('Coven', $original_values)->pluck('CovenFullName', 'Coven');
        } else {
            $covens = Coven::pluck('CovenFullName', 'Coven');
        }
        if (count($covens) > 0) {
            $covens_array = $covens->toArray();
            ksort($covens_array);
            $success = true;
        }
        return ['success' => $success, 'data' => $covens_array];
    }

    /**
     * Display individual Member.
     *
     * @param int $member_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function memberDetails($memberId = 0)
    {
        $memberData = $this->member->getDetails($memberId);

        return view('member_edit', $memberData);
    }

    public function memberSearch(Request $request)
    {
        \DB::listen(function($sql) {
            //var_dump($sql);
        });

        $return = [];
        $query_string = $request->q;
        $guild_id = $request->guild_id;

        if (strlen($query_string) >= 2) {
            $results = Member::where('Active', 1)
                ->where(function  ($query) use ($query_string) {
                    $query->where('First_Name', 'LIKE', $query_string . '%')
                        ->orWhere('Last_Name', 'LIKE', $query_string . '%');
                })
                ->orderBy('Last_Name', 'asc')
                ->get();
            foreach ($results as $member) {
                if (!GuildMembership::isMember($guild_id, $member->MemberID)) {
                    $return[] = ['id' => $member->MemberID, 'value' => $member->First_Name . ' ' . $member->Last_Name];
                }
            }
        }

        return $return;
    }

    public function missingDetails($member_id = 0)
    {
        $covens = Coven::all();
        $members = [];
        foreach ($covens as $coven) {
            $coveners = Member::where('Coven', $coven->Coven)
                ->where('Active', 1)
                ->orderBy('Last_Name', 'asc')
                ->get();
            if (!$coveners->isEmpty()) {
                $members[$coven->Coven] = $coveners;
            }
        }
//        foreach ($members['KHC'] as $keeper) {
//            echo $keeper->First_Name;
//        }
        $missing_data = [
            'covens' => $covens,
            'members' => $members
        ];
        return view('members_missing_details', $missing_data);
    }

    public function resetProfilePassword()
    {
        return view('auth/passwords/profile_reset', ['token' => csrf_token()]);
    }

    public function setNewPassword(Request $request)
    {
        // Set up validator rule to match existing password
        Validator::extend('match_old', function ($attribute, $value, $parameters) {
            $user_password = \Auth::user()->password;
            return (Hash::check($value, $user_password));
        });

        // Validate user input.  Send them errors and let them try again if they fail
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|match_old',
            'password' => 'required|min:6|bad_pattern|confirmed',
        ]);
        if ($validator->fails()) {
            return redirect('profile/password')
                ->withErrors($validator)
                ->withInput($request->all());
        }

        // Reset password
        $user = User::find(Auth::user()->id);
        $user->setPassword($request->password);

        // Redirect to login page after logging out
        Auth::logout();
        return redirect('login');
    }

    public function migrate()
    {
        $active = Member::getActiveMembers();
        $user = new UsersController;
        foreach ($active['members'] as $member) {
            if (!empty($member->LeadershipRole) && !empty($member->UserPassword)) {
                $hash = Hash::make($member->UserPassword);
                $data = array(
                    'member_id' => $member->MemberID,
                    'name' => $member->First_Name . ' ' . $member->Last_Name,
                    'email' => $member->Email_Address,
                    'password' => $hash
                );
                $user->insert((object)$data);
            }
        }
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Success status
     */
    public function update(Request $request, $id)
    {
        // Get array of Board Roles to use for validation
        $board_roles = BoardRole::pluck('BoardRole')->toArray();

        $rules = [
            'First_Name' => 'required',
            'Last_Name' => 'required',
            'Address1' => 'required',
            'City' => 'required',
            'State' => 'required',
            'Zip' => 'required',
            'Email_Address' => 'required',
            'BoardRole_Expiry_Date' => "required_if:BoardRole," . implode(',', $board_roles),
        ];
        // Validate user input.  Send them errors and let them try again if they fail
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $response = $this->member->saveMember($request->all());
        }

        return response()->json(['response' => $response]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
