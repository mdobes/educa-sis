@extends("main")

@section("title", "Vytvoření platby")

@section("content")
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

    {!! Form::open(["url" => url("payments"), "method" => "post", "id" => "add-form"]) !!}
        @include("payments.form")
    {!! Form::close() !!}
@endsection
