<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato de compra-venta</title>
</head>
<style type="text/css">
    @page {
        margin: 0cm 0cm 0cm 0cm;
    }

    body {
        font-size: 7pt;
        font-family: sans-serif;
    }

    .container {
        background-color: #0B173B;
        color: #fff;
        padding-left: 10px;
        padding-right: 10px;

    }

    .table1 {
        display: table;
        width: 84%;
        border-collapse: collapse;
    }

    .table-row {
        display: table-row;
    }

    .table-cell1 {
        display: table-cell;
        padding: 0.5em;
        font-size: 8pt;
    }

    .table {
        display: table;
        width: 116%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table-cell {
        display: table-cell;
        padding: 1px;
        padding-left: 5px;
        font-size: 7pt;
    }

    .table-cell2 {
        display: table-cell;
        padding-left: 3em;
        font-size: 7pt;
    }

    .table-cell3 {
        display: table-cell;
        padding: 0em;
        font-size: 10pt;
    }

    .table3 {
        display: table;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .hoja1{
        margin-top: 0.5cm;
        margin-left: 1cm;
        margin-right: 3.5cm;
        margin-bottom: 0.5cm;
        position: absolute;
    }

    .backblue{
        text-align: center;
        background-color: #003058;
        color: white;
        width: 100%;
        position: fixed;
    }


    .header{
        top: 0px;
    }

    .cover-container{
        top: 10%;
        background-color: #003058;
        opacity: 0.1;
        width: 100%;
        height: 240px;
        position: fixed;
    }

    .contenido{
        top: 140px;
        position: fixed;
        color: #003058;
        width: 100%;
        margin: 40px;
        text-align: center;
        align-items: center;
        font-size: 10pt;

        height: 40%;
    }

    .info{
        top: 31% !important;
        margin-left: 80px;
        margin-right: 80px;
        text-align: center;
    }

    .h1{
        font-size: 14.5pt;
        font-style: italic;
    }

    .sub-title-cell{
        style="border: ridge #0B173B 1px; font-size:10pt; color:white; margin-right: 300px; margin-top:25px; padding-left:5px; background-color: #0B173B;
    }

    .table-pagos { display: table; width: 98%; border-collapse: collapse; table-layout: fixed;}
    .table-bancaria { display: table; width: 98%; border-collapse: collapse; table-layout: fixed; line-height: 1%;}
    .table-row-pagos { display: table-row; align-items: center; text-align:center; vertical-align: middle;  color: white;}
    .table-cell-pago { display: table-cell; text-align:center; vertical-align: middle;}
    .table-cell-left { display: table-cell; text-align:left; vertical-align: middle;}
    .table-cell-right { display: table-cell; text-align:right; vertical-align: middle;}


</style>

<body>

    <div class="hoja1">
        <div style="position: static;">
            <p align="center"
                style="border: ridge #0B173B 1px; font-size:13pt; color:white; margin-right: 100px; background-color: #0B173B;">
                <strong>SOLICITUD DE CONTRATO DE COMPRA-VENTA</strong></p>

            <div class="table1" style="margin-top: -12px;">
                <div class="table-row" style="padding:0px;">
                    <div style="border: ridge #0B173B 1px; padding-right:-20px; color:white; font-size:8pt; background-color: #0B173B;"
                        class="table-cell1">FECHA</div>
                    <div style="border: ridge #000000 1px; color:black; " class="table-cell1">
                        {{ strtoupper($contratos[0]->fecha) }}</div>
                    <div style="border: ridge #000000 1px; color:black; " class="table-cell1">#Ref:
                        {{ $contratos[0]->id }} - {{ $contratos[0]->avance_lote }}%</div>
                    <div style="border: ridge #000000 1px; color:black; " class="table-cell1">TERMINO:
                        {{ strtoupper($contratos[0]->fecha_termino_ventas) }}</div>
                </div>
            </div>
        </div>
        <div style="position: static;  margin-top: -20px;">
            <p align="left" class="sub-title-cell">
                DATOS DEL CREDITO</p>
            <div class="table1" style="border: ridge #0B173B 1px; color:black;  margin-top: -10px;">
                <div class="table-row">
                    <div class="table-cell1">TIPO DE CREDITO: <u>{{ mb_strtoupper($contratos[0]->tipo_credito) }}</u>
                    </div>
                    <div class="table-cell1">INSTITUCION: <u>{{ strtoupper($contratos[0]->institucion) }}</u> </div>
                </div>
                <div class="table-row">
                    <div class="table-cell1">PLAZO: <u>{{ $contratos[0]->plazo }} AÑOS</u> </div>
                    <div class="table-cell1">DENOMINACION: <u>PESOS</u> </div>
                </div>
            </div>
        </div>

        <div style="position: static;  margin-top: -25px;">
            <p align="left" class="sub-title-cell">
                DATOS DEL COMPRADOR</p>
            <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: -10px;">
                <div class="table-row">
                    <div colspan="4"  class="table-cell">NOMBRE: <u>{{ mb_strtoupper($contratos[0]->apellidos) }}
                            {{ mb_strtoupper($contratos[0]->nombre) }}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell">N.S.S. <u>{{ $contratos[0]->nss }}</u></div>
                    <div class="table-cell">R.F.C. <u>{{ mb_strtoupper($contratos[0]->rfc) }} -
                            {{ mb_strtoupper($contratos[0]->homoclave) }}</u></div>
                    <div class="table-cell">C.U.R.P. <u>{{ mb_strtoupper($contratos[0]->curp) }}</u></div>
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell">LUGAR DE NACIMIENTO:
                        <u>{{ strtoupper($contratos[0]->lugar_nacimiento) }}</u></div>
                    <div class="table-cell">FECHA DE NACIMIENTO: <u>{{ $contratos[0]->f_nacimiento }}</u></div>
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                    @if ($contratos[0]->edo_civil == 1)
                        <div class="table-cell">ESTADO CIVIL: <u>CASADO</u></div>
                        <div class="table-cell"></div>
                        <div class="table-cell" colspan="2">REGIMEN DE MATRIMONIO: <u>SEPARACION DE BIENES</u></div>
                    @endif

                    @if ($contratos[0]->edo_civil == 2)
                        <div class="table-cell">ESTADO CIVIL: <u>CASADO</u></div>
                        <div class="table-cell"></div>
                        <div class="table-cell" colspan="2">REGIMEN DE MATRIMONIO: <u>SOCIEDAD CONYUGAL</u></div>
                    @endif

                    @if ($contratos[0]->edo_civil == 3)
                        <div class="table-cell">ESTADO CIVIL: <u>DIVORICIADO</u></div>
                        <div class="table-cell"></div>
                        <div class="table-cell" colspan="2"></div>
                    @endif
                    @if ($contratos[0]->edo_civil == 4)
                        <div class="table-cell">ESTADO CIVIL: <u>SOLTERO</u></div>
                        <div class="table-cell"></div>
                        <div class="table-cell" colspan="2"></div>
                    @endif
                    @if ($contratos[0]->edo_civil == 5)
                        <div class="table-cell">ESTADO CIVIL: <u>UNION LIBRE</u></div>
                        <div class="table-cell"></div>
                        <div class="table-cell" colspan="2"></div>
                    @endif
                    @if ($contratos[0]->edo_civil == 6)
                        <div class="table-cell">ESTADO CIVIL: <u>VIUDO</u></div>
                        <div class="table-cell"></div>
                        <div class="table-cell" colspan="2"></div>
                    @endif
                    @if ($contratos[0]->edo_civil == 7)
                        <div class="table-cell">ESTADO CIVIL: <u>OTRO</u></div>
                        <div class="table-cell"></div>
                        <div class="table-cell" colspan="2"></div>
                    @endif
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="2">DOMICILIO ACTUAL:
                        <u>{{ mb_strtoupper($contratos[0]->direccion) }}</u></div>
                    <div colspan="2" class="table-cell">COLONIA: <u>{{ mb_strtoupper($contratos[0]->colonia) }}</u>
                    </div>

                </div>
                <div class="table-row">
                    <div class="table-cell">MUNICIPIO: <u>{{ mb_strtoupper($contratos[0]->ciudad) }}</u></div>
                    <div class="table-cell">ESTADO: <u>{{ mb_strtoupper($contratos[0]->estado) }}</u></div>
                    <div class="table-cell">C.P. <u>{{ $contratos[0]->cp }}</u></div>
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                    <div class="table-cell">TELEFONO: <u>{{ $contratos[0]->telefono }}</u></div>
                    <div class="table-cell">CELULAR: <u>+{{ $contratos[0]->clv_lada }}
                            {{ $contratos[0]->celular }}</u></div>
                    <div colspan="2" class="table-cell">EMAIL PERSONAL:
                        <u>{{ strtoupper($contratos[0]->email) }}</u></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell">EMPRESA: <u>{{ mb_strtoupper($contratos[0]->empresa) }}</u>
                    </div>
                    <div colspan="2" class="table-cell">EMAIL EMPRESA:
                        <u>{{ strtoupper($contratos[0]->email_institucional) }}</u></div>
                </div>
                <div class="table-row">
                    <div colspan="4" class="table-cell">DOMICILIO DE EMPRESA:
                        <u>{{ mb_strtoupper($contratos[0]->direccion_empresa) }}
                            {{ strtoupper($contratos[0]->colonia_empresa) }} C.P. {{ $contratos[0]->cp_empresa }}
                            {{ strtoupper($contratos[0]->ciudad_empresa) }},
                            {{ strtoupper($contratos[0]->estado_empresa) }}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="2">OCUPACION: <u>{{ strtoupper($contratos[0]->puesto) }}</u>
                    </div>
                    <div class="table-cell">TEL. DEL TRABAJO: <u>{{ $contratos[0]->telefono_empresa }}</u></div>
                    <div class="table-cell">EXTENSION: <u>{{ $contratos[0]->ext_empresa }}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="2">NOMBRE DE REFERENCIA 1:
                        <u>{{ mb_strtoupper($contratos[0]->nombre_primera_ref) }}</u></div>
                    <div class="table-cell">TELEFONO: <u>{{ $contratos[0]->telefono_primera_ref }}</u></div>
                    <div class="table-cell">CELULAR: <u>{{ $contratos[0]->celular_primera_ref }}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="2">NOMBRE DE REFERENCIA 2:
                        <u>{{ mb_strtoupper($contratos[0]->nombre_segunda_ref) }}</u></div>
                    <div class="table-cell">TELEFONO: <u>{{ $contratos[0]->telefono_segunda_ref }}</u></div>
                    <div class="table-cell">CELULAR: <u>{{ $contratos[0]->celular_segunda_ref }}</u></div>
                </div>
            </div>
        </div>
        @if ($contratos[0]->coacreditado == 1)
            <div style="position: static; margin-top: -25px;">
                <p align="left" class="sub-title-cell">
                    DATOS DEL CONYUGE O COACREDITADO</p>
                <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: -10px;">
                    <div class="table-row">
                        <div colspan="2" class="table-cell">NOMBRE:
                            <u>{{ mb_strtoupper($contratos[0]->nombre_coa) }}
                                {{ mb_strtoupper($contratos[0]->apellidos_coa) }}</u></div>
                        <div class="table-cell">R.F.C. <u>{{ mb_strtoupper($contratos[0]->rfc_coa) }} -
                                {{ mb_strtoupper($contratos[0]->homoclave_coa) }}</u></div>
                        <div class="table-cell">C.U.R.P. <u>{{ mb_strtoupper($contratos[0]->curp_coa) }}</u></div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell">LUGAR DE NACIMIENTO:
                            <u>{{ strtoupper($contratos[0]->lugar_nacimiento_coa) }}</u></div>
                        <div class="table-cell">FECHA DE NACIMIENTO: <u>{{ $contratos[0]->f_nacimiento_coa }}</u>
                        </div>
                        <div class="table-cell">N.S.S. <u>{{ $contratos[0]->nss_coa }}</u></div>
                    </div>
                    {{-- <div class="table-row">
                        @if ($contratos[0]->edo_civil_coa == 1)
                            <div class="table-cell">ESTADO CIVIL: <u>CASADO</u></div>
                            <div class="table-cell"></div>
                            <div class="table-cell" colspan="2">REGIMEN DE MATRIMONIO: <u>SEPARACION DE BIENES</u>
                            </div>
                        @endif

                        @if ($contratos[0]->edo_civil_coa == 2)
                            <div class="table-cell">ESTADO CIVIL: <u>CASADO</u></div>
                            <div class="table-cell"></div>
                            <div class="table-cell" colspan="2">REGIMEN DE MATRIMONIO: <u>SOCIEDAD CONYUGAL</u>
                            </div>
                        @endif

                        @if ($contratos[0]->edo_civil_coa == 3)
                            <div class="table-cell">ESTADO CIVIL: <u>DIVORICIADO</u></div>
                            <div class="table-cell"></div>
                            <div class="table-cell" colspan="2"></div>
                        @endif
                        @if ($contratos[0]->edo_civil_coa == 4)
                            <div class="table-cell">ESTADO CIVIL: <u>SOLTERO</u></div>
                            <div class="table-cell"></div>
                            <div class="table-cell" colspan="2"></div>
                        @endif
                        @if ($contratos[0]->edo_civil_coa == 5)
                            <div class="table-cell">ESTADO CIVIL: <u>UNION LIBRE</u></div>
                            <div class="table-cell"></div>
                            <div class="table-cell" colspan="2"></div>
                        @endif
                        @if ($contratos[0]->edo_civil_coa == 6)
                            <div class="table-cell">ESTADO CIVIL: <u>VIUDO</u></div>
                            <div class="table-cell"></div>
                            <div class="table-cell" colspan="2"></div>
                        @endif
                        @if ($contratos[0]->edo_civil_coa == 7)
                            <div class="table-cell">ESTADO CIVIL: <u>OTRO</u></div>
                            <div class="table-cell"></div>
                            <div class="table-cell" colspan="2"></div>
                        @endif
                    </div> --}}
                    <div class="table-row">
                        <div colspan="2" class="table-cell">DOMICILIO ACTUAL:
                            <u>{{ mb_strtoupper($contratos[0]->direccion_coa) }}</u></div>
                        <div class="table-cell">COLONIA: <u>{{ mb_strtoupper($contratos[0]->colonia_coa) }}</u></div>
                        <div class="table-cell">C.P. <u>{{ $contratos[0]->cp_coa }}</u></div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell">MUNICIPIO:
                            <u>{{ strtoupper($contratos[0]->ciudad_coa) }}</u></div>
                        <div colspan="2" class="table-cell">ESTADO:
                            <u>{{ strtoupper($contratos[0]->estado_coa) }}</u></div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell">OCUPACION:
                            <u>{{ mb_strtoupper($contratos[0]->puesto_coa) }}</u></div>
                        <div colspan="2" class="table-cell">EMPRESA DONDE TRABAJA:
                            <u>{{ mb_strtoupper($contratos[0]->empresa_coa) }}</u></div>
                    </div>
                    <div class="table-row">
                        <div colspan="4" class="table-cell">DOMICILIO DE EMPRESA:
                            <u>{{ mb_strtoupper($contratos[0]->direccion_empresa_coa) }}
                                {{ strtoupper($contratos[0]->colonia_empresa_coa) }} C.P.
                                {{ $contratos[0]->cp_empresa_coa }}
                                {{ strtoupper($contratos[0]->ciudad_empresa_coa) }},
                                {{ strtoupper($contratos[0]->estado_empresa_coa) }}</u></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell">EMAIL: <u>{{ mb_strtoupper($contratos[0]->email_coa) }}</u></div>
                        <div class="table-cell">TEL.: <u>{{ $contratos[0]->telefono_coa }}</u></div>
                        <div class="table-cell">CEL.: <u>{{ $contratos[0]->celular_coa }}</u></div>
                        <div class="table-cell">TEL.TRABAJO: <u>{{ $contratos[0]->telefono_empresa_coa }}</u></div>
                    </div>
                </div>
            </div>
        @endif

        <div style="position: static; margin-top: -20px;">
            @if ($contratos[0]->tipo_proyecto == 1)
                @if ($contratos[0]->modelo == 'Terreno')
                    <p align="left" class="sub-title-cell">
                        DATOS DEL TERRENO</p>
                @else
                    <p align="left" class="sub-title-cell">
                        DATOS DE LA VIVIENDA</p>
                @endif
            @elseif($contratos[0]->tipo_proyecto == 2)
                <p align="left" class="sub-title-cell">
                    DATOS DEL DEPARTAMENTO</p>
            @endif
            <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: -10px;">
                @if ($contratos[0]->tipo_proyecto == 1)
                    <div class="table-row">
                        <div class="table-cell">FRACCIONAMIENTO: <u>{{ mb_strtoupper($contratos[0]->proyecto) }}</u>
                        </div>
                        <div class="table-cell">MANZANA: <u>{{ mb_strtoupper($contratos[0]->manzana) }}</u></div>
                        @if ($contratos[0]->num_lote == null)
                            <div class="table-cell">LOTE: <u>{{ $contratos[0]->num_lote }}</u></div>
                        @else
                            <div class="table-cell">LOTE: <u>{{ $contratos[0]->num_lote }}
                                    {{ $contratos[0]->sublote }}</u></div>
                        @endif
                    </div>
                @elseif($contratos[0]->tipo_proyecto == 1)
                    <div class="table-row">
                        <div class="table-cell">FRACCIONAMIENTO: <u>{{ mb_strtoupper($contratos[0]->proyecto) }}</u>
                        </div>
                        <div class="table-cell">NIVEL: <u>{{ mb_strtoupper($contratos[0]->manzana) }}</u></div>
                        @if ($contratos[0]->num_lote == null)
                            <div class="table-cell">DEPARTAMENTO: <u>{{ $contratos[0]->num_lote }}</u></div>
                        @else
                            <div class="table-cell">DEPARTAMENTO: <u>{{ $contratos[0]->num_lote }}
                                    {{ $contratos[0]->sublote }}</u></div>
                        @endif
                    </div>
                @endif
                <div class="table-row">
                    <div class="table-cell">CALLE: <u>{{ mb_strtoupper($contratos[0]->calle) }}</u></div>
                    <div class="table-cell">NO. OFICIAL: <u>{{ $contratos[0]->numero }} INT.
                            {{ strtoupper($contratos[0]->interior) }}</u></div>
                    <div class="table-cell">MODELO: <u>{{ mb_strtoupper($contratos[0]->modelo) }}</u></div>
                </div>
                <div class="table-row">
                    @if ($contratos[0]->tipo_proyecto == 1)
                        <div class="table-cell">SUPERFICIE DE TERRENO:
                            <u>{{ $contratos[0]->terreno }} M2
                                @if ($contratos[0]->emp_constructora == 'CONCRETANIA' &&
                                    $contratos[0]->emp_terreno == 'Grupo Constructor Cumbres')
                                    ( {{ $contratos[0]->porcentaje_terreno }}% )
                                @endif
                            </u>
                        </div>
                    @endif
                    @if ($contratos[0]->modelo != 'Terreno')
                        <div class="table-cell" colspan="2">SUPERFICIE DE CONSTRUCCION:
                            <u>{{ $contratos[0]->construccion }} M2
                                @if ($contratos[0]->emp_constructora == 'CONCRETANIA' &&
                                    $contratos[0]->emp_terreno == 'Grupo Constructor Cumbres' &&
                                    $contratos[0]->tipo_proyecto == 1)
                                    ( {{ 100 - $contratos[0]->porcentaje_terreno }}% )
                                @endif
                            </u>
                        </div>
                    @else
                        <div class="table-cell" colspan="2"></div>
                    @endif
                    @if ($contratos[0]->tipo_proyecto == 2)
                        <div class="table-cell">
                        </div>
                    @endif

                </div>
                <div class="table-row">
                    <div colspan="3" class="table-cell">PAQUETE: {{ mb_strtoupper($contratos[0]->paquete) }}
                    </div>
                </div>
                <div class="table-row">
                    <div colspan="3" class="table-cell">PROMOCION: {{ mb_strtoupper($contratos[0]->promocion) }}
                    </div>
                </div>
                @if($contratos[0]->descuento_desc != '' && $contratos[0]->descuento_desc != NULL)
                    <div class="table-row">
                        <div colspan="3" class="table-cell">PREMIO: {{ mb_strtoupper($contratos[0]->descuento_desc) }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div style="position: static; margin-top: -20px;">
            <p align="left" class="sub-title-cell">
                PRESUPUESTO</p>
            <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: -10px;">

                @if ($contratos[0]->tipo_proyecto == 1)
                    <div class="table-row">
                        @if ($contratos[0]->infonavit > 0)
                            @if ($contratos[0]->tipo_credito == 'BANJERCITO')
                                <div class="table-cell2">BANJERCITO: </div>
                            @else
                                <div class="table-cell2">INFONAVIT: </div>
                            @endif
                            <div class="table-cell2">${{ $contratos[0]->infonavit }}</div>
                        @elseif($contratos[0]->fovisste > 0)
                            <div class="table-cell2">FOVISSTE: </div>
                            <div class="table-cell2">${{ $contratos[0]->fovisste }}</div>
                        @else
                            <div class="table-cell2"></div>
                            <div class="table-cell2"></div>
                        @endif
                        @if ($contratos[0]->modelo != 'Terreno')
                            <div class="table-cell2">PRECIO DE LA VIVIENDA: </div>
                        @else
                            <div class="table-cell2">PRECIO DEL LOTE: </div>
                        @endif
                        <div class="table-cell2">${{ $contratos[0]->precio_base }}</div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell2">{{ mb_strtoupper($contratos[0]->institucion) }}: </div>
                        <div class="table-cell2">${{ $contratos[0]->credito_solic }}</div>
                        <div class="table-cell2"><u>{{ $contratos[0]->terreno_excedente }} M2</u> TERR. EXCEDENTE:
                        </div>
                        <div class="table-cell2">${{ $contratos[0]->precio_terreno_excedente }}</div>
                    </div>
                    <div class="table-row">
                        @if ($contratos[0]->modelo != 'Terreno')
                            <div class="table-cell2">COMISION X APERTURA: </div>
                            <div class="table-cell2">${{ $contratos[0]->comision_apertura }}</div>
                            <div class="table-cell2">OBRA EXTRA: </div>
                            <div class="table-cell2">${{ $contratos[0]->precio_obra_extra }}</div>
                        @else
                            <div class="table-cell2"></div>
                            <div class="table-cell2"></div>
                            <div class="table-cell2"></div>
                            <div class="table-cell2"></div>
                        @endif

                    </div>
                    <div class="table-row">
                        @if ($contratos[0]->modelo != 'Terreno')
                            <div class="table-cell2">INVESTIGACION: </div>
                            <div class="table-cell2">${{ $contratos[0]->investigacion }}</div>
                        @else
                            <div class="table-cell2"></div>
                            <div class="table-cell2"></div>
                        @endif
                        <div class="table-cell2">SOBREPRECIO: </div>
                        <div class="table-cell2">${{ $contratos[0]->sobreprecio }}</div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell2">AVALUO: </div>
                        <div class="table-cell2">${{ $contratos[0]->avaluo }}</div>
                        <div class="table-cell2">PAQUETE: </div>
                        <div class="table-cell2">${{ $contratos[0]->costo_paquete }}</div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell2">GASTOS DE ESCRITURACION: </div>
                        <div class="table-cell2">${{ $contratos[0]->escrituras }}</div>
                        @if ($contratos[0]->modelo != 'Terreno')
                            <div class="table-cell2">VALOR TOTAL CASA: </div>
                        @else
                            <div class="table-cell2">VALOR TOTAL LOTE: </div>
                        @endif
                        <div class="table-cell2">${{ $contratos[0]->precio_venta }}</div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell2">CREDITO NETO {{ strtoupper($contratos[0]->institucion) }}: </div>
                        <div class="table-cell2">${{ $contratos[0]->credito_neto }}</div>
                        <div class="table-cell2">MONTO NETO CREDITO: </div>
                        <div class="table-cell2">${{ $contratos[0]->monto_total_credito }}</div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                        <div class="table-cell2">TOTAL A PAGAR: </div>
                        <div class="table-cell2">${{ $contratos[0]->total_pagar }}</div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                        <div class="table-cell2">AVALUO: </div>
                        <div class="table-cell2">${{ $contratos[0]->avaluo_cliente }}</div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                        <div class="table-cell2"><b> ENGANCHE TOTAL: </div>
                        <div class="table-cell2"><b>${{ $contratos[0]->enganche_total }}</div>
                    </div>
                @elseif($contratos[0]->tipo_proyecto == 2)
                    <div class="table-row">
                        @if ($contratos[0]->infonavit > 0)
                            <div class="table-cell2">INFONAVIT: </div>
                            <div class="table-cell2">${{ $contratos[0]->infonavit }}</div>
                        @elseif($contratos[0]->fovisste > 0)
                            <div class="table-cell2">FOVISSTE: </div>
                            <div class="table-cell2">${{ $contratos[0]->fovisste }}</div>
                        @else
                            <div class="table-cell2"></div>
                            <div class="table-cell2"></div>
                        @endif
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell2">{{ mb_strtoupper($contratos[0]->institucion) }}: </div>
                        <div class="table-cell2">${{ $contratos[0]->credito_solic }}</div>
                        <div class="table-cell2">PRECIO DEL DEPARTAMENtO: </div>
                        <div class="table-cell2">${{ $contratos[0]->precio_base }}</div>

                    </div>
                    <div class="table-row">
                        <div class="table-cell2">COMISION X APERTURA: </div>
                        <div class="table-cell2">${{ $contratos[0]->comision_apertura }}</div>
                        <div class="table-cell2">PAQUETE: </div>
                        <div class="table-cell2">${{ $contratos[0]->costo_paquete }}</div>

                    </div>
                    <div class="table-row">
                        <div class="table-cell2">INVESTIGACION: </div>
                        <div class="table-cell2">${{ $contratos[0]->investigacion }}</div>
                        <div class="table-cell2">VALOR TOTAL DEPARTAMENTO: </div>
                        <div class="table-cell2">${{ $contratos[0]->precio_venta }}</div>


                    </div>
                    <div class="table-row">
                        <div class="table-cell2">AVALUO: </div>
                        <div class="table-cell2">${{ $contratos[0]->avaluo }}</div>
                        <div class="table-cell2">MONTO NETO CREDITO: </div>
                        <div class="table-cell2">${{ $contratos[0]->monto_total_credito }}</div>

                    </div>


                    <div class="table-row">
                        <div class="table-cell2">GASTOS DE ESCRITURACION: </div>
                        <div class="table-cell2">${{ $contratos[0]->escrituras }}</div>
                        <div class="table-cell2">TOTAL A PAGAR: </div>
                        <div class="table-cell2">${{ $contratos[0]->total_pagar }}</div>


                    </div>
                    <div class="table-row">
                        <div class="table-cell2">CREDITO NETO {{ strtoupper($contratos[0]->institucion) }}: </div>
                        <div class="table-cell2">${{ $contratos[0]->credito_neto }}</div>
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>


                    </div>
                    <div class="table-row">
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                        <div class="table-cell2">AVALUO: </div>
                        <div class="table-cell2">${{ $contratos[0]->avaluo_cliente }}</div>


                    </div>
                    <div class="table-row">
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                        <div class="table-cell2"><b> ENGANCHE TOTAL: </div>
                        <div class="table-cell2"><b>${{ $contratos[0]->enganche_total }}</div>

                    </div>
                    <div class="table-row">
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                        <div class="table-cell2"></div>
                    </div>
                @endif
            </div>
        </div>

        <div style="position: static; margin-top: 5px;">
            <div class="table" style="color:black; margin-top: -1px;">
                @if (count($pagos) > 0)
                    <div class="table-row">
                        @for ($i = 0; $i < count($pagos); $i++)
                            <div colspan="4" class="table-cell">{{ $pagos[$i]->fecha_pago }} PAGO
                                NO.{{ $pagos[$i]->num_pago + 1 }}: <u>${{ $pagos[$i]->monto_pago }}</u></div>
                        @endfor
                    </div>
                @endif
                <div class="table-row">
                    @if ($contratos[0]->coacreditado == 0)
                        <div colspan="10" class="table-cell">ASESOR DE VENTAS:
                            {{ mb_strtoupper($contratos[0]->vendedor_nombre) }}
                            {{ mb_strtoupper($contratos[0]->vendedor_apellidos) }} ______________________</div>
                        <div colspan="10" class="table-cell">COMPRADOR:
                            {{ mb_strtoupper($contratos[0]->apellidos) }}
                            {{ mb_strtoupper($contratos[0]->nombre) }}____________________</div>
                    @else
                        <div colspan="6" class="table-cell">ASESOR DE VENTAS:
                            {{ mb_strtoupper($contratos[0]->vendedor_nombre) }}
                            {{ mb_strtoupper($contratos[0]->vendedor_apellidos) }} ______________________</div>
                        <div colspan="6" class="table-cell">COMPRADOR:
                            {{ mb_strtoupper($contratos[0]->apellidos) }}
                            {{ mb_strtoupper($contratos[0]->nombre) }}____________________</div>
                        <div colspan="6" class="table-cell">Y : {{ mb_strtoupper($contratos[0]->nombre_coa) }}
                            {{ mb_strtoupper($contratos[0]->apellidos_coa) }}____________________</div>
                    @endif
                </div>
                @if ($contratos[0]->vendedor_aux != null)
                    <div class="table-row">
                        <div colspan="3" class="table-cell">ASESOR AUXILIAR:
                            {{ mb_strtoupper($contratos[0]->vendedor_aux) }}
                            {{ mb_strtoupper($contratos[0]->vendedor_apellidos) }} ______________________</div>

                        <div colspan="2" class="table-cell"></div>
                    </div>
                @endif
                <div class="table-row">
                    <div colspan="20" class="table-cell">NOTA: ESTE DOCUMENTO PIERDE SU EFECTO SI EN EL LAPSO DE 5
                        DIAS NATURALES NO ES LIQUIDADO EL PAGO #1.</div>
                </div>
                <div class="table-row">
                    @if ($contratos[0]->medio_publicidad === 'Recomendado')
                        <div colspan="20" class="table-cell">MEDIO POR EL CUAL SE ENTERO: RECOMENDADO. (NOMBRE:
                            {{ mb_strtoupper($contratos[0]->nombre_recomendado) }})</div>
                    @else
                        <div colspan="20" class="table-cell">MEDIO POR EL CUAL SE ENTERO:
                            {{ mb_strtoupper($contratos[0]->medio_publicidad) }}</div>
                    @endif
                </div>
                <div class="table-row">
                    <div colspan="20" class="table-cell">OBSERVACIONES:
                        {{ mb_strtoupper($contratos[0]->observacion) }}
                    </div>
                </div>
                <div class="table-row">
                    @if ($contratos[0]->emp_constructora == 'CONCRETANIA' && $contratos[0]->emp_terreno == 'CONCRETANIA')
                        <div colspan="20" class="table-cell" style="text-align:center;">
                            <hr style="border-color:gray;"> www.concretania.mx
                        </div>
                    @else
                        <div colspan="20" class="table-cell" style="text-align:center;">
                            <hr style="border-color:gray;"> www.casascumbres.mx
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div style="display: inline-block; float: right; margin-top: 15px; margin-right: 35px;">
        @if ($contratos[0]->emp_constructora == 'CONCRETANIA' && $contratos[0]->emp_terreno == 'CONCRETANIA')
            <IMG SRC="img/contratos/logoContratoC2.png" width="150" height="150">
        @else
            <IMG SRC="img/contratos/logoContrato1.png" width="150" height="150">
        @endif
    </div>

    <div style="page-break-after:always;"></div>

    <div class="backblue header">
        <div  class="table-pagos" >
            <div class="table-row-pagos">
                <div  class="table-cell-pago">
                    <IMG SRC="img/contratos/Logos-01-White.png" height="130" >
                </div>
                <div colspan="4" class="table-cell-pago">
                    <p class="h1"><strong>CUENTA PARA REALIZAR LA TRANSFERENCIA.</strong><br>
                        DE LOS PAGOS CORRESPONDIENTESA ENGANCHE</p>
                </div>
                <div  class="table-cell-pago">
                    <IMG SRC="img/contratos/Logos-02-White.png" height="130" >
                </div>
            </div>
        </div>
    </div>

    <div class="contenido">
        <div  class="table-bancaria" >
            <div class="table-row">
                <div class="table-cell-right">
                    <p>TRANSFERENCIA A NOMBRE DE:</p>
                </div>
                <div class="table-cell-left">
                    @if ($contratos[0]->emp_constructora == 'Grupo Constructor Cumbres')
                        <strong><p>&nbsp;&nbsp;&nbsp;GRUPO CONSTRUCTOR CUMBRES S.A DE C.V</p></strong>
                    @else
                        <strong><p>&nbsp;&nbsp;&nbsp;CONCRETANIA S.A. DE C.V.</p></strong>
                    @endif
                </div>
            </div>
            <div class="table-row">
                <div class="table-cell-right">
                    <p>BANCO:</p>
                </div>
                <div class="table-cell-left">
                    <strong><p>&nbsp;&nbsp;&nbsp;BBVA</p></strong>
                </div>
            </div>
            <div class="table-row">
                <div class="table-cell-right">
                    <p>N° DE CUENTA:</p>
                </div>
                <div class="table-cell-left">
                    @if ($contratos[0]->emp_constructora == 'Grupo Constructor Cumbres')
                        <strong><p>&nbsp;&nbsp;&nbsp;0107059795</p></strong>
                    @else
                        <strong><p>&nbsp;&nbsp;&nbsp;0114171918</p></strong>
                    @endif
                </div>
            </div>
            <div class="table-row">
                <div class="table-cell-right">
                    <p>CLABE PARA LA TRANSFERENCIA:</p>
                </div>
                <div class="table-cell-left">
                    @if ($contratos[0]->emp_constructora == 'Grupo Constructor Cumbres')
                        <strong><p>&nbsp;&nbsp;&nbsp;012700001070597953</p></strong>
                    @else
                        <strong><p>&nbsp;&nbsp;&nbsp;012700001141719181</p></strong>
                    @endif
                </div>
            </div>
            <div class="table-row">
                <div class="table-cell-right">
                    <p>RFC:</p>
                </div>
                <div class="table-cell-left">
                    @if ($contratos[0]->emp_constructora == 'Grupo Constructor Cumbres')
                        <strong><p>&nbsp;&nbsp;&nbsp;GCC000106QS6</p></strong>
                    @else
                        <strong><p>&nbsp;&nbsp;&nbsp;CON180725REA</p></strong>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="contenido info">
        <p style="font-size:10.5pt;">EN ASUNTO DE TRANSFERENCIA FAVOR DE ESCRIBIR SU NOMBRE COMPLETO.</p>

        <br>

        <div style="width: 60%; margin-left:20%;">
            <table style="background-color: #0B173B; border-radius: 85%; border: solid #0B173B 1px; justify-content: center;">
                <tr style="color: #fff; margin:0;">
                    <td>
                        <center>
                            <h4><strong>IMPORTANTE:</strong></h4>
                        </center>
                    </td>
                </tr>
                <tr style="background-color: #fff;">
                    <td>
                        <center> <p>En todas los depósitos o transferencias favor de anotar la referencia: <u>{{ $contratos[0]->id }}</u> para identificar su pago.</p> </center>
                    </td>
                </tr>
            </table>
        </div>

        <br><br>

        <h3><strong> INDICACIONES:</strong></h3>
        <div style="text-align: left; margin-left:70px; margin-right:70px;">
            <p>
                TODAS LAS TRANSFERENCIAS DEBERAN PROVENIR DE LA CUENTA DE LA PERSONA QUE ESTA ADQUIRIENDO LA CASA.
            </p>
            <p>
                EL TOTAL DE LOS DEPOSITOS EN EFECTIVO DEBERAN SER INFERIORES A <strong>$100,000.00</strong>.
            </p>
            <p>
                EN CASO DE REALIZAR TU PAGO ELECTRONICO EN LA SUCURSAL BANCARIA ENTREGA TU COMPROBANTE EN NUESTRAS OFICINAS
                CENTRALES O VIA CORREO ELECTRONICO (escaneado o foto digital del comprobante), AL EMAIL:
                @if($contratos[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    <strong>cobranza@grupocumbres.com</strong>
                @else
                    <strong>cobranza@concretania.mx</strong>
                @endif
            </p>

            <br>
            <strong>ES REQUISITO INDISPENSABLE SE ANEXE EL NUMERO DE CUENTA Y BANDO DE LA CUENTA DE LA QUE PROVIENEN LOS RECURSOS EN CADA MOVIMIENTO.</strong>
        </div>

    </div>

    <div class="cover-container"></div>



</body>

</html>
