@extends('layouts.app')

@section('mascss')
  
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>

  @toastr_render
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/visit.js') }}"></script>
  <script src="{{ asset('js/autos.js') }}"></script>
  <script src="{{ asset('js/visitor.js') }}"></script>
@endsection

@include('visit.nav_route')

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
  @yield('head_visit')
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
            
              <!-- form start -->
            <form method='POST' action="{{ route('visitas.update', $record->id) }}"  role="form"  enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="card">
                <div class="card-body">
                  <h3 class="h3 mb-md-5 text-center title-subline">Cedula del visitante</h3>
                  <div class="form-group row mb-md-4">
                    <label class="col-md-3 col-form-label" for="visitorDNI">{{ _('Cédula del visitante:') }}&nbsp;<sup class="text-danger">*</sup></label>
                    <div class="col-md-3">
                      <input 
                        id="visitorDNI" 
                        name="visitor_dni" 
                        type="text" 
                        class="form-control" 
                        style="text-transform:uppercase" 
                        placeholder="{{ __('Ingrese Cedula') }}"
                        value="{{ old('visitor_dni') ? old('visitor_dni') : $record->visitor_dni }}"
                        autocomplete="off"  
                        required
                      > 
                      <input type="hidden" id="visitorID" name="visitor_id" value="{{ old('visitor_id') ? old('visitor_id') : $record->visitor_id }}" readonly>
                    </div>
                    <div id="visitorLoader"class="col-md-1">
                      <div class="mt-md-2 loading d-none"></div> 
                    </div>
                    <div id="visitorResult" class="col-md-5">
                      <p class="text-uppercase mt-md-2">{{$record->visitor_firstname. ' '. $record->visitor_lastname }}</p>
                    </div>     
                  </div>
                </div>
              </div>

              <div class="card" id="visitorData">
                @if (old('visitor_id') === '-1')
                  @include('visitor.inputs', ['is_form_visit'=>true, 'is_show_view' => false])
                @endif
              </div>

              <div class="card">
                <div class="card-body">
                  <h3 class="h3 mb-md-5 text-center title-subline">Datos de la visita</h3>
                  
                  @if (Auth::user()->role_id === 4 && $is_my_visit === 0)
                    <div class="form-group row mb-md-4">
                      <label class="col-md-3 col-form-label" for="workerDNI">{{ _('Cédula del trabajador:') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <div class="col-md-3">
                        <input 
                          id="workerDNI" 
                          name="worker_dni" 
                          type="text" 
                          class="form-control" 
                          style="text-transform:uppercase" 
                          placeholder="{{ __('Ingrese Cedula') }}"
                          autocomplete="off"
                          value="{{ old('worker_dni') ? old('worker_dni') : $record->worker_dni  }}" 
                          required
                        > 
                        <input type="hidden" id="workerID" name="worker_id" value="{{ old('worker_id') ? old('worker_id') : $record->worker_id }}" readonly>
                      </div>
                      <div id="workerLoader"class="col-md-1">
                        <div class="mt-md-2 loading d-none"></div> 
                      </div>
                      <div id="workerResult" class="col-md-5"></div>      
                    </div>
                  @else
                    <input type="hidden" id="workerDNI" name="worker_dni" value="{{Auth::user()->worker->dni}}" readonly>
                    <input type="hidden" id="workerID" name="worker_id" value="{{Auth::user()->worker_id}}" readonly>
                  @endif

                  <div class="form-row mb-md-4">
                    <div class="form-group col-md-3">
                      <label for="attendingDate">{{ _('Fecha de asistencia:') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input 
                        type="text" 
                        class="form-control" 
                        id="attendingDate" 
                        name="attending_date" 
                        placeholder="Fecha de asistencia"
                        value="{{ old('attending_date') ? old('attending_date') : date('d-m-Y', strtotime($record->date_attendance))  }}"
                        required
                      >                   
                    </div>
                    <div class="form-group col-md-3">
                      <label for="entry-time">{{ _('Hora de entrada:') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input 
                        type="text" 
                        class="form-control time-picker" 
                        id="entry-time" 
                        name="entry_time" 
                        value="{{ old('entry_time') ? old('entry_time') : date('H:i', strtotime($record->entry_time)) }}"
                        placeholder="Hora de entrada"
                        required
                      >                   
                    </div>
                    <div class="form-group col-md-3">
                      <label for="departure-time">{{ _('Hora de salida:') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input 
                        type="text" 
                        class="form-control time-picker" 
                        id="departure-time" 
                        name="departure_time"
                        value="{{ old('departure_time') ? old('departure_time') : date('H:i', strtotime($record->departure_time)) }}"
                        placeholder="Hora de salida"
                        required
                      >                   
                    </div>
                  </div>

                  <div class="form-row mb-md-4">
                    <div class="form-group col-md-3">
                      <label for="building">{{ _('Edificio:') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input 
                        type="text" 
                        class="form-control" 
                        id="building" 
                        name="building" 
                        placeholder="Edificio"
                        value="{{ old('building') ? old('building') : $record->building_name  }}"
                        required
                      >                   
                    </div>
                    <div class="form-group col-md-3">
                      <label for="department">{{ _('Departamento:') }}&nbsp;<sup class="text-danger">*</sup></label>
                      <input 
                        type="text" 
                        class="form-control" 
                        id="department" 
                        name="department" 
                        value="{{ old('department') ? old('department') : $record->department_name  }}"
                        placeholder="Departamento"
                        required
                      >                   
                    </div>
                  </div>

                  <div class="form-group row mb-md-4">
                    <label class="col-md-4 col-form-label" for="thereIsAutoOpt">{{ _('El visitante ha aparcado un auto:') }}</label>  
                    <div class="form-check form-check-inline">
                      <input 
                        class="form-check-input" 
                        type="radio" 
                        id="thereIsAutoOpt" 
                        name="auto_option" 
                        value="1" 
                        {{ ( old('auto_option') && old('auto_option') === '1' 
                            ? "checked" 
                            : (isset($record) && !is_null($record->auto_id) 
                                ? "checked" 
                                : '' )) }}
                      >
                      <label class="form-check-label" for="thereIsAutoOpt">Si</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input 
                        class="form-check-input"
                        type="radio" 
                        id="noAutoOpt" 
                        name="auto_option" 
                        value="0" 
                        {{ ( old('auto_option') && old('auto_option') === '0' 
                            ? "checked" 
                            : (isset($record) && is_null($record->auto_id) 
                                ? "checked" 
                                : '' )) }}
                      >
                      <label class="form-check-label" for="noAutoOpt">No</label>
                    </div>                  
                  </div>
                  
                  <div class="form-group" id="autoData">
                    @if (old('auto_option') === '1' || !is_null($record->auto_id))
                      @include('auto.inputs')
                    @endif
                  </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                  <button id="visitBtnSubmit" type="submit" class="btn btn-success">{{ __('Editar visita') }}</button>
                </div>
              </div>

              <!--
              <div class="card-body">
      
                </div>

                
              </div>
            -->
            
            <!-- /.card -->
          

              <!-- /.card-body 
              <div class="card-footer">
                <button type="submit" class="btn btn-success">{{ __('Crear Registro') }}</button>
              </div>
              -->
            </form>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('layouts.error-modal')
@endsection
