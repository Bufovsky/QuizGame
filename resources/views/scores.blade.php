@extends('layouts.layout')

@section('content')
<style>
    table {
        width: 100%;
    }
    table td {
        border: 1px solid #888;
    }
</style>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3>Twoje dotychczasowe osiągnięcia!</h3></div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <table>
                <tr><td> ID </td><td> DATA </td><td> WYNIK </td><td> UDOSTĘPNIJ </td></tr>
                @foreach($results as $result)
                <tr><td>{!!$result['game_id']!!} </td><td> {!! \Carbon\Carbon::parse($result['created_at'])->format('d/m/Y h:i:s')!!} </td><td> {!!$result['score']!!} </td><td><a href="{{ route('quiz.game', ['id' => $result['id']]) }}">UDOSTĘPNIJ</a></td></tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
