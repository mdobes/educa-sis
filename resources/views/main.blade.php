<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Educa SIS | @yield("title")</title>
    <link href="{{asset("assets/css/scss/bootstrap.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("assets/css/tabler-icons.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/select2.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/select2-bootstrap-5-theme.min.css")}}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary mt-2 mb-2 shadow">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{asset("assets/images/logo.svg")}}" alt="Educa logo" height="45">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{request()->is('/') ? 'active' : ''}}" href="{{route("index")}}">Domů</a>
                </li>
                @if(Auth::user()->permission == "admin" || Auth::user()->permission == "teacher")
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ ( request()->is('payment*') || request()->is('payment')) ? 'active' : ''}}" href="#" role="button" data-bs-toggle="dropdown" data-bs- aria-expanded="false">
                        Správa plateb
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{route("payment.show")}}">Mnou vytvořené platby</a></li>
                        <li><a class="dropdown-item" href="{{route("payment.create")}}">Vytvořit platbu</a></li>
                        <li><a class="dropdown-item" href="{{route("payment.banklog")}}">Výpis BÚ</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{(request()->is('user') || request()->is('user/*') && !request()->is('usergroup')) ? 'active' : ''}}" href="{{route("users.index")}}">Správa uživatelů</a>
                </li>
                    @if(Auth::user()->permission == "admin")
                        <li class="nav-item">
                            <a class="nav-link {{(request()->is('usergroup*') && !request()->is('user/*')) ? 'active' : ''}}" href="{{route("usergroup.index")}}">Správa skupin</a>
                        </li>
                    @endif
            </ul>
            <ul class="navbar-nav d-flex">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs- aria-expanded="false">
                        <i class="ti ti-user-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/logout">Odhlásit se</a></li>
                    </ul>
                </li>
            </ul>
            @endif
        </div>
    </div>
</nav>

@if(isset($message))
    <div class="alert alert-danger" role="alert">
        {{$message}}
    </div>
@endif

<div class="container mt-5 mb-5">

    <div class="header mb-3">
        <h1><b>@yield("title")</b></h1>
    </div>

    <div class="actions mb-3">
        @yield("actions")
    </div>

    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

    @yield("content")
</div>

<div class="container">
    <div class="mt-5 mb-4 opacity-50">
        <div class="row text-center">
            <div class="col">
                <a href="https://dbes.cz" target="_blank" class="text-decoration-none"> <img src="{{asset("assets/images/dobes.svg")}}" class="logo-main" alt="Michal Dobeš favicon"></a>
                <br>
                <a href="https://dbes.cz" target="_blank" class="text-black text-decoration-none">© 2022-{{\Carbon\Carbon::now()->format("Y")}} Michal Dobeš</a><br>
                <span class="">{{config("app.name")}} v{{config('app.version')}}</span>
                <a class="text-black" href="https://github.com/mdobes/educa-sis" target="_blank">
                    <i class="ti ti-brand-github"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<script src="{{asset("assets/js/jquery-3.6.3.min.js")}}"></script>
<script src="{{asset("assets/js/bootstrap.bundle.min.js")}}"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table-locale-all.min.js"></script>
<script src="{{asset("assets/js/select2.full.min.js")}}"></script>
<script src="{{asset("assets/js/i18n/cs.js")}}"></script>

<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    $( "[data-select2-enable]" ).select2( {
        theme: 'bootstrap-5'
    } );
</script>
@yield("scripts")
</body>
</html>
