@extends('layouts.layout')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3>Witamy w quizie nacisnij start, aby rozpocząć grę.</h3></div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                </br>
                <a href="{{ route('quiz.new') }}"><button>START</button></a>
            </div>
        </div>
    </div>
</div>

@endsection
