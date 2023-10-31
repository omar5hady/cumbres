<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resumen de pago</title>
</head>
<style type="text/css">
    /* @import url('https://fonts.googleapis.com/css?family=Muli&display=swap'); */

    @page{
        margin: .8cm .8cm .8cm .8cm;
        background-color: #fff;
        size: landscape;
    }

    body {
        font-family: 'AvenirNextLTPro','Muli', Arial, Helvetica, sans-serif;
        font-size: 7pt;
    }

    p{
        color: #0C3456;
    }

    .h1{
        font-size: 22pt;
        padding: 0px;
        margin: 0px;
        margin-bottom: -10px;
        color: #0C3456;
        text-align: right
    }

    .contratista{
        font-size: 14pt;
        padding: 0px;
        margin: 0px;
        color: #0C3456;
        text-align: left;
    }

    .fecha{
        padding: 0px;
        color: #0C3456;
        text-align: right !important;
    }

    .subtitle{
        padding: 0px;
        margin: 0px;
        font-size: 15pt;
        color: #0C3456;
        font-style: bold;
        text-align: right
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
        height: 200px;
        text-align: center;
        top: 0px;
    }

    .contenido{
        margin-top: 30px;
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
        top: 12% !important;
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
        margin-top: 20px; margin-left: 5px; margin-bottom: 20px;
    }

    .title-cell{
        color:#fff;  padding: 1px; margin: 0px; text-align: center; font-size: 7pt;
    }

    .cell-detalle p{
        margin:0px; padding: 0px; font-size: 7pt;
        margin-top: 0px;
        margin-right: 5px;
    }

    .table {display: table; width: 100%; border-collapse: collapse; table-layout: fixed;}
    .table-row {display: table-row;}
    .table-cell1 {display: table-cell; height: 10px !important;
        vertical-align: middle; padding-left: 0.3cm; padding-bottom: 0.1cm; padding-top: 0.1cm;}
    .cell-title {display: table-cell; vertical-align: middle; padding-left: 0.3cm;}

</style>

<body>
    <div class="header">
        <div class="table" >
            <div class="table-row">
                {{-- Logo segun empresa constructora --}}
                    <div  class="table-cell1" style="padding-left: 40px;">
                        <img SRC="img/solicPagos/Cumbres.png" height="90">
                    </div>
                <div colspan="1" class="table-cell1">
                </div>
                <div colspan="3" class="table-cell1">
                    <p class="h1">REPORTE DE PAGO</p>
                    <p class="h1">
                        <strong>DE ESTIMACIONES</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="contenido">
        <div class="table">
            <div class="table-row" style="background-color: #0C3456;">
                <div colspan="2" class="table-cell1" style="background-color: #0C3456; height:18px;"></div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <p class="contratista">
                        <strong><i> {{$contratista}}</i></strong>
                    </p>
                </div>
                <div colspan="2" class="table-cell1 title-cabecera"
                >
                        <p class="fecha" style="margin-bottom: -8px; margin-right: 25px; font-size:11pt;">
                            Fecha de elaboración
                        </p>
                        <p class="fecha" style="margin-top: 0px; margin-right: 25px; font-size:12pt;">
                            {{$fecha}}
                        </p>
                </div>
            </div>
        </div>
    </div>

    <div class="detalle">

        <div class="table">

            <div class="table-row" style="margin-bottom:10px;">
                <div colspan="5" class="table-cell1">
                    <div class="table">
                        <div class="table-row" style="background-color: #0C3456;">
                            <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                                <p class="title-cell">CONCEPTO</p>
                            </div>
                            <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                                <p class="title-cell">ANTICIPO</p>
                            </div>
                            <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                                <p class="title-cell">ESTIMACIÓN</p>
                            </div>
                            <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                                <p class="title-cell">F. G.</p>
                            </div>
                            <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                                <p class="title-cell">OBRA EXTRA</p>
                            </div>
                        </div>
                        @foreach($resumen as $p)
                            <div class="table-row" style="background-color: #487296;">
                                <div colspan="5" class="table-cell1" style="background-color: #487296;">
                                    <p class="title-cell" style="text-align: center;">TRABAJOS DE EDIFICACIÓN {{$p->fraccionamiento}}</p>
                                </div>
                            </div>
                            @foreach($p->data as $det)
                                <div class="table-row">
                                    <div colspan="1" class="table-cell1 cell-detalle">
                                        <p>{{ $det->clave }}</p>
                                    </div>
                                    <div colspan="1" class="table-cell1 cell-detalle">
                                        <p>$ {{number_format((float)$det->anticipo, 2, '.', ',')}}</p>
                                    </div>
                                    <div colspan="1" class="table-cell1 cell-detalle">
                                        <p>$ {{number_format((float)$det->total_pagado, 2, '.', ',')}}</p>
                                    </div>
                                    <div colspan="1" class="table-cell1 cell-detalle">
                                        <p>$ {{number_format((float)$det->fg, 2, '.', ',')}}</p>
                                    </div>
                                    <div colspan="1" class="table-cell1 cell-detalle">
                                        <p>$ {{number_format((float)$det->extra, 2, '.', ',')}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach

                        {{-- <div class="table-row" style="background-color: #0C3456;">
                            <div colspan="4" class="table-cell1" style="background-color: #0C3456;">
                                <p class="title-cell" style="text-align: right;">Total del pago</p>
                            </div>
                            <div colspan="1" class="table-cell1" style="background-color: #fff; border: 1pt solid #0C3456;">
                                <p> $ 999.99 </p>
                            </div>

                        </div> --}}
                    </div>
                </div>

                <div colspan="3" class="table-cell1">
                    <div class="table">
                        <div class="table-row" style="background-color: #0C3456;">
                            @if($iva == 1)
                                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                                    <p class="title-cell">PRECIO</p>
                                </div>
                                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                                    <p class="title-cell">IVA</p>
                                </div>
                                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                                    <p class="title-cell">TOTAL</p>
                                </div>
                            @else
                                <div colspan="3" class="table-cell1" style="background-color: #0C3456;">
                                    <p class="title-cell">PRECIO</p>
                                </div>
                            @endif

                        </div>
                        @foreach($resumen as $p)
                            <div class="table-row" style="background-color: #487296;">
                                <div colspan="3" class="table-cell1" style="background-color: #487296;">
                                    <p class="title-cell" style="text-align: center;">TRABAJOS DE EDIFICACIÓN {{$p->fraccionamiento}}</p>
                                </div>
                            </div>
                            <div class="table-row">
                                @if($iva == 1)
                                    <div colspan="1" style="height: {{sizeof($p->data)*23}}px !important; text-align: center;" class="table-cell1 cell-detalle">
                                        <p>$ {{number_format((float)$p->total - $p->monto_iva, 2, '.', ',')}}</p>
                                    </div>
                                    <div colspan="1" style="height: {{sizeof($p->data)*23}}px !important; text-align: center;" class="table-cell1 cell-detalle">
                                        <p>$ {{number_format((float)$p->monto_iva, 2, '.', ',')}}</p>
                                    </div>
                                    <div colspan="1" style="height: {{sizeof($p->data)*23}}px !important; text-align: center;" class="table-cell1 cell-detalle">
                                        <p>$ {{number_format((float)$p->total, 2, '.', ',')}}</p>
                                    </div>
                                @else
                                    <div colspan="3" style="height: {{sizeof($p->data)*23}}px !important; text-align: center;" class="table-cell1 cell-detalle">
                                        <p>$ {{number_format((float)$p->total, 2, '.', ',')}}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>

            <div class="table-row" style="background-color: #0C3456;">
                <div colspan="2" class="table-cell1" style="background-color: #fff;">
                </div>
                <div colspan="4" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell"
                        style="text-align: right; margin-right: 15px; font-size:11pt; padding: 0px;">
                        TOTAL DEL PAGO:
                    </p>
                </div>
                <div colspan="2" class="table-cell1"
                    style="background-color: #fff; border: 1pt solid #0C3456; font-size:12pt; padding: 0px;">
                    <p style="margin: 0px;"> $ {{number_format((float)$total, 2, '.', ',')}}</p>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
