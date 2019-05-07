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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js">
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

        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li @click="menu=20" class="nav-item">
                        <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
                    </li>
                    <li class="nav-title">
                    <strong>ADMINISTRADOR</strong> 
                    </li>
                    @if(Auth::user()->administracion == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-energy"></i> Administración </a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->departamentos == 1)
                                    <li @click="menu=11" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-object-group"></i> Departamentos</a>
                                    </li>
                                @endif
                                @if(Auth::user()->personas == 1)
                                    <li @click="menu=12" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-vcard"></i> Personas</a>
                                    </li>
                                @endif
                                @if(Auth::user()->empresas == 1)
                                    <li @click="menu=13" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-industry"></i> Empresas</a>
                                    </li>
                                @endif
                                @if(Auth::user()->medios_public == 1)
                                    <li @click="menu=14" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-bullhorn"></i> Medios Publicitarios</a>
                                    </li>
                                @endif
                                @if(Auth::user()->lugares_contacto == 1)
                                    <li @click="menu=15" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-street-view"></i> Lugares de contacto</a>
                                    </li>
                                @endif
                                @if(Auth::user()->servicios == 1)
                                    <li @click="menu=18" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Servicios</a>
                                    </li>
                                @endif
                                @if(Auth::user()->inst_financiamiento == 1)
                                    <li @click="menu=16" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-building-o"></i> Inst. de financ.</a>
                                    </li>
                                @endif
                                @if(Auth::user()->tipos_credito == 1)
                                    <li @click="menu=17" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Tipos de crédito</a>
                                    </li>
                                @endif
                                @if(Auth::user()->asig_servicios == 1)
                                    <li @click="menu=19" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Asignar Servicios</a>
                                    </li>
                                @endif
                                @if(Auth::user()->mis_asesores == 1)
                                    <li @click="menu=73" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-user-circle-o"></i>Mis Asesores</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->desarrollo == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-home"></i> Desarrollo</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->fraccionamiento == 1)
                                    <li @click="menu=1" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Fraccionamiento</a>
                                    </li>
                                @endif
                                @if(Auth::user()->etapas == 1)
                                    <li @click="menu=2" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Etapas</a>
                                    </li>
                                @endif
                                @if(Auth::user()->p_fraccionamiento == 1)
                                    <li @click="menu=110" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Fraccionamiento</a>
                                    </li>
                                @endif
                                @if(Auth::user()->p_etapa == 1)
                                    <li @click="menu=111" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Etapas</a>
                                    </li>
                                @endif
                                @if(Auth::user()->modelos == 1)
                                    <li @click="menu=3" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Modelos</a>
                                    </li>
                                @endif
                                @if(Auth::user()->lotes == 1)
                                    <li @click="menu=4" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Lotes</a>
                                    </li>
                                @endif
                                @if(Auth::user()->asign_modelos == 1)
                                    <li @click="menu=5" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Asignar Modelo</a>
                                    </li>
                                @endif
                                @if(Auth::user()->licencias == 1)
                                    <li @click="menu=6" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Licencias</a>
                                    </li>
                                @endif
                                @if(Auth::user()->acta_terminacion == 1)
                                    <li @click="menu=7" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Acta de terminacion</a>
                                    </li>
                                @endif
                                
                                </ul>
                            </li>
                    @endif
                    @if(Auth::user()->precios == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-money"></i> Precios </a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->precios_etapas == 1)
                                    <li @click="menu=21" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-usd"></i> Precios de etapa</a>
                                    </li>
                                @endif
                                @if(Auth::user()->precios_viviendas == 1)
                                    <li @click="menu=25" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-usd"></i> Precios de vivienda</a>
                                    </li>
                                @endif
                                @if(Auth::user()->sobreprecios == 1)
                                    <li @click="menu=22" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-plus-square-o"></i> Sobreprecios</a>
                                    </li>
                                @endif
                                @if(Auth::user()->paquetes == 1)
                                    <li @click="menu=23" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-shopping-bag"></i> Paquetes</a>
                                    </li>
                                @endif
                                @if(Auth::user()->promociones == 1)
                                    <li @click="menu=24" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-percent"></i> Promociones</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->obra == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-plug"></i> Obra</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->contratistas == 1)
                                    <li @click="menu=50" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-handshake-o"></i> Contratistas</a>
                                    </li>
                                @endif
                                @if(Auth::user()->ini_obra == 1)
                                    <li @click="menu=51 "class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-play-circle"></i> Inicio de obra</a>
                                    </li>
                                @endif
                                @if(Auth::user()->aviso_obra == 1)
                                    <li @click="menu=54 "class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-newspaper-o"></i> Aviso de obra</a>
                                    </li>
                                @endif
                                @if(Auth::user()->partidas == 1)
                                    <li @click="menu=52 "class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-star-half"></i> Partidas</a>
                                    </li>
                                @endif
                                @if(Auth::user()->avance == 1)
                                    <li @click="menu=53 "class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-star-half-o"></i> Avance</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->ventas == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Ventas</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->lotes_disp == 1)
                                    <li @click="menu=59" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-circle-o-notch fa-spin"></i> Casas Disponibles</a>
                                    </li>
                                @endif
                                @if(Auth::user()->mis_prospectos == 1)
                                    <li @click="menu=60" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-group"></i> Mis prospectos</a>
                                    </li>
                                @endif 
                                @if(Auth::user()->simulacion_credito == 1)
                                    <li @click="menu=61" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-calculator"></i> Simulacion de credito</a>
                                    </li>
                                @endif
                                @if(Auth::user()->hist_simulaciones == 1)
                                    <li @click="menu=62" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Hist. de simulaciones</a>
                                    </li>
                                @endif
                                @if(Auth::user()->hist_creditos == 1)
                                    <li @click="menu=63" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Hist. creditos</a>
                                    </li>
                                @endif
                                @if(Auth::user()->contratos == 1)
                                    <li @click="menu=80" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Realizar contrato</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->acceso == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Acceso</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->usuarios == 1)
                                    <li @click="menu=72" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-user"></i> Usuarios</a>
                                    </li>
                                @endif
                                @if(Auth::user()->roles == 1)
                                    <li @click="menu=71" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-user-following"></i> Roles</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->reportes == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->mejora == 1)
                                    <li @click="menu=90" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Estadisticas Mejora</a>
                                    </li>
                                @endif
                                    <li @click="menu=10" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ventas</a>
                                    </li>
                            </ul>
                        </li>
                    @endif
                    <li @click="menu=31" class="nav-item">
                        <a class="nav-link" onclick="window.open('/pdf/manualUsuario.pdf','_blank')"><i class="icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
                    </li>
                    <li @click="menu=32" class="nav-item">
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
        
        <span><a href="http://www.casascumbres.mx/" target="_blank">Grupo constructor cumbres</a> &copy; 2018</span>
        
        <span class="ml-auto">Desarrollado por <a href="http://www.casascumbres.mx/" target="_blank">Casas Cumbres</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>

</body>

</html>