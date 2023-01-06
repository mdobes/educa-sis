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
        <a href="https://dbes.cz" target="_blank" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
            <img src="{{asset("assets/images/dobes.svg")}}" alt="Michal Dobeš favicon" height="24">
        </a><br>
        <span class="mb-3 mb-md-0 text-muted"><a href="https://dbes.cz" target="_blank" class="text-muted text-decoration-none">© 2022 Michal Dobeš</a></span><br><span class="text-muted">({{config("app.name")}} v{{config('app.version')}})</span>
    </div>

    </form>
</main>
</body>
