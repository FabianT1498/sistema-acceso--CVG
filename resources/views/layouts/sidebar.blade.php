<!-- /.sidebar -->
<!-- Brand Logo -->
<center>
  @if (Auth::user()->role->name === "ALMACENISTA")
  <a href="{{ route('almacen') }}" title="{{ __('Inicio') }}">
  @else      
    <a href="{{ route('home') }}" title="{{ __('Inicio') }}">
  @endif
    <img src="{{ asset('img/logocvg.png') }}" alt="CVG" class="img-circle elevation-3 my-1 mb-0" >
  </a>
</center>
<hr class="bg-secondary m-0">
<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      <img src="{{ asset('img/userlogo-160x160.png') }}" class="img-circle elevation-2 bg-primary" alt="User Image" title="{{ ucfirst( auth()->user()->firstname ) . " " . ucfirst( auth()->user()->lastname ) }}">
    </div>
    <div class="info" title="{{ ucfirst( auth()->user()->firstname ) . " " . ucfirst( auth()->user()->lastname ) }}">
      <span class="d-block text-white">{{ ucfirst( auth()->user()->username ) }}&nbsp;
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
            title="{{ __('Salir de COINTRA') }}">
              {{ __('(Salir)') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
      </span>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
            @if (Auth::user()->role->name == "ANALISTA" || Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")  
           <li class="nav-item has-treeview {{ setActive(['dashboard', 'compras', 'entregas', 'stocks'], 'menu-open') }}">
            <a href="{{ route('dashboard') }}" class="nav-link
                {{ setActive(['dashboard', 'compras', 'entregas', 'stocks']) }}">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                {{ __('COINTRA') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item pl-3">
                <a href="{{ route('compras.index') }}" class="nav-link {{ setActive('compras') }}">
                  <i class="ion ion-bag"></i>
                  &nbsp;<p>{{ __('Compras') }}</p>
                </a>
              </li>
              <li class="nav-item pl-3">
                <a href="{{ route('entregas.index') }}" class="nav-link {{ setActive('entregas') }}">
                  <i class="ion ion-thumbsup"></i>
                  &nbsp;<p>{{ __('Entregas') }}</p>
                </a>
              </li>
              <li class="nav-item pl-3">
                <a href="{{ route('stocks') }}" class="nav-link {{ setActive('stocks') }}">
                  <i class="ion ion-pricetags"></i>
                  &nbsp;<p>{{ __('Stock') }}</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          @if (Auth::user()->role->name == "ALMACENISTA" || Auth::user()->role->name == "ANALISTA" || Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
          <li class="nav-item has-treeview {{ setActive([ 'recepcion-paquetes', 'almacen.compras_confirmadas' , 'inventarios', 'inventarios-almacen'], 'menu-open') }}">
            <a href="#" class="nav-link
                {{ setActive([ 'almacen', 'recepcion-paquetes', 'inventarios', 'inventarios-almacen', 'almacen.compras_confirmadas']) }}">
              <i class="nav-icon icon ion-android-laptop"></i>
              <p>
                {{ __('ALMACEN') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (Auth::user()->role->name != "ANALISTA")
                <li class="nav-item pl-3">
                  <a href="{{ route('recepcion-paquetes') }}" class="nav-link {{ setActive('recepcion-paquetes') }}">
                    <i class="fas fa-box-open"></i>
                    &nbsp;<p>{{ __('Recepción de Paquetes') }}</p>
                  </a>
                </li>
              @endif
              {{-- @if (Auth::user()->role->name == "ANALISTA" or Auth::user()->role->name == "ADMIN" or Auth::user()->role->name == "SUPERADMIN" ) --}}
                <li class="nav-item pl-3">
                  <a href="{{ route('almacen.compras_confirmadas') }}" class="nav-link {{ setActive('almacen.compras_confirmadas') }}">
                    <i class="fas fa-archive"></i>
                    &nbsp;<p>{{ __('Paquetes Confirmados') }}</p>
                  </a>
                </li>
              {{-- @endif --}}
              <li class="nav-item pl-3">
                <a href="{{ route('inventarios.index') }}" class="nav-link {{ setActive('inventarios') }}">
                  <i class="fas fa-clipboard"></i>
                  &nbsp;<p>{{ __('Inventarios') }}</p>
                </a>
              </li>
            </ul>
          </li>
      @endif
      @if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
      
        <li class="nav-item has-treeview {{ setActive(['general', 'empresas', 'proveedores', 'localidades'], 'menu-open') }}">
          <a href="#" class="nav-link
              {{ setActive(['general', 'empresas', 'proveedores', 'localidades']) }}">
            <i class="nav-icon icon ion-android-globe"></i>
            <p>
              {{ __('GENERAL') }}
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item pl-3">
              <a href="{{ route('empresas.index') }}" class="nav-link {{ setActive('empresas') }}">
                <i class="ion ion-android-attach"></i>
                &nbsp;<p>{{ __('Empresas') }}</p>
              </a>
            </li>
            <li class="nav-item pl-3">
              <a href="{{ route('proveedores.index') }}" class="nav-link {{ setActive('proveedores') }}">
                <i class="ion ion-social-buffer"></i>
                &nbsp;<p>{{ __('Proveedores') }}</p>
              </a>
            </li>
            <li class="nav-item pl-3">
              <a href="{{ route('localidades.index') }}" class="nav-link {{ setActive('localidades') }}">
                <i class="ion ion-ios-location"></i>
                &nbsp;<p>{{ __('Localidades') }}</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ setActive(['items', 'grupos', 'sub_grupos', 'tipos', 'presentaciones', 'productos'], 'menu-open') }}">
          <a href="#" class="nav-link
              {{ setActive(['items', 'grupos', 'sub_grupos', 'tipos', 'presentaciones', 'productos']) }}">
            <i class="nav-icon ion-load-b"></i>
            <p>
              {{ __('ITEMS') }}
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item pl-3">
              <a href="{{ route('grupos.index') }}" class="nav-link {{ setActive('grupos') }}">
                <i class="ion ion-ios-color-filter-outline"></i>
                &nbsp;<p>{{ __('Grupos') }}</p>
              </a>
            </li>
            <li class="nav-item pl-3">
              <a href="{{ route('sub_grupos.index') }}" class="nav-link {{ setActive('sub_grupos') }}">
                <i class="ion ion-ios-photos-outline"></i>
                &nbsp;<p>{{ __('Subgrupos') }}</p>
              </a>
            </li>
            <li class="nav-item pl-3">
              <a href="{{ route('tipos.index') }}" class="nav-link {{ setActive('tipos') }}">
                <i class="ion ion-pound"></i>
                &nbsp;<p>{{ __('Tipos') }}</p>
              </a>
            </li>
            <li class="nav-item pl-3">
              <a href="{{ route('presentaciones.index') }}" class="nav-link {{ setActive('presentaciones') }}">
                <i class="ion ion-qr-scanner"></i>
                &nbsp;<p>{{ __('Presentaciones') }}</p>
              </a>
            </li>
            <li class="nav-item pl-3">
              <a href="{{ route('productos.index') }}" class="nav-link {{ setActive('productos') }}">
                <i class="ion ion-soup-can-outline"></i>
                &nbsp;<p>{{ __('Productos') }}</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item {{ setActive('usuarios') }}">
              <a href="{{ route('usuarios.index') }}" class="nav-link {{ setActive('usuarios') }}">
                <i class="nav-icon icon ion-android-contacts"></i>
                <p>{{ __('USUARIOS') }}</p>
              </a>
        </li>
      @endif
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
