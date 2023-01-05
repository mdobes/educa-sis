@extends("main")

@section("title", "Platba ID {{$data->variable_symbol}}")

@section("content")
    <div class="actions">
        <a href="/payment/"><i class="ti ti-arrow-back"></i> Zpět na seznam</a>
    </div>

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
    Zbývá zaplatit {{$data->remain}} Kč, již zaplaceno {{$data->paid}} Kč.

    <h2>Proběhlé transakce u platby</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Částka</th>
            <th scope="col">Autor</th>
            <th scope="col">Vytvořeno</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data->transactions as $transaction)
            <tr>
                <td>{{$transaction->amount}} Kč</td>
                <td>{{$transaction->author}}</td>
                <td>{{\Carbon\Carbon::parse($transaction->created_at)->format("d.m.Y")}}</td>
                <td></td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Neproběhly žádné platby.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
