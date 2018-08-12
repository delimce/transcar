<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transcar - @yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{ url('css/app.css') }}">
</head>
<body>
<div class="container">
    @yield('content')
</div>
<!-- Footer -->
<footer class="page-footer font-small cyan darken-3">
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">
        <span>Desarrollado por:</span>
        <a href="http://www.develemento.com.ve">
            <div class="logo-dev">&nbsp;</div>
        </a>
    </div>
    <!-- Copyright -->
</footer>
<script type="text/javascript" src="{{ url('js/app.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules.js') }}"></script>
</body>
</html>