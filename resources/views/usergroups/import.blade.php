@extends("main")

@section("title", "Import skupin")

@section("content")
    <table class="table" data-locale="cs-CZ" data-toggle="table" data-ajax="ajaxRequest" data-search="true" data-side-pagination="server"  data-pagination="false">
        <thead>
        <tr>
            <th scope="col" data-field="displayName">Název skupiny</th>
            <th scope="col" data-field="mail">E-mail skupiny</th>
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
            let url = "{{url()->route("usergroup.microsoft.search")}}";
            $.get(url + '?' + $.param(params.data)).then(function (res) {
                params.success(res.value)
            })
        }

        function operateFormatter(value, row, index) {
            return [
                `<a data-bs-toggle="tooltip" data-bs-title="Zahájit import skupin" href="/usergroup/import/${row.id}" class="text-decoration-none"><i class="ti ti-database-import"></i></a>`,
            ].join('')
        }
    </script>
@endsection
