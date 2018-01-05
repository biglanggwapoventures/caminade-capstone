<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pet Care Admin</title>

        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        @stack('css')
    </head>
    <body style="height:100%">
        @yield('content')
        @stack('modals')
        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('body').on('click', '.trash-row', function () {
                    if(!confirm('Are you sure you want to delete this entry? This action cannot be undone!')) return;
                    $(this).closest('form').submit();
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>