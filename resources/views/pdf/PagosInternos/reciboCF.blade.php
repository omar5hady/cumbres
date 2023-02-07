<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante de pago</title>
</head>

<style>
    @page {
        margin:0
    }

    body{
        font-size: 9pt;
        text-align: justify;
        font-family: Helvetica, Arial, sans-serif;
    }

    .fecha{
        margin-top: 3.5cm;
        margin-left: 8cm;
        position: absolute;
    }

    .importe{
        margin-top: 4.8cm;
        margin-left: 5cm;
        position: absolute;
    }
    .importe-letra{
        margin-top: 5.5cm;
        margin-left: 5cm;
        position: absolute;
    }
    .from{
        margin-top: 6.5cm;
        margin-left: 5cm;
        position: absolute;
    }
    .concepto{
        margin-top: 7.4cm;
        margin-left: 4.5cm;
        position: absolute;
    }
    .firma1{
        margin-top: 10.5cm;
        margin-left: 2cm;
        position: absolute;
        text-align: center;
        width: 7cm;
    }
    .firma2{
        margin-top: 10.5cm;
        margin-left: 12cm;
        position: absolute;
        text-align: center;
        width: 7cm;
    }


</style>

    <body>
       <div class="fecha">
           {{$solicitud->fecha_pago}}
       </div>
       <div class="importe">
            $ {{number_format((float)$solicitud->importe, 2, '.', ',')}}
        </div>
        <div class="importe-letra">
            {{$solicitud->importe_letra}}
        </div>
        <div class="concepto">
            @foreach ($solicitud->det as $det)
                {{$det->observacion}}
            @endforeach
        </div>
        <div class="firma1">
            OSO PARDOS EN LA PRADERA DE JUANITO CORTEZ SA
        </div>
        <div class="firma2">
            OSO PARDOS EN LA PRADERA DE JUANITO CORTEZ SA
        </div>
    </body>
</html>
