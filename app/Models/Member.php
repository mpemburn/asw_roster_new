<?php

namespace App\Models;

use App\Models\CovenRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Collective\Html\Eloquent\FormAccessible;
// Helpers and Facades
use App\Helpers\Utility;
use App\Facades\Audit;
use App\Facades\Membership;
use App\Facades\RosterAuth;
use App\Facades\Roles;

/**
 * Class Member
 */
class Member extends Model
{
    use FormAccessible;

    protected $table = 'tblMembers';

    protected $primaryKey = 'MemberID';

    public $timestamps = false;

    protected $fillable = [
        'Active',
        'Member_Since_Date',
        'Member_End_Date',
        'Last_Name',
        'First_Name',
        'Middle_Name',
        'Suffix',
        'Title',
        'Address1',
        'Address2',
        'Magickal_Name',
        'City',
        'State',
        'Zip',
        'Home_Phone',
        'Work_Phone',
        'Cell_Phone',
        'Fax_Phone',
        'Primary_Phone',
        'Email_Address',
        'Birth_Date',
        'Birth_Time',
        'Birth_Place',
        'Degree',
        'First_Degree_Date',
        'Second_Degree_Date',
        'Third_Degree_Date',
        'Fourth_Degree_Date',
        'Fifth_Degree_Date',
        'Bonded',
        'Bonded_Date',
        'Solitary',
        'Solitary_Date',
        'Coven',
        'LeadershipRole',
        'Leadership_Date',
        'BoardRole',
        'BoardRole_Expiry_Date',
        'Comments',
        'UserLogon',
        'UserPassword',
        'InitialPassword',
        'Security_Question_ID',
        'Security_Answer',
        'UserTimeZone',
        'LoginEnabled',
        'LastOnlineTime',
        'PasswordWarnings'
    ];

    protected $guarded = [];

    protected $member;
    protected $member_id;

    /**
     * Get Member details for editing or static details if user lacks edit permission
     *
     * @param int $member_id
     * @return array
     */
    public function getDetails($member_id = 0)
    {
        $this->member_id = $member_id;
        $this->member = $this->firstOrNew(['MemberID' => $member_id]);
        // Retrieve permissions
        $current_user_id = Auth::user()->id;
        $user_member = Membership::getMemberFromUserId($current_user_id);
        $can_create = $this->canCreate($current_user_id, $this->member->Coven);
        $can_edit = (!is_null($this->member)) ? $this->canEdit() : false;

        $isAdmin = RosterAuth::isAdmin();
        // Uses the RolesService (via Roles Facade) to determine whether user can edit
        $isPurseWarden = Roles::isPurseWarden($member_id, $this->member->Coven);
        $isScribe = Roles::isScribe($member_id, $this->member->Coven);

        $selected_coven = ($user_member->Coven == $this->member->Coven) ? $this->getSelectedCoven($can_create) : $this->member->Coven;
        $coven_name = (!is_null($selected_coven)) ? Coven::where('Coven', $selected_coven)->first()->CovenFullName : null;
        $data = [
            'can_edit' => ($can_create || $can_edit),
            'is_my_profile' => $this->isCurrentUsersProfile(),
            'is_active' => ($member_id == 0) ? 1 : null, // Default to checked if this is a new record
            'member_id' => $this->member_id,
            'user_id' => Auth::user()->id,
            'member' => $this->member,
            'is_admin' => $isAdmin,
            'is_pw' => $isPurseWarden,
            'is_scribe' => $isScribe,
            'selected_coven' => $selected_coven,
            'coven_name' => $coven_name,
            'static' => (object) Membership::getStaticMemberData($member_id),
            'main_col' => ($can_create || $can_edit) ? '9' : '6',
            'sidebar_col' => ($can_create || $can_edit) ? '3' : '6',
        ];
        if ($data['can_edit']) {
            $data = $data + $this->getDropdowns();
        }

        return $data;
    }

    /**
     * Get list of active members
     *
     * @param int $status
     * @return array
     */
    public function getActiveMembers($status = 1)
    {
        $active_members = Membership::getActiveMembers($status);
        return array('members' => $active_members);
    }

    /**
     * Insert or update Member record
     *
     * @param $data
     * @return array
     */
    public function saveMember($data)
    {
        $member_id = $data['MemberID'];
        $is_new = false;
        $changed = [];
        $count = 0;

        if ($member_id == 0) {
            $member = new Member();
            $is_new = true;
        } else {
            $member = $this->find($member_id);
        }

        // All date fields need to be reformatted
        $data = Utility::reformatDates($data, [
            'Member_Since_Date',
            'Member_End_Date',
            'Birth_Date',
            'First_Degree_Date',
            'Second_Degree_Date',
            'Third_Degree_Date',
            'Fourth_Degree_Date',
            'Fifth_Degree_Date',
            'Bonded_Date',
            'Solitary_Date',
            'Leadership_Date',
            'BoardRole_Expiry_Date',
        ], 'Y-m-d');

        $data = Utility::reformatCheckboxes($data, [
            'Active',
            'Bonded',
            'Scribe',
            'PurseWarden',
            'Solitary',
        ]);

        if (!$is_new) {
            // Find fields changed from current record
            $changed = $this->findChanges($member, $data);
            // Log changes
            Audit::writeAuditLog($changed, $this->table, $this->primaryKey, $member_id);
        }

        $result = $member->fill($data)->save();
        $member_id = $member->MemberID;


        // Make any changes necessary after Member record has been saved
        $count = Membership::postSaveMemberActions($changed, $member);

        return [
            'status' => $result,
            'member_id' => $member_id,
            'is_new' => $is_new,
            'changed' => $changed,
            'count' => $count,
            'data' => $data
        ];
    }

    /* Private Methods */

    /**
     * Use RosterAuth (facade to Services\RosterAuthService) to test if user can create a new record
     *
     * @return bool
     */
    private function canCreate($user_id, $coven)
    {
        // See if user is either a leader or scribe
        $is_leader_or_scribe = RosterAuth::userIsLeaderOrScribe($user_id, $coven);

        return $is_leader_or_scribe;

    }

    /**
     * Use RosterAuth (facade to Services\RosterAuthService) to test if user can edit this record
     *
     * @return bool
     */
    private function canEdit()
    {
        // Get the member id of the currently logged-in user
        $current_user_member_id = Membership::getMemberIdFromUserId(Auth::user()->id);
        // See if this is the current member (user may edit their own profile).
        $is_this_user = RosterAuth::isThisMember($this->member_id);
        // See if user is an Admin
        $is_admin =  RosterAuth::isMemberOf('admin');
        // See if this user is a leader of this member's coven
        $is_coven_leader = Roles::isLeader($current_user_member_id, $this->member->Coven);
        // See if this user is a scribe of this member's coven
        $is_coven_scribe = Roles::isScribe($current_user_member_id, $this->member->Coven);

        return ($this->member_id !=0 && ($is_this_user || $is_admin || $is_coven_leader || $is_coven_scribe));
    }

    /**
     * Test if current user is editing their own profile
     *
     * @return bool
     */
    private function isCurrentUsersProfile()
    {
        // See if this is the current member (user may edit their own profile).
        $is_this_user = RosterAuth::isThisMember($this->member_id);
        // See if user is an Admin
        $is_admin =  RosterAuth::isMemberOf('admin');
        // See if user is either a leader or scribe
        $is_leader_or_scribe = RosterAuth::userIsLeaderOrScribe();

        return ($is_this_user && !($is_admin || $is_leader_or_scribe));
    }

    /**
     * Find the changes (to and from) in the saved record
     *
     * @param Member $member
     * @param $new_data
     * @return array
     */
    public function findChanges(Member $member, $new_data)
    {
        $member_id = $member->MemberID;
        $changes = [];
        $isPurseWarden = Roles::isPurseWarden($member_id, $member->Coven);
        $isScribe = Roles::isScribe($member_id, $member->Coven);
        $attributes = $member->getAttributes();
        $attributes['Scribe'] = ($isScribe) ? 1 : 0;
        $attributes['PurseWarden'] = ($isPurseWarden) ? 1 : 0;
        foreach ($attributes as $field => $old_value) {
            $new_value = (isset($new_data[$field])) ? $new_data[$field] : null;
            if ($new_value != $old_value && !is_null($new_value) && !is_null($old_value)) {
                $changes[$field] = [ 'from' => $old_value, 'to' => $new_value ];
            }
        }
        return $changes;
    }
    /**
     * Use RosterAuth (facade to Services\RosterAuthService) to get coven abbreviation for new record
     *
     * @param $can_create
     * @return string
     */
    private function getSelectedCoven($can_create)
    {
        // Pre-select coven if this is a leader or scribe (but not an Elder) and it's a new record
        return ($can_create && !RosterAuth::isElder()) ? RosterAuth::getUserCoven() : null;
    }

    /**
     * Get dropdown lists needed for member create/edit
     *
     * @return array
     */
    private function getDropdowns()
    {
        $prefix = Title::lists('Title', 'Title')->prepend('', '');
        $suffix = Suffix::lists('Suffix', 'Suffix')->prepend('', '');
        $state = State::lists('State', 'Abbrev')->prepend('State *', '');
        $coven = Coven::lists('CovenFullName', 'Coven')->prepend('', '');
        $degree = Degree::lists('Degree_Name', 'Degree');
        $leadership = Roles::leadershipDropdown();
        $board = Roles::boardDropdown();

        return [
            'prefix' => $prefix,
            'suffix' => $suffix,
            'state' => $state,
            'coven' => $coven,
            'degree' => $degree,
            'leadership' => $leadership,
            'board' => $board,
        ];
    }

    private function createCovenRoles()
    {
        $roles = Roles::getCovenRoleArray();

        $members = Membership::getActiveMembers();
        foreach ($members as $member) {
            $coven = $member->Coven;
            $role = $member->LeadershipRole;
            if (in_array($role, $roles)) {
                $covenRole = new CovenRoles;
                $covenRole->Coven = $coven;
                $covenRole->MemberID = $member->MemberID;
                $covenRole->Role = $role;
                $covenRole->save();
            }
        }
    }
}
