<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema cumbres">
    <meta name="author" content="casascumbres.mx">
    <meta name="keyword" content="Sistema de administracion cumbres">
    <link rel="shortcut icon" href="img/favicon.png">
    <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">
    <title>Sistema Cumbres</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">

</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Escritorio</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Configuraciones</a>
            </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
        <notification rol-id="{{Auth::user()->rol_id}}" :notifications="notifications"></notification>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="img/avatars/{{Auth::user()->foto_user}}" class="img-avatar">
                    <span class="d-md-down-none">{{Auth::user()->usuario}} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div>
                    <a class="dropdown-item" @click="menu=100" href="#"><i class="fa fa-user"></i> Perfil</a>
                    <a class="dropdown-item" href="https://calendar.google.com" target="_blank"><i class="fa fa-calendar-check-o"></i> Agenda</a>
                    <a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i> Cerrar sesión</a>

                    <form  id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                    {{csrf_field()}}
                    
                    </form>
                </div>
            </li>
        </ul>
    </header>

    <div class="app-body">

        @if(Auth::check())

            @if(Auth::user()->rol_id == 1)
                @include('plantilla.sidebar_administrador')
            @elseif(Auth::user()->rol_id == 2)
                @include('plantilla.sidebar_asesor')
            @elseif(Auth::user()->rol_id == 3)
                @include('plantilla.sidebar_gerente_proyectos')
            @elseif(Auth::user()->rol_id == 4)
                @include('plantilla.sidebar_gerente_ventas')
            @elseif(Auth::user()->rol_id == 5)
                @include('plantilla.sidebar_gerente_obra')
            @elseif(Auth::user()->rol_id == 6)
                @include('plantilla.sidebar_administrador_ventas')

            @endif
        @endif


        <!-- Contenido Principal -->
        @yield('contenido')
        <!-- /Fin del contenido principal -->
    </div>
    </div>


    
         <!-- /Div para iconos de redes sociales-->
         <div class="app-footer">
        <a class="btn" href="https://www.facebook.com/CasasCumbresSLP/">
            <button type="button" class="btn btn-brand btn-sm btn-facebook" >
                <i class="fa fa-facebook"></i>
            </button>
        </a>
    </div>

   

    <footer class="app-footer">
        
        <span><a href="http://www.casascumbres.mx/">Grupo constructor cumbres</a> &copy; 2018</span>
        
        <span class="ml-auto">Desarrollado por <a href="http://www.casascumbres.mx/">Casas Cumbres</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>

</body>

</html>