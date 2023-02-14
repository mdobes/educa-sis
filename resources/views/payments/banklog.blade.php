@extends("main")

@section("title", "Výpís z BÚ  " . env("BANK_ACC_NUMBER"))

@section("content")
    <table class="table" data-locale="cs-CZ" data-toggle="table" data-ajax="ajaxRequest" data-search="true"  data-side-pagination="server"  data-pagination="true">
        <thead>
        <tr>
            <th scope="col" data-field="payer_account_number">Číslo BÚ</th>
            <th scope="col" data-field="payer_account_name">Název BÚ</th>
            <th scope="col" data-formatter="amountFormatter">Částka</th>
            <th scope="col" data-field="variable_symbol">VS</th>
            <th scope="col" data-field="specific_symbol">SS</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@endsection
@section("scripts")
    <script>
        function ajaxRequest(params) {
            let url = "{{url()->route("payment.banklog.search")}}";
            $.get(url + '?' + $.param(params.data)).then(function (res) {
                params.success(res)
            })
        }

        function amountFormatter(value, row, index) {
            return [
                `${row.amount} ${row.currency}`,
            ].join('')
        }
    </script>
@endsection
