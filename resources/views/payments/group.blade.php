@extends("main")

@section("title", "Skupina plateb: " . $group->name)

@section("actions")
    <a href="{{url()->route("payment.show")}}"><i class="ti ti-arrow-back"></i> Zpět na seznam</a>
@endsection

@section("content")
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Plátce</th>
            <th scope="col">Částka</th>
            <th scope="col">Zbývá</th>
            <th scope="col">Splatnost</th>
            <th scope="col">Hotově/Převodem</th>
            <th scope="col">Akce</th>
        </tr>
        </thead>
        <tbody>
        @foreach($group->payments as $p)
            <tr class="@if($p->remain == 0)bg-success-subtle @endif clickable-row" data-href="/payment/{{$p->id}}">
                <td>{{$p->payerFormatted}}</td>
                <td>{{$p->amountFormatted}}</td>
                <td>{{$p->remainFormatted}}</td>
                <td>{{$p->dueFormatted}}</td>
                <td>{{$p->transactionsCash}}/{{$p->transactionsBank}} Kč</td>
                <td><a data-bs-toggle="tooltip" data-bs-title="Zobrazit detail platby" href="/payment/{{$p->id}}" class="text-decoration-none"><i class="ti ti-info-circle"></i></a></td>
            </tr>
            </a>
        @endforeach
        </tbody>
    </table>

    <p>
        <b>Celkem zaplaceno hotově:</b> {{$group->paidCash}} Kč<br>
        <b>Celkem zaplaceno bankovním převodem:</b> {{$group->paidBank}} Kč
    </p>
@endsection

@section("scripts")
    <script>
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
    </script>
@endsection
