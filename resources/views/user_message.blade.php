@extends('layouts.app')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">{{ $heading }}</div>
            <div class="panel-body text-center">
                {!! $message !!}
            </div>
        </div>
    </div>
@endsection
