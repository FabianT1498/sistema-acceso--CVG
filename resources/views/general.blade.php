@extends('layouts.app')

@section('migasdepan')
    <span>{{ __('GENERAL') }}</span>
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
            <h1 class="m-0 text-dark">{{ __('CONFIGURACIÓN GENERAL DEL SISTEMA') }}</h1>
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
                <h3 class="d-none d-sm-block">{{ __('Empresas') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Empresas') }}</h4>
                <h4>{{ $estadisticas->empresas }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrada'), $estadisticas->empresas) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-android-attach"></i>
              </div>
              <a href="{{ route('empresas.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Proveedores') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Proveedores') }}</h4>
                <h4>{{ $estadisticas->proveedores }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrado'), $estadisticas->proveedores) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-social-buffer"></i>
              </div>
              <a href="{{ route('proveedores.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Localidades') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Localidades') }}</h4>
                <h4>{{ $estadisticas->localidades }}&nbsp;&nbsp;<small><small><small>{{ Str::Plural(__('Registrada'), $estadisticas->localidades) }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-ios-location"></i>
              </div>
              <a href="{{ route('localidades.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
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
