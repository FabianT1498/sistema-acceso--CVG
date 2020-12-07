@extends('layouts.app')

@section('mascss')
  
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>

  @toastr_render
  <script src="{{ asset('js/report.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('reportes.index') }}">{{ __('Visita') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('Visita') }}
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
         
                    <div class="card" id="visitorData">
                        <div class="card-body">
                            <h3 class="h3 mb-md-5 text-center title-subline">Datos del visitante</h3>
                            <div class="form-row mb-md-4">
                                <div class="form-group col-md-4">
                                    <label for="visitorFirstname">Cedula</label>
                                    <p>{{$report->visitor_dni}}</p>                 
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="visitorFirstname">Nombre(s):&nbsp;</label>
                                    <p>{{$report->visitor_firstname}}</p>                 
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="visitorLastname">Apellido(s):&nbsp;</label>
                                    <p>{{$report->visitor_lastname}}</p> 
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h3 class="h3 mb-md-5 text-center title-subline">Datos de la visita</h3>

                            
                            @if (Auth::user()->role_id !== 3)
                                <div class="form-row mb-md-4">
                                    <div class="form-group col-md-4">
                                        <label for="workerDNI">Cedula del trabajador</label>
                                        <p>{{$report->worker_dni}}</p>                 
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="visitorFirstname">Nombre(s):&nbsp;</label>
                                        <p>{{$report->worker_firstname}}</p>                 
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="visitorLastname">Apellido(s):&nbsp;</label>
                                        <p>{{$report->worker_lastname}}</p> 
                                    </div>
                                </div>
                            @endif

                            <div class="form-row mb-md-4">
                                <div class="form-group col-md-3">
                                    <label for="attendingDate">{{ _('Fecha de visita:') }}</label>
                                    <p>{{date('d-m-Y', strtotime($report->date_attendance))}}</p> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="entry-time">{{ _('Hora de entrada:') }}&nbsp;</label>
                                    <p>{{ date('H:i', strtotime($report->entry_time)) }}</p>           
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="departure-time">{{ _('Hora de salida:') }}&nbsp;</label>
                                    <p>{{ date('H:i', strtotime($report->departure_time)) }}</p>                 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="departure-time">{{ _('Estatus:') }}&nbsp;</label>
                                    <p>{{$report->status}}</p>                 
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="attendingDate">{{ _('Edificio:') }}</label>
                                    <p>{{$report->building_name}}</p> 
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="attendingDate">{{ _('Departamento:') }}</label>
                                    <p>{{$report->department_name}}</p> 
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!is_null($report->auto_id))
                        <div class="card">
                            <div class="card-body">
                                <h3 class="h3 mb-md-5 text-center title-subline">Datos del automovil</h3>
                                <div class="form-row mb-md-4">
                                    <div class="form-group col-md-4">
                                        <label for="workerDNI">Matricula del auto</label>
                                        <p>{{$report->auto_enrrolment}}</p>                 
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="visitorFirstname">Marca</label>
                                        <p>{{$report->auto_brand}}</p>                 
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="visitorFirstname">Modelo</label>
                                        <p>{{$report->auto_model}}</p>                 
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="workerDNI">Color del auto</label>
                                        <p>{{$report->auto_color}}</p>                 
                                    </div>
                                </div>
                            </div>
                        </div> 
                    @endif
                </div>

              </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
