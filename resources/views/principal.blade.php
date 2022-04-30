<!DOCTYPE html>
<html lang="es" class="dark">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Cumbres">
    <meta name="author" content="Grupo Constructor Cumbres">
    <meta name="keyword" content="Sistema de administracion Cumbres">
    <link rel="shortcut icon" href="img/favicon.png">
    <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">
    <title>Sistema Cumbres</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"> --}}

    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
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
            <!-- <li class="nav-item px-3">
                <a class="nav-link" href="#">Escritorio</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Configuraciones</a>
            </li> -->
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

            <div class="sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav">
                        <li @click="menu=20" class="nav-item">
                            <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
                        </li>
                        @if(Auth::user()->calendario == 1)
                            <li @click="menu=0" class="nav-item">
                                <a class="nav-link" @click="menu=0" href="#"><i class="fa fa-calendar"></i> Calendario de actividades</a>
                            </li>
                        @endif
                        @if(Auth::user()->notifications == 1)
                            <li @click="menu=264" class="nav-item">
                                <a class="nav-link" @click="menu=264" href="#"><i class="fa fa-commenting"></i> Notificaciones</a>
                            </li>
                        @endif
                        <li class="nav-title">
                        <strong>Menu</strong> 
                        </li>
                <!-- Administración -->
                        @if(Auth::user()->administracion == 1)
                            @include('sidebars.administracion')
                        @endif

                <!-- RH -->
                        @if(Auth::user()->mant_vehiculos == 1 || Auth::user()->admin_mant_vehiculos == 1)
                            @include('sidebars.rh')
                        @endif

                <!-- Oficina -->
                        @if(Auth::user()->inventarios == 1 || Auth::user()->prov_inventarios == 1)
                            @include('sidebars.oficina')
                        @endif
                        
                <!-- Modulo para proveedor ---->
                        @if(Auth::user()->rol_id == 10 || Auth::user()->rol_id == 1 || Auth::user()->usuario == 'david.hh')
                            @include('sidebars.proveedor')
                        @endif

                <!-- Modulo Desarrollo  ---->
                        @if(Auth::user()->desarrollo == 1)
                            @include('sidebars.desarrollo')
                        @endif

                <!-- Modulo Créditos Puente ---->
                        @if(Auth::user()->bases == 1 ||Auth::user()->solic_credito_puente == 1 || Auth::user()->seg_cp == 1 || Auth::user()->edo_cta_bancrea == 1)
                            @include('sidebars.creditopuente')
                        @endif
                <!-- Modulo Precios -->
                        @if(Auth::user()->precios == 1)
                            @include('sidebars.precios')
                        @endif

                <!-- Modulos para Obra -->
                        @if(Auth::user()->obra == 1)
                            @include('sidebars.obra')
                        @endif

                <!-- Modulo para Ventas-->
                        @if(Auth::user()->ventas == 1)
                            @include('sidebars.ventas')
                        @endif

                <!-- Modulo para Rentas-->
                        @if(Auth::user()->admin_rentas == 1)
                            @include('sidebars.rentas')
                        @endif

                <!----------      SALDOS      --->
                        @if(Auth::user()->saldo == 1)
                            @include('sidebars.saldos')
                        @endif

                <!----------      GESTORIA      --->
                        @if(Auth::user()->gestoria == 1)
                            @include('sidebars.gestoria')
                        @endif

                <!----------      Postventa      --->
                        @if(Auth::user()->postventa == 1)
                            @include('sidebars.postventa')
                        @endif

                <!-- Modulo pagos internos --->
                        @if(Auth::user()->rol_id != 2 && Auth::user()->rol_id != 10 && Auth::user()->rol_id != 13)
                            @include('sidebars.pagosinternos')
                        @endif
                <!-- Fin pago internos -->
                <!-- Modulo Accesos ---->
                        @if(Auth::user()->acceso == 1)
                            @include('sidebars.acceso')
                        @endif
                <!-- Modulo Comisiones ---->   
                        @if(Auth::user()->comisiones == 1)
                            @include('sidebars.comisiones')
                        @endif
                <!-- Modulo Reportes ---->
                        @if(Auth::user()->reportes == 1)
                            @include('sidebars.reportes')
                        @endif
                <!-- Modulo Cotizador de lotes ---->
                        @if(Auth::user()->opc_cotizador == 1 || Auth::user()->calc_lotes ==1 || Auth::user()->edit_cotizacion ==1)
                            @include('sidebars.cotizador')
                        @endif

                <!-- Manual para el administrador -->
                        @if(Auth::user()->rol_id == 1)
                            <li @click="menu=31" class="nav-item">
                                <a class="nav-link" onclick="window.open('/pdf/manualUsuarioAdm.pdf','_blank')"><i class="icon-book-open"></i> Manual de usuario <span class="badge badge-danger">PDF</span></a>
                            </li>
                        @endif
                <!-- Manual para los asesores -->
                        @if(Auth::user()->rol_id == 2)
                            <li @click="menu=31" class="nav-item">
                                <a class="nav-link" onclick="window.open('/pdf/manualUsuarioAsesor.pdf','_blank')"><i class="icon-book-open"></i> Manual de usuario <span class="badge badge-danger">PDF</span></a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-info"></i> Acerca de...<span class="badge badge-info">IT</span></a>
                        </li>
                    </ul>
                </nav>
                <button class="sidebar-minimizer brand-minimizer" type="button"></button>
            </div>

        @endif


        <!-- Contenido Principal -->
        @yield('contenido')
        <!-- /Fin del contenido principal -->
    </div>
    </div>

 
    
         <!-- /Div para iconos de redes sociales
         <div class="app-footer">
        <a class="btn" href="https://www.facebook.com/CasasCumbresSLP/">
            <button type="button" class="btn btn-brand btn-sm btn-facebook" >
                <i class="fa fa-facebook"></i>
            </button>
        </a>
    </div>-->

   

    <footer class="app-footer">
        
        <span><a href="http://www.casascumbres.mx/" target="_blank">Grupo Constructor Cumbres</a> &copy; 2022</span>
        
        <span class="ml-auto">Desarrollado por <a href="http://www.casascumbres.mx/" target="_blank">Grupo Cumbres</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>

</body>

</html>

