@extends('layouts.app')

@section('masjs')
  <script type="text/javascript" src="{{ asset('js/invoice.js') }}"></script>
  <script src="{{ asset('js/insumo.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('home') }}">{{ __('COINTRA') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('COMPRAS') }}
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
  @include('invoice.head')
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
              <form method='POST' action="{{ route('compras.update', $registro) }}"  role="form">
                @csrf
                @method('PUT')
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="control_number">{{ _('N° de Compra') }}&nbsp;</label>
                    <input id="control_number" name="control_number" type="text" class="form-control" value="{{ old('control_number', $registro->control_number) }}" disabled>
                  </div>
                  
                  <div class="form-group">
                    <label for="invoice_date">{{ _('Fecha de Compra') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="invoice_date" name="invoice_date" type="date" class="form-control" value="{{ old('invoice_date', $registro->invoice_date) }}" required>
                  </div>
                  <div class="form-group">
                    <label for="currency_value">{{ _('Valor del $') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="currency_value" name="currency_value" type="text" class="form-control money" value="{{ old('currency_value', (strpos($registro->currency_value, '.')) ? $registro->currency_value : $registro->currency_value . '00'  ) }}" required>
                  </div>
                  <div class="form-group">
                    <label for="register_by">{{ _('Registrado por') }}&nbsp;</label>
                    <input id="register_by" name="register_by" type="text" class="form-control" value="{{ old('register_by', $registro->register_by) }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="provider_name">{{ _('Proveedor') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="provider_name" name="provider_name" type="text" class="form-control" value="{{ old('provider_name', $registro->provider_name) }}" required>
                  </div>
                  <div class="form-group">
                    <label for="provider_dni">{{ _('Proveedor RIF') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="provider_dni" name="provider_dni" type="text" class="form-control" value="{{ old('provider_dni', $registro->provider_dni) }}" required>
                  </div>
                  <div class="form-group">
                    <label for="description">{{ _('Descripción') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <textarea id="description" name="description" class="form-control" placeholder="{{ __('Descripción de la Compra') }}" required>{{ old('description', $registro->description) }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="location_id">{{ _('Locacion') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="location" name="location_id" class="form-control">
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
                    <label for="state_id">{{ _('Estado') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="state" name="state_id" class="form-control">
                      @foreach ($states as $state)
                        @if ($state->id == $registro->state_id)
                          <option value="{{ $state->id }}" selected> {{ $state->name }}</option>
                        @else
                          <option value="{{ $state->id }}"> {{ $state->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  @if ($invoice_details)
              <table class="table table-bordered table-striped" id="product_table">
                <thead>
                  <tr>
                    <th>{{ __('Producto') }}</th>
                    <th>{{ __('Cant') }}</th>
                    <th>{{ __('Costo $') }}</th>
                    <th>{{ __('Costo Bs') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($invoice_details->get() as $invoice_detail)
                    <tr>
                        <td>{{ $invoice_detail->item->description }}</td>
                        <td><input id="quantity_{{ $invoice_detail->id }}" name="invoice_quantity[]" value="{{ $invoice_detail->invoice_quantity }}" type="text" class="form-control quantity" {{($registro->state_id == App\Http\Controllers\InvoiceController::CONFIRMADA) ?  "disabled" : ""}} required ></td>
                        <td><input id="unit_cost_foreign_money_{{ $invoice_detail->id }}" name="unit_cost_foreign_money[]" value="{{  (strpos($invoice_detail->unit_cost_foreign_money, '.') ? $invoice_detail->unit_cost_foreign_money : $invoice_detail->unit_cost_foreign_money . '00'  ) }}"  type="text" class="form-control money " {{($registro->state_id == App\Http\Controllers\InvoiceController::CONFIRMADA) ?  "disabled" : ""}}  required></td>
                        <td><input id="unit_cost_local_money_{{ $invoice_detail->id }}" name="unit_cost_local_money[]" value="{{ (strpos($invoice_detail->unit_cost_local_money, '.') ? $invoice_detail->unit_cost_local_money : $invoice_detail->unit_cost_local_money . '00'  )}}" type="text" class="form-control money " {{($registro->state_id == App\Http\Controllers\InvoiceController::CONFIRMADA) ?  "disabled" : ""}} required ></td>
                        
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
                @if ($registro->state_id == App\Http\Controllers\InvoiceController::POR_CONFIRMAR)
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">{{ __('Actualizar Registro') }}</button>
                </div>
                @endif
                
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
