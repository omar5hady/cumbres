<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Solicitud de Devolución</title>
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
        line-height: 1.2;
    }
    .fecha{
        position: relative;
        margin-top: 30px;
        padding-top: 90px;
        margin-left: 55px;
        text-align: left;
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
        margin-top: 140px;
        margin-left: 115px;
        margin-right: 115px;
        text-align: justify;
    }
    .firma{
        margin-top: 4cm;
        margin-left: 105px;
        position: absolute;
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
        font-size: 9pt;
    }
    .logo{
        height: 77px;
        position: absolute;
        top: 30px;
        left: 17cm;
    }
</style>

<body style="background-image: url('img/devolucion/Carta_Devolucion.png'); background-size: cover;">
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
            <p>
                Por este conducto solicitamos a usted atentamente la <b>DEVOLUCIÓN</b> correspondiente a la
                adquisición de vivienda ubicada en
                MZ {{ mb_strtoupper($data->manzana) }},
                LT {{$data->num_lote}} {{ $data->sublote ? $data->sublote : '' }}
                FRACC {{ strtoupper(
                    str_replace("FRACCIONAMIENTO RESIDENCIAL", "", $data->proyecto)
                )}}
                ubicado en esta ciudad.
                Que dicha vivienda adquirí mediante un crédito
                {{ mb_strtoupper($data->tipo_credito) }}. <br> <br>
                Monto solicitado:
                @if($tipo == 'Cancelacion')
                    {{ $data->depositado }}
                @else
                    {{ $data->saldo }}
                @endif
                <br> <br>
            </p>
            @if($tipo == 'Cancelacion')
                <p>
                    <br><br><br><br>
                </p>
            @else
                <p>
                    NOTA: LA DEVOLUCIÓN SE EFECTUARA EN UN PERIODO DE 3 SEMANAS DESPUES DE QUE LA INSTITUCIÓN (ES)
                    PAGUE LO CORRESPONDIENTE DE MI CRÉDITO A {{ mb_strtoupper($data->emp_constructora) }}. <br> <br>
                </p>
            @endif

    </div>

    <div class="firma">
        <div class="table">
            <div class="table-row">
                <div class="table-cell" style="font-size: 12.5pt"><b>ATENTAMENTE</div>
            </div>
            <div class="table-row">
                <div class="table-cell" style="padding-top: 60px;">
                    {{ mb_strtoupper( $data->cl_apellidos.' '.$data->cl_nombre ) }}
                </div>
            </div>
        </div>
    </div>

</body>
</html>
