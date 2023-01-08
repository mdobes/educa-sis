@extends("main")

@section("title", "Vytvoření platby")

@section("actions")
    <a href="{{ url()->previous() }}"><i class="ti ti-arrow-back"></i> Zpět na seznam</a>
@endsection

@section("content")
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{trans($error)}}</li>
        @endforeach
    </ul>

    {!! Form::open(["url" => url("payment"), "method" => "post", "id" => "add-form"]) !!}
        @include("payments.form")
    {!! Form::close() !!}
@endsection

@section("scripts")
    <script>
        $('#payer').select2({
            theme: 'bootstrap-5',
            ajax: {
                url: "{{route("payment.searchpayers")}}",
                data: function (params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.users, function (obj) {
                            obj.id = "user:" + obj.typeId;
                            return obj;
                        }).concat(
                            $.map(data.groups, function (obj) {
                                obj.id = "group:" + obj.typeId;
                                return obj;
                            })
                        )
                    };
                }
            }
        });

    </script>
@endsection
