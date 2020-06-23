<!DOCTYPE html>
<html lang="es">

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
                    <li class="nav-title">
                    <strong>Menu</strong> 
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

                                @if(Auth::user()->cuenta == 1)
                                    <li @click="menu=8" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i>Cuentas</a>
                                    </li>
                                @endif

                                @if(Auth::user()->notaria == 1)
                                    <li @click="menu=9" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i>Notarias</a>
                                    </li>
                                @endif

                                @if(Auth::user()->proveedores == 1)
                                    <li @click="menu=211" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-industry"></i>Proveedores</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                <!-- Modulo para proveedor ---->
                    @if(Auth::user()->rol_id == 10 || Auth::user()->rol_id == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-plug"></i> Proveedores</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 10)
                                    <li @click="menu=214" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Seguimiento de instalación</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                     <!-- Modulo para contratista ---->
                     @if(Auth::user()->rol_id == 13 || Auth::user()->rol_id == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-plug"></i> Contratistas</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 13)
                                    <li @click="menu=217" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Solicitudes</a>
                                    </li>
                                    <li @click="menu=223" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Revisión Previa</a>
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
                                    <li @click="menu=112" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Fraccionamiento</a>
                                    </li>
                                @endif
                              
                                @if(Auth::user()->p_etapa == 1)
                                    <li @click="menu=111" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Documentos anexos</a>
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
                                @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 6)
                                    <li @click="menu=230" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Especificaciones de modelo</a>
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
                                @if(Auth::user()->descarga_actas == 1)
                                    <li @click="menu=231" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Prediales y actas</a>
                                    </li>
                                @endif
                                @if(Auth::user()->ruv == 1)
                                    <li @click="menu=234" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Solicitud de RUV</a>
                                    </li>
                                @endif
                                @if(Auth::user()->seg_ruv == 1)
                                    <li @click="menu=235" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-bag"></i> Seguimiento de RUV</a>
                                    </li>
                                @endif
                                
                                </ul>
                            </li>
                    @endif
                    @if(Auth::user()->precios == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-money"></i> Precios </a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->agregar_sobreprecios == 1)
                                    <li @click="menu=26" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-usd"></i> Admn. Sobreprecios </a>
                                    </li>
                                @endif
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
                                @if(Auth::user()->bonos_rec == 1)
                                    <li @click="menu=241" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-percent"></i> Catalogo de bonos</a>
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
                                @if(Auth::user()->avance == 1 || Auth::user()->rol_id == 9)
                                    <li @click="menu=55 "class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-star-half-o"></i> Visita para avaluo</a>
                                    </li>
                                @endif
                                @if(Auth::user()->id == 25816 || Auth::user()->rol_id == 1)
                                    <li @click="menu=224" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Equipamiento</a>
                                    </li>
                                @endif
                                @if(Auth::user()->equipamientos == 1 && Auth::user()->id != 25816)
                                    <li @click="menu=213" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Solic. Equipamiento</a>
                                    </li>
                                @endif

                                @if(Auth::user()->entregas == 1)
                                    <li @click="menu=216" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-home"></i> Viviendas por entregar</a>
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

                                @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 7)
                                    <li @click="menu=228" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-group"></i> Prospectos</a>
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
                                @if(Auth::user()->equipamientos == 1)
                                    <li @click="menu=212" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Solic. Equipamiento</a>
                                    </li>
                                @endif
                                @if(Auth::user()->docs == 1)
                                    <li @click="menu=208" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-credit-card-alt"></i> Docs</a>
                                    </li>
                                @endif
                                
                            </ul>
                        </li>
                    @endif

        <!----------      SALDOS      --->
                    @if(Auth::user()->saldo == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-calculator"></i> Saldos</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->edo_cuenta == 1)
                                    <li @click="menu=206" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-dollar"></i> Estado de cuenta</a>
                                    </li>
                                @endif
                                @if(Auth::user()->depositos == 1)
                                    <li @click="menu=200" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-money"></i> Depositos</a>
                                    </li>
                                @endif
                                @if(Auth::user()->gastos_admn == 1)
                                    <li @click="menu=205" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-money"></i> Gastos administrativos</a>
                                    </li>
                                @endif
                                @if(Auth::user()->cobro_credito == 1)
                                    <li @click="menu=207" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-credit-card-alt"></i> Cobro de crédito</a>
                                    </li>
                                @endif
                                @if(Auth::user()->dev_cancel == 1)
                                    <li @click="menu=209" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-reload"></i> Devolución por cancelación</a>
                                    </li>
                                @endif

                                @if(Auth::user()->dev_exc == 1)
                                    <li @click="menu=210" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-reload"></i> Devolución por credito excedente</a>
                                    </li>
                                @endif
                                @if(Auth::user()->facturas == 1)
                                    <li @click="menu=1001" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-inbox"></i> Facturas</a>
                                    </li> 
                                @endif                                   
                            </ul>
                        </li>
                    @endif
        <!----------     FIN SALDOS      --->

        <!----------      GESTORIA      --->
                    @if(Auth::user()->gestoria == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-bank"></i> Gestoria</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->expediente == 1)
                                    <li @click="menu=201" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-folder-open"></i> Expediente</a>
                                    </li>
                                @endif
                                @if(Auth::user()->asig_gestor == 1)
                                    <li @click="menu=202" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-group"></i> Asignar gestor</a>
                                    </li>
                                @endif
                                @if(Auth::user()->seg_tramite == 1)
                                    <li @click="menu=203" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-bullseye"></i> Seguimiento de tramite</a>
                                    </li>
                                @endif
                                @if(Auth::user()->avaluos == 1)
                                    <li @click="menu=204" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-money"></i> Avaluos</a>
                                    </li>
                                @endif
                                @if(Auth::user()->bonos_rec == 1)
                                    <li @click="menu=242" class="nav-item">
                                        <a class="nav-link" href="#"><i class="fa fa-money"></i> Bonos por recomendación</a>
                                    </li>
                                @endif
                                
                            </ul>
                        </li>
                    @endif
        <!----------     FIN GESTORIA      --->

        <!----------      Postventa      --->
            @if(Auth::user()->postventa == 1)
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-handshake-o"></i> Post Venta</a>
                    <ul class="nav-dropdown-items">
                        @if(Auth::user()->solic_detalles == 1)
                            <li @click="menu=221" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-book"></i> Detalles generales</a>
                            </li>
                            <li @click="menu=222" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-book"></i> Catalogo de Detalles</a>
                            </li>
                        @endif
                            <li @click="menu=218" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-card-o"></i> Cartas de bienvenida</a>
                            </li>
                        @if(Auth::user()->postventa == 1)
                            <li @click="menu=220" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-check-circle-o"></i> Revisión previa</a>
                            </li>
                        @endif
                        @if(Auth::user()->entregas == 1)
                            <li @click="menu=215" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-key"></i> Entregas de vivienda</a>
                            </li>
                        @endif
                        @if(Auth::user()->solic_detalles == 1)
                            <li @click="menu=219" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-wrench"></i> Solicitud de Detalles</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

        <!----------     FIN Postventa      --->

        <!-- Modulo pagos internos --->
            @if(Auth::user()->rol_id != 2)
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-laptop"></i> Pagos Internos</a>
                    <ul class="nav-dropdown-items">
                        <li @click="menu=236" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-plus-square "></i> Generar Solicitud</a>
                        </li>
                        @if(Auth::user()->seg_pago == 1)
                            <li @click="menu=237" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-user-following"></i> Seg de solicitud</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            
        <!-- Fin pago internos -->

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
                    @if(Auth::user()->comisiones == 1)
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Comisiones</a>
                            <ul class="nav-dropdown-items">
                                @if(Auth::user()->exp_comision == 1)
                                    <li @click="menu=226" class="nav-item" >
                                        <a class="nav-link" href="#"><i class="icon-layers"></i> Expediente</a>
                                    </li>
                                @endif
                                @if(Auth::user()->gen_comision == 1)
                                    <li @click="menu=227" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-calculator"></i> Comisiones</a>
                                    </li>
                                @endif
                                @if(Auth::user()->bono_com == 1)
                                    <li @click="menu=229" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-calculator"></i> Bonos</a>
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
                                @if(Auth::user()->rep_proy == 1)
                                    <li @click="menu=91" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Resumen por proyecto</a>
                                    </li>
                                @endif
                                @if(Auth::user()->rep_publi == 1)
                                    <li @click="menu=225" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Estadisticas Publicidad</a>
                                    </li>
                                @endif
                                @if(Auth::user()->inventario == 1)
                                    <li @click="menu=232" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Inventario Contable</a>
                                    </li>
                                @endif
                                @if(Auth::user()->rep_asesores == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310)
                                    <li @click="menu=233" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de asesores</a>
                                    </li>
                                @endif
                                @if(Auth::user()->rep_ini_term_ventas == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310)
                                    <li @click="menu=238" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de inicio, termino, ventas y cobranza</a>
                                    </li>
                                @endif
                                @if(Auth::user()->rol_id == 1 || Auth::user()->rep_venta_canc == 1)
                                    <li @click="menu=239" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de ventas y cancelaciones</a>
                                    </li>
                                @endif
                                @if(Auth::user()->rep_recursos_propios	 == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310 ) 
                                    <li @click="menu=243" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de recursos propios</a>
                                    </li>
                                @endif
                                @if(Auth::user()->rep_recursos_propios	 == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310 ) 
                                <li @click="menu=244" class="nav-item">
                                    <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de casas con crédito puente</a>
                                </li>
                            @endif
                                @if(Auth::user()->rol_id == 1)
                                    <li @click="menu=240" class="nav-item">
                                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte acumulado</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
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
        
        <span><a href="http://www.casascumbres.mx/" target="_blank">Grupo Constructor Cumbres</a> &copy; 2020</span>
        
        <span class="ml-auto">Desarrollado por <a href="http://www.casascumbres.mx/" target="_blank">Grupo Cumbres</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>

</body>

</html>