<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Acceso</a>
    <ul class="nav-dropdown-items">
        @if(Auth::user()->usuarios == 1)
            <li @click="menu=72" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-user"></i> Usuarios</a>
            </li>
        @endif
        @if(Auth::user()->roles == 1)
            <li @click="menu=71" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-user-following"></i> Roles</a>
            </li>
        @endif
    </ul>
</li>