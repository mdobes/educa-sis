@extends("main")

@section("title", "Import skupiny: "  . $name)

@section("content")
    {!! Form::open(["url" => route("usergroup.store"), "method" => "post", "id" => "edit-form"]) !!}
    <div class="mb-3">
        <div class="mb-3">
            {!! Form::label("text", "Název skupiny", ["class" => "form-label"]) !!}
            {!! Form::text("name", request()->post("name") ?? $name ?? null, ["class" => "form-control", "required" => true]) !!}
        </div>
        <div class="mb-3">
            {!! Form::label("text", "Uživatelé skupiny", ["class" => "form-label"]) !!}
            {!! Form::textarea("users", request()->post("users") ?? $users ?? null, ["class" => "form-control", "required" => true]) !!}
        </div>
        <div class="mb-3">
            {!! Form::button("Vytvořit skupinu", ["type" => "submit", "class" => "btn btn-primary"]) !!}
        </div>
    </div>
    {!! Form::close() !!}
@endsection
