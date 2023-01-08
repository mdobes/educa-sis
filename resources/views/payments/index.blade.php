@extends("main")

@section("title", $title)

@section("actions")
    @if($showGrouping)
        <div class="btn-group" role="group">
            <a class="btn disabled btn-primary">Seznam plateb</a>
            <a class="btn disabled btn-outline-primary">Skupiny plateb</a>
        </div>
        <div class="d-block mb-2"></div>
    @endif
    @if($showPaid)
        <div class="btn-group" role="group">
            <a href="{{ route("payment.my") }}" class="btn {{request()->is("payment") ? 'btn-primary' : 'btn-outline-primary'}}">Neuhrazené</a>
            <a href="{{ route("payment.mypaid") }}" class="btn {{request()->is("payment/paid") ? 'btn-primary' : 'btn-outline-primary'}}">Uhrazené</a>
        </div>
        <div class="d-block mb-2"></div>
    @endif
    @can("payments.create")
    <a href="{{route("payment.create")}}"><i class="ti ti-plus"></i> Vytvořit novou platbu</a>
    @endcan
@endsection

@section("content")
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Popis platby</th>
            <th scope="col">Částka</th>
            <th scope="col">Zbývá</th>
            <th scope="col">Splatnost</th>
            <th scope="col">Akce</th>
        </tr>
        </thead>
        <tbody>
    @forelse($data as $payment)
        <tr @if(\Carbon\Carbon::parse($payment->due) < \Carbon\Carbon::now() && $payment->remain > 0) class="bg-danger bg-opacity-75" @endif>
            <td>{{$payment->title}}</td>
            <td>{{$payment->amount}} Kč</td>
            <td>{{$payment->remain}} Kč</td>
            <td>{{\Carbon\Carbon::parse($payment->due)->format("d.m.Y")}}</td>
            <td>
                @if($payment->author == $username || $payment->payer == $username || $user->hasPermissionTo('payments.view'))
                    <a data-bs-toggle="tooltip" data-bs-title="Zobrazit detail platby" href="{{url("/payment/$payment->id")}}" class="text-decoration-none"><i class="ti ti-info-circle"></i></a>
                @endif

                @if($payment->author == $username || $user->hasPermissionTo('payments.edit'))
                <a data-bs-toggle="tooltip" data-bs-title="Editovat platbu" href="{{url("/payment/edit/$payment->id")}}" class="text-decoration-none"><i class="ti ti-edit"></i></a>
                @endif
            </td>

        </tr>
        @empty
        <tr>
            <td colspan="6">Žádné platby nebyly nalezeny.</td>
        </tr>
    @endforelse
        </tbody>
    </table>

    {{ $data->links() }}


@endsection
