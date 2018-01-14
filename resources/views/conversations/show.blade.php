@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('conversations.users', ['users' => $users])

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">{{ $user->name }}</div>

                    <div class="card-body conversations">
                        @forelse($messages as $message)
                            <div class="row">
                                <div class="col-md-10 {{ $message->from->id !== $user->id ? 'offset-md-2 text-right' : '' }}">
                                    <p>
                                        <strong>{{  $message->from->id !== $user->id ? 'Moi' : $message->from->name }}</strong>
                                        {{ $message->content }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                        @empty
                            <div class="row">
                                <div class="col-md-10">
                                    <p><i>Aucun message</i></p>
                                </div>
                            </div>
                        @endforelse

                        <form action="" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <textarea name="content" id="content" class="form-control {{ $errors->has('content') ? 'is-invalid' : ''}}" placeholder="Ecrivez votre message"></textarea>
                                @if($errors->has('content'))
                                    <div class="invalid-feedback">{{ implode(', ', $errors->get('content')) }}</div>
                                @endif
                            </div>
                            <button class="btn btn-primary" type="submit">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection