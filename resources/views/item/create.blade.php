@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/items.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('items') }}">{{ __('ITEMS') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('PRODUCTOS') }}
     <span class="text-primary">({{ __('Nuevo') }})</span>
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
  @include('item.head')
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
              <form method='POST' action="{{ route('productos.store') }}"  role="form">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="group">{{ _('Grupo') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="group" name="group_id" class="form-control">
                      <option value=""> Grupo...</option>
                      @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="sub_group">{{ _('Subgrupo') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="sub_group" name="sub_group_id" class="form-control">
                      <option value=""> SubGrupo...</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="type">{{ _('Tipo') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="type" name="type_id" class="form-control">
                      <option value=""> Tipo...</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="presentation">{{ _('Presentación') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <select id="presentation" name="presentation_id" class="form-control">
                      <option value=""> Presentación...</option>
                      @foreach ($presentations as $presentation)
                        <option value="{{ $presentation->id }}">{{ $presentation->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="description">{{ _('Descripción del Item') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="description" name="description" type="text" class="form-control" placeholder="{{ __('Ingrese Descripción') }}" value="{{ old('name') }}" required>
                  </div>
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
