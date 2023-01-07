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
