@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/delivery.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('home') }}">{{ __('COINTRA') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('ENTREGAS') }}
     <span class="text-primary">({{ __('Nuevo') }})</span>
@endsection

@section('formsearch')
  <form class="form-inline ml-3" method="GET" action="{{ route('entregas.index') }}">
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
  @include('delivery.head')
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
              <form method='POST' action="{{ route('entregas.store') }}"  role="form">
                @csrf
                <input type="hidden" name="search" value="{{ $search }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="company">{{ _('Seleccione la empresa') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="company" name="company_id" class="form-control">
                      <option value=""> Seleccione...</option>
                      @foreach ($companies as $company)
                        <option value="{{ $company->id }}"> {{ $company->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="delivered_date">{{ _('Fecha de entrega') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input type="date" class="form-control" id="delivered_date" name="delivered_date" required>
                  </div>

                  <div class="form-group">
                    <label for="unity">{{ _('Unidad Vehicular') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input type="text" class="form-control" id="unity" name="unity" required>
                  </div>

                  <div class="form-group">
                    <label for="description">{{ _('Descripcion') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input type="text" class="form-control" id="description" name="description" required>
                  </div>

                  <div class="form-group">
                    <label for="location">{{ _('Seleccione el almacén') }}&nbsp;<sup class="text-danger">*</sup></label>
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
                                <th class="center">En Stock</th>
                                <th class="center">Cantidad</th>
                                <th class="center">Descartar</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        
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

@section('masjs')

<script src="{{ asset('js/delivery.js') }}"></script>

@endsection