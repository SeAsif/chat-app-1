@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns is-marginless is-centered" style="padding-top: 50px;">
            <div class="column is-8">
                <chat></chat>
            </div>
            <div class="column is-4">
                <chat-users></chat-users>
            </div>
        </div>
    </div>
@endsection
