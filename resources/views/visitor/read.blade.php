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
  <script src="{{ asset('js/user.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('visitantes.index') }}">{{ __('VISITANTES') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('VISITANTES') }}
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
  @include('visitor.head')
  <!-- /.content-header -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div id="contenedor_tbl" class="col-12 {{ $registros ? 'd-none' : '' }}">
            @if ($registros)
              <table id="tbl_read" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>{{ __('Nombres') }}</th>
                    <th>{{ __('Apellidos') }}</th>
                    <th>{{ __('Cédula') }}</th>
                    <th>{{ __('Numero de telefono') }}</th>
                    <th>{{ __('Opciones') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($registros->get() as $registro)
                    <tr>
                      <td>
                        <a href="{{ route('visitantes.edit', $registro->id) }}"
                          onclick="event.preventDefault();
                          document.getElementById('frm_registro_{{ $registro->id }}').submit();">
                              {{ $registro->firstname }}
                        </a>
                        <form id="frm_registro_{{ $registro->id }}" action="{{ route('visitantes.edit', $registro->id) }}" class="d-none">
                            @method('PUT')
                            @csrf
                        </form>
                      </td>
                      <td>{{ $registro->lastname }}</td>
                      <td>{{ $registro->dni }}</td>
                      <td>{{ $registro->phone_number }}</td>
                    
                      <td>
                        @if ($trashed == 0)
                        <a title="Eliminar" href="{{ route('visitantes-destroy', $registro->id) }}" onclick="return confirm('¿Está seguro? Esta acción no se puede deshacer.')" class="delete text-c-red"><small><small class="text-danger"><i class="fa fa-trash fa-2x"></i></small></small></a>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
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
