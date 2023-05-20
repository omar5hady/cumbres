<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Solicitud de Pago (Transferencia)</title>
</head>
<style type="text/css">
    /* @import url('https://fonts.googleapis.com/css?family=Muli&display=swap'); */

    @page{
        margin: .8cm .8cm .8cm .8cm;
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

    .det-title{
        top: 9% !important;
        text-align: CENTER;
        font-size: 10pt;
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
        margin-top: 0px;
        margin-right: 5px;
    }

    .table {display: table; width: 100%; border-collapse: collapse; table-layout: fixed;}
    .table-row {display: table-row;}
    .table-cell1 {display: table-cell; vertical-align: middle; padding-left: 0.3cm;}
    .cell-title {display: table-cell; vertical-align: middle; padding-left: 0.3cm;}

</style>

<body>
    <div class="header">
        <div class="table" >
            <div class="table-row">
                {{-- Logo segun empresa constructora --}}
                @if($solicitud->empresa_solic == 'CONCRETANIA')
                    <div  class="table-cell1" style="padding-left: 50px;">
                        <img SRC="img/solicPagos/Concretania.png" height="80">
                    </div>
                @elseif($solicitud->empresa_solic == 'SER CUMBRES')
                    <div  class="table-cell1" style="padding-left: 130px;">
                        <img SRC="img/solicPagos/SerCumbres.png" height="70">
                    </div>
                @elseif($solicitud->empresa_solic == 'MAGNACASA')
                    <div  class="table-cell1" style="padding-left: 40px;">
                        <img SRC="img/solicPagos/magnaCasa.png" height="90">
                    </div>
                @else
                    <div  class="table-cell1" style="padding-left: 40px;">
                        <img SRC="img/solicPagos/Cumbres.png" height="90">
                    </div>
                @endif
                <div colspan="1" class="table-cell1">
                </div>
                <div colspan="3" class="table-cell1">
                    <p class="h1">SOLICITUD DE <strong>PAGO</strong></p>
                    <p class="subtitle">
                        @if($solicitud->tipo_pago == 0)
                            CF
                        @else
                            @if($solicitud->forma_pago == 0)
                                TRANSFERENCIA
                            @else
                                CHEQUE
                            @endif
                        @endif
                    </p>
                </div>
            </div>

            <div class="table-row">
                <div colspan="2" class="table-cell1">
                </div>
                <div colspan="3" class="table-cell1">
                    <p class="estatus">
                        {{$solicitud->estatus}}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="contenido">
        <div class="table">
            <div class="table-row" style="background-color: #0C3456;">
                <div colspan="4" class="table-cell1" style="background-color: #0C3456; height:18px;"></div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <p>Empresa solicitante</p>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <p>¿Solicitud Extraordinaria?</p>
                </div>
                <div colspan="2" class="table-cell1 title-cabecera">
                    @if($solicitud->tipo_pago == 1)
                        <p>Cuenta de salida</p>
                    @endif
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div class="info-cabecera">
                        <p>{{$solicitud->empresa_solic}}</p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div class="info-cabecera">
                        <p>
                            @if($solicitud->extraordinario == 0)
                                No
                            @else
                                Si
                            @endif
                        </p>
                    </div>
                </div>
                <div colspan="2" class="table-cell1 title-cabecera">
                    @if($solicitud->tipo_pago == 1)
                        <div class="info-cabecera">
                            <p>{{$solicitud->cuenta_pago}}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera">
                    <div class="info-cabecera" style="background: #0C3456;">
                        <p style="color:#fff; font-style:bold; padding: 1px;">Proveedor</p>
                    </div>

                </div>
                <div colspan="2" class="table-cell1 title-cabecera">
                    <p>Importe de la solicitud</p>
                </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera">
                    <div class="info-cabecera" style="background-color: #fff;">
                        <p style="font-size:9.5pt; margin-top:-15px;"><strong>{{$solicitud->proveedor}}</strong></p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <div class="info-cabecera" style="background-color: #fff; border: 1pt solid #0C3456;">
                        <p style="font-size: 11.5pt; margin-top:-5px;">$ {{number_format((float)$solicitud->importe, 2, '.', ',')}}</p>
                    </div>
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera">
                    @if($solicitud->forma_pago == 0 && $solicitud->tipo_pago == 1)
                        <p>Cuenta Destino</p>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera"></div>
                <div colspan="1" class="table-cell1 title-cabecera"></div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1 title-cabecera">
                    @if($solicitud->forma_pago == 0 && $solicitud->tipo_pago == 1)
                        <div class="info-cabecera">
                            <p>{{$solicitud->banco}} - {{$solicitud->num_cuenta}}</p>
                        </div>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    @if($solicitud->forma_pago == 0 && $solicitud->tipo_pago == 1)
                        <p>Clabe interbancaria</p>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    @if($solicitud->forma_pago == 0 && $solicitud->tipo_pago == 1 && $solicitud->convenio != NULL)
                        <p>Convenio</p>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    @if($solicitud->forma_pago == 0 && $solicitud->tipo_pago == 1 && $solicitud->referencia != NULL)
                        <p>Referencia</p>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    Fecha de Solicitud
                </div>
            </div>
            <div class="table-row">
                <div colspan="1" class="table-cell1 title-cabecera">
                    @if($solicitud->forma_pago == 0 && $solicitud->tipo_pago == 1)
                        <div class="info-cabecera">
                            <p>{{$solicitud->clabe}}&nbsp;</p>
                        </div>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    @if($solicitud->forma_pago == 0 && $solicitud->tipo_pago == 1 && $solicitud->convenio != NULL)
                        <div class="info-cabecera">
                            <p>{{$solicitud->convenio}}&nbsp;</p>
                        </div>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    @if($solicitud->forma_pago == 0 && $solicitud->tipo_pago == 1 && $solicitud->referencia != NULL)
                        <div class="info-cabecera">
                            <p>{{$solicitud->referencia}}&nbsp;</p>
                        </div>
                    @endif
                </div>
                <div colspan="1" class="table-cell1 title-cabecera">
                    <p style="font-size:8pt; margin-top:-8px;">
                        {{$solicitud->f_created}}
                    </p>
                </div>
            </div>
            @if($solicitud->tipo_pago == 1 && $solicitud->forma_pago == 1 && $solicitud->fecha_elab_cheque)

                <div class="table-row">
                    <div colspan="3" class="table-cell1 title-cabecera">
                       <br>
                    </div>

                    <div colspan="1" class="table-cell1 title-cabecera">
                        <p>
                            Fecha de elaboración de cheque:
                        </p>
                    </div>
                </div>
                <div class="table-row">
                    <div colspan="3" class="table-cell1 title-cabecera">
                       <br>
                    </div>

                    <div colspan="1" class="table-cell1 title-cabecera">
                        <div class="info-cabecera">
                            {{$solicitud->fecha_elab_cheque}}
                        </div>
                    </div>
                </div>
            @endif

            <div class="table-row">
                <div colspan="4" class="table-cell1 title-cabecera">
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="contenido det-title">
        <p>DETALLE DE LA SOLICITUD</p>
    </div>

    <div class="detalle">
        <div class="table">
            <div class="table-row" style="background-color: #0C3456;">
                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell">Obra</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell">Cargo</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell">Subconcepto</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell">Obs.</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell">Tipo Mov.</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell">Importe Total</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell">Este pago</p>
                </div>

            </div>
            @foreach($solicitud->det as $det)
            <div class="table-row">
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$det->obra}} {{$det->sub_obra }}
                        @if($det->lote_id)
                            <br>Mzn. {{$det->manzana}}
                            Lote {{$det->num_lote}} {{$det->sublote}}
                        @endif
                    </p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$det->cargo}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$det->concepto}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$det->observacion}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>{{$det->obra}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>${{number_format((float)$det->total, 2, '.', ',')}}</p>
                </div>
                <div colspan="1" class="table-cell1 cell-detalle">
                    <p>${{number_format((float)$det->pago, 2, '.', ',')}}</p>
                </div>
            </div>
            @endforeach
            <div class="table-row" style="background-color: #0C3456;">
                <div colspan="6" class="table-cell1" style="background-color: #0C3456;">
                    <p class="title-cell" style="text-align: right;">Total del pago</p>
                </div>
                <div colspan="1" class="table-cell1" style="background-color: #fff; border: 1pt solid #0C3456;">
                    <p> ${{number_format((float)$solicitud->importe, 2, '.', ',')}} </p>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
