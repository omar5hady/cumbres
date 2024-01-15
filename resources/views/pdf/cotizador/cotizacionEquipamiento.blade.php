<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotización</title>
</head>
<style type="text/css">
    /* @import url('https://fonts.googleapis.com/css?family=Muli&display=swap'); */

    @page{
        margin: .5cm .5cm .5cm .5cm;
        background-color: #fff;
    }

    body {
        font-family: 'AvenirNextLTPro','Muli', Arial, Helvetica, sans-serif;
        font-size: 7pt;
    }

    p{
        color: #0C3456;
    }

    .h1{
        font-size: 20pt;
        padding: 0px;
        margin: 0px;
        margin-bottom: -7px;
        color: #0C3456;
        text-align: right
    }

    .folio{
        font-size: 14pt;
        padding: 0px;
        margin: 0px;
        margin-bottom: 30px;
        margin-right: 30px;
        color: #EC008C;
        text-align: right;
        font-weight: bold;
    }

    .modelo{
        font-size: 21pt;
        padding: 0px !important;
        margin: 0px !important;
        color: #EC008C;
        text-align: left;
        line-height: 0.8;
    }
    .nombre-modelo{
        font-size: 30.5pt;
        padding: 0px !important;
        margin: 0px !important;
        color: #fff;
        text-align: left;
        font-weight: bolder;
        line-height: 0.8;
    }

    .title-table{
        font-size: 19pt;
        padding: 0px !important;
        margin: 0px !important;
        color: #0C3456;
        text-align: left;
        line-height: 0.8;
    }

    .text-table{
        padding: 0px !important;
        margin: 0px !important;
        color: #0C3456;
        line-height: 1;
    }

    .text-9{
        font-size: 9pt;
    }

    .text-11{
        font-size: 11pt;
        font-weight: bold;
    }

    .subtitle{
        padding: 0px;
        margin: 0px;
        font-size: 15pt;
        color: #0C3456;
        font-style: bold;
        text-align: right;
    }

    .estatus{
        padding: 0px;
        margin: 0px;
        font-size: 11pt;
        color: #0C3456;
        font-style: bold;
        text-align: right
    }

    .backblue{
        text-align: center;
        vertical-align: middle;
        background-color: #0C3456;
        color: white;
        width: 100%;
        position: fixed;
    }

    .header{
        position: fixed;
        left: 0px;
        right: 0px;
        height: 7.5cm;
        text-align: center;
        top: 0px;
    }

    .contenido{
        margin-top: 7cm;
        top: 75px;
        position: relative;
        color: #001e2c;
        width: 100%;
        font-size: 7pt;
    }

    .contenido p{
        margin: 0px;
    }

    .detalle{
        position: relative;
        color: #001e2c;
        width: 100%;
        font-size: 7pt;
        top: 10% !important;
        text-align: CENTER;
        font-size: 9pt;
    }

    .info-cabecera{
        background-color: #0c335624; margin-left:0px; margin-right:10px;
    }

    .info-cabecera p{
        font-size:7.3pt; margin-left:5px;
    }

    .title-cabecera{
        background-color: #0C345610; margin-left:0px; margin-right:10px;
    }

    .cell-detalle{
        background-color: #0c33560b;
        margin:0px;
        border-right: 1px solid #0c33563d;
        border-bottom: 1px solid #0c33563d;
        text-align: justify;
    }


    .title-cabecera p{
        margin-top: 3px; margin-left: 5px; margin-bottom: 2px;
    }

    .title-cell{
        color:#fff; font-style:bold; padding: 4.5px; text-align: justify; font-size: 8pt;
    }

    .cell-detalle p{
        margin:0px; padding: 0px; font-size: 7pt;
        margin-top: 5px;
        margin-right: 5px;
    }

    .table {display: table; width: 100%; border-collapse: collapse; table-layout: fixed;}
    .table-row {display: table-row;}
    .table-cell1 {display: table-cell; vertical-align: middle; padding-left: 0.3cm; margin: 0;}
    .cell-title {display: table-cell; vertical-align: middle; padding-left: 0.3cm;}

    .contenedor{
        width : auto ;
        display: grid;
        grid-template-columns: 4;
        grid-template-rows: 2;
    }

    .grid-column{
    }

</style>

<body>
    <div class="header" style="background-image: url('img/cotizador/Encabezado_imagen.png'); background-size: cover;">
        <div class="table" style="margin-top: .5cm">
            <div class="table-row">
                {{-- Logo segun empresa constructora --}}
                <div colspan="2" class="table-cell1" style="padding-left: 30px;">
                    <img SRC="img/cotizador/Logo_Cumbres.png" height="66px">
                </div>

                <div colspan="1" class="table-cell1">
                </div>

                <div colspan="2" class="table-cell1" style="padding-left: 30px;">
                    <img SRC="img/cotizador/Logo_Concretania.png" height="56px">
                </div>


                <div colspan="1" class="table-cell1">
                </div>
                <div colspan="10" class="table-cell1" style="text-align: left;">
                    <p class="folio">N° {{$cotizacion->id}}</p>
                </div>
            </div>

            <div class="table-row">
                <div colspan="5" class="table-cell1" style="height: 30px;">
                </div>
            </div>

            <div class="table-row">
                @if($cotizacion->tipo == 1)
                    <div colspan="5" class="table-cell1">
                        <img
                        src="img/cotizador/Casas/{{ str_replace(' ', '', $cotizacion->modelo) }}/{{str_replace(' ', '', $cotizacion->modelo) }}.png" height="200px" alt="{{$cotizacion->modelo }}">
                    </div>
                    <div colspan="2" class="table-cell1">
                        <img
                        src="img/cotizador/Casas/{{ str_replace(' ', '', $cotizacion->modelo) }}/{{str_replace(' ', '', $cotizacion->modelo) }}_PB.png" height="200px" alt="{{$cotizacion->modelo }}">
                    </div>
                    <div colspan="2" class="table-cell1">
                        <img
                        src="img/cotizador/Casas/{{ str_replace(' ', '', $cotizacion->modelo) }}/{{str_replace(' ', '', $cotizacion->modelo) }}_PA.png" height="200px" alt="{{$cotizacion->modelo }}">
                    </div>
                @else
                    <div colspan="10" class="table-cell1">
                        <p class="modelo">MODELO</p>
                        <p class="nombre-modelo">{{$cotizacion->modelo}}</p>
                    </div>
                    <div colspan="5" class="table-cell1">
                        <img
                        src="img/cotizador/Casas/{{ str_replace(' ', '', $cotizacion->etapa) }}/{{str_replace(' ', '', $cotizacion->etapa) }}.png" height="200px" alt="{{$cotizacion->modelo }}">
                    </div>
                @endif
            </div>
        </div>
    </div>

    <br>

    <div class="contenido">
        <div class="table">
            <div class="table-row">
                <div colspan="1" class="table-cell1" style="text-align: left; vertical-align: baseline;">
                    <p class="title-table">DATOS DE <strong>CLIENTE</strong></p>
                </div>
                <div colspan="1" class="table-cell1" style="text-align: right; padding-right:15px;">
                    <p class="text-table text-9">Fecha de elaboración</p>
                    <p class="text-table text-11">{{$cotizacion->created_at}}</p>
                </div>
            </div>
        </div>
        <div class="table">
            <div class="table-row" style="background-color: #0C3456;">
                <div colspan="1" class="table-cell1" style="background-color: #0C3456; height:18px;"></div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div style="padding-top: 4px;">
                        <p style="font-size:9pt;">
                            <strong>
                                Nombre
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div class="info-cabecera" style="background-color: #fff; padding: 6px;">
                        <p class="text-table text-9" style="margin-top:-5px;" >
                            <strong>
                                &nbsp;&nbsp;{{ strtoupper($cotizacion->cliente)}}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <br><br>
                </div>
            </div>
        </div>

        <div class="table">
            <div class="table-row">
                <div colspan="1" class="table-cell1" style="text-align: left; vertical-align: baseline;">
                    <div  style="padding-top: 4px;">
                        <p class="title-table">DATOS DE <strong>COTIZACIÓN</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="table">
            <div class="table-row" style="background-color: #0C3456;">
                <div colspan="4" class="table-cell1" style="background-color: #0C3456; height:18px;"></div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div style="padding-top: 4px; margin-bottom:3px;">
                        <p style="font-size:7pt;">
                            FRACCIONAMIENTO
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div style="padding-top: 4px; margin-bottom:3px;">
                        <p style="font-size:7pt;">
                            MANZANA
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div style="padding-top: 4px; margin-bottom:3px;">
                        <p style="font-size:7pt;">
                            {{$cotizacion->tipo == 1 ? 'LOTE' : 'DEPARTAMENTO'}}
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">

                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 cell-detalle">
                    <div class="info-cabecera" style="padding: 3px;">
                        <p class="text-table text-7" style="margin-top:-5px;" >
                                &nbsp;&nbsp;{{ strtoupper(
                                    str_replace("FRACCIONAMIENTO RESIDENCIAL", "", $cotizacion->proyecto)
                                    )}}
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <div class="info-cabecera" style="padding: 3px;">
                        <p class="text-table text-7" style="margin-top:-5px;" >
                                &nbsp;&nbsp;{{ strtoupper($cotizacion->manzana)}}
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <div class="info-cabecera" style="padding: 3px;">
                        <p class="text-table text-7" style="margin-top:-5px;" >
                                &nbsp;&nbsp;{{ $cotizacion->num_lote }} {{ $cotizacion->sublote ? $cotizacion->sublote : '' }}
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <img style="position: absolute; margin-top: -15px;"
                    src="img/cotizador/Logo_calidad.png" height="130px" alt="Calidad-Cumbres">
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div style="padding-top: 4px; margin-bottom:3px;">
                        <p style="font-size:7pt;">
                            MODELO
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div style="padding-top: 4px; margin-bottom:3px;">
                        <p style="font-size:7pt;">
                            SUPERFICIE DE TERRENO
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div style="padding-top: 4px; margin-bottom:3px;">
                        <p style="font-size:7pt;">
                            SUPERFICIE DE CONSTRUCCIÓN
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 cell-detalle">
                    <div class="info-cabecera" style="padding: 3px;">
                        <p class="text-table text-7" style="margin-top:-5px;" >
                                &nbsp;&nbsp;{{ strtoupper($cotizacion->modelo)}}
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <div class="info-cabecera" style="padding: 3px;">
                        <p class="text-table text-7" style="margin-top:-5px;" >
                                &nbsp;&nbsp;{{$cotizacion->terreno}}m2
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <div class="info-cabecera" style="padding: 3px;">
                        <p class="text-table text-7" style="margin-top:-5px;" >
                            &nbsp;&nbsp;{{$cotizacion->construccion}}m2
                    </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                </div>
            </div>
            <div class="table-row">
                <div colspan="4" class="table-cell1 title-cabecera">
                    <br>
                </div>
            </div>
        </div>

        <div class="table">
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera" style="text-align: right; vertical-align: middle;">
                    <div style="padding-top: 4px; margin-bottom:3px;">
                        <p style="font-size:9pt;">
                            PRECIO DE VENTA:
                        </p>
                    </div>
                </div>
                <div colspan="2" class="table-cell1 title-cabecera" style="text-align: center; vertical-align: middle;">
                    <div class="info-cabecera" style="background-color: #fff; padding: 2px; border: 0.5pt solid #0C3456;">
                        <p class="text-table" style="margin-top:-5px; font-size:12pt;" >
                            &nbsp;${{number_format((float)$cotizacion->precio_venta, 2, '.', ',')}}
                        </p>
                    </div>
                </div>
                <div colspan="4" class="table-cell1 cell-detalle">
                </div>
            </div>
        </div>

        <div class="table">
            <div class="table-row">
                @foreach($cotizacion->columnas[0]  as $equip)
                    <div colspan="1" class="table-cell1 title-cabecera">
                        <div style="padding-top: 4px; margin-bottom:3px;">
                            @if($equip['monto'] != 0)
                                <p style="font-size:7pt;">
                                    {{$equip['titulo']}}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="table-row">
                @foreach($cotizacion->columnas[0]  as $equip)
                    <div colspan="1" class="table-cell1 cell-detalle">
                        @if($equip['monto'] != 0)
                            <div class="info-cabecera" style="padding: 3px;">
                                <p class="text-table text-7" style="margin-top:-5px;" >
                                    &nbsp;${{number_format((float)$equip['monto'], 2, '.', ',')}}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="table-row">
                @foreach($cotizacion->columnas[1]  as $equip)
                    <div colspan="1" class="table-cell1 title-cabecera">
                        <div style="padding-top: 4px; margin-bottom:3px;">
                            @if($equip['monto'] != 0)
                                <p style="font-size:7pt;">
                                    {{$equip['titulo']}}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="table-row">
                @foreach($cotizacion->columnas[1]  as $equip)
                    <div colspan="1" class="table-cell1 cell-detalle">
                        @if($equip['monto'] != 0)
                            <div class="info-cabecera" style="padding: 3px;">
                                <p class="text-table text-7" style="margin-top:-5px;" >
                                    &nbsp;${{number_format((float)$equip['monto'], 2, '.', ',')}}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="table-row">
                <div colspan="4" class="table-cell1 title-cabecera">
                    <br>
                </div>
            </div>
        </div>

        <div class="table">
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera" style="text-align: center; vertical-align: middle;">
                    <div style="padding-top: 4px; margin-bottom:3px;">
                        <p class="text-table text-11">
                            <strong>VALOR TOTAL:</strong>
                        </p>
                    </div>
                </div>
                <div colspan="2" class="table-cell1 cell-detalle">
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera" style="text-align: center; vertical-align: middle;">
                    <div class="info-cabecera" style="background-color: #fff; padding: 2px; border: 0.5pt solid #0C3456;">
                        <p class="text-table" style="margin-top:-5px; font-size:16pt;" >
                            &nbsp;${{number_format((float)$cotizacion->total, 2, '.', ',')}}
                        </p>
                    </div>
                </div>
                <div colspan="2" class="table-cell1 cell-detalle">
                    <div  style="padding-top: 4px;">
                        <p style="font-size:7pt;"><strong style="font-size:9pt;">OBSERVACIONES: </strong>
                            <u>{{$cotizacion->observacion ? $cotizacion->observacion : ''}}</u>
                        </p>
                    </div>
                </div>
            </div>
            <div class="table-row">
                <div colspan="4" class="table-cell1 title-cabecera">
                    <br><br>
                </div>
            </div>
        </div>

        <div class="table">
            <div class="table-row" style="background-color: #0C3456;">
                <div colspan="4" class="table-cell1" style="background-color: #0C3456; height:14px;"></div>
            </div>
        </div>
        <div class="table">
            <div class="table-row">
                <div colspan="1" class="table-cell1" style="text-align: left; vertical-align: top;">
                    <div  style="padding-top: 4px;">
                        <p style="font-size:7pt;"><strong style="font-size:9pt;">PROMOCIÓN: </strong>
                            <u>{{$cotizacion->promocion}}</u>
                        </p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1" style="text-align: left; vertical-align: top;">
                    <div  style="padding-top: 4px;">
                        <p style="font-size:8pt;"><strong>COTIZACIÓN REALIZADA POR: </strong>
                            <u>{{$cotizacion->creator}}</u>
                        </p>
                        <p style="font-size:8pt; width: 80%"><strong>ASESOR DE VENTAS: </strong>
                            <u>{{$cotizacion->asesor}}</u>
                        </p>
                        <img style="position: absolute; margin-top: -10px; margin-left: 250px;"
                            src="img/cotizador/Asesores_pics/{{str_replace(' ', '', $cotizacion->usuario) }}.png" height="100px" alt="Asesor">
                    </div>
                </div>
            </div>

            <div class="table-row">
                <div colspan="2" class="table-cell1" style="text-align: center; vertical-align: middle;">
                    <div  style="padding-top: 30px; padding-bottom: 10px;">
                        <p style="font-size:7pt;"><strong>*LA PRESENTE COTIZACIÓN PODRÁ TENER CAMBIOS O VARIACIONES EN PRECIOS SIN PREVIO AVISO. </strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="table-row">
                <div colspan="2" class="table-cell1" style="text-align: center; vertical-align: middle;">
                    <a href="https://casascumbres.mx">
                        <img src="img/cotizador/link.png" height="50px;" alt="{{$cotizacion->modelo }}">
                    </a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
