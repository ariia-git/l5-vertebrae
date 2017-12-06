<!DOCTYPE html>
<html lang="{{ \Localization::getCurrentLocaleRegional() }}" dir="{{ \Localization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('favicon.ico') }}" type="image/x-icon" rel="icon">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Architects+Daughter|Ubuntu|Ubuntu+Mono" type="text/css" rel="stylesheet">

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #aeaeae;
            font-family: 'Ubuntu', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            color: #f4645f;
            font-size: 84px;
        }

        .subtitle {
            font-size: 20px;
        }

        .links > a {
            color: #525252;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            transition: .2s ease-out;
        }

        .links > a:hover {
            color: #f4645f;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title">
            {{ config('app.name') }}
        </div>

        <div class="subtitle m-b-md">
            Built with Laravel {{ \App::version() }}
        </div>

        <div class="links">
            <a href="https://github.com/nlmenke/l55-vertebrae">GitHub</a>
            <a href="https://laravel.com/docs">Laradocs</a>
        </div>
    </div>
</div>
</body>
</html>
