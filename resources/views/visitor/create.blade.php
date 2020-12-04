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
            
              <form method='POST' action="{{ route('visitantes.store') }}"  role="form"  enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="card">
                  <!-- form start -->
                  @include('visitor.inputs', ['is_form_report' => false])
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" class="btn btn-success">{{ __('Crear Registro') }}</button>
                  </div>
                </div>

              </form>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include('layouts.error-modal')
@endsection
