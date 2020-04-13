@extends('layouts.app')
@php

    use App\Facades\Membership;
    use App\Facades\GuildMembership;
    use App\Helpers\Utility;

@endphp
@section('content')
    <div id="guild_manage" class="content col-md-10 col-md-offset-1">
        <h1>{{ GuildMembership::getGuildName() }}</h1>
        @if ($can_edit)
        <div class="member-search col-md-12">
            <div class="member-search-field col-md-4">
                <input class="typeahead" id="guild_search" type="search" data-provide="typeahead" placeholder="Search for Members"/>
            </div>
            <div class="member-add-button col-md-1">
                <input id="guild_add_member" type="button" class="btn btn-primary short" value="Add" disabled/>
            </div>
        </div>
        @endif
        <div class="">
            <table id="guild_member_list" class="member-list">
                <thead>
                <tr>
                    <td width="25%">Name</td>
                    <td width="25%">Phone</td>
                    <td class="show-sm-up" width="25%">Email</td>
                    <td class="filterable" width="15%">Coven</td>
                    @if ($can_edit)
                    <td class="{{ ($can_edit) ? 'show' : 'hide' }}">Remove</td>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($members as $member)
                    <tr data-id="{{ $member->MemberID }}">
                        <td class="nobr">{{ $member->First_Name }} {{ $member->Last_Name }}</td>
                        <td class="nobr">{{ Membership::getPrimaryPhone($member->MemberID) }}</td>
                        <td class="show-sm-up">{!! Utility::mailto($member->Email_Address) !!}</td>
                        <td>{{ $member->Coven }}</td>
                        @if ($can_edit)
                        <td class="center remove"><i class="fa fa-close guild-remove"></i></td>
                        @endif
                    </tr>
                @endforeach
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @if ($can_edit)
                    <td></td>
                    @endif
                </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
    </div>
@endsection
        <!-- Push any scripts needed for this page onto the stack -->
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}public/js/lib/typeahead.bundle.min.js"></script>
    <script>
        appSpace.authTimeout = '{!! trans('auth.timeout') !!}';
        appSpace.canEdit = {!! ($can_edit) ? 'true' : 'false' !!};
    </script>
@endpush
