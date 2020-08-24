<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style type="text/css">
.contenedor {
clear:both;
float: left;
width: 1000px;
}

body {
    font-family: sans-serif;
}

.table { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-cell { display: table-cell; padding: 0.4em; font-size: 8.5pt; }
.table-row { display: table-row; }

.table2 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; border: ridge #0B173B 1px; color:black; }
.table-cell2 { display: table-cell; padding: 0.1em; font-size: 7.5pt; border: ridge #0B173B 1px; color:black; }
.table-row { display: table-row; }
</style>
<body>

<div class="contenedor">
    <div style="width: 720px;">
        <div style="display: inline-block; float: left;" >
        @if($resultados[0]->emp_constructora == 'CONCRETANIA' && $resultados[0]->emp_terreno == 'CONCRETANIA')
            <IMG SRC="img/logo2C.png" width="250" height="50">
        @else 
            <IMG SRC="img/logo2.png" width="250" height="50">
        @endif
        </div>
        <div style="display: inline-block; float: right;" >
        <IMG SRC="img/recepcionGeneral.png" width="200" height="50">
        </div>

        <div class="table" style="margin-top:60px; border: ridge #0B173B 1px; color:black;">
            <div class="table-row">
                <div colspan="2" class="table-cell"><b>Fraccionamiento: <u>{{$resultados[0]->proyecto}}</u></b></div>
                <div class="table-cell"><b> Fecha de revision: <u>{{$resultados[0]->fecha_revision}}</u></b></div>
            </div>
            <div class="table-row">
                <div class="table-cell"><b>Manzana: <u>{{$resultados[0]->manzana}}</u></b></div>
                <div class="table-cell"><b>Lote: <u>{{$resultados[0]->num_lote}}</u></b></div>
                <div class="table-cell"><b>Prototipo: <u>{{$resultados[0]->modelo}}</u></b></div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell"><b>Proveedor: <u>{{$resultados[0]->proveedor}}</u></b></div>
                <div class="table-cell"><b>Folio Factura: ___________</b></div>
            </div>
            <div class="table-row">
            @if($resultados[0]->casa_muestra == 1)
                <div class="table-cell"><b>Casa Muestra: <u>Si</u></b></div>
            @else
                <div class="table-cell"><b>Casa Muestra: <u>No</u></b></div>
            @endif
                <div  colspan="2" class="table-cell"><b>Cliente: <u>{{$resultados[0]->nombre_cliente}}</u></b></div>
            </div>
        </div>
       
       <h3 style="text-align:center;">Concepto</h3>

        <h5 style="text-align:center;"><u>{{$resultados[0]->equipamiento}}</u></h5>
        <h5 style="text-align:center;">Proveedor: <u>{{$resultados[0]->proveedor}}</u></h5>

       <p><b>Observaciones: </b>{{$resultados[0]->observacion}}</p>

       <div class="table" style="border: ridge #0B173B 1px; color:black;">
            <div class="table-row">
                <div colspan="2" class="table-cell"><b>Supervisor: {{$resultados[0]->supervisor}}</b></div>
                <div class="table-cell"><b>Firma:</b></div>
            </div>
        </div>

    </div>
</div>
    
</body>
</html>