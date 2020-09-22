@extends('layouts.app')

@section('masjs')
  <script type="text/javascript" src="{{ asset('js/invoice.js') }}"></script>
  <script src="{{ asset('js/insumo.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('almacen') }}">{{ __('ALMACEN') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('PAQUETES CONFIRMADOS') }}
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
  @include('warehouse.confirmed_invoice.head')
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
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="control_number">{{ _('N° de Compra') }}&nbsp;</label>
                    <input id="control_number" name="control_number" type="text" class="form-control" value="{{ old('control_number', $registro->control_number) }}" disabled>
                  </div>
                  
                  <div class="form-group">
                    <label for="invoice_date">{{ _('Fecha de Compra') }}&nbsp;</label>
                    <input id="invoice_date" name="invoice_date" type="date" class="form-control" value="{{ old('invoice_date', $registro->invoice_date) }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="currency_value">{{ _('Valor del $') }}&nbsp;</label>
                    <input id="currency_value" name="currency_value" type="text" class="form-control money" value="{{ old('currency_value', (strpos($registro->currency_value, '.')) ? $registro->currency_value : $registro->currency_value . '00'  ) }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="register_by">{{ _('Registrado por') }}&nbsp;</label>
                    <input id="register_by" name="register_by" type="text" class="form-control" value="{{ old('register_by', $registro->register_by) }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="provider_name">{{ _('Proveedor') }}&nbsp;</label>
                    <input id="provider_name" name="provider_name" type="text" class="form-control" value="{{ old('provider_name', $registro->provider_name) }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="provider_dni">{{ _('Proveedor RIF') }}&nbsp;</label>
                    <input id="provider_dni" name="provider_dni" type="text" class="form-control" value="{{ old('provider_dni', $registro->provider_dni) }}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="description">{{ _('Descripción') }}&nbsp;</label>
                    <textarea id="description" name="description" class="form-control" placeholder="{{ __('Descripción de la Compra') }}" disabled>{{ old('description', $registro->description) }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="note">{{ _('Nota') }}&nbsp;</label>
                    <textarea id="note" name="note" class="form-control" placeholder="{{ __('Ninguna nota') }}" disabled>{{ old('note', $registro->note) }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="location_id">{{ _('Locacion') }}&nbsp;</label>
                    <input id="location_id" name="location_id" type="text" class="form-control" value="{{ $registro->location->name }}" disabled>
                  </div>

                  <div class="form-group">
                    <label for="state_id">{{ _('Estado') }}&nbsp;</label>
                    <input id="state_id" name="state_id" type="text" class="form-control" value="{{ $registro->state->name }}" disabled>
                  </div>

                  @if ($invoice_details)
              <table class="table table-bordered table-striped" id="product_table">
                <thead>
                  <tr>
                    <th>{{ __('Producto') }}</th>
                    <th>{{ __('Cant') }}</th>
                    @if (Auth::user()->role->name != "ALMACENISTA")
                        <th>{{ __('Costo $') }}</th>
                      <th>{{ __('Costo Bs') }}</th>
                    @endif
                    
                  </tr>
                </thead>
                <tbody>
                  @foreach ($invoice_details->get() as $invoice_detail)
                    <tr>
                        <td>{{ $invoice_detail->item->description }}</td>
                        <td><input id="quantity_{{ $invoice_detail->id }}" name="invoice_quantity[]" value="{{ $invoice_detail->invoice_quantity }}" type="text" class="form-control quantity" {{($registro->state_id == App\Http\Controllers\InvoiceController::CONFIRMADA) ?  "disabled" : ""}} disabled ></td>
                        @if (Auth::user()->role->name != "ALMACENISTA")
                          <td><input id="unit_cost_foreign_money_{{ $invoice_detail->id }}" name="unit_cost_foreign_money[]" value="{{  (strpos($invoice_detail->unit_cost_foreign_money, '.') ? $invoice_detail->unit_cost_foreign_money : $invoice_detail->unit_cost_foreign_money . '00'  ) }}"  type="text" class="form-control money " {{($registro->state_id == App\Http\Controllers\InvoiceController::CONFIRMADA) ?  "disabled" : ""}}  disabled></td>
                          <td><input id="unit_cost_local_money_{{ $invoice_detail->id }}" name="unit_cost_local_money[]" value="{{ (strpos($invoice_detail->unit_cost_local_money, '.') ? $invoice_detail->unit_cost_local_money : $invoice_detail->unit_cost_local_money . '00'  )}}" type="text" class="form-control money " {{($registro->state_id == App\Http\Controllers\InvoiceController::CONFIRMADA) ?  "disabled" : ""}} disabled ></td>
                        @endif
                        
                        
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
