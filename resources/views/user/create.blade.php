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
					<h3 class="h3 mb-md-5 text-center title-subline">Datos del usuario</h3>
					
					<div class="form-group row mb-md-4">
                      <label class="col-md-3 col-form-label" for="workerDNI">{{ _('Cédula del trabajador:') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <div class="col-md-3">
                        <input 
                          id="workerDNI" 
                          name="worker_dni" 
                          type="text" 
                          class="form-control" 
                          style="text-transform:uppercase" 
                          placeholder="{{ __('Ingrese Cedula') }}"
                          autocomplete="off"
                          value="{{ old('worker_dni') }}" 
                          required
                        > 
                        <input type="hidden" id="workerID" name="worker_id" value="{{ old('worker_id') ? old('worker_id') : -1  }}" readonly>
                      </div>
                      <div id="workerLoader"class="col-md-1">
                        <div class="mt-md-2 loading d-none"></div> 
                      </div>
                      <div id="workerResult" class="col-md-5"></div>      
                    </div>

					<div class="form-group row mb-md-4 justify-content-center">
                      <label class="col-md-3 col-form-label" for="username">{{ _('Nombre de usuario:') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <div class="col-md-3">
                        <input 
                          id="username" 
                          name="username" 
                          type="text" 
                          class="form-control" 
                          placeholder="{{ __('INGRESE USERNAME') }}"
                          autocomplete="off"
                          value="{{ old('username') }}" 
                          required
                        > 
                      </div>
                      <div id="usernameLoader"class="col-md-1">
                        <div class="mt-md-2 loading d-none"></div> 
                      </div>
                      <div id="usernameResult" class="col-md-5"></div>      
                    </div>

					<div class="form-row">
						
						<!-- <div class="form-group col-md-3">
							<label for="email">{{ _('Correo del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
							<input 
								id="email"
								name="email" 
								type="email" 
								class="form-control" 
								placeholder="{{ __('Ingrese Correo') }}"
								value="{{old('email')}}"
								autocomplete="off"
								required
							>               
						</div> -->

						<div class="form-group col-md-3">
							<label for="password">{{ _('Contraseña del usuario') }}&nbsp;<sup class="text-danger">*</sup></label>
							<input 
								id="password" 
								name="password" 
								type="password" 
								class="form-control" 
								placeholder="{{ __('Ingrese Contraseña') }}" 
								autocomplete="off"
								required
							>
						</div>

						<div class="form-group col-md-3 ml-md-3">
							<label for="role">{{ _('Rol') }}&nbsp;<sup class="text-danger">*</sup></label>
							<select id="role" name="role_id" class="form-control">
								<option hidden disabled selected value> -- seleccione un rol -- </option>
								@foreach ($roles as $role)
									<option value="{{ $role->id }}"> {{ $role->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
                
                <!-- /.card-body -->
                <div class="card-footer">
                  <button id="userBtnSubmit" type="submit" class="btn btn-primary">{{ __('Crear Registro') }}</button>
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
  @include('layouts.error-modal')
@endsection
