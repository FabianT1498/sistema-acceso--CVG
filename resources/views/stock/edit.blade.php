@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('home') }}">{{ __('COINTRA') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('STOCKS') }}
     <span class="text-info">({{ __('Listado') }})</span>
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
  @include('stock.head')
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
                        <!-- form start -->
                        <form method='PUT' action="{{ route('stocks.update', $registro->id) }}"  role="form">
                          @csrf
                          <input type="hidden" name="search" value="{{ $search }}">
                          <div class="card-body">
                            <div class="row">
                                <table id="tbl_edit" class="table table-bordered table-hover">
                                    <thead>
                                      <tr>
                                        <th>{{ __('Fecha de Compra') }}</th>
                                        <th>{{ __('Valor $ Unitario') }}</th>
                                        <th>{{ __('Cantidad Disponible') }}</th>
                                      </tr>
                                    </thead>
                                  <tbody>
                                    @foreach($stocks as $stock)
                                    <tr>
                                        <td>{{ $stock->invoice_date }}</td>
                                        <td>{{ $stock->unit_cost_foreign_money . "$"}}</td>
                                        <td><input class="form-control" type="number"  id="quantity_available" name="quantity_available[]" placeholder="{{ __('Cantidad Disponible') }}" value="{{ $stock->quantity_available }}" required>                                        
                                        </td>
                                    </tr>
                                    <input name="stocks[]" type="hidden" value="{{ $stock->id }}">
                                    @endforeach
                                  </tbody>
                              </table>
                          <!-- /.card-body -->
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">{{ __('Actualizar Registro') }}</button>
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
