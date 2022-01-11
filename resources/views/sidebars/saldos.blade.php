<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-calculator"></i> Saldos</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->edo_cuenta == 1)
            <li @click="menu=206" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-dollar"></i> Estado de cuenta</a>
            </li>
        @endif
        @if(Auth::user()->depositos == 1)
            <li @click="menu=200" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-money"></i> Depositos</a>
            </li>
            <li @click="menu=268" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-money"></i> Pago anticipado</a>
            </li>
        @endif
        @if(Auth::user()->gastos_admn == 1)
            <li @click="menu=205" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-money"></i> Gastos administrativos</a>
            </li>
        @endif
        @if(Auth::user()->cobro_credito == 1)
            <li @click="menu=207" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-credit-card-alt"></i> Cobro de crédito</a>
            </li>
        @endif
        @if(Auth::user()->dev_cancel == 1)
            <li @click="menu=209" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-reload"></i> Devolución por cancelación</a>
            </li>
        @endif

        @if(Auth::user()->dev_exc == 1)
            <li @click="menu=210" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-reload"></i> Devolución por credito excedente</a>
            </li>
        @endif
        @if(Auth::user()->dev_virtual == 1)
            <li @click="menu=266" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-reload"></i> Devolución virtual</a>
            </li>
        @endif
        @if(Auth::user()->dev_virtual == 1)
            <li @click="menu=267" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-reload"></i> Depositos por transferir</a>
            </li>
        @endif
        @if(Auth::user()->facturas == 1)
            <li @click="menu=1001" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-inbox"></i> Facturas</a>
            </li> 
        @endif    
        @if(Auth::user()->ingresos_concretania == 1)
            <li @click="menu=247" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-money"></i> Ingresos Concretania</a>
            </li>
        @endif
    </ul>
</li>