<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
  
    <style type="text/css">

        * {
          box-sizing: border-box;
        }

        body * { 
			box-sizing: border-box;
			font-size: 16px;
        }

		/* 9.30 cm x 7.19 cm */
        .container {
			margin: 0 auto;
			padding: 1rem;
			border: 2px solid;
			width:351.49px;
			height: 272.12px;
        }

        .img {
			width: 50px;
          	height: 25px;
		}
		
		.left {
			float: left;
		}

		.right {
			float: right;
		}

		.clearfix::after {
			content: "";
			clear: both;
			display: table;
		}

		.item {
			margin-bottom: 0.5rem;
		}


    </style>
  </head>
  <body>
        <div class="container">

			<div class="clearfix item">
				<div class="left">
					<img class="img" src="{{ public_path('img/logocvg.jpg') }}" alt="">
				</div>

				<div class="right">
					<span>Fecha de emision:</span>
					&nbsp;{{ date('Y-m-d H:i') }}
				</div>		
			</div>

			<div class="clearfix item">
				<div class="left">
					<span>Visitante:</span>
					&nbsp;{{$record->visitor_firstname}}&nbsp;{{$record->visitor_lastname}}
				</div>

				<div class="right">
					<span>Cedula:</span>
					&nbsp;{{$record->visitor_dni}}
				</div>
			</div>

			<div class="clearfix item">
				<div class="left">
					<span>Trabajador:</span>
					&nbsp;{{$record->worker_firstname}}&nbsp;{{$record->worker_lastname}}
				</div>

				<div class="right">
					<span>Cedula:</span>
					&nbsp;{{$record->worker_dni}}
				</div>
			</div>

			<div class="clearfix item">
				<div class="left">
					<span>Fecha de asistencia:</span>
					&nbsp;{{date('d-m-Y', strtotime($record->date_attendance))}}
				</div>
			</div>

			<div class="clearfix item">
				<div class="left">
					<span>Hora de entrada:</span>
					&nbsp;{{ date('H:i', strtotime($record->entry_time)) }}
				</div>
				<div class="right">
					<span>Hora de salida</span>
					&nbsp;{{ date('H:i', strtotime($record->departure_time)) }}
				</div>
			</div>

			<div class="clearfix item">
				<div class="left">
					<span>Edificio</span>
					&nbsp;{{ $record->building }}
				</div>
				<div class="right">
					<span>Departamento</span>
					&nbsp;{{ $record->department }}
				</div>
			</div>

			<div class="clearfix item">
				<div class="left">
					<span>Autorizado por:</span>
					&nbsp;{{ Auth::user()->worker->firstname . ' ' . Auth::user()->worker->lastname }}
				</div>
				<div class="right">
					<span>Cedula:</span>
					&nbsp;{{Auth::user()->worker->dni}}
				</div>
			</div>

          @if ($record->auto_enrrolment)
		  	<div class="clearfix item">
			  	<div class="left">
					<span>Auto:</span>
					&nbsp;{{$record->auto_model_name}}
				</div>

				<div class="right">
					<span>Matricula:</span>
					&nbsp;{{$record->auto_enrrolment}}
				</div>  
			</div>
          @endif

        </div>
  </body>
</html>