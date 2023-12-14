<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Solicitud de Cancelación</title>
</head>
<style type="text/css">
    /* @import url('https://fonts.googleapis.com/css?family=Muli&display=swap'); */

    @page{
        margin: 0cm;
        background-color: #fff;
    }

    body {
        font-family: 'AvenirNextLTPro','Muli', Helvetica, Arial, sans-serif;
        font-size: 11pt;
        color: #0C3456
    }

    p{
        color: #0C3456;
        line-height: 0.4px;
    }
    .fecha{
        position: relative;
        margin-top: 30px;
        padding-top: 90px;
        margin-right: 55px;
        text-align: right;
        font-weight: bold;
    }
    .asunto{
        margin-left: 115px;
        margin-top: 90px;
        margin-bottom: 90px;
        font-weight: bold;
    }
    .asunto p{
        line-height: 1.4;
    }
    .contenido{
        margin-top: 150px;
        margin-left: 115px;
        margin-right: 115px;
        text-align: justify;
    }
    .firma{
        margin-top: 2cm;
        position: absolute;
        text-align: center;
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
        font-size: 9pt;
    }
    .logo{
        height: 77px;
        position: absolute;
        top: 30px;
        left: 2cm;
    }
</style>

<body style="background-image: url('img/devolucion/Carta_Cancelacion.png'); background-size: cover;">
    @if($data->emp_constructora == 'CONCRETANIA')
        <img class="logo" style="margin-left: -45px; height: 65px;" SRC="img/cotizador/Logo_Concretania.png">
    @else
        <img class="logo" SRC="img/cotizador/Logo_Cumbres.png">
    @endif
    <div class="fecha">San Luis Potosí, S.L.P. a {{ $data->hoy }}</div>

    <div class="asunto">
        <p>
            {{ mb_strtoupper($data->g_nombre.' '.$data->g_apellidos) }} <br>
            Gerente del Fraccionamiento <br>
            {{ $data->emp_constructora }} S.A. de C.V. <br>
            Presente:
        </p>
    </div>

    <div class="contenido">
        Por este conducto solicitamos a usted atentamente la <b>CANCELACIÓN</b> del trámite
        correspondiente a la adquisición de la vivienda ubicada en
        MANZANA: {{ $data->manzana }}
        LOTE: {{ $data->num_lote }} {{ $data->sublote ? $data->sublote : ''}}
        del FRACC: {{ strtoupper(
            str_replace("FRACCIONAMIENTO RESIDENCIAL", "", $data->proyecto)
        )}}.
        A Nombre de <b>{{ mb_strtoupper( $data->cl_apellidos.' '.$data->cl_nombre ) }},</b>
        ubicado en esta ciudad y por lo tanto del contrato de promesa de compra-venta sujeto a
        condición suspensiva firmado con fecha {{ $data->fecha }}. Que dicha vivienda
        pretendía adquirir mediante un crédito: <b>{{ mb_strtoupper($data->tipo_credito) }}.</b> El
        motivo de la Cancelación es: <br>

        ______________________________________________________________________________________<br>
        ______________________________________________________________________________________<br>
        ______________________________________________________________________________________<br>
        ______________________________________________________________________________________<br>
        ______________________________________________________________________________________<br>
    </div>

    <div class="firma">
        <div class="table">
            <div class="table-row">
                <div class="table-cell"><b>SOLICITA DEVOLUCIÓN: SI</div>
                <div class="table-cell"><b>CANTIDAD: {{ $data->monto_deposito }}</div>
            </div>
            <div class="table-row">
                <div class="table-cell" style="padding-top: 70px;">________________________________________________</div>
                <div class="table-cell" style="padding-top: 70px;">________________________________________________</div>
            </div>
            <div class="table-row">
                <div class="table-cell">De conformidad</div>

                <div class="table-cell">Asesor</div>
            </div>
            <div class="table-row">
                <div class="table-cell"><b>{{ mb_strtoupper( $data->cl_apellidos.' '.$data->cl_nombre ) }}</div>
                <div class="table-cell"><b>{{ mb_strtoupper( $data->a_apellidos.' '.$data->a_nombre ) }}</div>
            </div>
        </div>
    </div>

</body>
</html>
