@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/user.js') }}"></script>
  @endsection

@section('migasdepan')
    <a href="{{ route('usuarios.index') }}">{{ __('USUARIOS') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('USUARIOS') }}
     <span class="text-success">({{ __('Editar') }})</span>
@endsection

@section('content')
  <input type="hidden" id="edit" value="{{ App\Http\Controllers\WebController::EDIT }}">
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
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div class="col-12 col-md-10">
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('usuarios.update', $registro) }}"  role="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                  <div class="form-group">
                    <label for="firstname">{{ _('Nombre de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="firstname" name="firstname" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" value="{{ $registro->firstname }}" required>
                  </div>
                  <div class="form-group">
                    <label for="lastname">{{ _('Apellido de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="lastname" name="lastname" type="text" class="form-control" placeholder="{{ __('Ingrese Apellido') }}" value="{{ $registro->lastname }}" required>
                  </div>
                  <div class="form-group">
                    <label for="dni">{{ _('Cédula de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="dni" name="dni" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Ingrese Cedula') }}" value="{{ $registro->dni }}" required>
                  </div>
                  <div class="form-group">
                    <label for="username">{{ _('Usuario de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="username" name="username" type="text" class="form-control" placeholder="{{ __('Ingrese Usuario') }}" value="{{ $registro->username }}" required>
                  </div>
                  <div class="form-group">
                    <label for="email">{{ _('Correo de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="{{ __('Ingrese Correo') }}" value="{{ $registro->email }}" required>
                  </div>
                  <div class="form-group">
                    <label for="password">{{ _('Contraseña de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="password" name="password" type="password" class="form-control" placeholder="{{ __('Ingrese Contraseña') }}" >
                  </div>
                  <div class="form-group">
                    <label for="role">{{ _('Rol') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="role" name="role_id" class="form-control">
                      <option value=""> Rol...</option>
                      @foreach ($roles as $role)
                        @if ($role->id == $registro->role_id)
                          <option value="{{ $role->id }}" selected> {{ $role->name }}</option>
                        @else
                          <option value="{{ $role->id }}"> {{ $role->name }}</option>
                        @endif

                      @endforeach
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">{{ __('Actualizar Registro') }}</button>
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
