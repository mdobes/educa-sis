@extends("main")

@section("title", "Platba ID {{$data->variable_symbol}}")

@section("content")
    <h1>Platba ID {{$data->variable_symbol}}</h1>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">Variabilní symbol</th>
            <th scope="col">Popis platby</th>
            <th scope="col">Částka</th>
            <th scope="col">Splatnost</th>
            <th scope="col">Akce</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{$data->variable_symbol}}</td>
            <td>{{$data->title}}</td>
            <td>{{$data->amount}} Kč</td>
            <td>{{\Carbon\Carbon::parse($data->due)->format("d.m.Y H:i")}}</td>
            <td></td>
        </tr>
        </tbody>
    </table>
@endsection
