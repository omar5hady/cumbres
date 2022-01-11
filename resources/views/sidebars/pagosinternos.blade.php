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