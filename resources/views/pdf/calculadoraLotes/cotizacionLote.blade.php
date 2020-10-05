<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>COTIZACIÓN DE LOTE CON SERVICIOS</title>
    <!-- Styles -->
    
</head>

<style>

body{
 font-size: 11pt;
 text-align: justify;
}

@page{
    margin: 55px;
    margin-right: 60px;
    margin-left: 60px;
}

.table-cell3 { display: table-cell; padding: 0em; font-size: 10pt; }
.table3 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row; }

.text-right{ text-align:right !important;}

.myTable {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.myTable td, .myTable th {
  border: 1px solid #ddd;
  padding: 4px;
}

.myTable tr:nth-child(even){background-color: #f2f2f2;}

.myTable tr:hover {background-color: #ddd;}

.myTable th {
  padding-top: 8px;
  padding-bottom: 8px;
  text-align: left;
  background-color: #00ADEF;
  color: white;
}

.myTable thead {
    font-size:11px;
}

.myTable tbody {
    font-size:10px;
}

</style>

<body>

<div>

    <div style="float: left; margin-top: 0px; margin-left: 0px;" >
        <!--IMG SRC="img/contratos/logoContratoC1.png" width="110" height="110"-->
        <IMG SRC="img/contratos/logoContratoC1.png" width="110" height="110">
    </div>

    <table class="myTable" style="margin-left:95px; margin-top:0px; width:95%;">
        <thead>
            <tr>
                <th class="text-right" colspan="3">Fecha de emisión: {{$cotizacion->fecha}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Cliente: {{mb_strtoupper($cotizacion->apellidos)}} {{mb_strtoupper($cotizacion->nombre)}}</td>
                <td>Proyecto: {{mb_strtoupper($cotizacion->fraccionamiento)}}</td>
                <td>Etapa: {{mb_strtoupper($cotizacion->num_etapa)}}</td>
            </tr>
            <tr>
                <td>Lote: {{mb_strtoupper($cotizacion->num_lote)}}</td>
                <td>m²: {{round($cotizacion->terreno_m2,2)}}</td>
                <td>Costo * m²: ${{$cotizacion->m2}}</td>
            </tr>
            <tr>
                <td>Valor de Venta: ${{$cotizacion->valor_venta}}</td>
                <td>Mensualidades: {{round($cotizacion->mensualidades)}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <br>

    <table class="myTable">
        <thead>
            <tr>
                @if($cotizacion->valor_descuento == 0)
                    <th class="text-right">Saldo inicial: ${{$cotizacion->valor_venta}}</th>
                @else 
                    <th class="text-right">
                        Saldo inicial: ${{$cotizacion->valor_venta}}
                        - Descuento: ${{$cotizacion->valor_descuento}} 
                        = Total a Pagar: ${{$cotizacion->total_pagar}}
                    </th>
                @endif
            </tr>
        </thead>
    </table>
    <br>

    <table class="myTable">
        <thead>
            <tr>
                <th># Pago</th>
                <th>Mensualidad</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Dias</th>
                <th>% Descuento</th>
                <th>Descuento</th>
                <th>Interes</th>
                <th>Total a Pagar</th>
                <th>Saldo Pendiente</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pago as $pago)
            <tr>
                <td>{{$pago->folio }}</td>
                @if($pago->pago == 0)<td>Enganche</td>
                @else<td>Mensualidad</td> @endif
                <td>${{ $pago->cantidad }}</td>
                <td> {{ $pago->fecha }} </td>
                <td>{{ $pago->dias }}</td>
                <td>{{$pago->descuento_porc }} %</td>
                <td>${{ $pago->descuento }}</td>
                <td>${{$pago->interes_monto }}</td>
                <td>${{$pago->total_a_pagar }}</td>
                <td>${{$pago->saldo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <table class="myTable">
        <thead>
            <tr>
                <td class="text-center" colspan="6">
                    <strong class="" style="background-color: #ffc107 !important;">
                        Nota: La presente cotización tiene vigencia de 8 días hábiles posteriores a la emisión y el lote cotizado estará sujeto a disponibilidad.
                    </strong>
                </td>
            </tr>
        </thead>
        <thead>
            <tr>
                <th colspan="6">Plan Comercial de Pagos</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center"><strong>De 0 a 1 mes</strong></td>
                <td class="text-center"><strong>De 1 a 6 mes</strong></td>
                <td class="text-center"><strong>De 7 a 12 meses</strong></td>
                <td class="text-center"><strong>De 13 a 24 meses</strong></td>
                <td class="text-center"><strong>De 25 a 36 meses</strong></td>
                <td class="text-center"><strong>De 37 a 48 meses</strong></td>
            </tr>
            <tr>
                <td class="text-center">0% de Interes de tasa anual</td>
                <td class="text-center">0% de Interes de tasa anual</td>
                <td class="text-center">12% de Interes de tasa anual</td>
                <td class="text-center">16% de Interes de tasa anual</td>
                <td class="text-center">18% de Interes de tasa anual</td>
                <td class="text-center">20% de Interes de tasa anual</td>
            </tr>
        </tbody>
    </table>
</div>
    
</body>
</html>