<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Conclusión de Detalles</title>
    <link href="css/plantilla.css" rel="stylesheet">
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

            <div class="table" style="margin-top:110px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell" style="text-align: center; font-size: 9pt; color: white; background-color:#063058;"><b>&nbsp; N° </b></div>
                    <div colspan="9" class="table-cell" style="text-align: center; font-size:9pt; color: white; background-color:#063058;"><b>&nbsp;&nbsp; Fecha conclusión de detalle </b></div>
                    <div colspan="9" class="table-cell" style="text-align: center; font-size: 9pt; color: white; background-color:#063058;"><b>&nbsp;&nbsp; Firma cliente Vo.Bo. reparación </b></div>
                </div>
                @for($i=0; $i < count($detalles); $i++)
                    <div class="table-row">
                        <div colspan="2" class="table-cell" style="text-align: center; "><b>&nbsp; {{$i+1}} </b></div>
                        <div colspan="9" class="table-cell" style="text-align: center; ">{{$detalles[$i]->fecha_concluido}}</div>
                        <div colspan="9" class="table-cell" style="text-align: center; "></div>
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
                
                <div colspan="4" class="table-cell4" style="text-align: center;"><b><br> _____________________________<br> Nombre del Cliente</b></div>

                <div colspan="3" class="table-cell2" style="text-align: center;"><b>FIRMA</b></div>
    
            </div>
                
            </div>
           
        </div>
    </div>
    
</body>
</html>