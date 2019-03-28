<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li @click="menu=20" class="nav-item">
                <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
            </li>
            <li class="nav-title">
            <strong>PUBLICIDAD</strong> 
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-energy"></i> Administración </a>
                <ul class="nav-dropdown-items">
                    
                    <li @click="menu=14" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-bullhorn"></i> Medios Publicitarios</a>
                    </li>
                    <li @click="menu=15" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-street-view"></i> Lugares de contacto</a>
                    </li>
                    <li @click="menu=18" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Servicios</a>
                    </li>
                    <li @click="menu=19" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Asignar Servicios</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-home"></i> Desarrollo</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=110" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-bag"></i> Fraccionamiento</a>
                    </li>
                    <li @click="menu=111" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-bag"></i> Etapas</a>
                    </li>
                    <li @click="menu=112" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-bag"></i> Modelos</a>
                    
                </ul>
            </li>
           
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Estadisticas</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=90" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-chart"></i> Estadisticas Mejora</a>
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