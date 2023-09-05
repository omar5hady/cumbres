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
        @if(Auth::user()->paquetes == 1)
            <li @click="menu=300" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-shopping-bag"></i> Cat. de cat-equipamiento</a>
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

        <li @click="menu=1002" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-dollar"></i> Precios de Terreno</a>
        </li>
    </ul>
</li>

