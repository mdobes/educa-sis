@extends("main")

@section("title", "Vytvoření platby")

@section("content")
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ __($error) }}</li>
        @endforeach
    </ul>

    {!! Form::open(["url" => url("payment"), "method" => "post", "id" => "add-form"]) !!}
        @include("payments.form")
    {!! Form::close() !!}
@endsection
