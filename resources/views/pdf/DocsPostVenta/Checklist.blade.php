<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checklist</title>
</head>
<style>
.contenedor {
clear:both;
float: left;
width: 1000px;
}

body {
    font-family: sans-serif;
}

.table { display: table; width:50%; border-collapse: collapse; table-layout: fixed; }
.table-cell { display: table-cell; padding: 0.2em; font-size: 7.5pt; }
.table-row { display: table-row; }

.table2 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; border: ridge #0B173B 1px; color:black; }
.table-cell2 { display: table-cell; padding: 0.3em; font-size: 7.5pt; border: ridge #0B173B 1px; color:black; }
.table-cell3 { display: table-cell; width:30%; padding: 0.3em; font-size: 7.5pt; border: ridge #0B173B 1px; color:black; }
.table-cell4 { display: table-cell; width:30%; font-size: 7.5pt; border: ridge #0B173B 1px; text-align: center; color: white; background-color:#36648e; padding:3em; }
.table-row { display: table-row; }

</style>
<body>
<div class="contenedor">
    <div style="width: 720px;">
        <div style="display: inline-block; margin-top:-15px; float: left;" >
        <IMG SRC="img/checklist.png" width="345" height="75">
        </div>

        <div class="table" style="color:#063058; float:right; ">
            <div class="table-row">
                <div colspan="4" class="table-cell"><b>Fraccionamiento: <u>{{$contratos[0]->proyecto}}</u></b></div>
            </div>
            <div class="table-row">
                <div class="table-cell"><b>Lote: <u>{{$contratos[0]->num_lote}}</u> </b></div>
                <div class="table-cell"><b>Manzana: <u>{{$contratos[0]->manzana}}</u></b></div>
                <div colspan="2" class="table-cell"><b>Fecha: <u>{{$observacion[0]->tiempo}}</u></b></div>
            </div>
            <div class="table-row">
                <div colspan="4" class="table-cell"><b>Prototipo: <u>{{$contratos[0]->modelo}}</u></b></div>
            </div>
            <div class="table-row">
                <div colspan="4" class="table-cell"><b>Equipamiento: <u> @for($i=0; $i < count($equipamientos); $i++){{$equipamientos[$i]->equipamiento}}, @endfor</u></b></div>
            </div>

        </div>

        @if($contratos[0]->cochera == 1)
        <div class="table2" style="margin-top:90px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>COCHERA</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 1)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif
       
        @if($contratos[0]->sala_comedor == 2)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>SALA COMEDOR</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 2)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif
        
        @if($contratos[0]->cocina == 3)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>COCINA</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 3)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif
            
        @if($contratos[0]->medio_baño == 4)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>MEDIO BAÑO</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 4)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor 
        </div>
        @endif

        @if($contratos[0]->patio == 5)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>PATIO</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 5)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor   
        </div>
        @endif

        @if($contratos[0]->escalera == 6)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>ESCALERA</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 6)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif

        @if($contratos[0]->baño_comun == 7)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>BAÑO COMÚN</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 7)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor 
        </div>
        @endif

        @if($contratos[0]->estancia == 8)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>ESTANCIA</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 8)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor 
        </div>
        @endif

        @if($contratos[0]->recamara_principal == 9)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>RECÁMARA PRINCIPAL</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 9)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif

        @if($contratos[0]->baño_recamara_principal == 10)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>BAÑO RECÁMARA PRINCIPAL</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 10)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif

        @if($contratos[0]->vestidor == 11)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>VESTIDOR</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 11)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif

        @if($contratos[0]->recamara2 == 12)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>RECÁMARA 2</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 12)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor 
        </div>
        @endif

        @if($contratos[0]->recamara3 == 13)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;"> 
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>RECÁMARA 3</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 13)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif

        @if($contratos[0]->azotea == 14)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>AZOTEA</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 14)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor
        </div>
        @endif

        @if($contratos[0]->generales == 15)
        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                <div colspan="2" class="table-cell2" style="text-align: center; color: white; background-color:#063058;"><b>GENERALES</u></b></div>
                </div>
            @for($i=0; $i < count($detalles); $i++)
                @if($detalles[$i]->identificador == 15)
                <div class="table-row">
                <div class="table-cell3"><b> {{$detalles[$i]->concepto}}</b></div>
                <div class="table-cell2"><b> {{$detalles[$i]->observacion}}</b></div>
                </div>
                @endif
            @endfor 
        </div>
        @endif


        <div class="table2" style="margin-top:40px; border: ridge #0B173B 1px; color:black;">
            
            <div class="table-row">
            <div colspan="4" class="table-cell2" style="color: white; background-color:#063058;"><b>OBSERVACIONES</b></div>
            </div>
            <div class="table-row">
            <div colspan="4" class="table-cell2" ><b>{{$observacion[0]->observaciones}}</b></div>
            </div>
            
        </div>

        <div class="table2" style="margin-top:20px; border: ridge #0B173B 1px; color:black;">
            
            
            <div colspan="4" class="table-cell4" style="text-align: center;"><b>FIRMA RESIDENTE DE OBRA</b></div>
           
            <div colspan="4" class="table-cell4" style="text-align: center;"><b>FIRMA SUPERVISOR DE OBRA</b></div>

            <div colspan="4" class="table-cell4" style="text-align: center;"><b>FIRMA SUPERVISOR POST VENTA</b></div>
            
            
        </div>
    </div>
</div>
    
</body>
</html>