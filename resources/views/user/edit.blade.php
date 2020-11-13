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
            @if ($errors->any())
              <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>
                              {{ $error }}
                          </li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('usuarios.update', $user->user_id) }}"  role="form">
                @csrf
                @method('PUT')
                <div class="card-body">

                	<div class="form-group">
                    <label for="workerSearch">{{ _('Nombre del trabajador') }}</label>
                    <p class="text-left">{{ $user->firstname . ' ' . $user->lastname }}</p>
                    <input type="hidden" id='workerID' name="worker_id" value="{{$user->worker_id}}" readonly>
                  </div>

                  <div class="form-group">
                    <label for="workerDNI">{{ _('Cédula del trabajador') }}</label>
                    <p class="text-left">{{$user->dni}}</p>
                  </div>

                  <div class="form-group">
                    <label for="email">{{ _('Correo de la persona') }}&nbsp;</label>
                    <p class="text-left">{{ $user->email }}</p>
                  </div>

                  <div class="form-group">
                    <label for="username">{{ _('Usuario de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="username" name="username" type="text" class="form-control" placeholder="{{ __('Ingrese Usuario') }}" value="{{ $user->username }}" required>
                  </div>

                  <div class="form-group">
                    <label for="password">{{ _('Contraseña de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="password" name="password" type="password" class="form-control" placeholder="{{ __('Ingrese Contraseña') }}" >
                  </div>
                  <div class="form-group">
                    <label for="role">{{ _('Rol') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="role" name="role_id" class="form-control">
                      <option hidden disabled selected value> -- seleccione un rol -- </option>
                      @foreach ($roles as $role)
                        @if ($role->id == $user->role_id)
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
                  <button type="submit" class="btn btn-success">{{ __('Actualizar user') }}</button>
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
