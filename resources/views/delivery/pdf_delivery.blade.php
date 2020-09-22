<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<meta name="generator" content="LibreOffice 6.0.7.3 (Linux)"/>
	<meta name="created" content="2020-02-27T11:41:11.913815834"/>
	<meta name="changed" content="2020-02-27T12:49:19.177667025"/>
	<style type="text/css">
		@page { size: 21.59cm 27.94cm; margin-left: 1cm; margin-right: 1cm }
		p { margin-bottom: 0.25cm; line-height: 115% }
		td p { margin-bottom: 0cm }
	</style>
</head>
<body lang="es-ES" dir="ltr">
<center>
	<table width="100%" cellpadding="4" cellspacing="0">
		<col width="51*">
		<col width="154*">
		<col width="51*">
		<tr>
			<td width="20%" style="border: none; padding: 0cm">
				<p align="center"><img width="200px" src="{{ asset('img/logocvg_150x150.jpg') }}"/>
					<br/>
				</p>
			</td>
			<td width="50%" style="border: none; padding: 0cm">
				<font size="6" style="font-size: 26pt">
					<p align="center"><strong>Nota de Entrega</strong></p>
				</font>
			</td>
			<td width="30%" style="border: none; padding: 0cm">
				<p align="left"><strong>Nro</strong>: {{ $registro->control_number }}</p>
			</td>
		</tr>
	</table>
</center>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<center>
	<table width="100%" cellpadding="4" cellspacing="0">
		<col width="205*">
		<col width="51*">
		<tr valign="top">
			<td width="80%" style="border: none; padding: 0cm">
				<p><strong>Empresa</strong>: {{ $registro->company->dni }} - {{ $registro->company->name }}</p>
			</td>
			<td width="20%" style="border: none; padding: 0cm">
				<p align="left"><strong>Fecha</strong>: {{ date('d-m-Y', strtotime($registro->delivered_date)) }}</p>
			</td>
		</tr>
		<tr valign="top">
			<td width="80%" style="border: none; padding: 0cm">
				<p><strong>Unidad</strong>: {{ $registro->unity }}</p>
			</td>
			<td width="20%" style="border: none; padding: 0cm">
				<p align="left"><strong>Almacén</strong>: {{ $registro->location->name }}</p>
			</td>
		</tr>
		<tr valign="top">
			<td width="80%" style="border: none; padding: 0cm">
				<p><strong>Descripción</strong>: {{ $registro->description }}
				</p>
			</td>
			<td width="20%" style="border: none; padding: 0cm">
				<p><br/>

				</p>
			</td>
		</tr>
	</table>
</center>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<center>
	<table width="100%" cellpadding="4" cellspacing="0">
		<col width="38*">
		<col width="38*">
		<col width="179*">
		<tr valign="top">
			<td width="15%" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
				<p align="center"><strong>Cantidad</strong></p>
			</td>
			<td width="15%" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
				<p align="center"><strong>ID</strong></p>
			</td>
			<td width="70%" style="border: 1px solid #000000; padding: 0.1cm; padding-left: 0.5cm">
				<p ><strong>Item</strong></p>
			</td>
		</tr>
		@foreach($details as $detail)
			<tr valign="top">
				<td width="15%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
					<center>
						<p>
							{{ $detail->quantity }}
						</p>
					</center>
				</td>
				<td width="15%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
					<center>
						<p>
							{{ $detail->id }}
						</p>
					</center>
				</td>
				<td width="70%" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.5cm; padding-right: 0.1cm">
					<p>
						{{ $detail->item->description }}
					</p>

				</td>
			</tr>
		@endforeach
	</table>
</center>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
<center>
	<table width="100%" cellpadding="4" cellspacing="0">
		<col width="128*">
		<col width="128*">
		<tr valign="top">
			<td width="50%" style="border: none; padding: 0cm">
				<p align="center">Entregado por:</p>
			</td>
			<td width="50%" style="border: none; padding: 0cm">
				<p align="center">Recibido por:</p>
			</td>
		</tr>
	</table>
</center>
<p style="margin-bottom: 0cm; line-height: 100%"><br/>

</p>
</body>
</html>