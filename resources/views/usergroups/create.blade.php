@extends("main")

@section("title", "Vytvoření skupiny")

@section("actions")
    <a href="{{url()->route("usergroup.index")}}"><i class="ti ti-arrow-back"></i> Zpět na seznam</a>
@endsection

@section("content")
    {!! Form::open(["url" => route("usergroup.store"), "method" => "post", "id" => "edit-form"]) !!}
    <div class="mb-3">
        <div class="mb-3">
            {!! Form::label("text", "Název skupiny", ["class" => "form-label"]) !!}
            {!! Form::text("name", request()->post("name") ?? null, ["class" => "form-control", "required" => true]) !!}
        </div>
        <div class="mb-3">
            {!! Form::label("text", "Uživatelé skupiny", ["class" => "form-label"]) !!}
            {!! Form::textarea("users", request()->post("users") ?? null, ["class" => "form-control", "required" => true]) !!}
        </div>
        <div class="mb-3">
            {!! Form::button("Vytvořit skupinu", ["type" => "submit", "class" => "btn btn-primary"]) !!}
        </div>

    </div>
    {!! Form::close() !!}
@endsection
