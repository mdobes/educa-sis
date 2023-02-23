@extends("main")

@section("nfTitle", "404 Forbidden")

@section("content")

    <div class="container-fluid py-5">
        <div class="h-100 p-5 text-bg-dark rounded-3">
            <h1 class="display-5 fw-bold">{{ app()->view->getSections()['nfTitle'] }}</h1>
            <p class="col-md-8 fs-4">K požadované akci nemáte dostatečná oprávnění.</p>
            <a class="btn btn-primary btn-lg" href="{{ url()->previous() }}"><i class="ti ti-arrow-back"></i> Zpět</a>
        </div>
    </div>

@endsection
