<!-- /.sidebar -->
<!-- Brand Logo -->
<center>   
    <a href="{{ route('home') }}" title="{{ __('Inicio') }}">
      <img src="{{ asset('img/logocvg.png') }}" alt="CVG" class="img-circle elevation-3 my-1 mb-0" >
    </a>
</center>
<hr class="bg-secondary m-0">
<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      <img src="{{ asset('img/userlogo-160x160.png') }}" class="img-circle elevation-2 bg-primary" alt="User Image" title="{{ ucfirst( Auth::user()->worker->firstname ) . " " . ucfirst( Auth::user()->worker->lastname ) }}">
    </div>
    <div class="info" title="{{ ucfirst( Auth::user()->worker->firstname ) . " " . ucfirst( Auth::user()->worker->lastname )}}">
      <span class="d-block text-white">{{ ucfirst( auth()->user()->username ) }}&nbsp;
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
            title="{{ __('Salir de COINVI') }}">
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
      
		<li class="nav-item {{ setActive('visitantes') }}">
			<a href="{{ route('visitantes.index') }}" class="nav-link {{ setActive('visitantes') }}">
				<i class="nav-icon icon fa fa-address-book"></i>
				<p>{{ __('VISITANTES') }}</p>
			</a>
		</li>

		<li class="nav-item {{ setActive('reportes') }}">
			<a href="{{ route('reportes.index') }}" class="nav-link {{ setActive('reportes') }}">
				<i class="nav-icon icon fa fa-file"></i>
				<p>{{ __('VISITAS') }}</p>
			</a>
		</li>

		<li class="nav-item {{ setActive('autos') }}">
			<a href="{{ route('autos.index') }}" class="nav-link {{ setActive('autos') }}">
				<i class="nav-icon icon fa fa-car"></i>
				<p>{{ __('AUTOS') }}</p>
			</a>
		</li>
          
		@if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
		
			<li class="nav-item {{ setActive('usuarios') }}">
				<a href="{{ route('usuarios.index') }}" class="nav-link {{ setActive('usuarios') }}">
					<i class="nav-icon icon fa fa-user"></i>
					<p>{{ __('USUARIOS') }}</p>
				</a>
			</li>
		@endif
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
