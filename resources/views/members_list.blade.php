@extends('layouts.app')
@section('content')
    <div class="content">
        <table id="main_member_list" class="member-list">
            <thead>
            <tr>
                <td>Name</td>
                <td class="show-sm-up">Address</td>
                <td>City</td>
                <td>State</td>
                <td class="show-sm-up">Zip</td>
                <td>Phone</td>
                <td class="show-sm-up">Email</td>
                <td class="filterable">Coven</td>
                <td class="show-lg-up filterable">Degree</td>
                <td class="show-lg-up">Bonded</td>
                <td class="show-lg-up filterable">Role</td>
                <td class="show-lg-up">Board</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($members as $member)
                <tr data-id="{{ $member->MemberID }}">
                    <td class="nobr">{{ $member->First_Name }} {{ $member->Last_Name }}</td>
                    <td class="show-sm-up">{{ $member->Address1 }}</td>
                    <td>{{ $member->City }}</td>
                    <td>{{ $member->State }}</td>
                    <td class="show-sm-up">{{ $member->Zip }}</td>
                    <td class="nobr">{{ Membership::getPrimaryPhone($member->MemberID) }}</td>
                    <td class="show-sm-up">{!! Utility::mailto($member->Email_Address) !!}</td>
                    <td>{{ $member->Coven }}</td>
                    <td class="show-lg-up">{{ Utility::ordinal($member->Degree) }}</td>
                    <td class="show-lg-up">{{ Utility::yesno($member->Bonded) }}</td>
                    <td class="show-lg-up">{{ Roles::getAllRoles($member) }}</td>
                    <td class="show-lg-up">{{ $member->BoardRole }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection
    <!-- Push any scripts needed for this page onto the stack -->
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/js/lib') }}/typeahead.bundle.min.js"></script>
@endpush