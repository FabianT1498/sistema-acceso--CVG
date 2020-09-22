@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/provider.js') }}"></script>
  @endsection

@section('migasdepan')
    <a href="{{ route('general') }}">{{ __('GENERAL') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('PROVEEDORES') }}
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
  @include('provider.head')
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
              <form method='POST' action="{{ route('proveedores.update', $registro) }}"  role="form">
                @csrf
                @method('PUT')
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">{{ _('Nombre del Proveedor') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="name" name="name" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" value="{{ old('name', $registro->name) }}" required>
                  </div>
                  <div class="form-group">
                    <label for="dni">{{ _('Nro DNI (rif)') }}</label>
                    <input id="dni" name="dni" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Documento de Identificación Nacional') }}" value="{{ old('dni', $registro->dni) }}" required>
                  </div>
                  <div class="form-group">
                    <label for="address">{{ _('Dirección') }}</label>
                    <textarea id="address" name="address" class="form-control" placeholder="{{ __('Escriba dirección física de la Empresa') }}" required>{{ old('address', $registro->address) }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="phone">{{ _('Teléfono') }}</label>
                    <input id="phone" name="phone" type="text" class="form-control" placeholder="{{ __('Ingrese Nro de Teléfono') }}"  value="{{ old('phone', $registro->phone) }}" required>
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
