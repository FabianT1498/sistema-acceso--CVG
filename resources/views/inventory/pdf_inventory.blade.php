<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title></title>
	<style type="text/css">
		@page { size: 21.59cm 27.94cm; margin: 0.5cm }
		p { margin-bottom: 0.25cm; line-height: 115% }
		td p { margin-bottom: 0cm }
	</style>
</head>
<body lang="es-ES" dir="ltr">
<center>
	<table width="100%" cellpadding="4" cellspacing="0">
		<tr>
			<td style="border: none; padding: 0cm; text-align: left;">
				<img width="300px" src="{{ asset('img/logocvg_150x150.jpg') }}"/>
			</td>
			<td style="border: none; padding: 0cm">
			</td>
			<td width="40%" style="border: none; padding: 0cm">
				<div align="right">
					<table width="283" cellpadding="4" cellspacing="0">
						<col width="273">
						<tr>
							<td width="273" height="32" valign="top" style="border: 1px solid #000000; padding: 0.1cm">
								<p>
									Fecha Inicio:
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Fecha Fin:
								</p>
							</td>
						</tr>
						<tr>
							<td width="273" height="33" valign="top" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0.1cm">
								<p>
									<strong>Realizado por</strong>: {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									
									Firma:
								</p>
							</td>
						</tr>
						<tr>
							<td width="273" height="33" valign="top" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-top: 0cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0.1cm">
								<p>
									<strong>Supervisor</strong>:
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;
									Firma:
								</p>
							</td>
						</tr>
					</table>
				</div>
				<p><br/></p>
			</td>
		</tr>
	</table>
</center>
<p align="center" style="margin-bottom: 0cm; font-weight: normal; line-height: 100%">
	<font size="5" style="font-size: 20pt;">Inventario: {{ $registro->description }}</font>
</p>


	@foreach ($items as $item)
		<p><font size="4" style="font-size: 16pt;">
			<strong>Producto</strong>: {{ $item->description }}
		</font></p>
		<table width="100%"cellspacing="0">
			@php $total = 0; @endphp
			@foreach ($details as $detail)
				@if ($item->id === $detail->item_id)
					@php
                      $total += $detail->quantity_stock;
                    @endphp
					<tr valign="center">
						<td width="50%" height="25" style="border-top: 1px solid #000000; border-bottom: none; border-left: 1px solid #000000; border-right: none; padding-top: 0.2cm; padding-left: 0.1cm; padding-right: 0cm">
							<strong>Lote:</strong> {{ $detail->invoice_detail->invoice->description }}
						</td>
						<td style="border-top: 1px solid #000000; border-bottom: none; border-left: none; border-right: none; padding-top: 0.2cm; padding-left: 0.1cm; padding-right: 0cm">
							<strong>Stock</strong>: <span style="font-size: 1.1rem">{{ $detail->quantity_stock }}</span>
						</td>
						<td style="border-top: 1px solid #000000; border-bottom: none; border-left: none; border-right: 1px solid #000000;; padding-top: 0.2cm; padding-left: 0.1cm; padding-right: 0cm">
							<strong>Existencia</strong>:
						</td>
					</tr>
					<tr>
						<td colspan="3" width="100%" valign="top" style="border-top: none; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0.1cm">
							<strong>Observación:</strong>
						</td>
					</tr>
				@endif
			@endforeach
			<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr>
				<td width="50%" height="25" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: .5cm; text-align: center;">
					<strong>Totales</strong>
				</td>
				<td width="25%" height="25" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: none; border-right: none; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
					<strong>Stock</strong>: {{ $total }}
				</td>
				<td width="25%" height="25" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: none; border-right: 1px solid #000000; padding-top: 0.1cm; padding-bottom: 0.1cm; padding-left: 0.1cm; padding-right: 0cm">
					<strong>Existencia</strong>:
				</td>
			</tr>
		</table>
	@endforeach
</body>
</html>