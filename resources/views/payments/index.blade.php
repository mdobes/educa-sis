@extends("main")

@section("title", "Moje platby")

@section("content")

    <div class="actions">
        <a href="/payment/create"><i class="ti ti-plus"></i> Vytvořit novou platbu</a>
    </div>

    <h1>Moje platby</h1>

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
    @forelse($data as $payment)
        <tr>
            <td>{{$payment->variable_symbol}}</td>
            <td>{{$payment->title}}</td>
            <td>{{$payment->amount}} Kč</td>
            <td>{{\Carbon\Carbon::parse($payment->due)->format("d.m.Y")}}</td>
            <td></td>
        </tr>
        @empty
        <tr>
            <td colspan="4">Nemáš žádné platby.</td>
        </tr>
    @endforelse
        </tbody>
    </table>

    {{ $data->links() }}


@endsection
