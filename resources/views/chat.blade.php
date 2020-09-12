@extends('layouts.app')

@section('content')
    <div class="mt-3 h-50">
        <chat-component
            v-bind:messages="{{ json_encode($messages, JSON_THROW_ON_ERROR) }}"
            v-bind:myselfuser="{{ json_encode(Auth::user(), JSON_THROW_ON_ERROR) }}"
        ></chat-component>
    </div>
@endsection
