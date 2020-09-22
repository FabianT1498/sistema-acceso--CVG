@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/provider.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('general') }}">{{ __('GENERAL') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('PROVEEDORES') }}
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
  @include('provider.head')
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
              <form method='POST' action="{{ route('proveedores.store') }}"  role="form">
                @csrf
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="condition" value="{{ App\Http\Controllers\ProviderController::PROVIDER }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">{{ _('Nombre del Proveedor') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="name" name="name" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" value="{{ old('name') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="dni">{{ _('Nro DNI (rif)') }}</label>
                    <input id="dni" name="dni" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Documento de Identificación Nacional') }}" value="{{ old('dni') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="address">{{ _('Dirección') }}</label>
                    <textarea id="address" name="address" class="form-control" placeholder="{{ __('Escriba dirección física de la Empresa') }}" required>{{ old('address') }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="phone">{{ _('Teléfono') }}</label>
                    <input id="phone" name="phone" type="text" class="form-control" placeholder="{{ __('Ingrese Nro de Teléfono') }}"  value="{{ old('phone') }}" required>
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
