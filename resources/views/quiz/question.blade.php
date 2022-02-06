@extends('layouts.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h1>{{$question['question']}}</h1></div>

            <div class="card-body">
                <form action="{{ route('quiz.store', ['id' => $game_id]) }}" method="POST">
                    @csrf
                    @for($i = 0; $i < 3; $i++)
                        <input type="submit" name="submit" value="{!!$question['answears'][$i]!!}"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    @endfor
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
