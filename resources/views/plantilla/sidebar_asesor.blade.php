<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li @click="menu=20" class="nav-item">
                <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
            </li>
            <li class="nav-title">
            <strong>ASESOR VENTAS</strong> 
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-energy"></i> Administración </a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-industry"></i> Empresas</a>
                    </li>
                    <li @click="menu=14" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-bullhorn"></i> Medios Publicitarios</a>
                    </li>
                </ul>
            </li>
          
            
            
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Ventas</a>
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
                        <a class="nav-link" href="#"><i class="fa fa-archive"></i> Historial de simulaciones</a>
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