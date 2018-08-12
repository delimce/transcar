@extends('layouts.basic')
@section('title', 'Login')

@section('content')
    <div class="card login-box col-lg-4 col-centered">
        <form id="main_form" class="form-signin">
            <h1 class="h3 mb-3 font-weight-normal">Acceso al sistema</h1>
            <div class="form-group">
                <label for="inputUser" class="sr-only">Usuario</label>
                <input type="text" id="inputUser" class="form-control" placeholder="Usuario" required autofocus>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
            </div>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Recordarme
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
        </form>
    </div>
@endsection