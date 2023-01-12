@extends("main")

@section("title", $title)

@section("actions")
    @if($showGrouping)
        @include("payments.includes.actions")
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
    @if($showBacklink)
        <a href="{{ url()->previous() }}"><i class="ti ti-arrow-back"></i> Zpět na seznam</a>
    @endif
@endsection

@section("content")
    <table class="table" data-locale="cs-CZ" data-toggle="table" data-ajax="ajaxRequest" data-search="true"  data-side-pagination="server"  data-pagination="true">
        <thead>
        <tr>
            <th scope="col" data-field="title">Popis platby</th>
            @if($showPayer)
                <th scope="col" data-field="payerFormatted">Plátce</th>
            @endif
            <th scope="col" data-field="amountFormatted">Částka</th>
            <th scope="col" data-field="remainFormatted">Zbývá</th>
            <th scope="col" data-field="dueFormatted">Splatnost</th>
            <th scope="col" data-formatter="operateFormatter">Akce</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    {{ $data->links() }}


@endsection
@section("scripts")
    <script>
        function ajaxRequest(params) {
            let url = "{{url()->route("payment.search")}}";
            $.get(url + '?' + $.param(params.data)).then(function (res) {
                params.success(res)
            })
        }

        function operateFormatter(value, row, index) {
            return [
                `<a data-bs-toggle="tooltip" data-bs-title="Zobrazit detail platby" href="/payment/${row.id}" class="text-decoration-none"><i class="ti ti-info-circle"></i></a>`,
                `<a data-bs-toggle="tooltip" data-bs-title="Editovat platbu" href="/payment/edit/${row.id}" class="text-decoration-none"><i class="ti ti-edit"></i></a>`
            ].join('')
        }
    </script>
@endsection
