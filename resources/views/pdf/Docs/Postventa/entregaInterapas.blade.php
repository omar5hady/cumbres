<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carta de Notificación a Interapas</title>
</head>
<style type="text/css">
    /* @import url('https://fonts.googleapis.com/css?family=Muli&display=swap'); */

    @page{
        margin: 0cm 0cm 0cm 0cm;
    }

    body {
        font-family: 'AvenirNextLTPro','Muli', Arial, Helvetica, sans-serif
    }

    .h1{
        font-size: 15pt;
        font-style: bold;
        padding: 0px;
        margin: 0px;
    }

    .subtitle{
        padding: 0px;
        margin: 0px;
        font-size: 9pt;
    }

    .backblue{
        text-align: center;
        vertical-align: middle;
        background-color: #003058;
        color: white;
        width: 100%;
        position: fixed;
    }

    .header{
        top: 0px;
    }

    .footer{
        align-items: center;
        vertical-align: middle;
        bottom: 0px;
        height: 55.4092px;
    }

    .cover-container{
        top: 10%;
        text-align: center;
        align-items: center;
        width: 100%;
        height: 28cm;
        position: fixed;
    }

    .contenido{
        top: 50px;
        position: fixed;
        color: #001e2c;
        width: 100%;
        margin-left: 30px;
        margin-right: 80px;
        font-size: 12pt;
    }

    .fecha{
        top: 20% !important;
        text-align: right;
    }

    .info{
        top: 35% !important;
        margin-left: 121.14px;
        margin-right: 121.14px;
        text-align: justify;
    }

    .firma{
        top: 65%;
        font-size: 9pt;
    }

    .firma p{
        line-height: 5%;
    }



    .table { display: table; width: 98%; border-collapse: collapse; table-layout: fixed;}
    .table-row { display: table-row;  }
    .table-cell1 { display: table-cell; text-align:center; vertical-align: middle;}

</style>

<body>
    <div class="backblue header">
        <div  class="table" >
            <div class="table-row">
                <div  class="table-cell1" style="padding-left: 35px;">
                    {{-- Logo segun empresa constructora --}}
                    @if($contrato->emp_constructora == 'Grupo Constructor Cumbres')
                        <img SRC="img/contratos/Logos-01-White.png" height="130" >
                    @else
                        <img SRC="img/contratos/Logos-02-White.png" height="130" >
                    @endif
                </div>
                <div colspan="4" class="table-cell1">
                    <p class="h1">MANIFIESTO</p>
                    <p class="subtitle">ACTA DE ENTREGA - VIVIENDA</p>
                </div>
                <div  class="table-cell1">
                    @if($contrato->logo_fracc2)
                        <img src="img/logosFraccionamientos/{{$contrato->logo_fracc2}}" height="90" >
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="contenido fecha">
        <p>SAN LUIS POTOSÍ, S.L.P. A {{mb_strtoupper($contrato->entrega_real)}}.</p>
    </div>

    <div class="contenido info">
        <p>RECIBí DE ENTERA CONFORMIDAD POR PARTE DE <strong>GRUPO CONSTRUCTOR CUMBRES S.A. DE C.V.</strong>
            LA VIVIENDA UBICADA EN
            @if($contrato->tipo_modelo != 2)
                EL LOTE {{$contrato->num_lote}}{{($contrato->sublote)?'-'.$contrato->sublote:''}}
                DE LA MANZANA {{ mb_strtoupper($contrato->manzana) }} DE
            @endif
            LA CALLE {{mb_strtoupper($contrato->calle)}}
            No. {{$contrato->numero}}{{($contrato->interior)?' Int.'.$contrato->interior:''}}
            DEL FRACCIONAMIENTO
            @if(str_contains($contrato->proyecto, 'FRACCIONAMIENTO RESIDENCIAL'))
                "{{ mb_strtoupper( str_replace('FRACCIONAMIENTO RESIDENCIAL', '', $contrato->proyecto)) }}"
            @else
                "{{mb_strtoupper($contrato->proyecto)}}"
            @endif
            UBICADO EN {{mb_strtoupper($contrato->delegacion)}} EN {{mb_strtoupper($contrato->estado_proy)}}.
        </p>
    </div>

    <div class="contenido firma">
        <center>
            <p>RECIBÍ</p>
            <strong>
                <p>{{mb_strtoupper($contrato->nombre)}} {{mb_strtoupper($contrato->apellidos)}}</p>
            </strong>
        </center>
    </div>

    <div class="cover-container"></div>
    <div class="backblue footer">
        <center>
            <p class="subtitle">
                FRACCIONAMIENTO:
                    @if(str_contains($contrato->proyecto, 'FRACCIONAMIENTO RESIDENCIAL'))
                        {{ mb_strtoupper( str_replace('FRACCIONAMIENTO RESIDENCIAL', '', $contrato->proyecto)) }}
                    @else
                        {{mb_strtoupper($contrato->proyecto)}}
                    @endif
                @if($contrato->tipo_modelo != 2)
                    MNZA: {{mb_strtoupper($contrato->manzana)}}
                    LOTE: {{$contrato->num_lote}} {{($contrato->sublote) ? $contrato->sublote : ''}}
                @endif
                CALLE: {{mb_strtoupper($contrato->calle)}}
                #{{$contrato->numero}} {{ ($contrato->interior ? 'Int. '.$contrato->interior : '')}}.
                {{mb_strtoupper($contrato->ciudad_proy)}}, {{mb_strtoupper($contrato->estado_proy)}}
            </p>
        </center>
    </div>
</body>

</html>
