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
    <a href="{{ route('reportes.index') }}">{{ __('REPORTES') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('REPORTES') }}
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
          <div id="contenedor_tbl" class="col-12 {{ $reports ? 'd-none' : '' }}">
            @if ($reports)
              <table id="tbl_read" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>{{ __('Visitante') }}</th>
                    <th>{{ __('Visitado') }}</th>
                    <th>{{ __('Emisor') }}</th>
                    <th>{{ __('Fecha de visita') }}</th>
                    <th>{{ __('Opciones') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($reports as $report)
                    <tr id="tr_{{$report->id}}">
                      <td>
                        <a href="{{ route('reportes.edit', $report->id) }}"
                          onclick="event.preventDefault();
                          document.getElementById('frm_report_{{ $report->id }}').submit();">
                              {{ $report->visitor_firstname. ' ' .$report->visitor_lastname }}
                        </a>
                        <form id="frm_report_{{ $report->id }}" action="{{ route('reportes.edit', $report->id) }}" class="d-none">
                            @method('PUT')
                            @csrf
                        </form>
                      </td>
                      <td>{{ $report->worker_firstname. ' ' .$report->worker_lastname }}</td>
                      <td>{{ $report->user_username }}</td>
                      <td>{{ $report->date_attendance }}</td>               
                      <td>
                        @if ($trashed == 0)
                          @if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
                            <a title="{{ __('Eliminar') }}" href="#" onclick="
                              event.preventDefault();
                              confirm('{{ __("Esta acción no se puede deshacer. ¿Desea continuar?") }}') ?
                                document.getElementById('frm_eliminar_{{ $report->id }}').submit() : false;"
                            >
                              <small>
                                <small class="text-danger"><i class="fa fa-trash fa-2x"></i></small>
                              </small>
                            </a>
                            <form method="POST" id="frm_eliminar_{{ $report->id }}"action="{{ route('reportes-destroy', $report->id) }}" class="d-none">
                                @method('DELETE')
                                @csrf
                                <input type="hidden" name="search" value="{{ $search }}">
                            </form>
                          @endif

                          <a title="{{ __('Generar PDF') }}" href="#" onclick="
                            event.preventDefault();
                            confirm('{{ __("Usted va a generar un pase, esto quedará registrado. ¿Desea continuar?") }}') ?
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

                            
                        @endif
                      </td>
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
