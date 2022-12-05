<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Poliza de Garantía</title>
</head>
<style type="text/css">
    /* @import url('https://fonts.googleapis.com/css?family=Muli&display=swap'); */

    @page{
        margin: 0cm 0cm 0cm 0cm;
    }

    body {
        font-family: 'AvenirNextLTPro','Muli', Helvetica;
    }

    .h1{
        font-size: 15pt;
        font-style: bold;
        padding: 0px;
        margin: 0px;
    }

    .h-apendice{
        font-size: 9.5pt;
        font-family: Helvetica;
        font-style: bold;
        text-align: center;
        background-color: #003058;
        color: #fff;
        margin-top: 30px;
        margin-bottom: 8px;
        padding: 3px;
        width: 20%;
    }

    .subtitle{
        padding: 0px;
        margin: 0px;
        font-size: 9pt;
    }

    .text{
        font-family: Helvetica;
        padding: 5px;
        margin: 0px;
        font-size: 8pt;
    }

    .text-table{
        font-family: Helvetica;
        padding: 0px;
        margin: 0px;
        font-size: 8pt;
    }

    .text-table2{
        font-family: Helvetica;
        padding: 3px;
        margin: 0px;
        font-size: 9pt;
        text-align: left;
    }

    .text-aviso{
        font-family: Helvetica;
        font-style: bold;
        padding: 0px;
        margin: 0px;
        font-size: 6.5pt;
        padding: 3px;
    }

    .text-apendice{
        font-family: Helvetica;
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

    .aviso{
        height: auto;
        position: relative !important;
        width: 21.59cm;
        margin-left: -2cm;
        margin-right: 0cm;
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
        top: 35%;
        background-color: #003058;
        opacity: 0.1;
        text-align: justify;
        align-items: center;
        width: 100%;
        position: fixed;
    }

    .contenido{
        top: 50px;
        position: fixed;
        color: #001e2c;
        width: 100%;
        margin: 10px;
    }

    .info{
        top: 13% !important;
        margin-left: 70px;
        margin-right: 70px;
        text-align: justify;
    }

    .table {
        display: table;
        width: 95%;
        border-collapse: collapse;
        table-layout: fixed;
        align-items: center;
        text-align: center;
    }

    .table-row {
        display: table-row;
    }

    .table-cell {
        display: table-cell;
        padding: 0.5em;
        font-size: 8pt;
    }
    .table2 {
        display: table; width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: -10px; margin-bottom: 15px;

    }
    .table-row2 { display: table-row;
        padding-top: -40px;
        padding-bottom: -50px;
        margin-bottom: -10px;
        margin-top: -10px;
    }
    .table-title {
        display: table-cell;
        padding-top: -40px;
        padding-bottom: -50px;
        margin-bottom: -10px;
        margin-top: -10px;
        font-size: 6.5pt;
        background-color: #003058;
        color: #fff;
        font-style: bold;
        border: ridge #fff 1px;
        text-align:center; vertical-align: middle;
    }

    .table-cell2 {
        display: table-cell;
        padding-top: -40px;
        padding-bottom: -50px;
        margin-bottom: -10px;
        margin-top: -10px;
        font-size: 6.5pt;
        background-color: #00305827;
        color: #000000;
        border: ridge #0B173B 1px;
        border-right: 0px;
        border-left: 0px;
        text-align:center; vertical-align: middle;
    }

    .llenado {
        background-color: #fff;
        color: #000000;
        border-left: 1px;
    }


    ul, li{
        margin-left: -10px;
        padding-left: 17px;
        font-family: Helvetica;
    }

    .list{
        padding-left: 10px;
    }

    .contenedor {
        clear:both;
        float: left;
        width: 710px;
    }


    .table { display: table; width: 98%; border-collapse: collapse; table-layout: fixed;}
    .table-row { display: table-row;  }
    .table-cell1 { display: table-cell; text-align:center; vertical-align: middle;}

</style>

<body>
    <div>
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
                        <p class="h1">PÓLIZA DE GARANTÍA</p>
                        <p class="subtitle">REPARACIÓN DE DAÑOS Y VICIOS OCULTOS DE VIVIENDA</p>
                    </div>
                    <div  class="table-cell1">
                        @if($contrato->logo_fracc2)
                            <img src="img/logosFraccionamientos/{{$contrato->logo_fracc2}}" height="90" >
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="contenido info">
            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GARANTÍA QUE OTORGA
                @if($contrato->emp_constructora == 'Grupo Constructor Cumbres')
                    <b>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.,</b>
                @else
                    <b>CONCRETANIA, S.A. DE C.V.,</b>
                @endif
                EN LO SUCESIVO <b>“LA CONSTRUCTORA”,</b> REPRESENTADA POR SU APODERADO EL
                @if($contrato->etapa == 'PRIVADA ALCAZAR')
                    <b>C.P. MARTÍN HERRERA SÁNCHEZ,</b>
                @else
                    <b>ING. SAJID MEZA MEDELLIN</b>
                @endif
                CON DOMICILIO EN MANUEL GUTIÉRREZ NÁJERA NO. 190 COL. TEQUISQUIAPAN CP. 78230  DE LA
                CIUDAD DE SAN LUIS POTOSÍ S.L.P. CON TELÉFONO 444 833-46-83,
                A FAVOR DE {{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }},
                EN LO SUCESIVO <b>“EL PROPIETARIO,</b> RESPECTO A LA VIVIENDA UBICADA EN EL
                LOTE {{$contrato->num_lote}} {{($contrato->sublote) ? $contrato->sublote : ''}}
                DE LA MANZANA {{mb_strtoupper($contrato->manzana)}} DE LA
                CALLE {{mb_strtoupper($contrato->calle_lote)}}
                #{{$contrato->num_oficial}} {{ ($contrato->interior ? $contrato->interior : '')}} DE EL FRACCIONAMIENTO HABITACIONAL
                @if(str_contains($contrato->proyecto, 'FRACCIONAMIENTO RESIDENCIAL'))
                    {{ mb_strtoupper( str_replace('FRACCIONAMIENTO RESIDENCIAL', '', $contrato->proyecto)) }}
                @else
                    {{mb_strtoupper($contrato->proyecto)}}
                @endif

                LOCALIZADO EN {{mb_strtoupper($contrato->ciudad_proy)}}, {{mb_strtoupper($contrato->estado_proy)}}
            </p>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LA PRESENTE PÓLIZA SE EXPIDE EN CUMPLIMIENTO A LO DISPUESTO EN EL CONTRATO DE PROMESA DE COMPRAVENTA DEL
                INMUEBLE ARRIBA CITADO. <b>“LA CONSTRUCTORA”</b> ASUMIÓ EL COMPROMISO PARA RESPONDER DIRECTAMENTE ANTE
                <b>“EL PROPIETARIO”,</b> DE LAS SIGUIENTES:
            </p>

            <center>
                <strong><p class="text">OBLIGACIONES</p></strong>
            </center>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>PRIMERA.-</strong> “LA CONSTRUCTORA” SE OBLIGA A RESPONDER POR LAS FALLAS TÉCNICAS Y VICIOS OCULTOS QUE APARECIEREN
                EN LA VIVIENDA DESCRITA EN ESTA PÓLIZA, DURANTE  LOS PLAZOS MARCADOS EN LOS APÉNDICES CORRESPONDIENTES A PARTIR DE LA FECHA DE
                ENTREGA DE LA VIVIENDA. LOS HORARIOS DE ATENCIÓN PARA RECIBIR UN REPORTE Y HACER VÁLIDA LA GARANTÍA ES EN DÍAS HÁBILES
                DE LUNES A VIERNES DE 9:00 AM A 5:00 PM Y SÁBADOS DE 9:00 AM A 1:00 PM
            </p>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>SEGUNDA.-</strong> “LA CONSTRUCTORA”, SE COMPROMETE, FRENTE A “EL PROPIETARIO” Y/O SUS BENEFICIARIOS,
                A PROCEDER A LA REPARACIÓN, POR SU CUENTA Y COSTO, DE LAS FALLAS TÉCNICAS Y VICIOS OCULTOS QUE SE PRESENTEN EN LA
                VIVIENDA OBJETO DE LA PRESENTE PÓLIZA. DICHOS TRABAJOS LOS INICIARÁ “LA CONSTRUCTORA” DENTRO DE UN PLAZO NO MAYOR
                DE 8 DÍAS HÁBILES CONTADOS A PARTIR DE LA FECHA EN QUE RECIBA LA COMUNICACIÓN RESPECTIVA POR PARTE DE “EL PROPIETARIO”,
                DEBIENDO CONCLUIRLOS DENTRO DE UN PLAZO DE 20 DÍAS HÁBILES. SALVO CASOS ESPECIALES QUE REQUIERAN DE UN MAYOR PLAZO
                PARA SU REPARACIÓN. “LA CONSTRUCTORA” NO RESPONDERÁ POR DESPERFECTOS DERIVADOS DEL MAL USO O POR FALTA DE MANTENIMIENTO
                DE LA VIVIENDA, POR PARTE DE “EL PROPIETARIO”.
            </p>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>TERCERA.-</strong> “EL PROPIETARIO” MANIFIESTA SU CONFORMIDAD CON EL ESTADO QUE GUARDAN LOS BIENES E
                INSTALACIONES DE LA VIVIENDA, EN LOS TÉRMINOS SEÑALADOS EN EL ANEXO Nº 1 DE LA PRESENTE PÓLIZA.
            </p>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CUARTA.-</strong> LA GARANTÍA DE QUE SE TRATA SE HARÁ EFECTIVA, A FAVOR DE “EL PROPIETARIO”,
                Y/O SUS BENEFICIARIOS CUANDO ASÍ SE SOLICITE A “LA CONSTRUCTORA”, SIEMPRE Y CUANDO SE PRESENTE DENTRO DEL
                TÉRMINO DE VIGENCIA DE LA MISMA, INTERRUMPIÉNDOSE SU PRESCRIPCIÓN DESDE LA FECHA DEL REQUERIMIENTO HECHO POR ESCRITO.
            </p>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;“LA CONSTRUCTORA” ADQUIERE LA RESPONSABILIDAD DERIVADA DE LAS RECLAMACIONES QUE DE ACUERDO A LO ESTABLECIDO
                EN LA PRESENTE PÓLIZA PUEDA EFECTUAR “EL PROPIETARIO”.
            </p>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>QUINTA.-</strong> “EL PROPIETARIO” DEBERÁ CONSERVAR EN SU PODER LA PRESENTE PÓLIZA DE GARANTÍA Y
                AL REPORTAR ALGÚN DESPERFECTO, OBTENER DE “LA CONSTRUCTORA” EL DOCUMENTO QUE ACREDITE LA REPARACIÓN SATISFACTORIA
                DEL DESPERFECTO REPORTADO, TENIENDO LA OBLIGACIÓN DE FIRMARLE A “LA CONSTRUCTORA” EL DOCUMENTO EN DONDE SE ACREDITE TAL CIRCUNSTANCIA.
            </p>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>SEXTA.-</strong> CUALQUIER MODIFICACIÓN O AMPLIACIÓN QUE REALICE “EL PROPIETARIO” A LA VIVIENDA DEJARÁ
                SIN EFECTO LA COBERTURA DE LA GARANTÍA DEL APÉNDICE CORRESPONDIENTE A LA PRESENTE PÓLIZA.
            </p>

            <p class="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>SEPTIMA.-</strong> EL TÉRMINO DE VIGENCIA DE LA PRESENTE PÓLIZA COMENZARÁ A PARTIR DEL
                {{mb_strtoupper($contrato->entrega_real)}}, Y CONCLUIRÁ EL {{mb_strtoupper($contrato->fin_poliza)}}.
                AJUSTANDO CADA CONCEPTO AL
                PERÍODO DE COBERTURA QUE SE ESPECIFICA EN LOS APÉNDICE 1, APÉNDICE 2 Y APÉNDICE 3, DE ESTA MISMA PÓLIZA.
            </p>
            <br>

            <center>
                <p class="text">
                    SAN LUIS POTOSÍ, S.L.P. A LOS {{mb_strtoupper($contrato->hoy)}}
                </p>
            </center>

            <div class="table">
                <div class="table-row">
                    <div class="table-cell">
                        <br><br><br><br><br>
                        <p class="text" style="text-indent: 0px;">
                            POR LA CONSTRUCTORA <br>
                            @if($contrato->etapa == 'PRIVADA ALCAZAR')
                                <b>C.P. MARTÍN HERRERA SÁNCHEZ</b><br>
                            @else
                                <b>ING. SAJID MEZA MEDELLIN</b><br>
                            @endif
                            APODERADO
                        </p>
                    </div>
                    <div class="table-cell">
                        <br><br><br><br><br>
                        <p class="text" style="text-indent: 0px;">
                            ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TÉRMINOS
                            Y ALCANCE DE LA PRESENTE PÓLIZA <br>
                            <b>{{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}</b><br>
                        </p>
                    </div>
                </div>
            </div>

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
                    MNZA: {{mb_strtoupper($contrato->manzana)}}
                    LOTE: {{$contrato->num_lote}} {{($contrato->sublote) ? $contrato->sublote : ''}}
                    CALLE: {{mb_strtoupper($contrato->calle_lote)}}
                    #{{$contrato->num_oficial}} {{ ($contrato->interior ? $contrato->interior : '')}}.
                    {{mb_strtoupper($contrato->ciudad_proy)}}, {{mb_strtoupper($contrato->estado_proy)}}
                </p>
            </center>
        </div>

    </div>
    <div style="page-break-after:always;"></div>

    <div>
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
                        <p class="h1">APÉNDICES</p>
                        <p class="subtitle">COBERTURAS DE GARANTÍAS</p>
                    </div>
                    <div  class="table-cell1">
                        @if($contrato->logo_fracc2)
                            <img src="img/logosFraccionamientos/{{$contrato->logo_fracc2}}" height="90" >
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="contenido info">
            <div class="h-apendice">
                <p style="margin: 2px;">APÉNDICE N° 1</p>
            </div>
            <p class="text-apendice">
                <b>COBERTURA DE GARANTÍA POR VICIOS OCULTOS QUE REPORTE LA VIVIENDA.</b> <br>
                <b>ACCESORIOS E INSTALACIONES REALIZADOS POR LA CONSTRUCTORA CON GARANTÍA DE 12 MESES.</b>
                <br>
            </p>
            <ul>
                <li><p class="text-apendice list">TUBERÍAS DE GAS, AGUA O SANITARIAS TAPADAS, CON FUGAS O EN MAL ESTADO.</p></li>
                <li><p class="text-apendice list">MUEBLES DE BAÑO, REGADERAS, MONOMANDOS, LLAVES, FREGADERO, LAVADERO Y SUS ACCESORIOS.</p></li>
                <li><p class="text-apendice list">ACCESORIOS DE BAÑO, MAL COLOCADOS O CON DEFECTO.</p></li>
                <li><p class="text-apendice list">DEFECTO EN FUNCIONAMIENTO DEL CALENTADOR DE GAS  Y CALENTADOR SOLAR (SI LA VIVIENDA LO INCLUYE EN SU EQUIPAMIENTO).</p></li>
                <li><p class="text-apendice list">FALLAS EN INSTALACIÓN ELÉCTRICA.</p></li>
                <li><p class="text-apendice list">ACCESORIOS ELÉCTRICOS DEFECTUOSOS.</p></li>
                <li><p class="text-apendice list">PISOS Y AZULEJOS MAL EMBOQUILLADOS , FLOJOS O FISURADOS.</p></li>
                <li><p class="text-apendice list">FUNCIONAMIENTO DE VENTANAS.</p></li>
                <li><p class="text-apendice list">HUMEDAD EN MUROS, NO DERIVADO DE UNA CAUSA EXTERNA PROPIA DE LA VIVIENDA.</p></li>
                <li><p class="text-apendice list">CANCELERÍA Y DOMOS, MALA INSTALACIÓN Y SELLADOS.</p></li>
                <li><p class="text-apendice list">PUERTAS EXTERIORES E INTERIORES Y CHAPAS EN MAL ESTADO O DEFECTUOSAS.</p></li>
                <li><p class="text-apendice list">FISURAS EN MUROS Y PLAFONES QUE TENGAN APERTURA MENOR A 1.5 MM.</p></li>
            </ul>

            <div class="h-apendice">
                <p style="margin: 2px;">APÉNDICE N° 2</p>
            </div>
            <p class="text-apendice">
                <b>COBERTURA DE GARANTÍA POR FALLAS  EN LA IMPERMEABILIZACIÓN DE LA VIVIENDA.</b> <br>
                <b>CON GARANTÍA DE 3 AÑOS.</b>
                <br>
            </p>
            <ul>
                <li><p class="text-apendice list">DEFECTOS EN IMPERMEABILIZACIÓN, A CAUSA DE INSTALACIÓN O APLICACIÓN  DE IMPERMEABILIZANTE.</p></li>
            </ul>

            <div class="h-apendice">
                <p style="margin: 2px;">APÉNDICE N° 3</p>
            </div>
            <p class="text-apendice">
                <b>COBERTURA DE GARANTÍA POR FALLAS ESTRUCTURALES CON GARANTIA DE {{$contrato->t_garanita}} AÑOS.</b> <br>
            </p>
            <ul>
                <li><p class="text-apendice list">DEFECTOS ESTRUCTURALES, GRIETAS O FISURAS QUE AFECTEN PISOS, MUROS, LOSAS Y  QUE TENGA UNA APERTURA MAYOR A 1.5 mm.</p></li>
            </ul>

            <div class="h-apendice">
                <p style="margin: 2px;">NOTA</p>
            </div>
            <p class="text-apendice">
                LA PÓLIZA <b>NO APLICA</b> PARA NINGÚN DEFECTO EN VIDRIOS, PUERTAS, VENTANAS NI DOMOS; YA SEAN ROTOS, FISURADOS O RAYADOS;
                DÉSPUES DE ENTREGADA LA VIVIENDA A "EL PROPIETARIO". <br>
                LA PÓLIZA <b>NO APLICA</b> EN EL CASO DE DAÑOS GENERADOS POR EL USO DE MULTI-CONTACTOS, MAL USO DE LAS INSTALACIONES O SOBRE CARGAS.
            </p>


            <br><br>


            <div class="table">
                <div class="table-row">
                    <div class="table-cell">
                        <br><br><br><br><br>
                        <p class="text" style="text-indent: 0px;">
                            POR LA CONSTRUCTORA <br>
                            @if($contrato->etapa == 'PRIVADA ALCAZAR')
                                <b>C.P. MARTÍN HERRERA SÁNCHEZ</b><br>
                            @else
                                <b>ING. SAJID MEZA MEDELLIN</b><br>
                            @endif
                            APODERADO
                        </p>
                    </div>
                    <div class="table-cell">
                        <br><br><br><br><br>
                        <p class="text" style="text-indent: 0px;">
                            ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TÉRMINOS
                            Y ALCANCE DE LA PRESENTE PÓLIZA <br>
                            <b>{{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}</b><br>
                        </p>
                    </div>
                </div>
            </div>



        </div>
    </div>
    <div style="page-break-after:always;"></div>

    <div>
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
                        <p class="h1">ANEXO N° 1</p>
                        <p class="subtitle">ESTADO QUE GUARDAN LOS BIENES E INSTALACIONES DE LA VIVIENDA</p>
                    </div>
                    <div  class="table-cell1">
                        @if($contrato->logo_fracc2)
                            <img src="img/logosFraccionamientos/{{$contrato->logo_fracc2}}" height="90" >
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="contenido info">
            <div class="table2">
                <div class="table-row2">
                    <div colspan="4" class="table-title">
                        <p class="text-table">ALBAÑILERÍA</p>
                    </div>
                    <div colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-title">
                        <p class="text-table">HERRERÍA Y CANCELERÍA</p>
                    </div>
                    <div colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                </div>

                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">PISOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">PUERTAS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">MUROS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">MARCOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">LOSAS Y PLAFONES</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">PASAMANOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">ESCALERA MARINA</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">BARANDALES</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-title">
                        <p class="text-table">INSTALACIÓNES ELÉCTRICAS</p>
                    </div>
                    <div colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">APAGADORES</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">CONTACTOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-title">
                        <p class="text-table">CARPINTERÍA</p>
                    </div>
                    <div  colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">SOCKETS, SPOT Y ARBOTANTES</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">PUERTAS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">INTERRUPTOR TERMOMAGNÉTICO</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">MARCOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">PASAMANOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-title">
                        <p class="text-table" style="font-size: 6.5pt;">INSTALACIONES HIDRÁULICAS<br>SANITARIAS Y DE GAS</p>
                    </div>
                    <div colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">LAVABO</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-title">
                        <p class="text-table">CERRAJERÍA</p>
                    </div>
                    <div  colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">W.C.</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">CHAPAS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">REGADERA</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">MONOMANDO</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">TOALLEROS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-title">
                        <p class="text-table">ALUMINIO</p>
                    </div>
                    <div  colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">PORTA ROLLO</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">CANCELERIA</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">ACCESORIOS DE BAÑO</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">VENTANAS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">TARJA DE COCINA</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table" style="font-size: 6pt;">VIDRIOS / SIN RAYONES, FISURAS O ROTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">REGADERA</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">FREGADERO</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-title">
                        <p class="text-table">PINTURA</p>
                    </div>
                    <div  colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">LAVADERO</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">VIVIENDA PINTADA</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">CALENTADOR</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table" style="font-size: 6pt;">DESCARGAS DE AGUAS GRISES Y COLADERAS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-title">
                        <p class="text-table">ÁREAS EXTERIORES</p>
                    </div>
                    <div  colspan="3" class="table-title">
                        <p class="text-table">SIN DEFECTO</p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">ACCESORIOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">JARDINERÍA</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table" style="font-size: 6.5pt">INSTALACIÓN DE GAS, LLAVES NARIZ</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">FIRMES</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">CALENTADOR COMPLETO</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                    <div class="table-cell">
                        <p class="text-table">&nbsp;</p>
                    </div>
                    <div colspan="4" class="table-cell2">
                        <p class="text-table">OTROS CONCEPTOS</p>
                    </div>
                    <div  colspan="3" class="table-cell2 llenado">
                        <p class="text-table"></p>
                    </div>
                </div>
            </div>



            <div class="backblue aviso">
                <p class="text-aviso">
                    CONFORME A LO DETALLADO, DESPUÉS DE UNA REVISIÓN CONJUNTA DEL INMUEBLE, SE FIRMA EL PRESENTE ANEXO DE LA PÓLIZA DE GARANTÍA.
                </p>
            </div>

            <div class="table">
                <div class="table-row">
                    <div class="table-cell">
                        <br><br><br><br><br>
                        <p class="text" style="text-indent: 0px;">
                            POR LA CONSTRUCTORA <br>
                            @if($contrato->etapa == 'PRIVADA ALCAZAR')
                                <b>C.P. MARTÍN HERRERA SÁNCHEZ</b><br>
                            @else
                                <b>ING. SAJID MEZA MEDELLIN</b><br>
                            @endif
                            APODERADO
                        </p>
                    </div>
                    <div class="table-cell">
                        <br><br><br><br><br>
                        <p class="text" style="text-indent: 0px;">
                            ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TÉRMINOS
                            Y ALCANCE DE LA PRESENTE PÓLIZA <br>
                            <b>{{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}</b><br>
                        </p>
                    </div>
                </div>
            </div>



        </div>

    </div>
    <div style="page-break-after:always;"></div>

    <div>
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
                        <p class="h1">ANEXO N° 2</p>
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
        <div class="contenido info">
            <br>
                <p class="text" style="font-size: 9pt;">
                    EL {{mb_strtoupper($contrato->entrega_real)}} FIRMAN POR UNA PARTE EL
                    @if($contrato->etapa == 'PRIVADA ALCAZAR')
                        <b>C.P. MARTÍN HERRERA SÁNCHEZ,</b>
                    @else
                        <b>ING. SAJID MEZA MEDELLIN</b>
                    @endif
                    COMO REPRESENTANTE DE
                    @if($contrato->emp_constructora != 'Grupo Constructor Cumstrongres')
                        <b>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</b>
                    @else
                        <b>CONCRETANIA, S.A. DE C.V.</b>
                    @endif
                    Y POR OTRA PARTE EL SR.(A) <b>{{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}</b>
                    PROPIETARIO(A) Y POR SU PROPIO DERECHO DE FORMALIZAR LA ENTREGA DE LA VIVIENDA UBICADA EN EL
                    <b>LOTE {{$contrato->num_lote}} {{($contrato->sublote) ? $contrato->sublote : ''}}
                    MANZANA {{mb_strtoupper($contrato->manzana)}} DE LA CALLE {{mb_strtoupper($contrato->calle_lote)}}
                    #{{$contrato->num_oficial}} {{ ($contrato->interior ? $contrato->interior : '')}}
                    DEL FRACCIONAMIENTO
                        @if(str_contains($contrato->proyecto, 'FRACCIONAMIENTO RESIDENCIAL'))
                            {{ mb_strtoupper( str_replace(' FRACCIONAMIENTO RESIDENCIAL', '', $contrato->proyecto))}};
                        @else
                            {{mb_strtoupper($contrato->proyecto)}};
                        @endif
                    </b>
                    LA CUAL SE ENCUENTRA TOTALMENTE TERMINADA Y EN CONDICIONES DE HABITARSE, POR LO QUE EL COMPRADOR LA RECIBE A SU ENTERA
                    SATISFACCIÓN CON TODA LA DOCUMENTACIÓN QUE A CONTINUACIÓN SE ENUMERA.
                </p>
            <br><br>

            <div class="table2">
                <div class="table-row2">
                    <div colspan="4" class="table-title" style="padding: 5px;">
                        <p class="text-table2" style="text-align: center;">CONCEPTO</p>
                    </div>
                    <div colspan="1" class="table-title">
                        <p class="text-table2" style="text-align: center;">RECIBIDO</p>
                    </div>
                </div>


                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">ACTA DE ENTREGA - VIVIENDA</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">COPIA DE FACTIBILIDAD DE AGUA</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">ACTA DE ENTREGA RECEPCIÓN INTERAPAS</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">COPIA DE PREDIAL AL CORRIENTE</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">PÓLIZA DE GARANTÍA</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">MANUAL DE MANTENIMIENTO</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">ENVÍO DIGITAL DE PLANO EJECUTIVO DE VIVIENDA</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;">
                            <p class="text-table2">
                                JUEGO DE LLAVES DE CADA UNA DE LAS CHAPAS DE LA VIVIENDA<br>
                                (EXCEPTO CHAPA DE PUERTAS DE BAÑOS)
                            </p>
                        </li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">PROCEDIMIENTO PARA REALIZAR CONTRATO INTERAPAS / CFE</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">PROCEDIMIENTO PARA HACER VÁLIDAS LAS GARANTIAS</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">TRÍPTICO DE INSTALACIÓN DE GAS</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                @if(!str_contains($contrato->etapa, 'EXTERIOR'))
                    <div class="table-row2">
                        <div colspan="4" class="table-cell2">
                            <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">2 MARBETES DE IDENTIFICACIÓN VEHICULAR</p></li>
                        </div>
                        <div colspan="1" class="table-cell2 llenado">
                            <p class="text-table2"></p>
                        </div>
                    </div>
                @endif
                @if(str_contains($contrato->promocion, 'ALARMA '))
                    <div class="table-row2">
                        <div colspan="4" class="table-cell2">
                            <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">PROCEDIMIENTO PARA CONTRATACIÓN DE ALARMA</p></li>
                        </div>
                        <div colspan="1" class="table-cell2 llenado">
                            <p class="text-table2"></p>
                        </div>
                    </div>
                @endif
                @if($contrato->getEquipamiento > 0)
                    <div class="table-row2">
                        <div colspan="4" class="table-cell2">
                            <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">GARANTÍA DE EQUIPAMIENTO OTORGADO POR PROVEEDOR</p></li>
                        </div>
                        <div colspan="1" class="table-cell2 llenado">
                            <p class="text-table2"></p>
                        </div>
                    </div>
                @endif
                @if(!str_contains($contrato->etapa, 'EXTERIOR'))
                    <div class="table-row2">
                        <div colspan="4" class="table-cell2">
                            <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">CARTA BIENVENIDA DE LA ADMINISTRACIÓN</p></li>
                        </div>
                        <div colspan="1" class="table-cell2 llenado">
                            <p class="text-table2"></p>
                        </div>
                    </div>
                @endif
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2">MANIFIESTO ACTA DE ENTREGA-VIVIENDA (USO PARA INTERAPAS)</p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2"><br></p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="4" class="table-cell2">
                        <li style="margin-left: 25px; padding: 2px;"><p class="text-table2"><br></p></li>
                    </div>
                    <div colspan="1" class="table-cell2 llenado">
                        <p class="text-table2"></p>
                    </div>
                </div>
                <div class="table-row2">
                    <div colspan="5" class="table-title"><br><br></div>
                </div>
            </div>

            <br><br>
            <br><br>

            <div class="table">
                <div class="table-row">
                    <div class="table-cell">
                        <br><br><br><br><br>
                        <p class="text" style="text-indent: 0px;">
                            POR LA CONSTRUCTORA <br>
                            @if($contrato->etapa == 'PRIVADA ALCAZAR')
                                <b>C.P. MARTÍN HERRERA SÁNCHEZ</b><br>
                            @else
                                <b>ING. SAJID MEZA MEDELLIN</b><br>
                            @endif
                            APODERADO
                        </p>
                    </div>
                    <div class="table-cell">
                        <br><br><br><br><br>
                        <p class="text" style="text-indent: 0px;">
                            ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TÉRMINOS
                            Y ALCANCE DE LA PRESENTE PÓLIZA <br>
                            <b>{{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}</b><br>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div class="cover-container"></div>

    </div>

    <div class="backblue footer">
        <center>
            <p class="subtitle">
                FRACCIONAMIENTO:
                    @if(str_contains($contrato->proyecto, 'FRACCIONAMIENTO RESIDENCIAL'))
                        {{ mb_strtoupper( str_replace('FRACCIONAMIENTO RESIDENCIAL', '', $contrato->proyecto)) }}
                    @else
                        {{mb_strtoupper($contrato->proyecto)}}
                    @endif
                MNZA: {{mb_strtoupper($contrato->manzana)}}
                LOTE: {{$contrato->num_lote}} {{($contrato->sublote) ? $contrato->sublote : ''}}
                CALLE: {{mb_strtoupper($contrato->calle_lote)}}
                #{{$contrato->num_oficial}} {{ ($contrato->interior ? $contrato->interior : '')}}.
                {{mb_strtoupper($contrato->ciudad_proy)}}, {{mb_strtoupper($contrato->estado_proy)}}
            </p>
        </center>
    </div>

</body>

</html>
