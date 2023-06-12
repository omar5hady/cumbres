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


        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="fa fa-television"></i> Donativos
            </a>
            <ul class="nav-dropdown-items nav-dropdown-items2">
                <li @click="menu=295" class="nav-item">
                    <a class="nav-link" href="#"><i class="icon-bag"></i> Panel de control</a>
                </li>
                <li @click="menu=296" class="nav-item">
                    <a class="nav-link" href="#"><i class="icon-bag"></i> Listado de Donaciones</a>
                </li>
            </ul>
        </li>
    </ul>
</li>
