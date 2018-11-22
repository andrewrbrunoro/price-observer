<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{!! csrf_token() !!}">

    <title>
        Price Observer - Andrew R Brunoro
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{!! mix('css/app.css') !!}">

    <style>
        html {
            height: 100%;
        }
        body {
            background-image: linear-gradient(to right top, #5e0836, #6d004c, #74006b, #6e0090, #4a07bc);
            height: 100%;
            margin: 0;
        }
        .jumbotron {
            background-color: transparent;
        }
        .text-jumbotron {
            color: #ffcdcd;
        }
        .text-jumbotron-muted {
            color: #def0ff !important;
        }
        label {
            color: #FFF;
            float: left;
        }
    </style>
</head>
<body>

<div id="app">
    @yield('content')
</div>

<script src="{!! mix('js/app.js') !!}"></script>
@yield('script')
</body>
</html>
