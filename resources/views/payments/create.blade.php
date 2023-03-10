@extends("main")

@section("title", "Vytvoření platby")

@section("actions")
    <a href="{{ url()->previous() }}"><i class="ti ti-arrow-back"></i> Zpět na seznam</a>
@endsection

@section("content")
    {!! Form::open(["url" => url("payment"), "method" => "post", "id" => "add-form"]) !!}
    <div class="mb-3">
        <div class="mb-3">
            {!! Form::label("text", "Popis platby", ["class" => "form-label"]) !!}
            {!! Form::text("title", $data->title ?? null, ["class" => "form-control", "required" => true]) !!}
        </div>
        <div class="mb-3">
            {!! Form::label("text", "Částka", ["class" => "form-label"]) !!}
            {!! Form::number("amount", $data->amount ?? null, ["class" => "form-control", "required" => true]) !!}
        </div>
        <div class="mb-3">
            {!! Form::label("text", "Plátce", ["class" => "form-label"]) !!}
            {!! Form::select("payer[]", [], null, ["id" => "payer", "class" => "form-control", "required" => true, "multiple" => true, "data-select2-enable" => true]) !!}
        </div>
        @if(Auth::user()->permission == "admin")
        <div class="mb-3">
            {!! Form::label("text", "Typ", ["class" => "form-label"]) !!}
            {!! Form::select("type", ["normal" => "Klasická", "adobe" => "Automatické přiřazení Adobe CC účtu"], null, ["class" => "form-select", "required" => true]) !!}
        </div>
        @endif
    <div class="mb-3">
        {!! Form::label("text", "Splatnost", ["class" => "form-label"]) !!}
        {!! Form::input("date", "due", $data->due ?? null, ["class" => "form-control", "required" => true]) !!}
    </div>
    <div class="mb-3">
        {!! Form::button($formButtonTitle, ["type" => "submit", "class" => "btn btn-primary"]) !!}
    </div>

</div>
{!! Form::close() !!}
@endsection

@section("scripts")
<script>
    $('#payer').select2({
        theme: 'bootstrap-5',
        language: "cs",
        minimumInputLength: 3,
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
