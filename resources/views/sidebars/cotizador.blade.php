<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-book-open"></i> Cotizador de lotes</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->calc_lotes == 1)
            <li class="nav-item">
                <a class="nav-link" href="#" @click="menu=1003"><i class="icon-book-open"></i> Calculadora</a>
            </li>
        @endif
        @if(Auth::user()->edit_cotizacion == 1)
            <li class="nav-item">
                <a class="nav-link" href="#" @click="menu=1005"><i class="icon-book-open"></i> Editar cotización</a>
            </li>
        @endif
        @if(Auth::user()->opc_cotizador == 1)
            <li class="nav-item">
                <a class="nav-link" href="#" @click="menu=1004"><i class="icon-book-open"></i> Opciones</a>
            </li>
        @endif
    </ul>
</li>