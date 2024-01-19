<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-home"></i> Desarrollo</a>
    <ul class="nav-dropdown-items">

        @if(Auth::user()->licencias == 1 || Auth::user()->acta_terminacion == 1 || Auth::user()->descarga_actas == 1)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-folder-open-o"></i> Proyectos
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->licencias == 1)
                        <li @click="menu=6" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Licencias</a>
                        </li>
                    @endif
                    @if(Auth::user()->acta_terminacion == 1)
                        <li @click="menu=7" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Acta de terminacion</a>
                        </li>
                    @endif
                    @if(Auth::user()->descarga_actas == 1)
                        <li @click="menu=231" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Prediales y actas</a>
                        </li>
                    @endif
                    @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3  || Auth::user()->rol_id == 8 || Auth::user()->rol_id == 6)
                        <li @click="menu=287" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Planos</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if(Auth::user()->p_fraccionamiento == 1 || Auth::user()->p_etapa == 1)
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-television"></i> Publicidad
                </a>
                <ul class="nav-dropdown-items nav-dropdown-items2">
                    @if(Auth::user()->p_fraccionamiento == 1)
                        <li @click="menu=112" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Logos</a>
                        </li>
                    @endif
                    @if(Auth::user()->p_etapa == 1)
                        <li @click="menu=111" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Documentos anexos</a>
                        </li>
                    @endif

                </ul>
            </li>
        @endif
        @if(Auth::user()->rol_id == 1
            || Auth::user()->usuario == 'eli_hdz')
            <li @click="menu=304" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-globe"></i> Reserva Territorial</a>
            </li>
        @endif
        @if(Auth::user()->fraccionamiento == 1)
            <li @click="menu=1" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-bag"></i> Fraccionamiento</a>
            </li>
        @endif
        @if(Auth::user()->etapas == 1)
            <li @click="menu=2" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-bag"></i> Etapas</a>
            </li>
        @endif
        @if(Auth::user()->modelos == 1)
            <li @click="menu=3" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-bag"></i> Modelos</a>
            </li>
        @endif
        @if(Auth::user()->lotes == 1)
            <li @click="menu=4" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-bag"></i> Lotes</a>
            </li>
        @endif
        @if(Auth::user()->asign_modelos == 1)
            <li @click="menu=5" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-bag"></i> Asignar Modelo</a>
            </li>
        @endif
        @if(Auth::user()->rol_id == 1 || Auth::user()->usuario == 'david.hh'
            || Auth::user()->usuario == 'Herlindo'
            || Auth::user()->usuario == 'karen.viramontes'
            || Auth::user()->usuario == 'eli_hdz')
            <li @click="menu=230" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-bag"></i> Especificaciones de modelo</a>
            </li>
        @endif
        @if(Auth::user()->ruv == 1)
            <li @click="menu=234" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-bag"></i> Solicitud de RUV</a>
            </li>
        @endif
        @if(Auth::user()->seg_ruv == 1)
            <li @click="menu=235" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-bag"></i> Seguimiento de RUV</a>
            </li>
        @endif

    </ul>
</li>
