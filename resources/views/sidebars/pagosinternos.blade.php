<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-laptop"></i> Pagos Internos</a>
    <ul class="nav-dropdown-items">
        <li @click="menu=236" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-plus-square "></i> Solicitud de pago</a>
        </li>
        <li @click="menu=237" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-plus-square "></i> Pagos pendientes</a>
        </li>
        @if(Auth::user()->rol_id == 1 ||
            Auth::user()->usuario == 'uriel.al' ||
            Auth::user()->usuario == 'eli-hdz'
        )
            <li @click="menu=292" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-plus-square "></i> Reporte ventas</a>
            </li>
        @endif
    </ul>
</li>
