@extends('layouts.app')

@section('masjs')
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/user.js') }}"></script>
  @endsection

@section('migasdepan')
    <a href="{{ route('usuarios.index') }}">{{ __('VISITANTES') }}</a>
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
  @include('user.head')
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
            <div class="card card-primary">
              <!-- form start -->
              <form method='POST' action="{{ route('visitantes.update', $visitor->id) }}"  role="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                  <div class="form-group">
                    <label for="firstname">{{ _('Nombre de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="firstname" name="firstname" type="text" class="form-control" placeholder="{{ __('Ingrese Nombre') }}" value="{{ $visitor->firstname }}" required>
                  </div>
                  <div class="form-group">
                    <label for="lastname">{{ _('Apellido de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="lastname" name="lastname" type="text" class="form-control" placeholder="{{ __('Ingrese Apellido') }}" value="{{ $visitor->lastname }}" required>
                  </div>
                  <div class="form-group">
                    <label for="dni">{{ _('Cédula de la persona') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="dni" name="dni" type="text" class="form-control" style="text-transform:uppercase" placeholder="{{ __('Ingrese Cedula') }}" value="{{ $visitor->dni }}" required>
                  </div>
                  <div class="form-group">
                    <label for="phone_number">{{ _('Telefono') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <input id="phone_number" name="phone_number" type="text" class="form-control" placeholder="{{ __('Ingrese numero') }}" value="{{ $visitor->phone_number }}" required>
                  </div>
                  <table class="table table-bordered table-striped" id="product_table">
                    <thead>
                      <tr>
                        <th>{{ __('Modelo de auto') }}</th>
                        <th>{{ __('Color') }}</th>
                        <th>{{ __('Placa') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($autos->get() as $auto)
                        <tr>
                            <td>            
                              <select id="auto_model" name="auto_model" class="form-control">
                                <option selected value="{{$auto->auto_model_id}}"> {{$auto->name}}</option>
                                @foreach ($auto_models as $auto_model)
                                  @if ($auto_model->id == $auto->auto_model_id)
                                    <option value="{{$auto->auto_model_id}}" selected>{{ $auto->name  }}</option>
                                  @else
                                    <option value="{{ $auto_model->id }}"> {{ $auto_model->name }}</option>
                                  @endif
                                @endforeach
                              </select>
                            </td>
                            <td><input id="color_{{ $auto->id }}" name="color" value="{{ $auto->color }}" type="text" class="form-control" required ></td>
                            <td><input id="enrrolment_{{ $auto->id }}" name="enrrolment" value="{{ $auto->enrrolment }}" type="text" class="form-control" required ></td>
        
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
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
