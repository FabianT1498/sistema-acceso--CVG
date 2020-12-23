@extends('layouts.app')

@section('mascss')
  <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection

@section('masjs')
  <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  @toastr_render

  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/visit.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('mis_visitas') }}">{{ __('MIS VISITAS') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('MIS VISITAS') }}
     <span class="text-info">({{ __('Listado') }})</span>
@endsection

@section('content')
  <input type="hidden" id="read" value="{{ App\Http\Controllers\WebController::READ }}">
  <input type="hidden" id="vista" value="{{ $vista }}">

  @include('layouts.navbar')

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('layouts.sidebar')
  </aside>
  <!-- Fin Main Sidebar Container -->
  <!-- Content Header (Page header) -->
  @include('visit.head_my_visits')
  <!-- /.content-header -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div id="contenedor_tbl" class="col-12 {{ $visits ? '' : 'd-none' }}">
            @if ($visits)
              <table id="tbl_read" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>{{ __('Visitante') }}</th>             
                    <th>{{ __('Creado por') }}</th>
                    <th>{{ __('Fecha de visita') }}</th>
                    <th>{{ __('Hora de entrada') }}</th>
                    <th>{{ __('Hora de salida') }}</th>
                    <th>{{ __('Estatus') }}</th>
                    <th>{{ __('Opciones') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($visits as $visit)
                    <tr data-visit-id="{{$visit->id}}">
                      <td>          
                        <a href="{{ route('visitas.show', $visit->id) }}">
                          {{ $visit->visitor_firstname. ' ' .$visit->visitor_lastname }}
                        </a>  
                      </td>                         
                      <td>{{ $visit->user_username }}</td>
                      <td>{{ date('d-m-Y', strtotime($visit->date_attendance)) }}</td>
                      <td>{{ date('H:i', strtotime($visit->entry_time)) }}</td>   
                      <td>{{ date('H:i', strtotime($visit->departure_time)) }}</td>
                      <td>{{ $visit->status }}</td>
                                    
                      <td>
                        
                        @if ($visit->status === "POR CONFIRMAR")

                          <a 
                            class="denyVisitBtn"
                            title="{{ __('Anular cita') }}"
                            href="#"
                            data-toggle="modal" 
                            data-target="#visitStatusModal"
                          >
                            <small>
                              <small class="text-danger"><i class="fa fa-ban fa-2x"></i></small>
                            </small>
                          </a>
                          <form method="POST" id="frm_anular_{{ $visit->id }}"action="{{ route('visitas.denyVisit', $visit->id) }}" class="d-none">
                              @method('PUT')
                              @csrf
                          </form>

                          <a 
                            class="ml-md-2 confirmVisitBtn"
                            title="{{ __('Confirmar cita') }}"
                            href="#"
                            data-toggle="modal" 
                            data-target="#visitStatusModal"
                          >
                            <small>
                              <small class="text-success"><i class="fa fa-check fa-2x"></i></small>
                            </small>
                          </a>
                          <form method="POST" id="frm_confirmar_{{ $visit->id }}" action="{{ route('visitas.confirmVisit', $visit->id) }}" class="d-none">
                              @method('PUT')
                              @csrf
                          </form>

                        @endif

                        @if (is_null($visit->report_id) && $visit->status !== "CANCELADA")
                          <a 
                            class="ml-md-2" 
                            title="{{ __('Editar cita') }}" 
                            href="{{ route('visitas.edit', $visit->id) }}" 
                          >
                            <small>
                              <small class="text-info"><i class="far fa-edit fa-2x"></i></small>
                            </small>
                          </a>
                        @endif
                        
                        @if ($today_date <= $visit->date_attendance)
                          @if ( (Auth::user()->role_id === 4 && $visit->status === "CONFIRMADA") 
                              || ( (Auth::user()->role_id === 1 || Auth::user()->role_id === 2) && $visit->status === "COMPLETADA" ) )
                              
                            <a 
                              class="ml-md-2 printReportBtn"
                              title="{{ __('Generar PDF') }}"
                              href="#"
                              data-toggle="modal" 
                              data-target="#printReportModal"
                            >
                              <small>
                                <small class="text-info"><i class="fa fa-file-pdf fa-2x"></i></small>
                              </small>
                            </a>
                            <form method="GET" id="frm_pdf_{{ $visit->id }}" action="{{ route('reportes.generar_pase', $visit->id) }}" class="d-none">
                                @csrf
                            </form>       
                                
                          @elseif(Auth::user()->role_id === 4 && $visit->status === "COMPLETADA")
                            <a
                              class="ml-md-2" 
                              data-toggle="modal" 
                              data-target="#reportPrintedModal"
                              title="{{ __('Pase generado') }}"
                              href="#"
                            >
                                <small>
                                  <small class="text-secondary"><i class="fa fa-file-pdf fa-2x"></i></small>
                                </small>
                            </a>
                            
                          @endif
                        @endif

                        &nbsp;
                      </td>
                      
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{-- Pagination --}}
              <div class="d-flex justify-content-center">
                  {!! $visits->links() !!}
              </div>
            @else
              <div class="alert alert-info h5">
                {{ __('Use la opción de búsqueda para obtener un listado') }}
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
  @include('report.print-report-modal')
  @include('visit.visit-status-modal')
  @include('report.report-printed-modal')
@endsection
