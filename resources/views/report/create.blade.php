@extends('layouts.app')

@section('mascss')
  
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>

  @toastr_render
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/report.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('reportes.index') }}">{{ __('Reportes') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('Reportes') }}
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
  @include('report.head')
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
              <form method='POST' action="{{ route('reportes.store') }}"  role="form"  enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body">

                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="visitorDNI">{{ _('Cédula del visitante') }}&nbsp;<sup class="text-danger">*</sup></label>   
                      <div class="form-inline">
                        <input 
                          id="visitorDNI" 
                          name="visitor_dni" 
                          type="text" 
                          class="form-control" 
                          style="text-transform:uppercase" 
                          placeholder="{{ __('Ingrese Cedula') }}"
                          aria-describedby="dniHelpBlock" 
                          required
                        >
                        <div class="loading ml-md-4"></div> 
                      </div>
                      <input type="hidden" id='visitorID' name="visitor_id" readonly>
                      <small id="dniHelpBlock" class="form-text text-muted">
                        El número de cedula debe iniciar con V o E.
                      </small>
                    </div>
                    <div class="form-group col-md-6">
                      <p id="visitorName">Hola mundo</p>
                    </div>

                  </div>

                  @if (Auth::user()->role_id !== 3)
                    <div class="form-group">
                      <label for="workerSearch">{{ _('Nombre del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input id="workerSearch" name="worker_name" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" required>
                      <input type="hidden" id='workerID' name="worker_id" readonly>
                    </div>

                    <div class="form-group">
                      <label for="workerDNI">{{ _('Cédula del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input id="workerDNI" name="worker_dni" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Ingrese Cedula') }}" readonly required>
                    </div>
                  @else
                    <div class="form-group">
                      <label for="workerSearch">{{ _('Nombre del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <p class="text-left">{{Auth::user()->worker->firstname . ' ' . Auth::user()->worker->lastname}}</p>
                      <input type="hidden" name="worker_id" value="{{Auth::user()->worker_id}}" readonly required>
                    </div>

                    <div class="form-group">
                      <label for="workerDNI">{{ _('Cédula del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <p class="text-left">{{Auth::user()->worker->dni}}</p>
                      <input type="hidden" name="worker_dni" value="{{Auth::user()->worker->dni}}" readonly required>
                    </div>
                  @endif
                  
                  <div class="form-group">
                    <label for="attendingDate">{{ _('Fecha y hora de asistencia:') }}&nbsp;</label>
                    <input type="text" class="form-control" id="attendingDate" name="attending_date" placeholder="Fecha y hora de asistencia"> 
                  </div>

                  <div class="form-group">
                    <label for="autoSelect">{{ _('Auto aparcado:') }}&nbsp;</label>
                    <select id="autoSelect" name="auto_id" class="form-control">
                      <option value="-1">Ninguno</option>
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
