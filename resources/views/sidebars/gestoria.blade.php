<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-bank"></i> Gestoria</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->expediente == 1)
            <li @click="menu=201" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-folder-open"></i> Expediente</a>
            </li>
        @endif
        @if(Auth::user()->asig_gestor == 1)
            <li @click="menu=202" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-group"></i> Asignar gestor</a>
            </li>
        @endif
        @if(Auth::user()->seg_tramite == 1)
            <li @click="menu=203" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-bullseye"></i> Seguimiento de tramite</a>
            </li>
        @endif
        @if(Auth::user()->avaluos == 1)
            <li @click="menu=204" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-money"></i> Avaluos</a>
            </li>
        @endif
        @if(Auth::user()->bonos_rec == 1)
            <li @click="menu=242" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-money"></i> Bonos por recomendación</a>
            </li>
        @endif
        @if(Auth::user()->int_cobros == 1)
            <li @click="menu=275" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-usd"></i> Integracion de cobros</a>
            </li>
        @endif
        
    </ul>
</li>