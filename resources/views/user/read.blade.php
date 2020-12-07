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
    <a href="{{ route('usuarios.index') }}">{{ __('USUARIOS') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('USUARIOS') }}
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
  @include('user.head')
  <!-- /.content-header -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div id="contenedor_tbl" class="col-12 {{ $users ? 'd-none' : '' }}">
            @if ($users)
              <table id="tbl_read" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>{{ __('Nombres') }}</th>
                    <th>{{ __('Apellidos') }}</th>
                    <th>{{ __('Cédula') }}</th>
                    <th>{{ __('Usuario') }}</th>
                    <th>{{ __('Correo') }}</th>
                    <th>{{ __('Rol') }}</th>
                    <th>{{ __('Opciones') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                      <td>
                        @if ($trashed == 0)
                          <a href="{{ route('usuarios.edit', $user->user_id) }}"
                            onclick="event.preventDefault();
                            document.getElementById('frm_user_{{ $user->user_id }}').submit();">
                                {{ $user->firstname }}
                          </a>
                          <form id="frm_user_{{ $user->user_id }}" action="{{ route('usuarios.edit', $user->user_id) }}" class="d-none">
                              @method('PUT')
                              @csrf
                          </form>
                        @else
                          {{ $user->firstname }}
                        @endif
                      </td>
                      <td>{{ $user->lastname }}</td>
                      <td>{{ $user->dni }}</td>
                      <td>{{ $user->username }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->role_name }}</td>
                      <td>
                        @if ($trashed == 0 && !$user->deleted_at)
                          <a title="{{ __('Dar de baja') }}" href="#" onclick="
                              event.preventDefault();
                              confirm('{{ __("Usted va a dar de baja a este usuario, esta acción es reversible. ¿Desea continuar?") }}') ?
                                document.getElementById('frm_desactivar_{{ $user->user_id }}').submit() : false;"
                          >
                            <small>
                              <small class="text-danger"><i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true"></i></small>
                            </small>
                          </a>
                          <form method="POST" id="frm_desactivar_{{ $user->user_id }}"action="{{ route('usuarios-destroy', $user->user_id) }}" class="d-none">
                              @method('DELETE')
                              @csrf
                              <input type="hidden" name="search" value="{{ $search }}">
                          </form>
                        @else
                          <a title="{{ __('Dar de alta') }}" href="#" onclick="
                                event.preventDefault();
                                confirm('{{ __("Usted va a dar de alta a este usuario, esta acción es reversible. ¿Desea continuar?") }}') ?
                                  document.getElementById('frm_restaurar_{{ $user->user_id }}').submit() : false;"
                            >
                              <small>
                                <small class="text-info"><i class="fa fa-arrow-circle-up fa-2x"></i></small>
                              </small>
                          </a>
                          <form method="POST" id="frm_restaurar_{{ $user->user_id }}"action="{{ route('usuarios-restore', $user->user_id) }}" class="d-none">
                              @method('PUT')
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
                  {!! $users->links() !!}
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
