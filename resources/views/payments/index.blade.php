@extends("main")

@section("title", $title)

@section("actions")
    @if($showGrouping)
        <div class="btn-group" role="group">
            <a class="btn btn-primary">Seznam plateb</a>
            <a class="btn btn-outline-primary">Skupiny plateb</a>
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
                @can("payments.view")
                    <a href="{{url("/payment/$payment->id")}}" class="text-decoration-none"><i class="ti ti-info-circle"></i></a>
                @endcan
                <a href="{{url("/payment/edit/$payment->id")}}" class="text-decoration-none"><i class="ti ti-edit"></i></a>
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
