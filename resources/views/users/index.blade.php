@extends("main")

@section("title", "Správa uživatelů")

@section("content")
    <table class="table" data-classes="table-striped" data-locale="cs-CZ" data-toggle="table" data-ajax="ajaxRequest" data-search="true"  data-side-pagination="server"  data-pagination="true">
        <thead>
        <tr>
            <th scope="col" data-field="name">Zobrazované jméno</th>
            <th scope="col" data-field="email">E-mail</th>
            <th scope="col" data-field="displayPermission">Oprávnění</th>
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
            let url = "{{url()->route("users.search")}}";
            $.get(url + '?' + $.param(params.data)).then(function (res) {
                params.success(res)
            })
        }

        function operateFormatter(value, row, index) {
            return [
                `<a data-bs-toggle="tooltip" data-bs-title="Upravit uživatele" href="/user/${row.id}" class="text-decoration-none"><i class="ti ti-edit"></i></a>`,
            ].join('')
        }
    </script>
@endsection
