<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Conclusión de Detalles</title>
    {{-- <link href="css/plantilla.css" rel="stylesheet"> --}}
</head>

<style>
    .contenedor {
        clear:both;
        float: left;
        width: 1000px;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .table { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
    .table-cell { display: table-cell; padding: 0.5em; font-size: 9pt;  border: solid #063058 1.5px; border-radius: 25px; }
    .table-row { display: table-row; }

    .table2 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; border: ridge #0B173B 1px; color:black; }
    .table-cell2 { display: table-cell;  font-size: 8pt; border: ridge #0B173B 1px; text-align: center; color: #55728C; background-color:white; padding:1em; }
    .table-cell3 { display: table-cell;  font-size: 7pt; border: ridge #0B173B 1px; text-align: justify; color: white; background-color:#55728C; padding:1em; }
    .table-cell4 { display: table-cell;  font-size: 7.5pt; border: ridge #0B173B 1px; text-align: center; color: white; background-color:#55728C; padding:2.8em; }
</style>

<body>
    <div class="contenedor">
        <div style="width: 720px;">
            <div style="display: inline-block; margin-top:-15px; float: left;" >
                <IMG SRC="img/reportconclusion.png" width="720" height="100">
            </div>

            <div class="table" style="float:left; margin-top:110px;">
                <div class="table-row">
                    <div colspan="13" class="table-cell"><i class="icon-home fa-lg"></i><b>&nbsp;&nbsp;FRACCIONAMIENTO RESIDENDIAL {{mb_strtoupper($solicitud[0]->fraccionamiento)}}</b></div>
                    <div colspan="7" class="table-cell"><i class="icon-home fa-lg"></i><b>&nbsp;&nbsp; Etapa: {{mb_strtoupper($solicitud[0]->etapa)}}</b></div>
                </div>
                <div class="table-row">
                    <div colspan="4" class="table-cell"><i class="icon-home fa-lg"></i><b>&nbsp;&nbsp;{{mb_strtoupper($solicitud[0]->modelo)}}</b></div>
                    <div colspan="9" class="table-cell"><i class="fa fa-apple fa-lg"></i><b>&nbsp;&nbsp;{{mb_strtoupper($solicitud[0]->manzana)}}</b></div>
                    <div colspan="7" class="table-cell"><i class="fa fa-square fa-lg"></i><b>&nbsp;&nbsp; Numero de Lote: {{$solicitud[0]->num_lote}}</b></div>
                </div>
                <div class="table-row">
                    <div colspan="12" class="table-cell"><i class="fa fa-user fa-lg"></i><b>&nbsp;&nbsp;{{$solicitud[0]->cliente}} </b></div>
                    <div colspan="8" class="table-cell"><i class="fa fa-calendar fa-lg"></i><b>&nbsp;&nbsp;{{$solicitud[0]->fecha}}</b></div>
                </div>
                <div class="table-row">
                    <div colspan="15" class="table-cell"><i class="fa fa-map-marker fa-lg"></i><b>&nbsp;&nbsp;Dirección: {{$solicitud[0]->calle}} No.{{$solicitud[0]->numero}} </b></div>
                    <div colspan="5" class="table-cell"><i class="fa fa-phone fa-lg"></i><b>&nbsp;&nbsp;{{$solicitud[0]->celular}}</b></div>
                </div>
                <div class="table-row">
                    <div colspan="6" class="table-cell"><i class="fa fa-calendar fa-lg"></i><b>&nbsp;&nbsp;{{$solicitud[0]->fecha_entrega_real}}</b></div>
                    <div colspan="14" class="table-cell">
                        <i class="fa fa-clock-o fa-lg"></i>
                        <b>&nbsp; L @if($solicitud[0]->lunes == 1)<i class="fa fa-check-square-o"></i> @endif
                        &nbsp; M @if($solicitud[0]->martes == 1)<i class="fa fa-check-square-o"></i> @endif
                        &nbsp; Mi @if($solicitud[0]->miercoles == 1)<i class="fa fa-check-square-o"></i> @endif
                        &nbsp; J @if($solicitud[0]->jueves == 1)<i class="fa fa-check-square-o"></i> @endif
                        &nbsp; V @if($solicitud[0]->viernes == 1)<i class="fa fa-check-square-o"></i> @endif
                        &nbsp; S @if($solicitud[0]->sabado == 1)<i class="fa fa-check-square-o"></i> @endif
                        &nbsp;&nbsp;&nbsp;{{$solicitud[0]->horario}}</b>
                    </div>
                </div>
            </div>

            <div class="table" style="margin-top:305px;">
                <div class="table-row">
                    <div colspan="14" class="table-cell" style="text-align: center; font-size:10pt; color: white; background-color:#063058;"><b>&nbsp;&nbsp; Descripción del detalle a reparar </b></div>
                    <div colspan="6" class="table-cell" style="text-align: center; font-size: 10pt; color: white; background-color:#063058;"><b>&nbsp;&nbsp; Fecha conclusión de detalle </b></div>
                </div>
                @for($i=0; $i < count($detalles); $i++)
                    <div class="table-row">
                    <div colspan="14" class="table-cell" style="text-align: center; ">{{$detalles[$i]->general}}, {{$detalles[$i]->subconcepto}}, {{$detalles[$i]->detalle}} , {{$detalles[$i]->observacion}}</div>
                        <div colspan="6" class="table-cell" style="text-align: center; "><b>{{$detalles[$i]->fecha_concluido}}</b></div>
                    </div>
                @endfor                          
            </div>


            <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
            
            <div class="table-row">
                <div colspan="6" class="table-cell3"><b>
                    YO, AL FIRMAR; ACEPTO QUE SE CONCLUYERON LAS REPARACIONES DESCRITAS EN ESTE FORMATO
                    Y QUE ESTOY SATISFECHO(A) CON EL TRABAJO REALIZADO POR EL DEPARTAMENTO DE POST VENTA
                    DE GRUPO CONSTRUCTOR CUMBRES S.A. DE C.V.
                </b></div>
                
                <div colspan="4" class="table-cell4" style="text-align: center;"><b><br> _____________________________<br> {{$solicitud[0]->cliente}}</b></div>

                <div colspan="3" class="table-cell2" style="text-align: center;"><b>FIRMA</b></div>
    
            </div>
                
            </div>
           
        </div>
    </div>
    
</body>
</html>