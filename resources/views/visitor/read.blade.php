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
  <script src="{{ asset('js/visitor.js') }}"></script>
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
          <div id="contenedor_tbl" class="col-12 {{ $visitors ? 'd-none' : '' }}">
            @if ($visitors)
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
                  @foreach ($visitors as $visitor)
                    <tr id="tr_{{$visitor->id}}">
                      <td>
                        @if (Auth::user()->role_id !== 3 || $visitor->user_id === Auth::user()->id)
                          <a href="{{ route('visitantes.edit', $visitor->id) }}"
                            onclick="event.preventDefault();
                            document.getElementById('frm_registro_{{ $visitor->id }}').submit();">
                                {{ $visitor->firstname }}
                          </a>
                          <form id="frm_registro_{{ $visitor->id }}" action="{{ route('visitantes.edit', $visitor->id) }}" class="d-none">
                              @method('PUT')
                              @csrf
                          </form>
                        @else
                          {{ $visitor->firstname }}
                        @endif
                      </td>
                      <td>{{ $visitor->lastname }}</td>
                      <td>{{ $visitor->dni }}</td>
                      <td>{{ $visitor->phone_number }}</td>
            
                      <td>
                        @if ($trashed == 0 && (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN"))
                          <a title="{{ __('Eliminar') }}" href="#" onclick="
                            event.preventDefault();
                            confirm('{{ __("Esta acción no se puede deshacer. ¿Desea continuar?") }}') ?
                              document.getElementById('frm_eliminar_{{ $visitor->id }}').submit() : false;"
                          >
                            <small>
                              <small class="text-danger"><i class="fa fa-trash fa-2x"></i></small>
                            </small>
                          </a>
                          <form method="POST" id="frm_eliminar_{{ $visitor->id }}"action="{{ route('visitantes-destroy', $visitor->id) }}" class="d-none">
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
                  {!! $visitors->links() !!}
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
