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
    margin-right: 90px;
    margin-left: 90px;
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
    font-size:13px;
}

.myTable tbody {
    font-size:10px;
}

</style>

<body>

<div>
    <table class="myTable">
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
                <td>Costo * m²: ${{round($cotizacion->valor_venta/$cotizacion->terreno_m2, 2)}}</td>
            </tr>
            <tr>
                <td>Valor de Venta: ${{round($cotizacion->valor_venta)}}</td>
                <td>Mensualidades: {{count($pago, COUNT_RECURSIVE)}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="myTable">
        <thead>
            <tr>
                @if($cotizacion->valor_descuento == 0)
                    <th class="text-right">Saldo inicial :${{round($cotizacion->valor_venta, 2)}}</th>
                @else 
                    <th class="text-right">
                        Saldo inicial :${{round($cotizacion->valor_venta,2)}}
                        - Descuento :${{round($cotizacion->valor_descuento, 2)}} 
                        = Total a Pagar :${{round($cotizacion->valor_venta-$cotizacion->valor_descuento, 2)}}
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
    </table>
</div>
    
</body>
</html>