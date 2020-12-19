@extends('layouts.app')

@section('mascss')
  
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>

  @toastr_render
  <script src="{{ asset('js/visit.js') }}"></script>
@endsection

@include('visit.nav_route')

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
  @yield('head_visit')
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
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="visitorFirstname">Nombre del visitante:&nbsp;</label>
                                    <p>{{$visit->visitor_firstname .' '. $visit->visitor_lastname}}</p>                 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="visitorFirstname">Cedula</label>
                                    <p>{{$visit->visitor_dni}}</p>                 
                                </div>          
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h3 class="h3 mb-md-5 text-center title-subline">Datos de la visita</h3>

                            
                            @if (Auth::user()->role_id !== 3)
                                <div class="form-row mb-md-1">
                                    <div class="form-group col-md-3">
                                        <label for="visitorFirstname">Nombre del trabajador:&nbsp;</label>
                                        <p>{{$visit->worker_firstname . ' ' . $visit->worker_lastname }}</p>                 
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="workerDNI">Cedula del trabajador</label>
                                        <p>{{$visit->worker_dni}}</p>                 
                                    </div>                   
                                </div>
                            @endif

                            <div class="form-row mb-md-1">
                                <div class="form-group col-md-3">
                                    <label for="attendingDate">{{ _('Fecha de visita:') }}</label>
                                    <p>{{date('d-m-Y', strtotime($visit->date_attendance))}}</p> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="entry-time">{{ _('Hora de entrada:') }}&nbsp;</label>
                                    <p>{{ date('H:i', strtotime($visit->entry_time)) }}</p>           
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="departure-time">{{ _('Hora de salida:') }}&nbsp;</label>
                                    <p>{{ date('H:i', strtotime($visit->departure_time)) }}</p>                 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="departure-time">{{ _('Estatus:') }}&nbsp;</label>
                                    <p>{{$visit->status}}</p>                 
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="attendingDate">{{ _('Edificio:') }}</label>
                                    <p>{{$visit->building_name}}</p> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="attendingDate">{{ _('Departamento:') }}</label>
                                    <p>{{$visit->department_name}}</p> 
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!is_null($visit->auto_id))
                        <div class="card">
                            <div class="card-body">
                                <h3 class="h3 mb-md-5 text-center title-subline">Datos del automovil</h3>
                                <div class="form-row mb-md-2">
                                    <div class="form-group col-md-3">
                                        <label for="workerDNI">Matricula del auto</label>
                                        <p>{{$visit->auto_enrrolment}}</p>                 
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="visitorFirstname">Marca</label>
                                        <p>{{$visit->auto_brand}}</p>                 
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="visitorFirstname">Modelo</label>
                                        <p>{{$visit->auto_model}}</p>                 
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="workerDNI">Color del auto</label>
                                        <p>{{$visit->auto_color}}</p>                 
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
