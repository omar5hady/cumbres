<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Ventas</a>
    <ul class="nav-dropdown-items">

        @if(Auth::user()->simulacion_credito == 1 ||
                Auth::user()->hist_simulaciones == 1 ||
                Auth::user()->contratos == 1 ||
                Auth::user()->reubicaciones == 1 ||
                Auth::user()->hist_creditos == 1
            )
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-folder-open-o"></i> Contratos
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->simulacion_credito == 1)
                        <li @click="menu=61" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-calculator"></i> Simulacion de credito</a>
                        </li>
                    @endif
                    @if(Auth::user()->hist_simulaciones == 1)
                        <li @click="menu=62" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-archive"></i> Hist. de simulaciones</a>
                        </li>
                    @endif
                    @if(Auth::user()->hist_creditos == 1)
                        <li @click="menu=63" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-archive"></i> Hist. creditos</a>
                        </li>
                    @endif
                    @if(Auth::user()->contratos == 1)
                        <li @click="menu=80" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-archive"></i> Realizar contrato</a>
                        </li>
                    @endif
                    @if(Auth::user()->reubicaciones == 1)
                        <li @click="menu=265" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-group"></i> Reubicaciones</a>
                        </li>
                    @endif

                </ul>
            </li>
        @endif


        @if(Auth::user()->lotes_disp == 1)
            <li @click="menu=59" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-circle-o-notch fa-spin"></i> Casas Disponibles</a>
            </li>
        @endif
        @if(Auth::user()->digital_lead == 1)
            <li @click="menu=250" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-vcard"></i> Digital Leads</a>
            </li>
        @endif
        @if(Auth::user()->mis_prospectos == 1)
            <li @click="menu=60" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-group"></i> Mis prospectos</a>
            </li>
        @endif

        @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 7)
            <li @click="menu=228" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-group"></i> Prospectos</a>
            </li>
        @endif

        @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 4 || Auth::user()->rol_id == 6)
            <li @click="menu=260" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-group"></i> Por reasignar</a>
            </li>
        @endif

        @if(Auth::user()->equipamientos == 1 )
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-folder-open-o"></i> Equipamientos
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    <li @click="menu=288" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-unlock"></i> Equipamientos por lote</a>
                    </li>
                    <li @click="menu=212" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-unlock-alt"></i> Equipamientos por contrato</a>
                    </li>
                </ul>
            </li>
        @endif
        @if(Auth::user()->docs == 1)
            <li @click="menu=208" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-credit-card-alt"></i> Docs</a>
            </li>
        @endif
        @if(Auth::user()->mis_prospectos == 1)
            <li @click="menu=301" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-group"></i> Cotizaciones</a>
            </li>
        @endif

    </ul>
</li>
