@extends('layouts.app')

@section('migasdepan')
    <span>{{ mb_strtoupper(config('app.name', 'COINTRA')) }}</span>
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
            <h1 class="m-0 text-dark">{{ __('CONTROL DE INVENTARIO DE TRANSPORTES (INSUMOS)') }}</h1>
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
                <h3 class="d-none d-sm-block">{{ __('Compras') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Compras') }}</h4>
                <h4>{{ $estadisticas->comprasAnio }}&nbsp;&nbsp;<small><small><small>{{ __('Este Año') }}</small></small></small></h4>
                <h4>{{ $estadisticas->comprasMes }}&nbsp;&nbsp;<small><small><small>{{ __('Este mes') }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('compras.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-8 mx-auto">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="d-none d-sm-block">{{ __('Entregas') }}</h3>
                <h4 class="d-block d-sm-none">{{ __('Entregas') }}</h4>
                <h4>{{ $estadisticas->entregasAnio }}&nbsp;&nbsp;<small><small><small>{{ __('Este Año') }}</small></small></small></h4>
                <h4>{{ $estadisticas->entregasMes }}&nbsp;&nbsp;<small><small><small>{{ __('Este mes') }}</small></small></small></h4>
              </div>
              <div class="icon">
                <i class="ion ion-thumbsup"></i>
              </div>
              <a href="{{ route('entregas.index') }}" class="small-box-footer">{{ __('Más información') }} <i class="fas fa-arrow-circle-right"></i></a>
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
