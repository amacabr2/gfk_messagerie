@extends('layouts.app')

@section('content')
    <div id="messagerie" class="container" data-base="{{ route('conversations.index', [], false) }}">
        <Messagerie :user="{{ Auth::user()->id }}"></Messagerie>
    </div>
@endsection