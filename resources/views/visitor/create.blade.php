@extends('layouts.app')

@section('mascss')
  
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  @toastr_render
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/visitor.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('visitantes.index') }}">{{ __('VISITANTES') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('VISITANTES') }}
     <span class="text-success">({{ __('Crear') }})</span>
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
  @include('visitor.head')
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
              <form method='POST' action="{{ route('visitantes.store') }}"  role="form"  enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body">
                  <div class="form-group">
                    <label for="firstname">{{ _('Nombre de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="firstname" name="firstname" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="lastname">{{ _('Apellido de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="lastname" name="lastname" type="text" class="form-control" placeholder="{{ __('Ingrese Apellido') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="dni">{{ _('Cédula de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="dni" name="dni" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Ingrese Cedula') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="phone_number">{{ _('Telefono') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="phone_number" name="phone_number" type="text" class="form-control" placeholder="{{ __('Ingrese numero') }}" required>
                  </div>
                  <div class="form-group">
                    <input type="file" name="image" class="file" accept="image/*">
                    <div class="input-group my-3">
                      <input type="text" class="form-control" disabled placeholder="Upload File" id="file" required>
                      <div class="input-group-append">
                        <button type="button" class="browse btn btn-primary">Browse...</button>
                      </div>
                    </div>
                    <div class="ml-2 col-sm-4">
                      <img src="" id="preview" class="img-thumbnail">
                    </div>
                  </div>
                  <div class="row">           
                    <table class="table table-bordered table-striped" id="autos_table">
                      <thead>
                        <tr>
                          <th>{{ __('Modelo de auto') }}</th>
                          <th>{{ __('Color') }}</th>
                          <th>{{ __('Placa') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <a class="btn btn-primary btn-sm" id="add" title="AÑADIR">
                    <i class="icon ion-android-add px-1"></i>
                  </a>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">{{ __('Crear Registro') }}</button>
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
