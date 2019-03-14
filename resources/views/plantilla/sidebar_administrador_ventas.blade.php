<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li @click="menu=20" class="nav-item">
                <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
            </li>
            <li class="nav-title">
                Mantenimiento
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-energy"></i> Administración </a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=12" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-vcard"></i> Personas</a>
                    </li>
                    <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-industry"></i> Empresas</a>
                    </li>
                    <li @click="menu=14" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-bullhorn"></i> Medios Publicitarios</a>
                    </li>
                    <li @click="menu=15" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-street-view"></i> Lugares de contacto</a>
                    </li>
                    <li @click="menu=18" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Servicios</a>
                    </li>
                    <li @click="menu=16" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-building-o"></i> Instituciones de financiamiento</a>
                    </li>
                    <li @click="menu=17" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Tipos de crédito</a>
                    </li>
                    <li @click="menu=19" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Asignar Servicios</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-home"></i> Desarrollo</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-bag"></i> Etapas</a>
                    </li>
                    <li @click="menu=5" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-bag"></i> Asignar Modelo</a>
                    </li>
                    <li @click="menu=51 "class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-play-circle"></i> Inicio de obra</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-money"></i> Precios </a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=21" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-usd"></i> Precios de etapa</a>
                    </li>
                    <li @click="menu=22" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-plus-square-o"></i> Sobreprecios</a>
                    </li>
                    <li @click="menu=23" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-shopping-bag"></i> Paquetes</a>
                    </li>
                    <li @click="menu=24" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-percent"></i> Promociones</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Asesores</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=59" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-circle-o-notch fa-spin"></i> Lotes Disponibles</a>
                    </li>
                    <li @click="menu=60" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-group"></i> Mis prospectos</a>
                    </li>
                    <li @click="menu=61" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-calculator"></i> Simulacion de credito</a>
                    </li>
                    <li @click="menu=62" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Hist. de simulaciones</a>
                    </li>
                    <li @click="menu=63" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Hist. creditos</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Acceso</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=72" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-user"></i> Usuarios</a>
                    </li>
                    <li @click="menu=71" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-user-following"></i> Roles</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=9" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ingresos</a>
                    </li>
                    <li @click="menu=10" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ventas</a>
                    </li>
                </ul>
            </li>
            <li @click="menu=31" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
            </li>
            <li @click="menu=32" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-info"></i> Acerca de...<span class="badge badge-info">IT</span></a>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>