<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-energy"></i> Administración </a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->vehiculos == 1)
            <li @click="menu=270" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-car"></i> Vehiculos</a>
            </li>
        @endif
        @if(Auth::user()->departamentos == 1)
            <li @click="menu=11" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-object-group"></i> Departamentos</a>
            </li>
        @endif
        @if(Auth::user()->personas == 1)
            <li @click="menu=12" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-vcard"></i> Personas</a>
            </li>
        @endif
        @if(Auth::user()->empresas == 1)
            <li @click="menu=13" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-industry"></i> Empresas</a>
            </li>
        @endif
        @if(Auth::user()->medios_public == 1)
            <li @click="menu=14" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-bullhorn"></i> Medios Publicitarios</a>
            </li>
        @endif
        @if(Auth::user()->digital_campain == 1)
            <li @click="menu=249" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-vcard"></i> Campañas digitales</a>
            </li>
        @endif
        @if(Auth::user()->lugares_contacto == 1)
            <li @click="menu=15" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-street-view"></i> Lugares de contacto</a>
            </li>
        @endif
        @if(Auth::user()->servicios == 1)
            <li @click="menu=18" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Servicios</a>
            </li>
        @endif
        @if(Auth::user()->inst_financiamiento == 1)
            <li @click="menu=16" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-building-o"></i> Inst. de financ.</a>
            </li>
        @endif
        @if(Auth::user()->tipos_credito == 1)
            <li @click="menu=17" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Tipos de crédito</a>
            </li>
        @endif
        @if(Auth::user()->asig_servicios == 1)
            <li @click="menu=19" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-credit-card"></i> Asignar Servicios</a>
            </li>
        @endif
        @if(Auth::user()->mis_asesores == 1)
            <li @click="menu=73" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-user-circle-o"></i>Mis Asesores</a>
            </li>
            <li @click="menu=254" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-user-circle-o"></i>Asignacion de proyectos</a>
            </li>
        @endif
        @if(Auth::user()->mis_asesores == 1 || (Auth::user()->rol_id === 2 && Auth::user()->vendedor->tipo === 1))
            <li @click="menu=303" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-user-circle-o"></i>Inmobiliarias</a>
            </li>
        @endif

        @if(Auth::user()->cuenta == 1)
            <li @click="menu=8" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-credit-card"></i>Cuentas</a>
            </li>
        @endif

        @if(Auth::user()->notaria == 1)
            <li @click="menu=9" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-credit-card"></i>Notarias</a>
            </li>
        @endif

        @if(Auth::user()->proveedores == 1)
            <li @click="menu=211" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-industry"></i>Proveedores</a>
            </li>
        @endif
    </ul>
</li>

