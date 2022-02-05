<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-building-o"></i> Oficina</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->inventarios == 1)
            <li @click="menu=273" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-coffee"></i> Inventario</a>
            </li>
        @endif
        @if(Auth::user()->prov_inventarios == 1)
            <li @click="menu=274" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-address-card"></i> Proveedor</a>
            </li>
        @endif
        
    </ul>
</li>