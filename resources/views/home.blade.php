@extends('layouts.app')

@section('migasdepan')
    <span>{{ mb_strtoupper(config('app.name', 'COINVI')) }}</span>
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
            <h1 class="m-0 text-dark">{{ __('CONTROL DE VISITANTES') }}</h1>
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
                <h3 class="d-none d-sm-block">{{ __('Visitantes') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Visitantes') }}</h4>
                <h4>{{ $estadisticas->visitantesAnio }}&nbsp;&nbsp;<small><small><small>{{ __('Este Año') }}</small></small></small></h4>
                <h4>{{ $estadisticas->visitantesMes }}&nbsp;&nbsp;<small><small><small>{{ __('Este mes') }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="fa fa-address-book"></i>
              </div>
              <a href="{{ route('visitantes.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Visitas') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Visitas') }}</h4>
                <h4>{{ $estadisticas->visitasAnio }}&nbsp;&nbsp;<small><small><small>{{ __('Este Año') }}</small></small></small></h4>
                <h4>{{ $estadisticas->visitasMes }}&nbsp;&nbsp;<small><small><small>{{ __('Este mes') }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="fa fa-file"></i>
              </div>
              <a href="{{ route('visitas.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
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
