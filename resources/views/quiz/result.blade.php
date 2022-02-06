@extends('layouts.layout')

@section('content')


<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h1>Graturacje ukończyłeś quiz!</h1></div>

            <div class="card-body">
                Twój wynik to: <b>{!!$result!!}</b></br></br>
                &raquo; {!!$share!!} &laquo; 
                <!-- &raquo; <a href="{{route('quiz.new')}}">START</a> &laquo; -->

            </div>
        </div>
    </div>
</div>

@endsection
