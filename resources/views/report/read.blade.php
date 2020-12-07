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
  <script src="{{ asset('js/report.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('reportes.index') }}">{{ __('VISITAS') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('VISITAS') }}
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
          <div id="contenedor_tbl" class="col-12 {{ $reports ? '' : 'd-none' }}">
            @if ($reports)
              <table id="tbl_read" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>{{ __('Visitante') }}</th>
                    <th>{{ __('Trabajador') }}</th>       
                    <th>{{ __('Creado por') }}</th>
                    <th>{{ __('Fecha de visita') }}</th>
                    <th>{{ __('Hora de entrada') }}</th>
                    <th>{{ __('Hora de salida') }}</th>
                    <th>{{ __('Estatus') }}</th>

                    @if (Auth::user()->role_id > 2)
                      <th>{{ __('Opciones') }}</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach ($reports as $report)
                    <tr id="tr_{{$report->id}}">
                      <td>          
                        <a href="{{ route('reportes.show', $report->id) }}">
                          {{ $report->visitor_firstname. ' ' .$report->visitor_lastname }}
                        </a>  
                      </td>        
                      <td>{{ $report->worker_firstname. ' ' .$report->worker_lastname }}</td>                    
                      <td>{{ $report->user_username }}</td>
                      <td>{{ date('d-m-Y', strtotime($report->date_attendance)) }}</td>
                      <td>{{ date('H:i', strtotime($report->entry_time)) }}</td>   
                      <td>{{ date('H:i', strtotime($report->departure_time)) }}</td>
                      <td>{{ $report->status }}</td>
                                    
                      @if(Auth::user()->role_id === 4 && $report->status === "CONFIRMADA")
                        <td>
                            <a title="{{ __('Generar PDF') }}" href="#" onclick="
                              event.preventDefault();
                              confirm('{{ __("Usted va a generar un reporte, esto quedará registrado. ¿Desea continuar?") }}') ?
                                document.getElementById('frm_pdf_{{ $report->id }}').submit() : false;"
                            >
                              <small>
                                <small class="text-info"><i class="fa fa-file-pdf fa-2x"></i></small>
                              </small>
                            </a>
                            <form method="GET" id="frm_pdf_{{ $report->id }}" action="{{ route('reportes.generar_pase', $report->id) }}" class="d-none">
                                @csrf
                                <input type="hidden" name="search" value="{{ $search }}">
                            </form>           
                        </td>
                      @endif
                      
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{-- Pagination --}}
              <div class="d-flex justify-content-center">
                  {!! $reports->links() !!}
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
