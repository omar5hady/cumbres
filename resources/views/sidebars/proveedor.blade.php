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