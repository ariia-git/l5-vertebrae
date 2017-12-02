<!DOCTYPE html>
<html dir="ltr" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('favicon.ico') }}" type="image/x-icon" rel="icon">

    <title>@hasSection('title')@yield('title') | @endif{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('assets/styles/style.css') }}" type="text/css" rel="stylesheet">

    @yield('styles')
</head>
<body>
@include('layouts.partials.top-navigation')

<div class="container">

    @include('layouts.partials.breadcrumbs')

    @include('layouts.partials.alerts')

    @yield('content')

    @include('layouts.partials.footer')

</div>

<!-- Scripts -->
<script src="{{ asset('assets/scripts/example.js') }}" type="text/javascript"></script>

@include('layouts.partials.toasts')

@yield('scripts')
</body>
</html>
