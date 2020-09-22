@extends('layouts.app')

@section('migasdepan')
    <span>{{ __('ITEMS') }}</span>
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
            <h1 class="m-0 text-dark">{{ __('CONFIGURACIÓN DE PRODUCTOS') }}</h1>
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
                <h3 class="d-none d-sm-block">{{ __('Grupos') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Grupos') }}</h4>
                <h4>{{ $estadisticas->grupos }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrada'), $estadisticas->grupos) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-ios-color-filter-outline"></i>
              </div>
              <a href="{{ route('grupos.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Sub Grupos') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Sub Grupos') }}</h4>
                <h4>{{ $estadisticas->subGrupos }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrado'), $estadisticas->subGrupos) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-ios-photos-outline"></i>
              </div>
              <a href="{{ route('sub_grupos.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Tipos') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Tipos') }}</h4>
                <h4>{{ $estadisticas->tipos }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrada'), $estadisticas->tipos) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-pound"></i>
              </div>
              <a href="{{ route('tipos.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Presentaciones') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Presentaciones') }}</h4>
                <h4>{{ $estadisticas->presentaciones }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrada'), $estadisticas->presentaciones) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-qr-scanner"></i>
              </div>
              <a href="{{ route('presentaciones.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Productos') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Productos') }}</h4>
                <h4>{{ $estadisticas->productos }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrada'), $estadisticas->productos) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-soup-can-outline"></i>
              </div>
              <a href="{{ route('productos.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
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
