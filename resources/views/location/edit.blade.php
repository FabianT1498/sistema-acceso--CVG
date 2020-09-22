@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('general') }}">{{ __('GENERAL') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('LOCALIDADES') }}
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
  @include('location.head')
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
              <form method='POST' action="{{ route('localidades.update', $registro) }}"  role="form">
                @csrf
                @method('PUT')
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">{{ _('Nombre de la Localidad') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="name" name="name" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" value="{{ old('name', $registro->name) }}" required>
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
