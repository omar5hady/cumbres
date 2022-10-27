<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ANEXO E </title>
</head>

<style>
    body {
        font-size: 9.5pt;
        text-align: justify;
        font-family: Verdana, Tahoma, sans-serif;
    }

    @page {
        margin: 45px;
        margin-bottom: 70px;
        margin-right: 75px;
        margin-left: 75px;
    }

    .table-cell {
        display: table-cell;
        padding: 0em;
        font-size: 9.5pt;
    }

    .table {
        display: table;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table2 { display: table; width:100%;  border: ridge #0B173B 1px; color:black;}
    .table-cell2 {
        display: table-cell; padding: 0.1em; font-size: 9pt;
        border: ridge; border-width:0.5px; #0B173B 1px; color:black;
    }

    .table-row {
        display: table-row;
    }
    .cuadrado{
        position: absolute;
        margin-left: -40px;
        margin-right: 15px;
    }
    .ul-custom{
        list-style-type:none;
    }

</style>

<body>
    <div>
        <h4 align="center">“Anexo E”</h4>
    </div>
    <div>
        <p>
            <center><strong>PETICION DE ACCESORIOS, MODIFICACIONES Y SERVICIOS ADICIONALES, PRECIO Y PLAZO DE ENTREGA:</strong></center>
        </p>

        <p>
            <div class="table2">
                <div class="table-row">
                    <div colspan="1" class="table-cell2"><strong><center>&nbsp;NO.&nbsp;</center></strong></div>
                    <div colspan="8" class="table-cell2"><strong>
                        <center>&nbsp;ACCESORIOS, MODIFICACIONES Y SERVICIOS ADICIONALES/OPCIONALES&nbsp;</center></strong>
                    </div>
                    <div colspan="3" class="table-cell2"><strong><center>&nbsp;PRECIO&nbsp;</center></strong></div>
                </div>
                <div class="table-row">
                    <div colspan="1" class="table-cell2"><br></div>
                    <div colspan="8" class="table-cell2"><br></div>
                    <div colspan="3" class="table-cell2"><br></div>
                </div>
                @foreach($equipamientos as $e)
                    <div class="table-row">
                        <div colspan="1" class="table-cell2">
                                {{$loop->iteration}}.-
                        </div>
                        <div colspan="8" class="table-cell2">
                            <center>{{ucwords($e->equipamiento)}}</center>
                        </div>
                        <div colspan="3" class="table-cell2">
                            <center>${{number_format($e->costo,2)}}</center>
                        </div>
                    </div>
                @endforeach
            </div>
        </p>
        <br>


        <p class="small">
            Las solicitudes de accesorios, modificaciones y servicios adicionales con los que cuenta el inmueble acordados por las partes,
            son los que se detallan en el cuadro que antecede, los cuales “EL PROMITENTE VENDEDOR”, deberá realizarlos y entregarlos a
            “EL PROMITENTE COMPRADOR”, al momento de la entrega física del bien inmueble o en fecha _____________________.
        </p>
        <br>

        <p>
            <div class="table2">
                <div class="table-row">
                    <div colspan="2" class="table-cell2"><strong><center>&nbsp;NO.&nbsp;</center></strong></div>
                    <div colspan="10" class="table-cell2">
                        <strong><center>&nbsp;GASTOS OPERATIVOS&nbsp;</center></strong>
                    </div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2"><br></div>
                    <div colspan="10" class="table-cell2"><br></div>
                </div>
                @foreach($gastos as $gasto)
                    <div class="table-row">
                        <div colspan="2" class="table-cell2">
                                {{$loop->iteration}}.-
                        </div>
                        <div colspan="10" class="table-cell2">
                            <center>{{$gasto->concepto}}</center>
                        </div>
                    </div>
                @endforeach
            </div>
        </p>
        <br>

        <p class="small">
            En caso de que “EL PROMITENTE COMPRADOR”, elija que no desea continuar con los servicios adicionales descritos en el cuadro que
            antecede, notificará por escrito o correo electrónico a “EL PROMITENTE VENDEDOR”, en cualquier momento la prestación de servicios
            adicionales, especiales o conexos a la compraventa, sin que ello implique la conclusión del contrato principal.
        </p>
        <br>


        <p align="center">
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                “LOS PROMITENTES VENDEDORES”:
            @else
                “EL PROMITENTE VENDEDOR”:
            @endif
        </p>

        <p  align="center">
            @if($contrato->emp_terreno == $contrato->emp_constructora)
                <div class="table">
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><br><br><br><center>_____________________________________</center></div>
                        <div class="table-cell"></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><center>{{$contrato->emp_constructora}} S.A. DE C.V.</center></div>
                        <div class="table-cell"></div>
                    </div>
                </div>
            @else
                <div class="table">
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><br><br><br><center>___________________________________</center></div>
                        <div colspan="" class="table-cell"><center></center></div>
                        <div colspan="5" class="table-cell"><br><br><br><center>___________________________________</center></div>
                    </div>
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><center>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</center></div>
                        <div class="table-cell"></div>
                        <div colspan="5" class="table-cell"><center>CONCRETANIA S.A DE C.V.</center></div>
                    </div>
                </div>

            @endif
        </p>

        <p align="center"><br><br><br><br>
            “EL PROMITENTE COMPRADOR”
        </p>

        <p  align="center">
            @if($contrato->coacreditaod == 0)
                <div class="table">
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><center><br><br><br>_____________________________________</center></div>
                        <div class="table-cell"></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><center>{{$contrato->c_nombre}} {{$contrato->c_apellidos}}</center></div>
                        <div class="table-cell"></div>
                    </div>

                </div>
            @else
                <div class="table">
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><br><br><br><center>___________________________________</center></div>
                        <div colspan="" class="table-cell"><center></center></div>
                        <div colspan="5" class="table-cell"><br><br><br><center>___________________________________</center></div>
                    </div>
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><center>{{$contrato->c_nombre}} {{$contrato->c_apellidos}}</center></div>
                        <div colspan="" class="table-cell"><center></center></div>
                        <div colspan="5" class="table-cell"><center>{{$contrato->nombre_coa}} {{$contrato->apellidos_coa}}</center></div>
                    </div>

                </div>
            @endif

            <br>
        </p>

    </div>


</body>

</html>
