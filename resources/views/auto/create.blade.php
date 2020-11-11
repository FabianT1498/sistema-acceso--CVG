@extends('layouts.app')

@section('mascss')
  
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>

  @toastr_render
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/autos.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('autos.index') }}">{{ __('Autos') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('Autos') }}
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
  @include('auto.head')
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
              <form method='POST' action="{{ route('autos.store') }}"  role="form"  enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body">

                  <div class="form-group">
                    <label for="visitorSearch">{{ _('Nombre del visitante') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="visitorSearch" name="visitor_name" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" required>
                    <input type="hidden" id='visitorID' name="visitor_id" readonly>
                  </div>
                  
                  <div class="form-group">
                    <label for="visitorDNI">{{ _('Cédula del visitante') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="visitorDNI" name="visitor_dni" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Ingrese Cedula') }}" readonly required>
                  </div>

                  <div class="form-group">
                    <label for="autoBrand">{{ _('Marca del automovil') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="autoBrandSelect" name="auto_brand_select" class="form-control" required>
                      <option hidden disabled selected value> -- selecciona una marca -- </option>
                      @foreach ($auto_brands as $auto_brand)
                          <option value="{{$auto_brand->id}}">{{$auto_brand->name}}</option> 
                      @endforeach
                    </select>
                  </div>

                  <div class="form-check mb-md-3">
                    <input class="form-check-input" type="checkbox" id="checkAutoBrand" name="check_auto_brand" value="0">
                    <label class="form-check-label" for="checkAutoBrand">
                      La marca no se encuentra en la lista
                    </label>
                  </div>

                  <div class="form-group d-none" id="autoBrandGroup">
                  </div>
                  
                  <div class="form-group">
                    <label for="autoModel">{{ _('Modelo del automovil') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="autoModelSelect" name="auto_model_select" class="form-control" required>
                      <option hidden disabled selected value> -- selecciona un modelo -- </option>
                    </select>
                  </div>

                  <div class="form-check mb-md-3">
                    <input class="form-check-input" type="checkbox" id="checkAutoModel" name="check_auto_model" value="0">
                    <label class="form-check-label" for="checkAutoModel">
                        El modelo no se encuentra en la lista
                      </label>   
                  </div>

                  <div class="form-group d-none" id="autoModelGroup">
                  </div>

                  <div class="form-group">
                    <label for="autoEnrrolment">{{ _('Matricula del auto') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="autoEnrrolment" name="auto_enrrolment" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Ingrese Matricula') }}" required>
                  </div>

                  <div class="form-group">
                    <label for="autoColor">{{ _('Color del auto') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="autoColor" name="auto_color" class="form-control" required>
                      <option hidden disabled selected value> -- selecciona un color -- </option>
                    </select>
                  </div>
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
