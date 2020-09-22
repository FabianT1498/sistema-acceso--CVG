<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<meta name="generator" content="LibreOffice 6.0.7.3 (Linux)"/>
	<meta name="created" content="2020-02-12T09:45:57.923838174"/>
	<meta name="changed" content="2020-03-16T12:14:27.234346297"/>
	<style type="text/css">
		@page { margin: 0.25cm; margin-left: 1cm; margin-right: 1cm; }
		p { margin-left: 0.25cm; line-height: 120% }
		td p { margin-bottom: 0cm }
	</style>
</head>
<body lang="es-VE" dir="ltr">
<table width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="20%">
			<p align="center">
				<img width="150px" src="{{ asset('img/logocvg_150x150.jpg') }}"/>
			</p>
		</td>
		<td width="80%">
			<font size="7" style="font-size: 40pt">{{ __('Reporte de Entregas') }}</font></p>
		</td>
	</tr>
</table>
<div style="border-left: 1px solid #555; border-top: 1px solid #555; border-right: 1px solid #555;">
	<p style="margin-bottom: 0cm; line-height: 100%">
		<font size="3" style="font-size: 13pt">
			<strong>Analista</strong>: {{ ucwords(auth()->user()->firstname) }} {{ ucwords(auth()->user()->lastname) }}
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<strong>Creado</strong>: {{ (Carbon\Carbon::now())->format('d-m-Y H:i:s') }}
		</font>
	</p>
	<p style="margin-bottom: 0cm; line-height: 100%">
		<strong>Grupo</strong>: @if ($group)
								{{ strtoupper($group->name) }}
								@else
								TODOS
								@endif
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<strong>Rango de Fecha:</strong> {{ $start_date_query ? $start_date_query->format('d-m-Y') : "" }} - {{ $finish_date_query ? $finish_date_query->format('d-m-Y') : "" }}
	</p>
	<p style="margin-bottom: 0cm; line-height: 100%">
		<strong>Empresa</strong>:	@if ($company)
									{{ strtoupper($company->name) }}
									@else
									TODAS
									@endif
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		@if (Auth::user()->role->name == "ADMIN" || Auth::user()->role->name == "SUPERADMIN")
		<strong>Analista</strong>:	@if ($analyst)
									{{ $analyst->firstname }}&nbsp;{{ $analyst->lastname }}&nbsp;({{ $analyst->dni }})
									@else
									TODOS
									@endif
		@endif
	</p>
	<br>
</div>
<table width="100%" cellpadding="4" cellspacing="0">
	<col width="22%">
	<col width="27%">
	<col width="35%">
	<col width="16">
	<tr valign="top">
		<td width="15%" style="border-top: 1px solid #555; border-bottom: 1px solid #555; border-left: 1px solid #555; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0cm; padding-right: 0cm">
			<p style="text-align: center;"><strong>Nº Entrega</strong></p>
		</td>

		@if (!$analyst)
		<td width="15%" style="border-top: 1px solid #555; border-bottom: 1px solid #555; border-left: 1px solid #555; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
			<p style="text-align: center;"><strong>Analista</strong></p>
		</td>
		@endif

		@if (!$company)
		<td width="15%" style="border-top: 1px solid #555; border-bottom: 1px solid #555; border-left: 1px solid #555; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
			<p style="text-align: center;"><strong>Empresa</strong></p>
		</td>
		@endif
		<td width="15%" style="border-top: 1px solid #555; border-bottom: 1px solid #555; border-left: 1px solid #555; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
			<p style="text-align: center;"><strong>Descripción</strong></p>
		</td>
		<td width="15%" style="border-top: 1px solid #555; border-bottom: 1px solid #555; border-left: 1px solid #555; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
			<p style="text-align: center;"><strong>Item</strong></p>
		</td>
		<td width="15%" style="border-top: 1px solid #555; border-bottom: 1px solid #555; border-left: 1px solid #555; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
			<p style="text-align: center;"><strong>Cant</strong></p>
		</td>
		<td width="15%" style="border: 1px solid #555; padding: 0.1cm">
			<p style="text-align: center;"><strong>Total $</strong></p>
		</td>
	</tr>
	@foreach ($registros as $registro)
		<tr valign="top">
			<td width="15%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
				<p style="text-align: center;">{{ $registro->control_number }}</p>
			</td>
			@if (!$analyst)
			<td width="15%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
				<p style="text-align: center;">{{ $registro->deliverer }} ({{ $registro->dni_deliverer }})</p>
			</td>
			@endif
			@if (!$company)
			<td width="15%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
				<p style="text-align: center;">{{ $registro->company_name }}</p>
			</td>
			@endif
			<td width="25%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
				<p>{{ $registro->description }}</p>
			</td>
			<td width="15%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
				<p>@if (!$group)
					{{ $registro->group }}: {{ $registro->item }}
				@endif</p>
			</td>
			<td width="10%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
				<p>{{ $registro->quantity }}</p>
			</td>
			<td width="15%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0.3cm; text-align: right;">
				<p>{{ number_format($registro->total, 2) }}</p>
			</td>
		</tr>
	@endforeach
</table>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
</body>
</html>