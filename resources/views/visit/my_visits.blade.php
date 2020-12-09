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
    <a href="{{ route('visitas.mis_visitas') }}">{{ __('MIS VISITAS') }}</a>
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
                    <tr id="tr_{{$visit->id}}">
                      <td>          
                        <a href="{{ route('visites.show', $visit->id) }}">
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

                          <a class="ml-md-3" title="{{ __('Anular cita') }}" href="#" onclick="
                            event.preventDefault();
                            confirm('Está a punto de cancelar la cita, esta acción no se puede deshacer. ¿Desea continuar?') 
                              ? document.getElementById('frm_anular_{{ $visit->id }}').submit() :
                                false;"
                          >
                            <small>
                              <small class="text-danger"><i class="fa fa-ban fa-2x"></i></small>
                            </small>
                          </a>
                          <form method="POST" id="frm_anular_{{ $visit->id }}"action="{{ route('visitas.denyVisit', $visit->id) }}" class="d-none">
                              @method('PUT')
                              @csrf
                              <input type="hidden" name="search" value="{{ $search }}">
                          </form>

                          <a class="ml-md-3" title="{{ __('Confirmar cita') }}" href="#" onclick="
                            event.preventDefault();
                            confirm('Está a punto de confirmar la cita, esta acción no se puede deshacer. ¿Desea continuar?') 
                              ? document.getElementById('frm_confirmar_{{ $visit->id }}').submit() 
                              : false;"
                          >
                            <small>
                              <small class="text-success"><i class="fa fa-check fa-2x"></i></small>
                            </small>
                          </a>
                          <form method="POST" id="frm_confirmar_{{ $visit->id }}" action="{{ route('visitas.confirmVisit', $visit->id) }}" class="d-none">
                              @method('PUT')
                              @csrf
                              <input type="hidden" name="search" value="{{ $search }}">
                          </form>

                        @elseif(Auth::user()->role_id === 4 && $visit->status === "CONFIRMADA")
                          <a title="{{ __('Generar PDF') }}" href="#" onclick="
                            event.preventDefault();
                            confirm('{{ __("Usted va a generar un visite, esto quedará registrado. ¿Desea continuar?") }}') ?
                              document.getElementById('frm_pdf_{{ $visit->id }}').submit() : false;"
                          >
                            <small>
                              <small class="text-info"><i class="fa fa-file-pdf fa-2x"></i></small>
                            </small>
                          </a>
                          <form method="GET" id="frm_pdf_{{ $visit->id }}" action="{{ route('reportes.generar_pase', $visit->id) }}" class="d-none">
                              @csrf
                              <input type="hidden" name="search" value="{{ $search }}">
                          </form>       
                        @endif
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
@endsection
