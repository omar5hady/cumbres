<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> RH</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->mant_vehiculos == 1 || Auth::user()->admin_mant_vehiculos)
            <li @click="menu=272" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-car"></i> Mantenimiento de vehiculos</a>
            </li>
        @endif
        @if( Auth::user()->fondo_ahorro == 1 )
            <li @click="menu=285" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-suitcase"></i> Fondo de ahorro</a>
            </li>
        @endif
        @if( Auth::user()->fondo_pension == 1 )
            <li @click="menu=286" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-university"></i> Fondo de pensión</a>
            </li>
        @endif
        <li @click="menu=293" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-money" aria-hidden="true">
            </i> Reto Cumbres </a>
        </li>

        @if(Auth::user()->prestamos_personales ==1)
        <li @click="menu=284" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-money" aria-hidden="true">
            </i> Prestamos personales</a>
        </li>
        @endif
    </ul>
</li>
