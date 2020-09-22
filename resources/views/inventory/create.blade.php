@extends('layouts.app')

@section('mascss')
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  @toastr_render
  <script src="{{ asset('js/inventory.js') }}"></script>
@endsection

@section('migasdepan')
    @if (Auth::user()->role->name == "ANALISTA")  
      <a href="{{ route('home') }}">{{ __('ALMACEN') }}</a>
    @else
      <a href="{{ route('almacen') }}">{{ __('ALMACEN') }}</a>
    @endif
    
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('INVENTARIOS') }}
     <span class="text-primary">({{ __('Nuevo') }})</span>
@endsection

@section('content')
  <input type="hidden" id="create" value="{{ App\Http\Controllers\WebController::CREATE }}">
  <input type="hidden" id="vista" value="{{ $vista }}">
  @include('layouts.navbar')

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('layouts.sidebar')
  </aside>
  <!-- Fin Main Sidebar Container -->
  <!-- Content Header (Page header) -->
  @include('inventory.head')
  <!-- /.content-header -->
  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">
    <br>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div class="col-12 col-md-10">
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('inventarios.store') }}"  role="form">
                @csrf
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="description">{{ _('Descripción') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input class="form-control" type="text"  id="description" name="description" placeholder="{{ __('Descripción del nuevo Inventario') }}" value="{{ old('description') }}" required>
                  </div>
                  <div class="form-group">
                    <label for="location">{{ _('Seleccione el almacén') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="location" name="location_id" class="form-control" required>
                      <option value=""> Seleccione...</option>
                      @foreach ($locations as $location)
                        <option value="{{ $location->id }}"> {{ $location->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="row">
                    <table class="table table-bordered table-striped" id="items_table">
                        <thead>
                            <tr>
                                <th class="pb-4"><input id="chk_all" type="checkbox"></th>
                                <th><label for="chk_all" style="cursor: pointer;" class="h3">Item</label></th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                          {{-- @foreach($items as $item)
                          <tr>
                            <td class="center"><input type="checkbox" value="{{ $item->id }}" id="check_{{ $item->id }}" name="check[]" class="chk_item"></td>
                          <td>
                            <label for="check_{{ $item->id }}" style="cursor: pointer;" for="">{{ $item->type->sub_group->group->name . ' - ' . $item->type->sub_group->name . ' - ' . $item->type->name . ' - ' . $item->presentation->name . ' - (' . $item->description . ')' }}
                            </label>
                          </td>
                          </tr>

                          @endforeach --}}
                        </tbody>
                    </table>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ __('Crear Registro') }}</button>
                  <button type="submit" name="btn-imprimir" class="btn btn-success">{{ __('Crear e Imprimir') }}</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('masjs')

<script src="{{ asset('js/inventory.js') }}"></script>

@endsection
