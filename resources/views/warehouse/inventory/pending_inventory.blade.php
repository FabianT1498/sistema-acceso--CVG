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
@endsection

@section('migasdepan')
    <a href="{{ route('almacen') }}">{{ __('ALMACEN') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('INVENTARIOS PENDIENTES') }}
     <span class="text-info">({{ __('Listado') }})</span>
@endsection

@section('formsearch')
  <form class="form-inline ml-3">
    <div class="input-group input-group-sm">
      <input type="hidden" name="buscar" value="true">
      <input id="search" name="search" class="form-control form-control-navbar" type="search" placeholder="{{ __('Buscar') }}" aria-label="Search" value="{{ $search }}">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>
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
  @include('warehouse.inventory.head')
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
                    <th>{{ __('Fecha de Creación') }}</th>
                    <th>{{ __('Estado') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($registros->get() as $registro)
                    <tr>
                      <td>
                        <a href="{{ route('inventarios-confirmar', $registro->id) }}"
                          onclick="event.preventDefault();
                          document.getElementById('frm_registro_{{ $registro->id }}').submit();">
                              {{ $registro->start_date }}
                        </a>
                        <form id="frm_registro_{{ $registro->id }}" action="{{ route('inventarios-confirmar', $registro->id) }}" class="d-none">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="search" value="{{ $search }}">
                        </form>
                      </td>
                        <td>{{ $registro->state->name }}</td>
                        
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
