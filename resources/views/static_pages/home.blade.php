@extends('layouts.default')

@section('content')
    <div class="bg-light p-3 p-sm-5 rounded">
        <h1>Minamoto</h1>
        <p class="lead">
            I'm Minamoto.
        </p>
        <p>
            欢迎来到我的Mina妙妙屋.
        </p>
        <p>
            <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">Signup now</a>
        </p>
    </div>
@stop
