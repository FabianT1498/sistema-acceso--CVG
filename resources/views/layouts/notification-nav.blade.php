<!--  Navbar Right Menu -->
<ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item">
        <a class="nav-link" id="visitByConfirm" title="" href="{{route('mis_visitas', 'POR CONFIRMAR')}}">
            <i class="far fa-bell"></i>
            <span id="visitByConfirmBadge" class="badge badge-warning navbar-badge"></span>
        </a>
    </li>

    <li class="nav-item">
        <a 
            class="nav-link"
            id="configBtn" 
            title="Configuración" 
            href="#"
            data-toggle="modal" 
            data-target="#configurationModal"
        >
            <i class="fas fa-cog"></i>
        </a>
    </li>
</ul>
    