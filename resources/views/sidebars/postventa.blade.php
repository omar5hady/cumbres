<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-handshake-o"></i> Post Venta</a>
    <ul class="nav-dropdown-items">
        <li @click="menu=271" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-book"></i>Datos de la administración </a>
        </li>
        @if(Auth::user()->solic_detalles == 1)
            <li @click="menu=221" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-book"></i> Detalles generales</a>
            </li>
            <li @click="menu=222" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-book"></i> Catalogo de Detalles</a>
            </li>
        @endif
            <li @click="menu=218" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-address-card-o"></i> Documentos</a>
            </li>
            {{-- Modulo deja de ser usado --}}
        {{-- @if(Auth::user()->postventa == 1)
            <li @click="menu=220" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-check-circle-o"></i> Revisión previa</a>
            </li>
        @endif --}}
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
