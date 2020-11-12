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
  <script src="{{ asset('js/autos.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('autos.index') }}">{{ __('AUTOS') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('AUTOS') }}
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
          <div id="contenedor_tbl" class="col-12 {{ $autos ? 'd-none' : '' }}">
            @if ($autos)
              <table id="tbl_read" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>{{ __('Modelo') }}</th>
                    <th>{{ __('Matricula') }}</th>
                    <th>{{ __('Visitante') }}</th>
                    <th>{{ __('Fecha de registro') }}</th>
                    <th>{{ __('Opciones') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($autos as $auto)
                    <tr id="tr_{{$auto->auto_id}}">
                      <td>
                        <a href="{{ route('autos.edit', $auto->auto_id) }}"
                          onclick="event.preventDefault();
                          document.getElementById('frm_report_{{ $auto->auto_id }}').submit();">
                              {{ $auto->auto_model_name }}
                        </a>
                        <form id="frm_report_{{ $auto->auto_id }}" action="{{ route('autos.edit', $auto->auto_id) }}" class="d-none">
                            @method('PUT')
                            @csrf
                        </form>
                      </td>
					  <td>{{ $auto->auto_enrrolment }}</td>   
					  <td>{{ $auto->visitor_firstname. ' ' .$auto->visitor_lastname }}</td> 
                      <td>{{ $auto->auto_created_at }}</td>               
                      <td>
                        @if ($trashed == 0 && (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN"))
                          <a title="{{ __('Eliminar') }}" href="#" onclick="
                            event.preventDefault();
                            confirm('{{ __("Esta acción no se puede deshacer. ¿Desea continuar?") }}') ?
                              document.getElementById('frm_eliminar_{{ $auto->auto_id }}').submit() : false;"
                          >
                            <small>
                              <small class="text-danger"><i class="fa fa-trash fa-2x"></i></small>
                            </small>
                          </a>
                          <form method="POST" id="frm_eliminar_{{ $auto->auto_id }}"action="{{ route('autos-destroy', $auto->auto_id) }}" class="d-none">
                              @method('DELETE')
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
                  {!! $autos->links() !!}
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
