<!doctype html>
<html lang="{{ app()->getLocale() }}"  class="h-100">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pet Care Admin</title>

        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        @stack('css')
    </head>
    <body class="h-100">
        @yield('content')
        @stack('modals')
        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
        @stack('scripts')
    </body>
</html>
