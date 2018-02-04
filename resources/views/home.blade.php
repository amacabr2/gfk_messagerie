@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 4em;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    For tchat it's <a href="{{ route('conversations.index') }}">here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
