<?php
namespace App\Services;

use App\Facades\RosterAuth;
use App\Models\Coven;
use App\Models\LeadershipRole;
use App\Models\Member;
use App\User;
use App\Facades\Rbac;
use App\Facades\Roles;
use App\Helpers\Utility;
use Illuminate\Support\Collection;

class MembershipService
{
    /** @var Member $member */
    protected $member;

    public function __construct()
    {
        $this->member = new Member;
    }

    /**
     * Retrieve all active members.
     *
     * @param int $status
     * @return Collection|null
     */
    public function getActiveMembers(int $status = 1): ?Collection
    {
        if ($this->member) {
            return $this->member->where('Active', '=', $status)
                ->orderBy('Last_Name', 'asc')
                ->get();
        }

        return null;

    }


    public function getGuildMembers($guildId): ?Collection
    {
        if ($this->member) {
            return $this->member->where('Active', '=', 1)
                ->where('tblGuildMembers.GuildID', $guildId)
                ->join('tblGuildMembers', 'tblMembers.MemberID', '=', 'tblGuildMembers.MemberID')
                ->orderBy('Last_Name', 'asc')
                ->get();
        }

        return null;
    }

    /**
     * Retrieve existing record or, if none, return an empty Member object for "new".
     *
     * @param int $memberId
     * @return Member|null
     */
    public function getMemberById(int $memberId): ?Member
    {
        return Member::firstOrNew(['MemberID' => $memberId]);
    }

    /**
     * Retrieve a member if email matches (single or in comma-delimited string)
     *
     * @param $testEmail
     * @return null|Member
     */
    public function getMemberFromEmail($testEmail): ?Member
    {
        if ($this->member) {
            return $this->member->whereRaw('LOWER(`Email_Address`) LIKE ?', array('%' . strtolower($testEmail) . '%'))
                ->select('*')
                ->get()
                ->first();
        }

        return null;
    }

    /**
     * Retrieve the MemberID if a matching email address is found
     *
     * @param $testEmail
     * @return int
     */
    public function getMemberIdFromEmail($testEmail): ?int
    {
        $member = $this->getMemberFromEmail($testEmail);

        return $member ? $member->getMemberId() : null;
    }

    /**
     * Retrieve the MemberID if a matching email address is found
     *
     * @param $userId
     * @return int
     */
    public function getMemberFromUserId(int $userId): ?Member
    {
        $memberId = $this->getMemberIdFromUserId($userId);

        return $memberId ? $this->getMemberById($memberId) : null;
    }

    /**
     * Retrieve the MemberID if a matching email address is found
     *
     * @param $user_id
     * @return int
     */
    public function getMemberIdFromUserId(int $user_id): ?int
    {
        /** @var User $user */
        $user = User::find($user_id);

        return $user ? $user->getMemberId() : 0;
    }

    /**
     * Get the phone number listed as "primary" from the member record
     *
     * @param $memberId
     * @return string|null
     */
    public function getPrimaryPhone($memberId): ?string
    {
        $member = $this->getMemberById($memberId);
        if ($member) {
            $primaryPhoneId = $member->Primary_Phone;
            if ($primaryPhoneId > 0) {
                $phoneTypes = array('Home_Phone', 'Work_Phone', 'Cell_Phone');
                $chosen = $phoneTypes[$primaryPhoneId - 1];
                $phones = $member->where('MemberID', $memberId)
                    ->select($phoneTypes)
                    ->get();
                $phone = $phones->first();
                $primaryPhone = $phone[$chosen] ?? '';

                return Utility::formatPhone($primaryPhone);
            }
        }

        return null;
    }

    /**
     * Retrieve data to display in member detail when user does not have edit permission
     *
     * @param $memberId
     * @return array|null
     */
    public function getStaticMemberData($memberId): ?array
    {
        $member = $this->getMemberById($memberId);
        if (! $member) {
            return null;
        }

        $middle = (!empty($member->Middle_Name)) ? $member->Middle_Name . ' ' : '';
        $name = $member->Title . ' ' . $member->First_Name . ' ' . $middle . $member->Last_Name . ' ' . $member->Suffix;
        $coven = Coven::find($member->Coven);
        $leadership = LeadershipRole::where('Role', $member->LeadershipRole)->first();
        $degree = Utility::ordinal($member->Degree);
        $bonded = ($member->Bonded) ? Utility::yesno($member->Bonded) : '';
        $solitary = ($member->Solitary) ? Utility::yesno($member->Solitary) : '';
        $board = ($this->isCurrentBoardMember($memberId)) ? $member->BoardRole : '';
        $board_expiry = date('M j, Y', strtotime($member->BoardRole_Expiry_Date));

        return [
            'name' => trim($name),
            'address1' => $member->Address1,
            'address2' => $member->Address2,
            'email' => $member->Email_Address,
            'csz' => $member->City . ', ' . $member->State . ' ' . $member->Zip,
            'home_phone' => Utility::formatPhone($member->Home_Phone),
            'cell_phone' => Utility::formatPhone($member->Cell_Phone),
            'work_phone' => Utility::formatPhone($member->Work_Phone),
            'coven' => ($coven !== null) ? $coven->CovenFullName : '',
            'leadership' => ($leadership !== null) ? $leadership->Description : '',
            'degree' => $degree ?? '',
            'bonded' => $bonded,
            'solitary' => $solitary,
            'board' => $board,
            'board_expiry' => $board_expiry,
        ];
    }

    public function getUserFromMemberId($memberId): User
    {
        return User::where('member_id', '=', $memberId)->first();
    }

    /**
     * Test if the board member role is current
     *
     * @param null $memberId
     * @return bool
     */
    public function isCurrentBoardMember($memberId = null): bool
    {
        $member = $this->getMemberById($memberId);

        if ($member) {
            $has_role = (!empty($this->member->BoardRole));
            $expiry = $this->member->BoardRole_Expiry_Date;
            if (!Utility::isDate($expiry)) {
                return false;
            }
            $expired = (strtotime($expiry) < time());

            return ($has_role && !$expired);
        }

        return false;
    }

    /**
     * Test if email exists in members table
     *
     * @param $testEmail
     * @return bool
     */
    public function isValidEmail($testEmail): bool
    {
        $memberId = $this->getMemberIdFromEmail($testEmail);

        return ($memberId !== null);
    }

    /**
     * Do operations necessary after the member record has been created or saved
     *
     * @param $changes
     * @param $member_id
     * @return void
     */
    public function postSaveMemberActions($changes, Member $member)
    {
        $memberId = $member->getMemberId();

        $coven = $member->Coven;
        $user = $this->getUserFromMemberId($memberId);

        // If leadership role has been added or changed, we need to rewrite role permissions
        if (array_key_exists('LeadershipRole', $changes)) {
            Rbac::setLeadershipRoles();
        }
        // If PurseWarden status has changed, insert, update, or delete coven scribe record in CovenRoles
        if (array_key_exists('PurseWarden', $changes)) {
            $status = $changes['PurseWarden']['to'];
            Roles::changePurseWardenRole($coven, $memberId, $status);
        }
        // If Scribe status has changed, insert, update, or delete coven scribe record in CovenRoles
        if (array_key_exists('Scribe', $changes)) {
            $status = $changes['Scribe']['to'];
            Roles::changeScribeRole($coven, $memberId, $status);
            if ((int) $changes['Scribe']['to'] === 1) {
                RosterAuth::grantRoleToUser($user, 'coven-scribe');
            } else {
                RosterAuth::revokeRoleFromUser($user, 'coven-scribe');
            }
        }

    }

    /* Methods used in "Missing Data" page only */

    public function boardExpired($memberId): ?string
    {
        if ($this->isCurrentBoardMember($memberId)) {
            return 'Active';
        }

        $member = $this->getMemberById($memberId);
        if ($member) {
            $has_role = (!empty($member->BoardRole));
            $has_date = (Utility::isDate($member->BoardRole_Expiry_Date));
            $status = '';
            if ($has_role && $has_date) {
                $status = 'Expired';
            }
            if ($has_role && !$has_date) {
                $status = 'No Date';
            }
            return $status;
        }

        return null;
    }

    public function hasAll(array $memberFields): string
    {
        $hasAll = true;
        foreach ($memberFields as $field) {
            $hasAll = ($hasAll && !empty($field));
        }

        return (!$hasAll) ? 'X' : '';
    }

    public function hasNo($memberField): string
    {
        return (empty($memberField)) ? 'X' : '';
    }

    public function nonAlphaOrMissing($memberField): string
    {
        if (empty($memberField)) {
            return 'X';
        }
        $numbers = preg_replace('/[^0-9]/', '', $memberField);
        
        return (empty($numbers)) ? 'X' : '';
    }

}
