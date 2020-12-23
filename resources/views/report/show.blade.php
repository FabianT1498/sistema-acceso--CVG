@extends('layouts.app')

@section('migasdepan')
    <a href="{{ route('reportes.index') }}">{{ __('REPORTES') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('REPORTES') }}
     <span class="text-info">({{ __('Listado') }})</span>
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
                            <h3 class="h3 mb-md-5 text-center title-subline">Datos del reporte</h3>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="visitorFirstname">Numero de visita:&nbsp;</label>
                                    <p>{{ $report->visit_id }}</p>                 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="visitorFirstname">Fecha de emisión:&nbsp;</label>
                                    <p>{{ date('d-m-Y H:i', strtotime($report->created_at)) }}</p>                 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="visitorFirstname">Emitido por:</label>
                                    <p>{{$report->user_username}}</p>                 
                                </div>          
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h3 class="h3 mb-md-5 text-center title-subline">Datos del visitante</h3>
   
                            <div class="form-row mb-md-1">
                                <div class="form-group col-md-3">
                                    <label for="visitorFirstname">Nombre del visitante:&nbsp;</label>
                                    <p>{{ $report->visitor_fullname }}</p>                 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="visitorDNI">Cedula del visitante</label>
                                    <p>{{ $report->visitor_dni }}</p>                 
                                </div>                   
                            </div>
                        </div>
                    </div>

                    @if(!is_null($report->auto_enrrolment))
                        <div class="card">
                            <div class="card-body">
                                <h3 class="h3 mb-md-5 text-center title-subline">Datos del automovil</h3>
                                <div class="form-row mb-md-2">
                                    <div class="form-group col-md-3">
                                        <label for="workerDNI">Matricula del auto</label>
                                        <p>{{$report->auto_enrrolment}}</p>                 
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="visitorFirstname">Modelo</label>
                                        <p>{{$report->auto_model}}</p>                 
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="visitorFirstname">Color</label>
                                        <p>{{$report->auto_color}}</p>                 
                                    </div>
                                </div>
                            </div>
                        </div> 
                    @endif

                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a href="{{ route('visitas.show', $report->visit_id) }}" class="btn btn-success">{{ __('Ver detalles de la visita') }}</a>
                    </div>
                </div>
              </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
