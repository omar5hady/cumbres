<!DOCTYPE HTML>
<HTML>

<head>
    <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
    <TITLE>Contrato</TITLE>
    <style TYPE="text/css">
        @page {
            margin-left: 0.65in;
            margin-right: 0.65in;
            margin-top: 170px;
            margin-bottom: 100px;
            font-family: "Arial", serif;
            font-size: 11pt;
        }

        p{
            font-family: "Arial", serif;
            margin-bottom: 0in;
            direction: ltr;
            text-align: justify;
            widows: 2;
            orphans: 2;
            padding: 0em;,
            font-size: 10pt;
        }

        h5{
            margin-top: 0in;
            margin-bottom: 0in;
            border: 1px solid #00000a;
            padding: 0.01in 0.06in;
            direction: ltr;
            text-align: center;
            widows: 2;
            orphans: 2
        }

        ol {
            counter-reset: item;
            list-style-type: none;
        }
        ol > li {
            position: relative;
            margin-bottom: 7px;
        }
        /* li { display: block; } */
        ol > li:before {
            content: counter(item, upper-alpha) ")";
            counter-increment: item;
            position: absolute;
            font-weight:bold;
            left: -28px;
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
        }

        #header {
            position: fixed;
            left: 0px;
            top: -150px;
            right: 0px;
            height: 160px;
            text-align: center;
        }

        #footer {
            position: fixed;
            left: 0px;
            bottom: -200px;
            right: 0px;
            height: 180px;
        }

        #footer .page:after {
            content: counter(page, upper-roman);
        }
    </style>

</head>

<body STYLE="padding-left: 0.05in; padding-right: 0.05in">
    <div id="header">
        <div class="table">
            <div class="table-row">
                @if ($cabecera[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    <div class="table-cell"><IMG
                            SRC="img/contratos/CONTRATOS_html_7790d2bb.png" ALIGN=BOTTOM STYLE="margin-top:2px"></div>
                @endif
                @if ($cabecera[0]->emp_constructora == 'CONCRETANIA')
                    <div class="table-cell"><IMG
                            SRC="img/contratos/logoConcretaniaObra.png" ALIGN=BOTTOM STYLE="margin-top:2px"></div>
                @endif
                <div class="table-cell"><br><br><br>
                        <I><B>{{ $cabecera[0]->proyecto }}
                        &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ $cabecera[0]->clave }}</B></I>
    			</div>
  			</div>
            <div class="table-row">
                <div class="table-cell">
                    &nbsp;
    			</div>

                <div class="table-cell" style="text-align: right; font-size: 9pt;">
                        <I>
                            Contrato referente a {{ $cabecera[0]->descripcion_corta }},
                            Monto del Contrato ${{ $cabecera[0]->total_importe2 }}</I></SPAN>
    			</div>
  			</div>
 		</div>
		<hr style="color: darkblue;">
    </div>

	<table style="margin-top: -15px;">
		<tr>
            <TD BGCOLOR="#c2c2c2"
                STYLE="border: 1px solid #00000a; padding:10px;">

                <p ALIGN=JUSTIFY
                    STYLE="margin-top: 0in; margin-left: 0.05in; font-size:11pt;">
                        ADENDUM DEL CONTRATO DE OBRA A PRECIO ALZADO, CELEBRADO ENTRE
                        @if ($cabecera[0]->emp_constructora == 'CONCRETANIA')
                            <B>CONCRETANIA, S.A. DE C.V.,</B>
                        @else
                            <B>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.,</B>
                        @endif
                        REPRESENTADA POR EL SR. <B>{{ $cabecera[0]->apoderado }},</B>
                        EN SU CARACTER DE APODERADO LEGAL Y A QUIEN EN LO SUCESIVO SE LE DENOMINA <b>“LA CONTRATANTE”,</b>
                        Y POR LA OTRA PARTE
                        <B>{{ mb_strtoupper($cabecera[0]->contratista) }} </B> REPRESENTADA POR EL
                        <b>{{ mb_strtoupper($cabecera[0]->representante) }}</b>,
                        A QUIEN EN LO SUBSECUENTE SE LE IDENTIFICARA COMO <B>“LA CONTRATISTA”,</B>
                        EN LOS SIGUIENTES TERMINOS.
                </p>
            </TD>
		</tr>
	</table>

	<h5><br></h5>

    <p ALIGN=JUSTIFY>
        AMBAS PARTES DECLARAN QUE TIENEN CELEBRADO EL CONTRATO DE OBRA MENCIONADO CON UN MONTO DE
        <b>
            $ {{strToUpper($cabecera[0]->totalImporteLetra)}}
        </b>
        Y QUE A LA FECHA DE FIRMA DEL PRESENTE ADENDUM SE ENCUENTRA
        VIGENTE EN TODOS SUS TERMINOS DERECHOS Y OBLIGACIONES.
    </p>

    <p ALIGN=JUSTIFY>
        QUE ES SU DESEO CELEBRAR EL PRESENTE ADENDUM QUE FORMARÁ PARTE INTEGRANTE DEL CONTRATO
        ANTERIORMENTE MENCIONADO CON EL FIN DE MODIFICAR
        @if($cabecera[0]->total_original != $cabecera[0]->total_importe && $cabecera[0]->f_fin != $cabecera[0]->f_fin2)
            LAS CLAUSULAS <b>NO.6</b> Y <b>NO. 7</b>
        @else
            LA CLAUSULA <b>
            {{ ($cabecera[0]->total_original != $cabecera[0]->total_importe) ?  'NO.6' : ''}}
            {{ $cabecera[0]->f_fin != $cabecera[0]->f_fin2 ?  'NO.7' : ''}}
        @endif
        </b>
        DEL CONTRATO DE OBRA A PRECIO ALZADO A EFECTO DE MODIFICAR
        @if($cabecera[0]->total_original != $cabecera[0]->total_importe && $cabecera[0]->f_fin != $cabecera[0]->f_fin2)
            EL IMPORTE Y LA FECHA DE TERMINO,
        @else
            {{ ($cabecera[0]->total_original != $cabecera[0]->total_importe) ?  'EL IMPORTE,' : ''}}
            {{ $cabecera[0]->f_fin != $cabecera[0]->f_fin2 ?  'LA FECHA DE TERMINO,' : ''}}
        @endif
        QUEDANDO ACORDADO EN LA SIGUIENTE FORMA:
    </p>

    <ol>
        @if($cabecera[0]->total_original != $cabecera[0]->total_importe)
            <li>
                EL IMPORTE DEL VALOR DE LAS OBRAS SERÁ DE
                <b>
                    $ {{strToUpper($cabecera[0]->totalOriginalLetra )}}.
                </b>
            </li>
        @endif
        @if($cabecera[0]->f_fin != $cabecera[0]->f_fin2)
        <li>
            EL PERIODO DE LA REALIZACIÓN DE LAS OBRAS SERÁ DEL
            <b>
                {{strToUpper($cabecera[0]->f_ini)}} AL {{strToUpper($cabecera[0]->f_fin2)}}.
            </b>
        </li>
        @endif
    </ol>

    <p>
        AMBAS PARTES ACUERDAN QUE LO ANTERIORMENTE SEÑALADO FORMARÁ PARTE INTEGRANTE DEL
        CONTRATO DE OBRA A PRECIO ALZADO FIRMADO POR LAS PARTES EN FECHA
        <b>
            {{strToUpper($cabecera[0]->f_ini)}}
        </b>
        SUBSISTIENDO INTEGRAMENTE EL RESTO DEL CLAUSULADO DE DICHO CONTRATO.
    </p>

    <p>
        LAS PARTES RECONOCEN QUE EL PRESENTE ADDENDUM FORMARÁ PARTE INTEGRANTE DEL CONTRATO DE
        OBRA A PRECIO ALZADO REFERIDO, POR LO QUE DEBERÁN CONSIDERARSE E INTERPRETARSE DE
        MANERA CONJUNTA
    </p>

    <p>
        PARA CONSTANCIA Y EFECTOS LEGALES LAS PARTES EN UNION DE LOS TESTIGOS QUE AL FINAL
        APARECEN SUSCRIBEN EL PRESENTE CONVENIO EN LA CIUDAD DE SAN LUIS POTOSÍ, S.L.P.
        A <b>{{strToUpper($cabecera[0]->hoy)}}.</b>
    </p>
    <br><br>


    <div class="table">
        <div class="table-row">
            <div class="table-cell"><b>"EL CONTRATANTE"</b></div>
            <div class="table-cell"><b>"EL CONTRATISTA"</b></div>
        </div>
        <div class="table-row" >
            <div class="table-cell" style="padding-top: 60px;"><b>_________________________________</div>
            <div class="table-cell" style="padding-top: 60px;"><b>_________________________________</div>
        </div>
        <div class="table-row">
            <div class="table-cell"> <b>{{ $cabecera[0]->apoderado }}</div>
            <div class="table-cell"> <b>{{ mb_strtoupper($cabecera[0]->representante) }}</div>
        </div>
        <div class="table-row">
            <div class="table-cell"> APODERADO LEGAL</div>
            <div class="table-cell"> REPRESENTANTE LEGAL</div>
        </div>
    </div>

    <div id="footer">
        <IMG SRC="img/contratos/CONTRATOS_html_5d82e5a4.png">
        @if ($cabecera[0]->emp_constructora == 'Grupo Constructor Cumbres')
            <p ALIGN=CENTER>
                <FONT SIZE=1 STYLE="font-size: 8pt; text-align:center;">
                    <SPAN LANG="es-ES">
                        MANUEL GUTIERREZ NAJERA #190 COL
                        TEQUIS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        TEL Y FAX 01 (4) 833-46-83-85 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        San Luis Potosí, S.L.P.
                    </SPAN>
                </FONT>
            </p>
        @endif
        @if ($cabecera[0]->emp_constructora == 'CONCRETANIA')
            <p ALIGN=CENTER>
                <FONT SIZE=1 STYLE="font-size: 8pt; text-align:center;">
                    <SPAN LANG="es-ES">
                        MANUEL GUTIERREZ NAJERA #180 COL
                        TEQUIS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        TEL Y FAX 01 (4) 833-46-83-85 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        San Luis Potosí, S.L.P.
                    </SPAN>
                </FONT>
            </p>
        @endif
    </div>

</body>

</HTML>
