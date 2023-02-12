@extends("main")

@section("title", "Skupina plateb: " . $group->name)

@section("content")
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Plátce</th>
            <th scope="col">Částka</th>
            <th scope="col">Zbývá</th>
            <th scope="col">Splatnost</th>
            <th scope="col">Akce</th>
        </tr>
        </thead>
        <tbody>
        @foreach($group->payments as $p)
            <tr>
                <td>{{$p->payerFormatted}}</td>
                <td>{{$p->amountFormatted}}</td>
                <td>{{$p->remainFormatted}}</td>
                <td>{{$p->dueFormatted}}</td>
                <td><a data-bs-toggle="tooltip" data-bs-title="Zobrazit detail platby" href="/payment/{{$p->id}}" class="text-decoration-none"><i class="ti ti-info-circle"></i></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
