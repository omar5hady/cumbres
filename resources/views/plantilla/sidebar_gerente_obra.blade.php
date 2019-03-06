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
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Obra</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=50" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-wallet"></i> Contratistas</a>
                    </li>
                    <li @click="menu=51 "class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Inicio de obra</a>
                    </li>
                    <li @click="menu=54 "class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Aviso de obra</a>
                    </li>
                    <li @click="menu=52 "class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Partidas</a>
                    </li>
                    <li @click="menu=53 "class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Avance</a>
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