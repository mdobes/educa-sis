@extends("main")

@section("title", "Editace skupiny")

@section("content")
    {!! Form::open(["url" => route("usergroup.update"), "method" => "patch", "id" => "edit-form"]) !!}
    {!! Form::hidden("id", $id->id)!!}
    <div class="mb-3">
        <div class="mb-3">
            {!! Form::label("text", "Název skupiny", ["class" => "form-label"]) !!}
            {!! Form::text("name", $id->name ?? null, ["class" => "form-control", "required" => true, "disabled" => true]) !!}
        </div>
        <div class="mb-3">
            {!! Form::label("text", "Uživatelé skupiny", ["class" => "form-label"]) !!}
            {!! Form::textarea("users", $id->users ?? null, ["class" => "form-control", "required" => true]) !!}
        </div>
        <div class="mb-3">
            {!! Form::button("Upravit skupinu", ["type" => "submit", "class" => "btn btn-primary"]) !!}
        </div>

    </div>
    {!! Form::close() !!}
@endsection
