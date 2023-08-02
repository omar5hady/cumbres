<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
    <ul class="nav-dropdown-items">
        {{-- Publicidad --}}
        @if( Auth::user()->mejora == 1 || Auth::user()->rep_publi == 1 || Auth::user()->rep_empresas == 1)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-line-chart"></i> Publicidad
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->mejora == 1)
                        <li @click="menu=90" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Estadisticas Mejora</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_publi == 1)
                        <li @click="menu=225" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Estadisticas Publicidad</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_empresas == 1)
                        <li @click="menu=279" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Empresas</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        {{-- Ejecutivos --}}
        @if( Auth::user()->rep_proy == 1 || Auth::user()->rep_recursos_propios == 1
            || Auth::user()->rep_ini_term_ventas == 1
            || Auth::user()->rep_empresas == 1
            || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310
        )
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-line-chart"></i> Ejecutivos
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->rep_proy == 1)
                        <li @click="menu=91" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Resumen por proyecto</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_proy == 1)
                        <li @click="menu=297" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de individualizaciones</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_recursos_propios	 == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310 )
                        <li @click="menu=243" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de recursos propios</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_recursos_propios	 == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310 )
                        <li @click="menu=244" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de casas con crédito puente</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_ini_term_ventas == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310)
                        <li @click="menu=238" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de inicio, termino, ventas y cobranza</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        {{-- Contabilidad --}}
        @if( Auth::user()->inventario == 1)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-line-chart"></i> Contabilidad
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->inventario == 1)
                        <li @click="menu=232" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Inventario Contable</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        {{-- Ventas --}}
        @if( Auth::user()->rep_asesores == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310
            || Auth::user()->rep_venta_canc == 1 || Auth::user()->rep_vent_modelos == 1 || Auth::user()->rep_leads == 1
        )
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-line-chart"></i> Ventas
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->rep_asesores == 1 || Auth::user()->rol_id == 6 || Auth::user()->id == 26545 || Auth::user()->id == 26310)
                        <li @click="menu=233" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de asesores</a>
                        </li>
                    @endif
                    @if(Auth::user()->rol_id == 1 || Auth::user()->rep_venta_canc == 1)
                        <li @click="menu=239" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de ventas y cancelaciones</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_vent_modelos == 1)
                        <li @click="menu=245" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte por modelo</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_leads == 1)
                        <li @click="menu=258" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Digital Leads</a>
                        </li>
                        <li @click="menu=298" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Recepción Digital</a>
                        </li>
                    @endif

                    @if(Auth::user()->rep_leads == 1)
                        <li @click="menu=280" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Prospectos</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        {{-- Cobranza --}}
        @if(Auth::user()->rep_acumulado == 1 || Auth::user()->rep_ingresos == 1
        )
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-line-chart"></i> Cobranza
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->rep_acumulado == 1)
                        <li @click="menu=240" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de expedientes</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_ingresos == 1)
                        <li @click="menu=251" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de ingresos de Créditos</a>
                        </li>
                        <li @click="menu=253" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de ingresos de Enganches</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        {{-- Postventa --}}
        @if( Auth::user()->rep_detalles_post == 1 || Auth::user()->rep_entregas == 1)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-line-chart"></i> Postventa
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->rep_detalles_post == 1)
                        <li @click="menu=246" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de Detalles</a>
                        </li>
                    @endif
                    @if(Auth::user()->rep_entregas == 1)
                        <li @click="menu=269" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de Entregas</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if(Auth::user()->rep_escrituras == 1)
            <li @click="menu=252" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte de escrituras</a>
            </li>
        @endif

    </ul>
</li>
