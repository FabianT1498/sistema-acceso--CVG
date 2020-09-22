@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('home') }}">{{ __('COINTRA') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('ENTREGAS') }}
     <span class="text-success">({{ __('Editar') }})</span>
@endsection

@section('content')
  <input type="hidden" id="edit" value="{{ App\Http\Controllers\WebController::EDIT }}">
  <input type="hidden" id="vista" value="{{ $vista }}">
  @include('layouts.navbar')

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('layouts.sidebar')
  </aside>
  <!-- Fin Main Sidebar Container -->
  <!-- Content Header (Page header) -->
  @include('delivery.head')
  <!-- /.content-header -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
          <div class="col-12 col-md-10">
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('entregas.update', $registro) }}"  role="form">
                @csrf
                @method('PUT')
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="control_number">{{ _('N° de Entrega') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="control_number" name="control_number" type="text" class="form-control" value="{{ old('control_number', $registro->control_number) }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="delivered_date">{{ _('Fecha de Entrega') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="delivered_date" name="delivered_date" type="date" class="form-control" value="{{ old('delivered_date', $registro->delivered_date) }}">
                  </div>

                  <div class="form-group">
                    <label for="deliverer">{{ _('Entregado por') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="deliverer" name="deliverer" type="text" class="form-control" value="{{ $registro->deliverer . ' (' . $registro->dni_deliverer . ')' }}" disabled>
                  </div>

                  <div class="form-group">
                    <label for="location_id">{{ _('Locación') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="location" name="location_id" class="form-control" disabled>
                      @foreach ($locations as $location)
                        @if ($location->id == $registro->location_id)
                          <option value="{{ $location->id }}" selected> {{ $location->name }}</option>
                        @else
                          <option value="{{ $location->id }}"> {{ $location->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="unity">{{ _('Unidad') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="unity" name="unity" type="text" class="form-control" value="{{ $registro->unity }}" >
                  </div>

                  <div class="form-group">
                    <label for="description">{{ _('Descripción') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="description" name="description" type="text" class="form-control" value="{{ $registro->description }}" >
                  </div>

                  <div class="form-group">
                    <label for="company_id">{{ _('Empresa') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="company" name="company_id" class="form-control">
                      @foreach ($companies as $company)
                        @if ($company->id == $registro->company_id)
                          <option value="{{ $company->id }}" selected> {{ $company->name }}</option>
                        @else
                          <option value="{{ $company->id }}"> {{ $company->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>

                  @if ($delivery_details)
                    <table class="table table-bordered table-striped" id="product_table">
                      <thead>
                        <tr>
                          <th>{{ __('Producto') }}</th>
                          <th>{{ __('Cant Entregada') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($delivery_details->get() as $delivery_detail)
                          <tr>
                              <td>{{ $delivery_detail->item->description }}</td>
                              <td><input id="quantity" name="quantity[]" value="{{ $delivery_detail->quantity }}" type="text" class="form-control" disabled readonly></td>

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
                <!-- /.card-body -->
                <div class="card-footer">

                  @if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
                  <button type="submit" class="btn btn-success align-items-center">
                    <i class="ion-archive h1"></i>&nbsp;&nbsp;
                    <div>
                      {{ __('Actualizar Registro') }}
                    </div>
                  </button>
                  @endif
                  

                  <a title="{{ __('Imprimir') }}" href="#" onclick="
                            event.preventDefault();
                            document.getElementById('frm_imprimir_{{ $registro->id }}').submit();" class="btn btn-primary align-items-center">
                    <i class="ion-printer h1"></i>
                    <div>
                      {{ __('Imprimir Entrega') }}
                    </div>
                  </a>
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
  <!-- Formulario para impresión -->
  <form target="_blank" method="GET" id="frm_imprimir_{{ $registro->id }}" action="{{ route('entregas.printing', $registro->id) }}" class="d-none"></form>
  <!-- /.Formulario para impresión -->
@endsection
