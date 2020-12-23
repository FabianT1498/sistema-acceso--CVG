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
			font-size: 14px;
        }

		.border-bottom {
			border-bottom: 0.5px solid #000;
		}

		/* 10 cm x 9.26 cm */
        .container {
			margin: 0 auto;
			padding: 1rem;
			border: 2px solid;
			width: 377.95275591px;
			height: 350px;
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
			margin-bottom: 0.2rem;
		}

		.items-container {
			margin-bottom: 0.4rem;
		}

		.items-container:last-of-type {
			margin-bottom: 2rem;
		}

		.items-container .item:last-child {
			margin-bottom: 0;
		}

		.signature:before {
			display: block;
			height: 1px;
			background-color: #000;
			content: ' ';
			width: 200px;
			margin-bottom: 5px;
		}

    </style>
  </head>
  <body>
        <div class="container">

			<div class="items-container border-bottom">
				<div class="clearfix item">
					<div class="left">
						<img class="img" src="{{ public_path('img/logocvg.jpg') }}" alt="">
					</div>

					<div class="right">
						<b>Fecha de emision:</b>
						&nbsp;{{ date('d-m-Y H:i') }}
					</div>
				</div>
				
				<div class="clearfix item">
					<div class="left">
						<b>Nro de solicitud:</b>
						&nbsp;{{ $record->id }}
					</div>
				</div>
			</div>

			<div class="items-container border-bottom">
				<div class="clearfix item">
					<div class="left">
						<b>Visitante:</b>
						&nbsp;{{ ucwords($record->visitor_firstname . ' '. $record->visitor_lastname) }}
					</div>
				</div>

				<div class="clearfix item">
					<div class="left">
						<b>Cedula:</b>
						&nbsp;{{$record->visitor_dni}}
					</div>
				</div>	
			</div>

			<div class="items-container border-bottom">
				<div class="clearfix item">
					<div class="left">
						<b>Autorizado por:</b>
						&nbsp;{{ucwords($record->worker_firstname. ' '. $record->worker_lastname)}}
					</div>
				</div>
			</div>

			<div class="items-container border-bottom">
				<div class="clearfix item">
					<div class="left">
						<b>Fecha de asistencia:</b>
						&nbsp;{{date('d-m-Y', strtotime($record->date_attendance))}}
					</div>
				</div>

				<div class="clearfix item">
					<div class="left">
						<b>Hora de entrada:</b>
						&nbsp;{{ date('H:i', strtotime($record->entry_time)) }}
					</div>
					<div class="right">
						<b>Hora de salida</b>
						&nbsp;{{ date('H:i', strtotime($record->departure_time)) }}
					</div>
				</div>			
			</div>

			<div class="items-container border-bottom">
				<div class="clearfix item">
					<div class="left">
						<b>Edif.:</b>
						&nbsp;{{ $record->building }}
					</div>
					<div class="right">
							<b>Dpto.:</b>
							&nbsp;{{ $record->department }}
					</div>
				</div>
			</div>

			<div class="items-container border-bottom">
				<div class="clearfix item">
					<div class="left">
						<b>Emitido por:</b>
						&nbsp;{{ ucwords(Auth::user()->worker->firstname . ' ' . Auth::user()->worker->lastname) }}
					</div>
				</div>
			</div>

			@if ($record->auto_enrrolment)
				<div class="items-container border-bottom">
					<div class="clearfix item">
						<div class="left">
							<b>Datos del Auto:</b>	
						</div>
					</div>
					<div class="clearfix item">
						<div class="left">
							<b>Modelo Auto:</b>
							&nbsp;{{$record->auto_model}}
						</div>

						<div class="right">
							<b>Matricula:</b>
							&nbsp;{{$record->auto_enrrolment}}
						</div>  
					</div>
				</div>
			@endif

			<div class="signature">
				Firma autorizada y sello:
			</div>
			
        </div>
  </body>
</html>