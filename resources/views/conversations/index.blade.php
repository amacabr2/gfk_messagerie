@extends('layouts.app')

@section('content')
    <div id="messagerie" class="container">
        <Messagerie :user="{{ Auth::user()->id }}"></Messagerie>
    </div>
@endsection