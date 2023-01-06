@extends("main")

@section("title", "Platba ID {$data->variable_symbol}")

@section("actions")
    <a href="{{url("/payment/")}}"><i class="ti ti-arrow-back"></i> Zpět na seznam</a>
@endsection

@section("content")

    <div class="row">
        <div class="col-12 col-md-7">
            <h2>Detail platby</h2>
            <div class="row">
                <div class="col-12 col-md-4">
                    <img class="img-fluid" src="https://api.paylibo.com/paylibo/generator/czech/image?compress=false&size=440&accountNumber=2342343432&bankCode=0100&amount=43298&currency=CZK" alt="">
                </div>
                <div class="col-12 col-md-7">
                    <b>Variabilní symbol:</b> {{$data->variable_symbol}}<br>
                    <b>Částka:</b> {{$data->amount}} Kč<br>
                    <b>Splatnost:</b> {{\Carbon\Carbon::parse($data->due)->format("d.m.Y")}}<br>
                    <b>Popis platby:</b> {{$data->title}}<br>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <h2>Proběhlé transakce</h2>
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
        </div>
    </div>

    Zbývá zaplatit {{$data->remain}} Kč, již zaplaceno {{$data->paid}} Kč.

@endsection
