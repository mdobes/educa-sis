@extends("main")

@section("title", "Správa skupin uživatelů")

@section("actions")
    <a href="{{route("usergroup.create")}}"><i class="ti ti-plus"></i> Vytvořit novou skupinu</a>
@endsection

@section("content")
    <table class="table" data-locale="cs-CZ" data-toggle="table" data-ajax="ajaxRequest" data-search="true"  data-side-pagination="server"  data-pagination="true">
        <thead>
        <tr>
            <th scope="col" data-field="name">Název skupiny</th>
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
            let url = "{{url()->route("usergroup.search")}}";
            $.get(url + '?' + $.param(params.data)).then(function (res) {
                params.success(res)
            })
        }

        function operateFormatter(value, row, index) {
            return [
                `<a data-bs-toggle="tooltip" data-bs-title="Upravit uživatele" href="/usergroup/${row.id}" class="text-decoration-none"><i class="ti ti-edit"></i></a>`,
            ].join('')
        }
    </script>
@endsection
