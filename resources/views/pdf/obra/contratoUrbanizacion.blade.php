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

        .h3{

        }

        h5{
            margin-top: 0in;
            margin-bottom: 0in;
            border: 1px solid #00000a;
            padding: 0.01in 0.06in;
            direction: ltr;
            text-align: center;
            widows: 2;
            orphans: 2;
            font-size: 12pt;
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

	<table style="margin-top: -15px; margin-bottom: 15px;">
		<tr>
            <TD BGCOLOR="#c2c2c2"
                STYLE="border: 1px solid #00000a; padding:10px;">

                <p ALIGN=JUSTIFY
                    STYLE="margin-top: 0in; margin-left: 0.05in; font-size:11pt;">
                    CONTRATO
                    DE OBRA A PRECIO ALZADO Y TIEMPO DETERMINADO QUE CELEBRAN POR UNA
                    PARTE
                    @if ($cabecera[0]->emp_constructora == 'CONCRETANIA')
                        <b>CONCRETANIA, S.A. DE C.V.,</b>
                    @else
                        <b>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.,</b>
                    @endif
                    REPRESENTADA POR EL SR. <B>{{ $cabecera[0]->apoderado }},</B>
                    EN SU CARACTER DE APODERADO LEGAL Y A QUIEN EN LO SUCESIVO SE LE
                    DENOMINA <B>“LA CONTRATANTE”</B>, Y POR LA OTRA PARTE <B>{{ mb_strtoupper($cabecera[0]->contratista) }} </B>
                    REPRESENTADA POR <b>{{ mb_strtoupper($cabecera[0]->representante) }}</b>,
                    A QUIEN EN LO SUBSECUENTE SE LE IDENTIFICARA COMO <B>“LA CONTRATISTA”,</B>
                    MISMO QUE SUJETAN AL TENOR DE LAS SIGUIENTES Y CLAUSULAS.</SPAN>
                </p>
            </TD>
		</tr>
	</table>

	<h5>
        C L A U S U L A S
    </h5>

    <p ALIGN=JUSTIFY>
       <b>1. -</b> Contrato de Obra a Precio Alzado y Por Tiempo Determinado
    </p>

    <p>
        <b>2. -</b> Datos del contratista:
    </p>

    <div style="margin-left: 50px; margin-bottom: 30px;">
        <p>
            <b>{{ mb_strtoupper($cabecera[0]->contratista) }}</b>
            <br>
            REPRESENTADA POR EL <b>{{ mb_strtoupper($cabecera[0]->representante) }}</b>
            <br><br>
            Domicilio:
            <div style="margin-left: 100px;">
                {{ $cabecera[0]->direccion }}, <br>
                {{ $cabecera[0]->colonia }}, C.P. {{ $cabecera[0]->codigoPostal }}<br>
                {{ $cabecera[0]->estado }}
            </div>
            <br>

            Registro Federal de Contribuyentes: <b>{{ $cabecera[0]->rfc }}</b>  <br> <br>
            Instituto Mexicano del Seguro Social: <b>{{ $cabecera[0]->imss }}</b>
    </div>

    <p>
        <b>3. - LA CONTRATISTA.</b> Empresario y patrón del personal que ocupa para la ejecución de
        los trabajos objeto del presente contrato, será el único responsable de las obligaciones
        derivadas de las disposiciones legales y demás ordenamientos en materia de trabajo y
        seguridad social. LA CONTRATISTA conviene por lo mismo, en responder de todas las
        reclamaciones que sus trabajadores o terceros presenten en contra de LA CONTRATANTE.
        En relación con los trabajos materia de este contrato.
    </p>

    <p>
        <b>4. – LA CONTRATISTA</b> cuenta con los conocimientos técnicos y la experiencia necesaria para cumplir y
        llevar a cabo la obra del presente contrato.
    </p>

    <p>
        <b>5. -</b> El contrato es referente a los Trabajos de
        <B>{{ $cabecera[0]->descripcion_corta }}</B>
        según presupuesto; de la obra “
        <b>
            {{ $cabecera[0]->proyecto }},
            @if (!is_numeric($cabecera[0]->etapa->num_etapa))
                {{ $cabecera[0]->etapa->num_etapa }},</B>
            @endif
        </b>”. Ubicada en
        @if ($cabecera[0]->direccion_proy != '' && $cabecera[0]->direccion_proy != '')
            {{ $cabecera[0]->direccion_proy }},
        @else
            {{ $cabecera[0]->calleFracc }},
        @endif
        {{ $cabecera[0]->delegacion }}, {{ $cabecera[0]->ciudadFracc }}, {{ $cabecera[0]->estadoFracc }},
        misma que identifican ambas partes.
    </p>

    <p>
        <b>6. -</b> El monto del presente contratos es de <B>${{ $cabecera[0]->totalImporteLetra }}</B>
        @if ($cabecera[0]->iva == 1)
            Este monto incluye el impuesto al valor agregado del 16%.
        @endif
    </p>

    <p>
        <b>7. – LA CONTRATISTA</b> se compromete a realizar los trabajos a partir del
        <b>{{ $cabecera[0]->f_ini }}</b> al <b>{{ $cabecera[0]->f_fin }}.</b>
    </p>

    <p>
        <b>8. - LA CONTRATISTA</b> se responsabiliza de la calidad de los trabajos objeto de este contrato
        y se obliga a responder en caso de fallas en estas, debidas a la falta de cuidado, conocimiento
        o pericia, o que por negligencia de su parte se ocasionaran daños en las construcciones,
        comprometiéndose en tal caso a la reparación de los daños y al pago inmediato de los
        perjuicios ocasionados.
    </p>

    <p>
        <b>9. - LA CONTRATISTA</b> entregará a la persona asignada por
        <b>LA CONTRATANTE,</b> el trabajo señalado en este contrato, completamente terminado,
        le recibirá por escrito, debiendo firmar de recibido de conformidad.
    </p>

    <p>
        <b>10.- LA CONTRATANTE</b> se compromete a liquidar de la siguiente forma:
    </p>

    <p>
        a) Mediante un anticipo correspondiente al <b>{{ $cabecera[0]->anticipo }}%</b>
        del monto del contrato al iniciar las obras. El total del pago es
        <b>${{ $cabecera[0]->anticipoLetra }}.</b>
    </p>

    <p>
        b) <b>LA CONTRATANTE</b> realizara pagos a <b>LA CONTRATISTA</b> por concepto de
        estimación, dicho importe será
        calculado en base al avance de obra, que determine la persona asignada
        por <b>LA CONTRATANTE</b>.
        De los pagos por estimación se retendrá el
        {{ $cabecera[0]->anticipo }}% de dicho importe para amortizar el
        anticipo del contrato y adicionalmente <b>LA CONTRATISTA</b> autoriza que se le
        retenga el 3% de
        cada estimación por concepto de retención de fondo de garantía, para
        cubrir gastos por vicios
        ocultos al culminar las obras.
    </p>

    <p>
        c) <b>LA CONTRATANTE</b> realizara pagos a <b>LA CONTRATISTA</b> por concepto de
        devolución de fondo de garantía, cuando la persona asignada por <b>LA CONTRATANTE</b> autorice.
        En caso de existir un vicio oculto en la obra y
        que <b>LA CONTRATISTA</b> haga caso omiso para su reparación. <b>LA CONTRATANTE</b> reparará los daños
        generados en las obras por medio de otra empresa y el importe generado por dicha reparación será con cargo al
        fondo de garantía retenido a <b>LA CONTRATISTA</b>. Para realizar lo anterior no habrá necesidad de algún
        documento que autorice los trabajos por parte de <b>LA CONTRATISTA</b> a <b>LA CONTRATANTE</b>.
    </p>

    <p>
        <b>11.- LA CONTRATISTA</b> acepta, que en caso de no concluir en tiempo los trabajos señalados en
        el  presente contrato y en incumplimiento al programa de ejecución de obra. Incurrirá en una
        Pena Convencional de razón de aplicar una tasa del 3% de interés ordinario, de
        manera mensual sobre el importe de los trabajos no realizados en la fecha de su
        vencimiento, dicha sanción quedara vigente desde la fecha en que se incurra en el
        atraso hasta la recepción final de los trabajos.
    </p>

    <p>
        En el entendido de que cuando no se devenguen intereses por meses completos se aplicaran de manera
        proporcional a los días naturales transcurridos de cada mes.
    </p>

    <p>
        Los intereses devengados por mora serán pagaderos a la vista sin que exista necesidad alguna
        de firmar algún otro documento, comprometiéndose  por este medio <b>LA CONTRATISTA</b>
        a cubrir su importe de manera incondicional a favor de <b>LA CONTRATANTE,</b> en el momento
        en que incurran, en su defecto autoriza por este medio <b>LA CONTRATISTA</b> a disminuir
        el importe de los mismos de los pagos pendiente a realizar por concepto de
        estimaciones o fondos de garantía.
    </p>

    <p>
        <b>12.- “LA CONTRATISTA”</b> se obliga a expedir a favor de <b>“LA CONTRATANTE”</b>
        los siguientes documentos:
    </p>

    <p style="margin-left: 50px; text-indent: -0.2in">
        a.	Un pagare por el monto del anticipo para garantizar el buen uso y aplicación del mismo.
    </p>

    <p style="margin-left: 50px; text-indent: -0.2in">
        b.	Un pagare por el <b>10%</b> del monto total de los trabajos contratados para garantizar
        el cumplimiento en su totalidad de las obligaciones a cargo de <b>“LA CONTRATISTA”</b> así como,
        para responder por los defectos y vicios ocultos en las obras hasta por un periodo de
        dos años o de cualquier otra obligación contraída por virtud del contrato celebrado o
        que derive del mismo.
    </p>

    <p>
        Estos documentos serán aplicables a partir del día hábil siguiente en el que sean exigibles
        cualesquiera de las obligaciones a cargo de <b>“LA CONTRATISTA”</b> para cuyo cumplimiento fueron
        constituidas dichas garantías.
    </p>

    <p>
        Adicionalmente <b>“LA CONTRATISTA”</b> se obliga a que todos los materiales y equipo que se
        utilicen en los trabajos de la obra motivo de este contrato, cumplan con las normas de
        calidad establecidas por <b>“LA CONTRATANTE”,</b> así como a responder por su cuenta y
        riesgo de los defectos y vicios ocultos de los mismos y de los daños y perjuicios
        que por inobservancia o negligencia de su parte lleguen a causarse a <b>“LA CONTRATANTE”</b>
        o a terceros, en cuyo caso se hará efectiva la garantía otorgada para el cumplimiento
        del contrato, hasta por el monto total de la misma.
    </p>

    <p>
        <b>13.- LA CONTRATISTA</b> deberá entregar la factura correspondiente al contratante para
        su revisión y pago.
    </p>

    <p>
        <b>14. -</b> Todos los pagos se efectuarán en Manuel Gutiérrez Nájera # 190 Col. Tequis,
        en San Luis Potosí, S.L.P.
    </p>

    <p>
        Para cualquier controversia que se suscite con motivo del presente contrato los contratantes
        se someten a la Jurisdicción de los Tribunales de San Luis Potosí, S.L.P.
    </p>

    <p>
        <b>Se firma el presente contrato a los {{ $cabecera[0]->f_ini2 }}.</b>
    </p>

    <br><br><br><br>


    <div class="table">
        <div class="table-row">
            <div class="table-cell"><b>_____________________________</div>
            <div class="table-cell"></div>
            <div class="table-cell"><b>_____________________________</div>
        </div>
        <div class="table-row">
            <div class="table-cell"><b>CONTRATANTE</div>
            <div class="table-cell"></div>
            <div class="table-cell"><b>CONTRATISTA</div>
        </div>
        <div class="table-row">
            <div class="table-cell"> <b>APDO. {{ $cabecera[0]->apoderado }}</div>
            <div class="table-cell"></div>
            <div class="table-cell"> <b>REP.{{ mb_strtoupper($cabecera[0]->representante) }}</div>
        </div>
        @if ($cabecera[0]->emp_constructora == 'Grupo Constructor Cumbres')
            <div class="table-row">
                <div class="table-cell"> <b>GRUPO CONSTRUCTOR CUMBRES, SA DE CV</div>
                <div class="table-cell"></div>
                <div class="table-cell"> <b>{{ mb_strtoupper($cabecera[0]->contratista) }}</div>
            </div>
            <div class="table-row">
                <div class="table-cell"> <b>Manuel Gutiérrez Nájera # 190 Col. Tequisquiapan</div>
                <div class="table-cell"></div>
                <div class="table-cell"> <b>{{ $cabecera[0]->direccion }}</div>
            </div>
        @endif
        @if ($cabecera[0]->emp_constructora == 'CONCRETANIA')
            <div class="table-row">
                <div class="table-cell"> <b>CONCRETANIA, SA DE CV</div>
                <div class="table-cell"></div>
                <div class="table-cell"> <b>{{ mb_strtoupper($cabecera[0]->contratista) }}</div>
            </div>
            <div class="table-row">
                <div class="table-cell"> <b>Manuel Gutiérrez Nájera # 180 <br> Col. Tequisquiapan</div>
                <div class="table-cell"></div>
                <div class="table-cell"> <b>{{ $cabecera[0]->direccion }}</div>
            </div>
        @endif

        <div class="table-row">
            <div class="table-cell"> <b>San Luis Potosí, S.L.P.</div>
            <div class="table-cell"></div>
            <div class="table-cell"> <b>{{ $cabecera[0]->colonia }}, C.P.
                    {{ $cabecera[0]->codigoPostal }}</div>
        </div>
        <div class="table-row">
            <div class="table-cell"> <b>Tel. 01 (444) 8 33 46 83 </div>
            <div class="table-cell"></div>
            <div class="table-cell"> <b>{{ $cabecera[0]->estado }}</div>
        </div>
        <div class="table-row">
            <div class="table-cell"> <b>Tel. 01 (444) 8 33 46 84</div>
            <div class="table-cell"></div>
            <div class="table-cell"> <b>Tel. {{ $cabecera[0]->telefono }}</div>
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
