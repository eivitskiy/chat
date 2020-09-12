@extends('layouts.app')

@section('content')

    <div class="mt-3 h-50">
        <chat-component v-bind:messages="{{ json_encode($messages) }}"></chat-component>
    </div>
@endsection
