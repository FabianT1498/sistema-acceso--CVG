@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/user.js') }}"></script>
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
                    <label for="firstname">{{ _('Nombre de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="firstname" name="firstname" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" value="{{ old('firstname') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="lastname">{{ _('Apellido de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="lastname" name="lastname" type="text" class="form-control" placeholder="{{ __('Ingrese Apellido') }}" value="{{ old('lastname') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="dni">{{ _('Cédula de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="dni" name="dni" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Ingrese Cedula') }}" value="{{ old('dni') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="username">{{ _('Usuario de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="username" name="username" type="text" class="form-control" placeholder="{{ __('Ingrese Usuario') }}" value="{{ old('username') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="email">{{ _('Correo de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="{{ __('Ingrese Correo') }}" value="{{ old('email') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="password">{{ _('Contraseña de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="password" name="password" type="password" class="form-control" placeholder="{{ __('Ingrese Contraseña') }}" value="{{ old('password') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="role">{{ _('Rol') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="role" name="role_id" class="form-control">
                      <option value=""> Rol...</option>
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
