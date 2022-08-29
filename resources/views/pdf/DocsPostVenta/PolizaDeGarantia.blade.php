<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Poliza de garantia</title>
    </head>
    <style type="text/css">

    div {
    page-break-inside: avoid;
    }
    body {
        font-size: 10pt;
        font-family: Verdana, Tahoma, sans-serif;
        text-align: justify;
    }

    @page{
        margin: 40px;
        margin-right: 35px;
        margin-left: 35px;
        margin-bottom: 10px
    }
    .table { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
    .table-cell { display: table-cell; padding: 0.5em; font-size: 10pt; }
    .table-row { display: table-row; }

    .table2 { display: table; width:100%;  border: ridge #0B173B 1px; color:black;}
    .table-cell2 { display: table-cell; padding: 0.1em; font-size: 8.5pt; border: ridge #0B173B 1px; color:black; font-family:"DejaVu Sans";}
    .table-cell3 { display: table-cell; padding: 0.2em; font-size: 10pt; border: ridge #0B173B 1px; color:black; font-family:"DejaVu Sans";}

    .contenedor {
    clear:both;
    float: left;
    width: 710px;
    }
    </style>

    <body>

        <div style="margin: 20px;">
            <h3 style="text-align: center;">POLIZA DE GARANTIA</h3>
            <p style="text-align: center;">REPARACION DE DAÑOS Y VICIOS OCULTOS DE VIVIENDA</p><br>

            <p>GARANTÍA QUE OTORGA <b>GRUPO CONSTRUCTOR CUMBRES S.A. DE C.V.,</b> EN LO SUCESIVO <i>"LA CONSTRUCTORA"</i>, REPRESENTADA
                POR <b>SU APODERADO LEGAL EL C.P. MARTÍN HERRERA SÁNCHEZ,</b> CON DOMICILIO <b>MANUEL GUTIERREZ NAJERA NO.190</b> COL.
                <b>TEQUISQUIAPAN</b> DE LA CIUDAD DE <b>SAN LUIS POTOSI, S.L.P.</b> CON TELÉFONO <b>444-833-46-83</b>, A FAVOR DEL <b>C. {{mb_strtoupper($contratos[0]->nombre_cliente)}},</b>
                EN LO SUCESIVO <i>"EL ACREDITADO",</i> RESPECTO A LA VIVIENDA UBICADA EN EL LOTE <b>{{$contratos[0]->num_lote}}</b> DE LA MANZANA <b>{{$contratos[0]->manzana}}</b> DE LA CALLE
                <b>{{mb_strtoupper($contratos[0]->calle)}} NO. {{$contratos[0]->numero}}</b> DEL CONJUNTO HABITACIONAL <b>"{{mb_strtoupper($contratos[0]->proyecto)}}"</b>
                LOCALIZADO EN EL MUNICIPIO DE <b>{{mb_strtoupper($contratos[0]->delegacion)}}</b> DE LA CIUDAD DE <b>{{mb_strtoupper($contratos[0]->ciudadFraccionamiento)}}</b>.
            </p>

            <p>LA PRESENTE PÓLIZA SE EXPIDE EN CUMPLIMIENTO A LO DISPUESTO EN EL CONTRATO DE COMPRAVENTA DEL INMUEBLE ARRIBA CITADO, <i>LA CONSTRUCTORA</i>,
                ASUMIÓ EL COMPROMISO PARA RESPONDER DIRECTAMENTE <i>"EL ACREDITADO"</i>, DE LAS SIGUIENTES:
            </p><br>

            <h3 style="text-align: center;">O B L I G A C I O N E S</h3><br>

            <p><u><b>PRIMERA</b></u>.- <i>"LA CONSTRUCTORA"</i> SE OBLIGA A RESPONDER POR LAS FALLAS TÉCNICAS Y VICIOS OCULTOS QUE APARECIEREN EN LA VIVIENDA DESCRITA EN ÉSTA
                PÓLIZA, DURANTE UN TÉRMINO QUE NO EXCEDERÁ DE
                @if($tiempo == 2)
                    DOS AÑOS,
                @else
                    CINCO AÑOS,
                @endif
                CONTANDO A PARTIR DE LA FECHA DE LA ENTREG DE LA VIVIENDA.
            </p>

            <p><u><b>SEGUNDA</b></u>.- <i>"LA CONSTRUCTORA"</i>, SE COMPROMETE, FRENTE A <i>"EL ACREDITADO"</i> Y/O SUS BENEFICIARIOS,
            DURANTE EL TÉRMINO DE
            @if($tiempo == 2)
                DOS AÑOS
            @else
                CINCO AÑOS
            @endif
            A PROCEDER A LA REPARACIÓN, POR SU CUENTA Y COSTO, DE LAS FALLAS TÉCNICAS Y VICIOS OCULTOS QUE
            SE PRESENTEN EN LA VIVIENDA OBJETO DE LA PRESENTE PÓLIZA. DICHOS TRABAJOS LOS INICIARÁ <i>"LA CONSTRUCTORA"</i> DENTRO DE UN PLAZO
            NO MAYOR DE 10 DÍAS HÁBILES CONTADOS A PARTIR DE LA FECHA EN QUE RECIBA LA COMUNICACIÓN RESPECTIVA POR PARTE DE <i>"EL ACREDITADO"</i>,
            DEBIENDO CONCLUIRLOS DENTRO DE UNA PLAZO DE 15 DÍAS HÁBILES. <i>"LA COSTRUCTORA"</i> NO RESPONDERÁ POR DESPERFECTOS DERIVADOS DEL MAL USO O POR
            FALTA DE MANTENIMIENTO DE LA VIVIENDA, POR PARTE DE <i>"EL ACREDITADO"</i>.
            </p>

            <p><u><b>TERCERA</b></u>.- <i>"EL ACREDITADO"</i> MANIFIESTA SU CONFORMIDAD CON EL ESTADO QUE GUARDAN LOS BIENES E INSTALACIONES DE LA VIVIENDA,
            EN LOS TÉRMINOS SEÑALADOS EN EL ANEXO N°1 DE LA PRESENTE PÓLIZA.
            </p><br>

            <div class="table">
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"><b>POR LA CONSTRUCTORA</b></div>
                    <div class="table-cell" style="text-align:center;"><b>ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TERMINOS Y ALCANCE DE LA PRESENTE POLIZA</b></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"></div>
                    <div class="table-cell" style="text-align:center;"><br><br> {{mb_strtoupper($contratos[0]->nombre_cliente)}}</div>
                </div>
            </div>
            <br>

            <p style="text-align:center; font-size: 9pt;"><b>DIRECCION DE LA VIVIENDA:</b> {{mb_strtoupper($contratos[0]->calle)}} NO. {{$contratos[0]->numero}}, FRACC. "{{mb_strtoupper($contratos[0]->proyecto)}}", {{$contratos[0]->ciudadFraccionamiento}}.</p>
        </div>

    <div style="page-break-after:always;"></div>



        <div style="margin: 20px;">
            <p><u><b>CUARTA</b></u>.- LA GARANTÍA DE QUE SE TRATA SE HARÁ EFECTIVA, A FAVOR DE <i>"EL ACREDITADO"</i>, Y/O SUS
                BENEFICIARIOS CUANDO ASÍ SE SOLICITE A <i>"LA CONSTRUCTORA"</i>, SIEMPRE Y CUANDO SE PRESENTE DENTRO DEL TÉRMINO
                DE VIGENCIA DE LA MISMA, INTERRUPIÉNDOSE SU PRESCRIPCIÓN DESDE LA FECHA DEL REQUERIMIENTO HECHO POR ESCRITO.
            </p>

            <p><i>"LA CONSTRUCTORA"</i> ADQUIERE CUALQUIER RESPONSABILIDAD DERIVADA DE LAS RECLAMACIONES QUE, CON FUNDAMENTO EN LA PRESENTE PÓLIZA
                PUEDA EFECTUAR <i>"EL ACREDITADO"</i>.
            </p>

            <p><u><b>QUINTA</b></u>.- <i>"EL ACREDITADO"</i> DEBERÁ CONSERVAR EN SU PODER LA PRESENTE PÓLIZA DE GARANTÍA Y AL
                REPORTAR ALGÚN DESPERFECTO, OBTENER DE <i>"LA CONSTRUCTORA"</i> EL QUE HAYA ATENDIDO SATISFACTORIAMENTE EL DESPERFECTO
                REPORTADO, TENIENDO LA OBLIGACION DE FIRMAR A ÉSTA, EL DOCUMENTO EN DONDE SE ACREDITE TAL CIRCUNSTANCIA.
            </p>

            <p><u><b>SEXTA</b></u>.- CUALQUIER MODIFICACIÓN O AMPLIACIÓN QUE REALICE EL ACREDITADO O CLIENTE A LA VIVIENDA DEJARÁ SIN
                EFECTO LA COBERTURA DE LA GARANTÍA DEL APÉNDICE CORRESPONDIENTE A LA PRESENTE PÓLIZA.
            </p>

            <p><u><b>SEPTIMA</b></u>.- EL TÉRMINO DE VIGENCIA DE LA PRESENTE PÓLIZA COMENZARÁ A PARTIR DEL DÍA ______ DEL MES DE
                ___________________ DEL AÑO DE __________, Y CONCLUIRÁ EL DÍA _______ DEL MES DE _____________________ DEL AÑO DE
                ____________. AJUSTANDO CADA CONCEPTO AL PERIODO DE COBERTURA QUE SE ESPECIFICA EN EL
                @if($tiempo == 2)
                    APENDICE 1 Y APENDICE 2,
                @else
                    APENDICE 1, APENDICE 2 Y APENDICE 3,
                @endif
                DE ESTA MISMA PÓLIZA.
            </p>

            <br><br><br><br>
            <br><br><br><br>

            <p style="text-align: center;">SAN LUIS POTOSI, S.L.P. A LOS ________ DÍAS DEL MES DE _________________ DE ____________.</p>

            <br><br><br><br>
            <br><br><br><br>

            <div class="table">
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"><b>POR LA CONSTRUCTORA</b></div>
                    <div class="table-cell" style="text-align:center;"><b>ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TERMINOS Y ALCANCE DE LA PRESENTE POLIZA</b></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"></div>
                    <div class="table-cell" style="text-align:center;"><br><br> {{mb_strtoupper($contratos[0]->nombre_cliente)}}</div>
                </div>
            </div>

            <br><br><br>

            <p style="text-align:center; font-size: 9pt;"><b>DIRECCION DE LA VIVIENDA:</b> {{mb_strtoupper($contratos[0]->calle)}} NO. {{$contratos[0]->numero}}, FRACC. "{{mb_strtoupper($contratos[0]->proyecto)}}", {{$contratos[0]->ciudadFraccionamiento}}.</p>

        </div>

        <div style="margin 20px;">
            <h3>A N E X O No.1</h3>
            <p>ESTADO QUE GUARDAN LOS BIENES E INSTALACIONES DE LA VIVIENDA</p>



        <div class="contenedor">
            <div class="table2" style="float:left; width: 350px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell2"><b>ALBAÑILERIA</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">PISOS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">MUROS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">LOSAS Y PLAFONES</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
            </div>

            <div class="table2" style="float:right; width: 350px;">
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2"><b>CARPINTERIA</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">PUERTAS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">MARCOS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">OTROS CONCEPTOS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
            </div>
        </div>



        <div class="contenedor" style="margin-top: 100px;">
            <div class="table2" style="float:left; width: 350px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell2"><b>INST. ELECTRICAS</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">APAGADORES</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">CONTACTOS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">SOCKETS Y ARBOTANTES</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">BREAKERS O PASTILLAS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
            </div>

            <div class="table2" style="float:right; width: 350px;">
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2"><b>CERRAJERIA</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">CHAPAS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
            </div>
            <br><br><br><br>
            <div class="table2" style="float:right; width: 350px;">
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2"><b>ALUMINIO</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">CANCELERIA</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">VIDRIOS</div>
                    <div class="table-cell2" style="font-size:7pt;">SIN RAYONES,FISURAS O ROTOS</div>
                </div>
            </div>
        </div>



        <div class="contenedor" style="margin-top: 220px;">
            <div class="table2" style="float:left; width: 350px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell2"><b>INST. HID. SANITARIA Y GAS</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">LAVABO</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">W.C.</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">REGADERA</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">FREGADERO</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">LAVADERO</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">CALENTADOR</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">DESAGÛES</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">ACCESORIOS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">INSTALACIÓN DE GAS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
            </div> <br>

            <div class="table2" style="float:right; width: 350px;">
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2"><b>PINTURA</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">VIVIENDA PINTADA</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
            </div>
            <br><br><br><br>
            <div class="table2" style="float:right; width: 350px;">
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2"><b>AREAS EXTERIORES</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">JARDINERÍA</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">ANDADORES</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">AC. HIDRÁULICAS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row" style="float:right;">
                    <div colspan="2" class="table-cell2">AC. ELÉCTRICAS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
            </div>
        </div>



        <div class="contenedor" style="margin-top: 450px;">
            <div class="table2" style="float:left; width: 350px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell2"><b>HERRERIA Y CANCELERIA</b></div>
                    <div class="table-cell2"><b>SIN DEFECTO</b></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">PUERTAS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">MARCOS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">PASAMANOS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">ESCALERA MARINA</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell2">OTROS CONCEPTOS</div>
                    <div class="table-cell2">&#10004;</div>
                </div>
            </div>
        </div>

        <div style="margin-top: 590px;">
            <p>CONFORME A LO DETALLADO, DESPUÉS DE UNA REVISIÓN CONJUNTA DEL INMUEBLE, SE FIRMA EL PRESENTE ANEXO DE LA PÓLIZA DE GARANTÍA.</p>

            <div class="table">
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"><b>POR LA CONSTRUCTORA</b></div>
                    <div class="table-cell" style="text-align:center;"><b>ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TERMINOS Y ALCANCE DE LA PRESENTE POLIZA</b></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"></div>
                    <div class="table-cell" style="text-align:center;"><br><br> {{mb_strtoupper($contratos[0]->nombre_cliente)}}</div>
                </div>
            </div>

            <br>

            <p style="text-align:center; font-size: 9pt;"><b>DIRECCION DE LA VIVIENDA:</b> {{mb_strtoupper($contratos[0]->calle)}} NO. {{$contratos[0]->numero}}, FRACC. "{{mb_strtoupper($contratos[0]->proyecto)}}", {{$contratos[0]->ciudadFraccionamiento}}.</p>
        </div>

        </div>


        <div style="margin: 20px;">

            @if($tiempo == 2)
                <h3>A P E N D I C E No. 1</h3>
                <p>COBERTURA DE GARANTIA POR FALLAS TECNICAS Y VICIOS OCULTOS QUE REPORTE LA VIVIENDA</p>
                <h3>BIENES E INSTALACIONES CON GARANTIA DE TRES MESES.</h3>
                <ul>
                    <li>TUBERÍAS DE GAS, AGUA O SANITARIAS TAPADAS, CON FUGAS O EN MAL ESTADO.</li>
                    <li>MUEBLES DE BAÑO, REGADERAS, MONOMANDOS, LLAVES, FREGADERO, LAVADERO Y SUS ACCESORIOS.</li>
                    <li>ACCESORIOS DE BAÑO, MAL COLOCADOS O CON DEFECTO.</li>
                    <li>DEFECTO EN FUNCIONAMIENTO DEL CALENTADOR A GAS LP Y CALENTADOR SOLAR (SI LA VIVIENDA LO INCLUYE EN SU EQUIPAMIENTO)</li>
                    <li>FALLAS EN INSTALACIÓN ELÉCTRICA</li>
                    <li>ACCESORIOS ELECTRICOS DEFECTUOSOS</li>
                    <li>PISOS Y AZULEJOS MAL EMBOQUILLADOS</li>
                    <li>PISOS Y AZULEJOS, FLOJOS O FISURADOS (ABERTURA MAYOR A 1.5 MM)</li>
                    <li>VENTANAS, PUERTAS Y CHAPAS EN MAL ESTADO O DEFECTUOSAS.</li>
                    <li>HUMEDAD EN MUROS, NO DERIVADO DE UNA CAUSA EXTERNA PROPIA DE LA VIVIENDA.</li>
                    <li>CANCELERIA Y DOMOS, MALA INSTALACIÓN Y SELLADOS</li>
                </ul>
                <h3>A P E N D I C E No. 2</h3>
                <p>COBERTURA DE GARANTIA POR VICIOS OCULTOS QUE REPORTE LA VIVIENDA.</p>
                <h3>BIENES E INSTALACIONES CON GARANTIA DE DOS AÑOS </h3>
                <ul>
                    <li>DEFECTOS EN IMPERMEABILIZACIÓN, NO DERVADO DE UNA CAUSA EXTERNA PROPIA DE LA VIVIENDA.</li>
                    <li>DEFECTOS ESTRUCTURALES, GRIETAS O FISURAS QUE AFECTEN MUROS Y PLAFONES, AQUELLAS EN LAS QUE SE PUEDE VER DE LADO A LADO, O QUE TENGA UNA APERTURA MAYOR A 1.5 MM</li>
                </ul>

            @else
                <h3>A P E N D I C E No. 1</h3>
                <p>COBERTURA DE GARANTIA POR VICIOS OCULTOS QUE REPORTE LA VIVIENDA.</p>
                <h3>BIENES E INSTALACIONES CON GARANTIA DE DOCE MESES.</h3>
                <ul>
                    <li>TUBERÍAS DE GAS, AGUA O SANITARIAS TAPADAS, CON FUGAS O EN MAL ESTADO.</li>
                    <li>MUEBLES DE BAÑO, REGADERAS, MONOMANDOS, LLAVES, FREGADERO, LAVADERO Y SUS ACCESORIOS.</li>
                    <li>ACCESORIOS DE BAÑO, MAL COLOCADOS O CON DEFECTO.</li>
                    <li>DEFECTO EN FUNCIONAMIENTO DEL CALENTADOR A GAS LP Y CALENTADOR SOLAR (SI LA VIVIENDA LO INCLUYE EN SU EQUIPAMIENTO)</li>
                    <li>FALLAS EN INSTALACIÓN ELÉCTRICA</li>
                    <li>ACCESORIOS ELECTRICOS DEFECTUOSOS</li>
                    <li>PISOS Y AZULEJOS MAL EMBOQUILLADOS</li>
                    <li>PISOS Y AZULEJOS, FLOJOS O FISURADOS (ABERTURA MAYOR A 1.5 MM)</li>
                    <li>FUNCIONAMIENTO DE VENTANAS</li>
                    <li>HUMEDAD EN MUROS, NO DERIVADO DE UNA CAUSA EXTERNA PROPIA DE LA VIVIENDA.</li>
                    <li>CANCELERIA Y DOMOS, MALA INSTALACIÓN Y SELLADOS</li>
                </ul>
                <h3>A P E N D I C E No. 2</h3>
                <p>COBERTURA DE GARANTIA POR FALLAS TECNICAS QUE REPORTE LA VIVIENDA.</p>
                <h3>BIENES E INSTALACIONES CON GARANTIA DE CINCO AÑOS. </h3>
                <ul>
                    <li>DEFECTOS EN IMPERMEABILIZACIÓN, NO DERVADO DE UNA CAUSA EXTERNA PROPIA DE LA VIVIENDA.</li>
                    <li>DEFECTOS ESTRUCTURALES, GRIETAS O FISURAS QUE AFECTEN MUROS Y PLAFONES, AQUELLAS EN LAS
                        QUE SE PUEDE VER DE LADO A LADO, O QUE TENGA UNA APERTURA MAYOR A 1.5 MM</li>
                </ul>

                <h3>A P E N D I C E No. 3</h3>
                <h3>BIENES E INSTALACIONES CON GARANTIA DE SEIS MESES. </h3>
                <ul>
                    <li>PUERTAS EXTERIORES E INTERIORES Y CHAPAS EN MAL ESTADO O DEFECTUOSAS.</li>

                </ul>
            @endif

            <h4>NOTA:</h4>
            <p>LA PÓLIZA <b>NO</b> APLICA PARA NINGÚN DEFECTO EN VIDRIOS; YA SEAN ROTOS, FISURADOS O RAYADOS;
                EN PUERTAS, VENTANAS NI DOMOS, DESPUÉS DE ENTREGADA LA VIVIENDA A <i>"EL ACREDITADO"</i>.</p>
            <br>
            <div class="table">
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"><b>POR LA CONSTRUCTORA</b></div>
                    <div class="table-cell" style="text-align:center;"><b>ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TERMINOS Y ALCANCE DE LA PRESENTE POLIZA</b></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"></div>
                    <div class="table-cell" style="text-align:center;"><br><br> {{mb_strtoupper($contratos[0]->nombre_cliente)}}</div>
                </div>
            </div>

            <br>

            <p style="text-align:center; font-size: 9pt;"><b>DIRECCION DE LA VIVIENDA:</b> {{mb_strtoupper($contratos[0]->calle)}} NO. {{$contratos[0]->numero}}, FRACC. "{{mb_strtoupper($contratos[0]->proyecto)}}", {{$contratos[0]->ciudadFraccionamiento}}.</p>

        </div>



        <div style="margin:20px;">
            <h3>A N E X O No. 2</h3>
            <p>RECIBO DE LLAVES, DOCUMENTACION Y ACCESORIOS DE LA VIVIENDA</p>
            <br>
            <p>POR ESTE CONDUCTO HAGO CONSTAR QUE RECIBÍ DE GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V., LO SIGUIENTE:</p>
            <br><br>
            <div class="table">
                <div class="table-row">
                    <div class="table-cell3" style="text-align:center;"><b>CONCEPTO</b></div>
                    <div class="table-cell3" style="text-align:center; width:20%;"><b>RECIBIDO</b></div>
                </div>
                <div class="table-row">
                    <div class="table-cell3" style="text-align:left;">DOCUMENTACIÓN DE LA VIVIENDA (CARTA DE ENTREGA, PÓLIZA DE GARANTÍA, FACTIBILIDAD DE AGUA, PREDIAL Y PLANO ARQUITECTONICO)</div>
                    <div class="table-cell3" style="text-align:center;">&#10004;</div>
                </div>
                <div class="table-row">
                    <div class="table-cell3" style="text-align:left;">JUEGOS DE LLAVES DE CADA UNA DE LAS CHAPAS DE LA VIVIENDA (EXCEPTO CHAPA DE PUERTAS DE BAÑOS)</div>
                    <div class="table-cell3" style="text-align:center;">&#10004;</div>
                </div>
                <div class="table-row">
                    <div class="table-cell3" style="text-align:left;">W.C. CON TAPA Y ASIENTO</div>
                    <div class="table-cell3" style="text-align:center;">&#10004;</div>
                </div>
                <div class="table-row">
                    <div class="table-cell3" style="text-align:left;">ACCESORIOS DE BAÑO (TOALLEROS Y PORTAROLLOS)</div>
                    <div class="table-cell3" style="text-align:center;">&#10004;</div>
                </div>
                <div class="table-row">
                    <div class="table-cell3" style="text-align:left;">LLAVES NARIZ (CUADRO DE TOMA DOMICILIARIA O AUXILIAR DE COCHERA)</div>
                    <div class="table-cell3" style="text-align:center;">&#10004;</div>
                </div>
                <div class="table-row">
                    <div class="table-cell3" style="text-align:left;">CALENTADOR COMPLETO (SERPENTÍN, CAPUCHÓN, PERILLA Y TERMOSTATO)</div>
                    <div class="table-cell3" style="text-align:center;">&#10004;</div>
                </div>
                <div class="table-row">
                    <div class="table-cell3" style="text-align:left;">TARJA DE COCINA Y ACCESORIOS</div>
                    <div class="table-cell3" style="text-align:center;">&#10004;</div>
                </div>
            </div>
            <br><br><br><br><br>
            <div class="table">
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"><b>POR LA CONSTRUCTORA</b></div>
                    <div class="table-cell" style="text-align:center;"><b>ESTOY DE ACUERDO Y ME FUERON EXPLICADOS LOS TERMINOS Y ALCANCE DE LA PRESENTE POLIZA</b></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" style="text-align:center;"></div>
                    <div class="table-cell" style="text-align:center;"><br><br> {{mb_strtoupper($contratos[0]->nombre_cliente)}}</div>
                </div>
            </div>

            <br><br><br><br><br>
            <br><br><br>

            <p style="text-align:center; font-size: 9pt;"><b>DIRECCION DE LA VIVIENDA:</b> {{mb_strtoupper($contratos[0]->calle)}} NO. {{$contratos[0]->numero}}, FRACC. "{{mb_strtoupper($contratos[0]->proyecto)}}", {{$contratos[0]->ciudadFraccionamiento}}.</p>
        </div>

    <div style="page-break-after:always;"></div>

        <div style="display: inline-block; float: center;">
            {{-- @if($contrato[0]->logo_fracc != NULL)
                <div margin: 10px; style="display: inline-block; float: left;" >
                    <IMG SRC="img/logosFraccionamientos/{{ $contratos[0]->logo_fracc }}" width="17%" height="17%">
                </div>
            @endif --}}
            <div style="display: inline-block; float: right;" >
                <IMG SRC="img/contratos/logoContrato.jpg" width="120" height="120">
            </div>
        </div>

        <div style="margin: 60px; margin-top: 120px;">
            <hr>
            <br>
            <br>
            <br>
            <p style="text-align: right; font-size: 12pt;"> San Luis Potosí, S.L.P. a ____ de ____________ del 20_____
            </p>
            <br>
            <br>
            <br><br>
            <br>


            <p style="margin: 10px; line-height: 190%; text-align: justify; font-size: 12pt;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Recibí de conformidad de <b>GRUPO CONSTRUCTOR CUMBRES S.A. DE C.V.</b>
                La vivienda ubicada en el <b>LOTE {{$contratos[0]->num_lote}}</b> de la <b>

                        MANZANA {{mb_strtoupper($contratos[0]->manzana)}}

                </b> en la calle
                <b>{{mb_strtoupper($contratos[0]->calle)}} No. {{$contratos[0]->numero}}</b> del
                <b> FRACCIONAMIENTO "{{mb_strtoupper($contratos[0]->proyecto)}}"</b> en la
                {{ucwords ($contratos[0]->delegacion)}}, en la ciudad de San Luis Potosí. De la cual
                Recibí la Póliza de Garantía correspondiente a
                la vivienda en la dirección antes mencionada en la cual se
                asentaran los posibles detalles que pudiera existir en la construcción.
            </p>



            <br>
            <br>
            <br>
            <br>
            <br>
            <p style="text-align: center; font-size: 12pt;"> Recibí </p>
            <br>
            <br>
            <br>
            <p style="text-align: center;">______________________________________</p>
            <p style="text-align: center; font-size: 12pt;"><b>{{mb_strtoupper($contratos[0]->nombre_cliente)}}</b></p>



        </div>



    </body>
</html>
