<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de renta en Depósito</title>
</head>
<style type="text/css">
    @font-face {
        font-family: Arial, sans-serif;
        font-style: Book;
    }

    body {
        font-size: 14pt;
        font-family: Arial, sans-serif;
    }

    ul {
        text-align: left;
    }

    .table {
        display: table;
        width: 100%;
        border-collapse: collapse;
    }

    .table-row {
        display: table-row;
    }

    .table-cell {
        display: table-cell;
        padding: 0em;
        font-size: 14pt;
    }

</style>

<body>
    <div style="width: 100%; position: fixed; text-align: center;">
       
        <div style="margin-top: 100px; position: absolute; width: 100%; left:60px; right:60px;">

            <p align="right">San Luis Potosí, S.L.P, a <u>{{$contrato->fecha_firma}}</u></p>
            <br>
            
            <center>
                <h4>RECIBO DE RENTA EN DEPÓSITO</h4>
            </center>

            <br><br>
                <p align="justify">
                    Recibí del SR 
                    @if($contrato->tipo_arrendatario == 1)
                        {{mb_strtoupper($contrato->representante_arrendatario)}}
                    @else
                        {{mb_strtoupper($contrato->nombre_arrendatario)}}
                    @endif
                    la cantidad de ${{$contrato->dep_garantia}} 
                    por concepto de depósito de en garantía, equivalente a {{$contrato->meses_garantia}} 
                        @if($contrato->meses_garantia == 'Un ') 
                            mes 
                        @else 
                            meses     
                        @endif
                    de renta, como garantía para asegurar la reparación de desperfectos si los hubiera o para el pago de servicios como luz, 
                    agua teléfono, servicio de televisión etc, que no fueran cubiertos por el arrendatario en el 
                    periodo de arrendamiento de la vivienda  que se encuentra ubicada en {{$contrato->calle}} #{{$contrato->numero}} 
                    @if($contrato->interior != NULL)
                        Int. {{$contrato->interior}}
                    @endif
                    {{$contrato->etapa}} Fraccionamiento {{$contrato->proyecto}} C.P. {{$contrato->cp_proyecto}}, 
                    @if($contrato->delegacion != NULL)
                        {{$contrato->delegacion}}, 
                    @endif
                    {{$contrato->ciudad_proyecto}}, {{$contrato->estado_proyecto}}.
                </p>

                <br><br>
            
            <p align="center">RECIBI.</p>
            <br>
            <br>
            <br>
            <br>

            <p align="center">______________________________ <br>
                @if($contrato->tipo_arrendador == 1)
                    {{mb_strtoupper($representante)}}
                @else
                    {{mb_strtoupper($contrato->nombre_arrendador)}}
                @endif
            </p>

        </div>

    </div>
</body>

</html>
