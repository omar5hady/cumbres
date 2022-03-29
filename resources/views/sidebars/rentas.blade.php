<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-building-o"></i> Rentas</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->rol_id == 1)
        <li @click="menu=276" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-book"></i> Admin Rentas</a>
        </li>
        @endif
    </ul>
</li>