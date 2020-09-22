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
  <script src="{{ asset('js/wharehouse.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('almacen') }}">{{ __('ALMACEN') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('CONFIRMACION DE RECEPCION') }}
     <span class="text-info">({{ __('Confirmación') }})</span>
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
  @include('warehouse.reception.head')
  <!-- /.content-header -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
           <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div class="col-12 col-md-10">
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('recepcion-confirmada', $invoice->id) }}"  role="form">
                @method('PUT')
                @csrf
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">{{ _('Nombre del Proveedor') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ $invoice->provider_name }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="dni">{{ _('Rif del Proveedor') }}</label>
                    <input id="dni" name="dni" type="text" class="form-control" value="{{ $invoice->provider_dni }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="note">{{ _('Nota') }}</label>
                      <textarea class="form-control" id="note" name="note" cols="40" rows="5" ></textarea>
                  </div>
                </div>
                <!-- /.card-body -->
                <div id="contenedor_tbl" class="col-12 {{ $invoice_details ? 'd-none' : '' }}">
            @if ($invoice_details)
              <table class="table table-bordered table-striped" id="product_table">
                <thead>
                  <tr>
                    <th>{{ __('Producto') }}</th>
                    <th>{{ __('Cant Comprada') }}</th>
                    <th>{{ __('Cant Recibida') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($invoice_details->get() as $invoice_detail)
                    <tr>
                        <td>{{ $invoice_detail->item->description }}</td>
                        <td><span id="quantity_{{ $invoice_detail->id }}">{{ $invoice_detail->invoice_quantity }}</span></td>
                        <td><input id="recived_quantity_{{ $invoice_detail->id }}" name="recived_quantity[]" type="text" class="form-control quantity quantity-stock"></td>
                        
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

        <!-- /.row -->
        <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ __('Confirmar Recepción') }}</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
          
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('masjs')

<script src="{{ asset('js/wharehouse.js') }}"></script>

@endsection
