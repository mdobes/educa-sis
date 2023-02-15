@extends("main")

@section("title", $title)

@section("actions")
    @can("payments.showAll")
        <div class="btn-group" role="group">
            <a href="{{url()->route("payment.show")}}" class="btn {{(request()->is("payment") || request()->is("payment/my")) ? 'btn-primary' : 'btn-outline-primary'}}">Mnou vytvořené</a>
            <a href="{{url()->route("payment.show.all")}}" class="btn {{request()->is("payment/all") ? 'btn-primary' : 'btn-outline-primary'}}">Všechny vytvořené</a>
        </div>
        <div class="d-block mb-2"></div>
    @endcan
    @can("payments.create")
    <a href="{{route("payment.create")}}"><i class="ti ti-plus"></i> Vytvořit novou platbu</a>
    @endcan
@endsection

@section("content")
    <table class="table" data-locale="cs-CZ" data-toggle="table" data-ajax="ajaxRequest" data-search="true"  data-side-pagination="server"  data-pagination="true">
        <thead>
        <tr>
            <th scope="col" data-field="name">Název skupiny</th>
            @if(true)
                <th scope="col" data-field="authorFormatted">Autor</th>
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

@endsection
@section("scripts")
    <script>
        function ajaxRequest(params) {
            let url = "{{url()->route("payment.search")}}";
            params.data.showType = "{{$type}}";
            $.get(url + '?' + $.param(params.data)).then(function (res) {
                params.success(res)
            })
        }

        function operateFormatter(value, row, index) {
            return [
                `<a data-bs-toggle="tooltip" data-bs-title="Zobrazit detail skupiny" href="/payment/group/${row.id}" class="text-decoration-none"><i class="ti ti-info-circle"></i></a>`,
            ].join('')
        }
    </script>
@endsection
