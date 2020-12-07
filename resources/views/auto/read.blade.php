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
                    <th>{{ __('Marca') }}</th>
                    <th>{{ __('Modelo') }}</th>
                    <th>{{ __('Matricula') }}</th>
                    <th>{{ __('Color') }}</th>
                    <th>{{ __('Fecha de registro') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($autos as $auto)
                    <tr id="tr_{{$auto->auto_id}}">
                      <td>          
                          @if (Auth::user()->role_id === 4)
                            <a href="{{ route('autos.edit', $auto->id) }}"
                              onclick="document.getElementById('frm_auto_{{ $auto->id }}').submit();">
                              {{ $auto->auto_brand }}
                            </a>
                            <form id="frm_auto_{{ $auto->auto_id }}" action="{{ route('autos.edit', $auto->id) }}" class="d-none">
                                @method('PUT')
                                @csrf
                            </form>
                          @else
                            {{ $auto->auto_brand }}
                          @endif
                      </td>
                      <td>{{ $auto->auto_model }}</td>             
                      <td>{{ $auto->enrrolment }}</td>   
                      <td>{{ $auto->color }}</td> 
                      <td>{{ date('d-m-Y', strtotime($auto->created_at)) }}</td>               
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
