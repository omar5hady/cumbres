<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Comisiones</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->exp_comision == 1)
            <li @click="menu=226" class="nav-item" >
                <a class="nav-link" href="#"><i class="icon-layers"></i> Expediente</a>
            </li>
        @endif
        @if(Auth::user()->gen_comision == 1)
            <li @click="menu=227" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-calculator"></i> Comisiones</a>
            </li>
        @endif
        @if(Auth::user()->bono_com == 1)
            <li @click="menu=229" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-calculator"></i> Bonos</a>
            </li>
        @endif
    </ul>
</li>