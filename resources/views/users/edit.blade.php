@extends("main")

@section("title", "Editace uživatele")

@section("content")
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

    {!! Form::open(["url" => route("users.update"), "method" => "patch", "id" => "edit-form"]) !!}
    {!! Form::hidden("id", $id->id)!!}
    <div class="mb-3">
        <div class="mb-3">
            {!! Form::label("text", "Zobrazované jméno", ["class" => "form-label"]) !!}
            {!! Form::text("name", $id->name ?? null, ["placeholder" => "Aleš Medek", "class" => "form-control", "required" => true, "disabled" => true]) !!}
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="true" name="noPassword" id="noPassword">
                <label class="form-check-label" for="noPassword">
                    Při dalším příhlášení vyžadovat vytvoření nového hesla
                </label>
            </div>
        </div>
        <div class="mb-3">
            {!! Form::button("Upravit uživatele", ["type" => "submit", "class" => "btn btn-primary"]) !!}
        </div>

    </div>
    {!! Form::close() !!}
@endsection