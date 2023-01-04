<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Educa SIS | @yield("title")</title>
    <link href="{{asset("assets/css/bootstrap.min.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("assets/css/tabler-icons.min.css")}}">
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
                    <a class="nav-link {{request()->is('/') ? 'active' : ''}}" href="/">Domů</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{request()->is('payment*') ? 'active' : ''}}" href="/payment">Správa plateb</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    @yield("content")
</div>

<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <div class="col-md-4 d-flex align-items-center">
            <a href="https://dbes.cz" target="_blank" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
                <img src="{{asset("assets/images/dobes.svg")}}" alt="Michal Dobeš favicon" height="24">
            </a>
            <span class="mb-3 mb-md-0 text-muted me-4"><a href="https://dbes.cz" target="_blank" class="text-muted text-decoration-none">© 2022 Michal Dobeš</a></span> <span class="text-muted">({{config("app.name")}} v{{config('app.version')}})</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <li class="ms-3">
                <a class="text-muted" href="https://github.com/mdobes/educa-sis" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-git" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="16" cy="12" r="1" />
                        <circle cx="12" cy="8" r="1" />
                        <circle cx="12" cy="16" r="1" />
                        <path d="M12 15v-6" />
                        <path d="M15 11l-2 -2" />
                        <path d="M11 7l-1.9 -1.9" />
                        <path d="M10.5 20.4l-6.9 -6.9c-.781 -.781 -.781 -2.219 0 -3l6.9 -6.9c.781 -.781 2.219 -.781 3 0l6.9 6.9c.781 .781 .781 2.219 0 3l-6.9 6.9c-.781 .781 -2.219 .781 -3 0z" />
                    </svg>
                </a>
            </li>
        </ul>
    </footer>
</div>

<script src="{{asset("assets/js/bootstrap.min.js")}}"></script>
</body>
</html>
