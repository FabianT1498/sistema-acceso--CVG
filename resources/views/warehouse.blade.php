@extends('layouts.app')

@section('migasdepan')
    <span>{{ __('ALMACEN') }}</span>
@endsection

@section('content')
  @include('layouts.navbar')

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    @include('layouts.sidebar')
  </aside>
  <!-- Fin Main Sidebar Container -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <h1 class="m-0 text-dark">{{ __('CONFIGURACIÓN DEL ALMACEN') }}</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-between">
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Recepcion de Paquetes') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Recepcion de Paquetes') }}</h4>
                <h4>{{ $estadisticas->recepcionPaquetes }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrada'), $estadisticas->recepcionPaquetes) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-android-attach"></i>
              </div>
              <a href="{{ route('recepcion-paquetes') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Inventarios Pendientes') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Inventarios Pendientes') }}</h4>
                <h4>{{ $estadisticas->inventariosPendientes }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrado'), $estadisticas->inventariosPendientes) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-social-buffer"></i>
              </div>
              <a href="{{ route('inventarios.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Inventarios') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Inventarios') }}</h4>
                <h4>{{ $estadisticas->inventariosAnio }}&nbsp;&nbsp;<small><small><small>{{ __('Este Año') }}</small></small></small></h4>
                <h4>{{ $estadisticas->inventariosMes }}&nbsp;&nbsp;<small><small><small>{{ __('Este mes') }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-clipboard"></i>
              </div>
              <a href="{{ route('inventarios.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
