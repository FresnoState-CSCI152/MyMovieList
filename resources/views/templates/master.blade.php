<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="shortcut icon" href="favicon.ico" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MyMovieList</title>

        <!-- Fonts -->
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <!-- Styles -->
        <style type="text/css">
            .nav-tabs {
                border-bottom: none;
            }
            x-star-rating {
                font-family: 'Ionicons';
                font-size: 30px;
                display: inline-flex;
                cursor: pointer;
            }

            x-star-rating > .star::after {
                content: '\f3ae';
                color: #777;
            }

            x-star-rating > .star.full::after {
                content: '\f2fc';
                color: #fd0;
            }
        </style>

        <!-- JQuery -->
        <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous">
        </script>

        {{-- for ionicon icons --}}
        <script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>
    </head>

    <body>
        @include('templates/topnav')

        <div class="m-4">
            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>