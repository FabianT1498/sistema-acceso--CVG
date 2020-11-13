@extends('layouts.app')

@section('mascss')
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>

  <!-- User.js -->
  <script src="{{ asset('js/user.js') }}"></script>

  <script src="{{ asset('js/toastr.min.js') }}"></script>
  @toastr_render
@endsection

@section('migasdepan')
    <a href="{{ route('usuarios.index') }}">{{ __('USUARIOS') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('USUARIOS') }}
     <span class="text-primary">({{ __('Nuevo') }})</span>
@endsection

@section('content')
  <input type="hidden" id="create" value="{{ App\Http\Controllers\WebController::CREATE }}">
  <input type="hidden" id="vista" value="{{ $vista }}">
  @include('layouts.navbar')

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('layouts.sidebar')
  </aside>
  <!-- Fin Main Sidebar Container -->
  <!-- Content Header (Page header) -->
  @include('user.head')
  <!-- /.content-header -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <br>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div class="col-12 col-md-10">
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('usuarios.store') }}"  role="form">
                @csrf
                <div class="card-body">
                	<div class="form-group">
						<label for="workerSearch">{{ _('Nombre del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
						<input 
							id="workerSearch"
							name="worker_name"
							type="text" 
							class="form-control"
							placeholder="{{ __('Ingrese Nombre') }}"
							value="{{old('worker_name')}}"
							required
						>
						<input type="hidden" id='workerID' name="worker_id" value="{{old('worker_id')}}" readonly>
                  	</div>

					<div class="form-group">
						<label for="workerDNI">{{ _('Cédula del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
						<input 
							id="workerDNI"
							name="worker_dni"
							type="text" 
							class="form-control" 
							style="text-transform:uppercase" 
							placeholder="{{ __('Ingrese Cedula') }}" 
							value="{{old('worker_dni')}}"
							readonly 
							required
						>
					</div>

					<div class="form-group">
						<label for="username">{{ _('Usuario de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
						<input 
							id="username" 
							name="username" 
							type="text" 
							class="form-control" 
							placeholder="{{ __('Ingrese Usuario') }}" 
							value="{{old('username')}}"
							required
						>
					</div>

					<div class="form-group">
						<label for="email">{{ _('Correo de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
						<input 
							id="email"
							name="email" 
							type="email" 
							class="form-control" 
							placeholder="{{ __('Ingrese Correo') }}"
							value="{{old('email')}}"
							required
						>
					</div>

					<div class="form-group">
						<label for="password">{{ _('Contraseña de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
						<input 
							id="password" 
							name="password" 
							type="password" 
							class="form-control" 
							placeholder="{{ __('Ingrese Contraseña') }}" 
							required
						>
					</div>

					<div class="form-group">
						<label for="role">{{ _('Rol') }}&nbsp;<sup class="text-danger">*</sup></label>
						<select id="role" name="role_id" class="form-control">
							<option hidden disabled selected value> -- seleccione un rol -- </option>
							@foreach ($roles as $role)
								<option value="{{ $role->id }}"> {{ $role->name }}</option>
							@endforeach
						</select>
					</div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ __('Crear Registro') }}</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
