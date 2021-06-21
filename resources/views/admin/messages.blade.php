@extends('layouts.app')

@section('title')
    BoolDoctors - Messaggi
@endsection

@section('content')
    <div class="my_container">
        <h1 id="title">I tuoi messaggi</h1>
        <div class="messages">
            @foreach ($messages as $message)
            <div class="message">
                <div class="message_top">
                    <h5>Inviato da: {{$message->email}}</h5>
                    <button type="button" class="btn btn-primary">Risolvi richiesta</button>
                </div>
                <p>{{$message->message}}</p>
                <p><small>Data e orario dell'invio: {{$message->added_on}}</small></p>
            </div>
            @endforeach
        </div>
        <p class="link_dashboard"><a href="{{ route('admin.profile.index') }}">Torna alla Dashboard</a></p>
    </div>
@endsection
