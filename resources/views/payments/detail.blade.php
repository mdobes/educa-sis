@extends("main")

@section("title", "Informace o platbě")

@section("actions")
    <a href="{{url()->route("payment.group", $data->group)}}"><i class="ti ti-arrow-back"></i> Zpět na seznam</a>

    @if($data->remain > 0)
        @if($data->author == $username || Auth::user()->permission == "admin")
            <a href="#" data-bs-toggle="modal" data-bs-target="#addTransaction"><i class="ti ti-cash"></i> Přidat transakci</a>
        @endif
    @endif
@endsection

@section("content")

    <div class="row mb-3">
        <div class="col-12">
            <h5 class="mb-0">
                @if(\Carbon\Carbon::parse($data->due . "23:59") < \Carbon\Carbon::now() && $data->remain > 0)
                    <span class="badge bg-danger text-uppercase">Po datu splatnosti</span>
                @endif

                @if($data->remain == 0)
                    <span class="badge bg-success text-uppercase">Uhrazeno</span>
                @endif

                @if($data->remain > 0)
                    <span class="badge bg-secondary text-uppercase">Zbývá uhradit {{$data->remain}} Kč</span>
                @endif

                @if($data->remain < 0)
                    <span class="badge bg-warning text-uppercase">Přeplatek {{$data->remain}} Kč</span>
                @endif
            </h5>
        </div>
    </div>

    <div class="row">
        <h2>{{$data->payerFormatted}} - {{$data->title}}</h2>
        <div class="col-12 col-md-6">
            <h3>Detail platby</h3>
            <div class="row">
                <div class="col-12 col-md-4">
                    <img class="img-fluid" src="{{$img}}">
                </div>
                <div class="col-12 col-md-7">
                    <b>Číslo BÚ:</b> {{config("bank.bank.acc_number")}}<br>
                    <b>Variabilní symbol:</b> {{$data->payerUserId}}<br>
                    <b>Specifický symbol</b> {{ $data->specific_symbol }}<br/>
                    <b>Částka:</b> {{$data->amount}} Kč<br>
                    <b>Zbývá:</b> {{$data->remain}} Kč<br>
                    <hr>
                    <b>Splatnost:</b> {{$data->dueFormatted}}<br>
                    <b>Popis platby:</b> {{$data->title}}<br>
                    <b>Zadal:</b> {{$data->authorFormatted}}
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <h3>Proběhlé transakce</h3>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Částka</th>
                    <th scope="col">Autor</th>
                    <th scope="col">Typ</th>
                    <th scope="col">Vytvořeno</th>
                    <th scope="col">Akce</th>
                </tr>
                </thead>
                <tbody>
                @forelse($data->transactions as $transaction)
                    <tr class="@if($transaction->deleted_at)opacity-50 bg-secondary @endif" @if($transaction->note)style="border-bottom-width: 0; border-bottom-style: hidden;"@endif>
                        <td>{{$transaction->amount}} Kč</td>
                        <td>{{ ($transaction->author == "System") ? "System" : \App\Models\User::where("username", "=", $transaction->author)->first()->name }}</td>
                        <td>{{ ($transaction->type == "cash") ? "Hotově" : "Bankovní převod" }} </td>
                        <td>{{\Carbon\Carbon::parse($transaction->created_at)->format("d.m.Y H:i")}}</td>
                        <td>
                            @if(!$transaction->deleted_at)
                            <a data-bs-toggle="tooltip" data-bs-title="Odpárovat transakci" href="/transaction/{{$transaction->id}}/unpair" class="text-decoration-none"><i class="ti ti-trash"></i></a>
                            @endif

                            @if($transaction->deleted_at)
                            <a data-bs-toggle="tooltip" data-bs-title="Znovu spárovat transakci" href="/transaction/{{$transaction->id}}/restore" class="text-decoration-none"><i class="ti ti-trash-off"></i></a>
                           @endif
                        </td>
                    </tr>
                    @if($transaction->note)
                        <tr class="@if($transaction->deleted_at)opacity-50 bg-secondary @endif">
                            <td colspan="5">Poznámka: {{$transaction->note}}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5">Neproběhly žádné platby.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addTransaction" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Přidat transakci</h1>
                    <button type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    {!! Form::open(["url" => url("transaction"), "method" => "post", "id" => "login"]) !!}
                    {!! Form::hidden("payment_id", $data->id) !!}
                    <div class="mb-3">
                        {!! Form::label("text", "Částka", ["class" => "form-label"]) !!}
                        {!! Form::number("amount", $data->title ?? null, ["placeholder" => "200", "class" => "form-control", "required" => true]) !!}
                    </div>
                    <div class="mb-3">
                        {!! Form::label("text", "Typ", ["class" => "form-label"]) !!}
                        {!! Form::select("type", ["cash" => "Hotově", "bank_transfer" => "Bankovní převod"], null, ["class" => "form-select", "required" => true]) !!}
                    </div>
                    <div class="mb-3">
                        {!! Form::label("text", "Poznámka", ["class" => "form-label"]) !!}
                        {!! Form::text("note", $data->note ?? null, ["class" => "form-control"]) !!}
                    </div>
                    {!! Form::button("Přidat", ["type" => "submit", "class" => "btn btn-primary"]) !!}
                </div>
            </div>
        </div>
    </div>

@endsection
