@extends('layouts.default')

@section('content')
    <div class="bg-light p-3 p-sm-5 rounded">
        <h1>Minamoto</h1>
        <p class="lead">
            Hi, I'm Minamoto.
        </p>
        <p>
            This is the first web application I built using Laravel.
        </p>
        <p>
            <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">Signup now</a>
        </p>
    </div>
@stop
