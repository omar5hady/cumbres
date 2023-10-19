<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-plug"></i> Obra</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->contratistas == 1)
            <li @click="menu=50" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-handshake-o"></i> Contratistas</a>
            </li>
        @endif
        @if(Auth::user()->ini_obra == 1)
            <li @click="menu=51 "class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-play-circle"></i> Inicio de obra</a>
            </li>
        @endif
        @if(Auth::user()->aviso_obra == 1 || Auth::user()->partidas == 1
            || Auth::user()->avance == 1 || Auth::user()->estimaciones == 1
            )
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-home"></i> Control de obra
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->aviso_obra == 1)
                        <li @click="menu=54 "class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-newspaper-o"></i> Contratos</a>
                        </li>
                    @endif
                    @if(Auth::user()->partidas == 1)
                        <li @click="menu=52 "class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-star-half"></i> Partidas</a>
                        </li>
                    @endif
                    @if(Auth::user()->avance == 1)
                        <li @click="menu=53 "class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-star-half-o"></i> Avance</a>
                        </li>
                    @endif
                    @if(Auth::user()->estimaciones == 1)
                        <li @click="menu=248" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-calculator"></i> Estimaciones</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if(Auth::user()->rol_id != 13)
            @if(Auth::user()->aviso_obra == 1 || Auth::user()->partidas == 1 || Auth::user()->estimaciones == 1)
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fa fa-building-o"></i> Departamentos
                    </a>
                    <ul class="nav-dropdown-items nav-dropdown-items2">
                        @if(Auth::user()->aviso_obra == 1)
                            <li @click="menu=281 "class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-newspaper-o"></i> Aviso Departamentos</a>
                            </li>
                        @endif
                        @if(Auth::user()->estimaciones == 1)
                            <li @click="menu=282" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-star-half"></i> Partidas</a>
                            </li>
                        @endif
                        @if(Auth::user()->estimaciones == 1)
                            <li @click="menu=283" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-calculator"></i> Estimaciones</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        @if(Auth::user()->entregas == 1)
            <li @click="menu=216" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-home"></i> Viviendas por entregar</a>
            </li>
        @endif
        @if(Auth::user()->avance == 1 || Auth::user()->rol_id == 9)
            <li @click="menu=55 "class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-star-half-o"></i> Visita para avaluo</a>
            </li>
        @endif


        @if(Auth::user()->id == 25816 || Auth::user()->id == 30993 || Auth::user()->rol_id == 1 || Auth::user()->equipamientos == 1)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-building-o"></i> Equipamientos
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->id == 25816 || Auth::user()->id == 30993 || Auth::user()->rol_id == 1)
                        <li @click="menu=224" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-archive"></i> Administracion de pagos</a>
                        </li>
                    @endif
                    @if(Auth::user()->equipamientos == 1 && Auth::user()->id != 25816 && Auth::user()->id != 25816)
                        <li @click="menu=213" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-archive"></i> Equipamientos por contrato</a>
                        </li>
                    @endif
                    @if(Auth::user()->equipamientos == 1 && Auth::user()->id != 25816 && Auth::user()->id != 25816)
                        <li @click="menu=290" class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-archive"></i> Equipamientos por lote</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif


    </ul>
</li>
