@extends('layouts.app')

@section('mascss')
  <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
@endsection


@section('masjs')
  <script src="{{ asset('js/toastr.min.js') }}"></script>

  @toastr_render
  <script src="{{ asset('js/insumo.js') }}"></script>
  <script src="{{ asset('js/report.js') }}"></script>
@endsection

@section('migasdepan')
    <a href="{{ route('reportes.index') }}">{{ __('REPORTES') }}</a>
    &nbsp;&nbsp;<i class="icon ion-android-arrow-forward"></i>&nbsp;&nbsp;{{ __('REPORTES') }}
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
	@include('report.head')
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
						<form method='POST' action="{{ route('reportes.update', $report->report_id) }}"  role="form" enctype="multipart/form-data">
							@csrf
							@method('PUT')

							<div class="card-body">

								<div class="form-group">
									<label for="visitorSearch">{{ _('Nombre del visitante') }}&nbsp;<sup class="text-danger">*</sup></label>
									<input 
										id="visitorSearch"
										name="visitor_name"
										type="text"
										class="form-control"
										placeholder="{{ __('Ingrese Nombre') }}"
										value="{{$report->visitor_firstname.' '.$report->visitor_lastname }}" 
										required
									>
									<input type="hidden" id='visitorID' name="visitor_id" value="{{$report->visitor_id}}" readonly>
								</div>

								<div class="form-group">
									<label for="visitorDNI">{{ _('Cédula del visitante') }}&nbsp;<sup class="text-danger">*</sup></label>
									<input 
										id="visitorDNI" 
										name="visitor_dni" 
										type="text" 
										class="form-control" 
										style="text-transform:uppercase" 
										placeholder="{{ __('Ingrese Cedula') }}" 
										value="{{$report->visitor_dni}}"
										readonly 
										required
									>
								</div>

								<div class="form-group">
									<label for="workerSearch">{{ _('Nombre del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
									<input 
										id="workerSearch" 
										name="worker_name" 
										type="text" 
										class="form-control" 
										placeholder="{{ __('Ingrese Nombre') }}"
										value="{{$report->worker_firstname.' '.$report->worker_lastname }}" 
										required
									>
									<input type="hidden" id='workerID' name="worker_id" value="{{$report->worker_id}}" readonly>
								</div>

								<div class="form-group">
									<label for="workerDNI">{{ _('Cédula del trabajador') }}&nbsp;<sup class="text-danger">*</sup></label>
									<input 
										id="workerDNI"
										name="worker_dni"
										type="text"
										class="form-control"
										style="text-transform:uppercase"
										placeholder="{{ __('Ingrese Cedula') }}"
										value="{{$report->worker_dni}}"
										readonly 
										required
									>
								</div>

								<div class="form-group">
									<label for="attendingDate">{{ _('Fecha y hora de asistencia:') }}&nbsp;</label>
									<input 
										type="text" 
										class="form-control" 
										id="attendingDate" 
										name="attending_date" 
										placeholder="Fecha y hora de asistencia"
										value="{{$report->date_attendance}}"
									>
								</div>

								<div class="form-group">
									<label for="autoSelect">{{ _('Auto aparcado:') }}&nbsp;</label>
									<select id="autoSelect" name="auto_id" class="form-control">
										<option value="-1">Ninguno</option>
										@if($report->auto_id)
											<option selected value="{{$report->auto_id}}">{{$report->auto_model_name. ' '. $report->auto_enrrolment}}</option>		
										@endif
										@foreach ($autos as $auto)
											<option value="{{$auto->auto_id}}">{{$auto->auto_model_name. ' '. $auto->auto_enrrolment}}</option>
										@endforeach
									</select>
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
		</section>
    <!-- /.content -->
  	</div>
  <!-- /.content-wrapper -->
@endsection
