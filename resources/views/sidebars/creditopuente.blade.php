<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-plug"></i> Créditos Puente</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->bases == 1)
            <li @click="menu=257" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-archive"></i> Base presupuestal</a>
            </li>
        @endif
        @if(Auth::user()->solic_credito_puente == 1)
            <li @click="menu=255" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-archive"></i> Solicitar Crédito</a>
            </li>
        @endif
        @if(Auth::user()->seg_cp == 1)
            <li @click="menu=256" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-archive"></i> Créditos Puente</a>
            </li>
        @endif
        @if(Auth::user()->edo_cta_bancrea == 1)
            <li @click="menu=259" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-archive"></i> Estado de Cuenta - BANCREA</a>
            </li>
        @endif
        @if(Auth::user()->edo_cta_bancrea == 1)
            <li @click="menu=263" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-archive"></i> Estado de Cuenta - BBVA</a>
            </li>
            <li @click="menu=261" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-archive"></i> Avance de obra</a>
            </li>
            <li @click="menu=262" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-archive"></i> Resumen de Créditos</a>
            </li>
        @endif
    </ul>
</li>