@extends('layouts.app')

@section('mascss')
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  @toastr_render
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/visitor.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('visitantes.index') }}">{{ __('VISITANTES') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('VISITANTES') }}
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
  @include('visitor.head')
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
              <form method='POST' action="{{ route('visitantes.update', $visitor->id) }}"  role="form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                  <h3 class="h3 mb-md-5 text-center title-subline">Datos del visitante</h3>
                  <div class="form-row mb-md-4">

                      <div class="form-group col-md-4">
                          <label for="visitorFirstname">Nombre(s):&nbsp;<sup class="text-danger">*</sup></label>
                          <p>{{$visitor->firstname}}</p>            
                      </div>

                      <div class="form-group col-md-4">
                          <label for="visitorLastname">Apellido(s):&nbsp;<sup class="text-danger">*</sup></label>
                          <p>{{$visitor->lastname}}</p>                
                      </div>

                      <div class="form-group col-md-4">
                          <label for="visitorDNI">Cedula del visitante:&nbsp;<sup class="text-danger">*</sup></label>
                          <p>{{$visitor->dni}}</p>              
                      </div>
                  </div>

                  <div class="form-row">

                    <div class="form-group col-md-4">
                      <label for="visitorPhoneNumber">Telefono:&nbsp;<sup class="text-danger">*</sup></label>
                      <input 
                        type="text" 
                        class="form-control" 
                        id="visitorPhoneNumber" 
                        name="visitor_phone_number" 
                        value="{{$visitor->phone_number}}"
                        placeholder="Telefono del visitante"
                        required
                      >
                    </div>

                    <div class="form-group col-md-4">
                        <label for="file">Foto del visitante &nbsp;<sup class="text-danger">*</sup></label>
                        <input type="file" name="image" class="file" accept="image/*">
                        <div class="input-group">
                            <input type="text" class="form-control" disabled placeholder="Subir Foto" id="file">
                            <div class="input-group-append">
                                <button type="button" class="browse btn btn-primary">Buscar...</button>
                            </div>
                        </div>
                    </div>
                      <div class="form-group col-md-2 ml-md-4">                      
                        @if (!is_null($photo))
                          <img src="{{ Storage::url($photo->path) }}" id="preview" class="img-thumbnail">
                        @else
                          <img src="" id="preview" class="img-thumbnail">
                        @endif                   
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
