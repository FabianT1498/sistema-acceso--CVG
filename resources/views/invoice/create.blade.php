@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/invoice.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/cointra.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('home') }}">{{ __('COINTRA') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('COMPRAS') }}
     <span class="text-primary">({{ __('Nuevo') }})</span>
@endsection

@section('formsearch')
  <form class="form-inline ml-3" method="GET" action="{{ route('compras.index') }}">
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
  <input type="hidden" id="create" value="{{ App\Http\Controllers\WebController::CREATE }}">
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
              <form method='POST' action="{{ route('compras.store') }}"  role="form">
                @csrf
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="provider">{{ _('Seleccione el proveedor') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="provider" name="provider_id" class="form-control">
                      <option value=""> Seleccione...</option>
                      @foreach ($providers as $provider)
                        <option value="{{ $provider->id }}"> {{ $provider->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="invoice_date">{{ _('Fecha de emisión') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
                  </div>

                  <div class="form-group">
                    <label for="currency_value">{{ _('Valor del $') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="currency_value" name="currency_value" type="text" class="form-control money" placeholder="{{ __('Ingrese Valor del $') }}" value="{{ old('currency_value') }}" required>
                  </div>

                  <div class="form-group">
                    <label for="description">{{ _('Descripción') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <textarea id="description" name="description" class="form-control" placeholder="{{ __('Descripción de la compra') }}" required>{{ old('description') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="location">{{ _('Seleccione dónde se localizará') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="location" name="location_id" class="form-control">
                      <option value=""> Seleccione...</option>
                      @foreach ($locations as $location)
                        <option value="{{ $location->id }}"> {{ $location->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="row">
                    <table class="table table-bordered table-striped" id="product_table">
                        <thead>
                            <tr>
                                <th class="center">Producto</th>
                                <th class="center">Cantidad</th>
                                <th class="center"> 
                                   
                                   Costo Unitario Bs
                                   </th>
                                  <th class="center"> 
                                   
                                    Costo Unitario $ </th>                        
                                <th class="center">Total Bs</th>
                                <th class="center">Total $</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th  class="center" width="20%"><p id="total_local_money">Bs</p></th>
                            <th  class="center" width="20%"><p id="total_foreign_moeny">$</p> </th>
                        </tfoot>
                    </table>
                  </div>
                  <a class="btn btn-primary btn-sm" id="add" title="AÑADIR">
                    <i class="icon ion-android-add px-1"></i>
                  </a>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ __('Crear Registro') }}</button>
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
