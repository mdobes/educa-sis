<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Educa SIS | Login</title>
    <link href="{{asset("assets/css/scss/bootstrap.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("assets/css/tabler-icons.min.css")}}">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body class="text-center">
<main class="form-signin w-100 m-auto">
    {!! Form::open(["url" => url("login"), "method" => "post", "id" => "login"]) !!}
        <img class="mb-5" src="{{asset("assets/images/logo.svg")}}" alt="Educa Logo" height="80">

        @if(!empty($errors->all()))
        <div class="alert alert-secondary" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h1 class="h3 mb-3 fw-normal">Přihlášení</h1>

        <div class="form-floating">
            {!! Form::text("username", old("mail"), ["class" => "form-control", "required" => true, "id" => "username", "placeholder" => "Username"]) !!}
            {!! Form::label("username", "Uživatelské jméno") !!}
        </div>
        <div class="form-floating">
            {!! Form::password("password", ["class" => "form-control", "required" => true, "id" => "mail", "placeholder" => "Password"]) !!}
            {!! Form::label("password", "Heslo") !!}
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Přihlásit se</button>
    <div class="mt-5">
        <span class="mb-3 mb-md-0 text-muted"><a href="https://dbes.cz" target="_blank" class="text-muted text-decoration-none"> <img src="{{asset("assets/images/dobes.svg")}}" alt="Michal Dobeš favicon" height="24"> © 2022 Michal Dobeš</a></span>
        <hr>
        <span class="text-muted">{{config("app.name")}} v{{config('app.version')}}</span>
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
    </div>

    </form>
</main>
</body>
