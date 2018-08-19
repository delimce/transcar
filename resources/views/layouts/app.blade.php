<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transcar - @yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{ url('css/app.css') }}">
    <script type="text/javascript" src="{{ url('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/vendor.js') }}"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Transcar</a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li><span class="glyphicon glyphicon-user"></span>{{session()->get("myUser")->nombre}}</li>
            <li>
                <a href="{!! url('account') !!}"><span class="glyphicon glyphicon-log-in"></span> Editar datos</a>
                &nbsp;|&nbsp;
                <a href="{!! url('logout') !!}"><span class="glyphicon glyphicon-log-in"></span> Cerrar</a>
            </li>
        </ul>
    </div>
</nav>
<!-- Content here -->
<div id="wrapper">
    <!-- Sidebar -->
@component("components.menu",['person' => session()->get("myUser")])
@endcomponent
<!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
    <!-- /#page-content-wrapper -->

</div>


<!-- /#wrapper -->
<!-- Menu Toggle Script -->
<script type="text/javascript" src="{{ url('js/modules.js') }}"></script>
<script>
    $(function () {  @stack('scripts-ready') });
</script>
</body>
</html>