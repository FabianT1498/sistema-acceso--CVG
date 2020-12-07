@extends('layouts.app')

@section('mascss')
  
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>

  @toastr_render
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/autos.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('autos.index') }}">{{ __('Autos') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('Autos') }}
     <span class="text-success">({{ __('Crear') }})</span>
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
  @include('auto.head')
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
            @if ($errors->any())
              <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>
                              {{ $error }}
                          </li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('autos.update', $auto->id) }}"  role="form"  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">

                  <h3 class="h3 mb-md-5 text-center title-subline">Datos del auto</h3>
                  <div class="form-row mb-md-4">
                      <div class="form-group col-md-3">
                          <label for="autoEnrrolment">Matricula del auto:&nbsp;</label>
                          <p>{{$auto->enrrolment}}</p>                 
                          <input 
                              type="hidden" 
                              id="autoID" 
                              name="auto_id" 
                              value="{{$auto->id}}"
                              readonly
                          >
                      </div>
                      <div class="form-group col-md-3">
                          <label for="autoBrand">Marca del auto:</label>
                          <p>{{$auto->brand}}</p>           
                      </div>
                      <div class="form-group col-md-3">
                          <label for="autoModel">Modelo del auto:</label>
                          <p>{{$auto->model}}</p>               
                      </div>
                  </div>
                      
                  <div class="form-row">
                      <div class="form-group col-md-3">
                          <label for="autoColor">Color:</label>
                          <input 
                              type="text" 
                              class="form-control" 
                              id="autoColor" 
                              name="auto_color" 
                              value="{{$auto->color}}"
                              placeholder="COLOR DEL AUTO"
                          >                   
                      </div> 
                  </div>
                </div>

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
