<!DOCTYPE HTML >
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>Contrato</TITLE>
	<STYLE TYPE="text/css">

		@page { size: 8.5in 11in; margin-left: 0.98in; margin-right: 0.98in; margin-top: 180px; margin-bottom: 50px }
		P { margin-bottom: 0in; direction: ltr; text-align: justify; widows: 2; orphans: 2; padding: 0em; }
		P.western { so-language: en-US }
		P.cjk { so-language: es-ES }
		H2 { text-indent: 0.5in; margin-top: 0in; margin-bottom: 0in; direction: ltr; text-align: justify; widows: 2; orphans: 2 }
		H2.western { font-family: "Arial", serif; font-size: 11pt; so-language: es-ES }
		H2.cjk { font-family: "Times New Roman"; font-size: 11pt; so-language: es-ES }
		H2.ctl { font-size: 10pt }
		H5 { margin-top: 0in; margin-bottom: 0in; border: 1px solid #00000a; padding: 0.01in 0.06in; direction: ltr; text-align: center; widows: 2; orphans: 2 }
		H5.western { font-family: "Times New Roman", serif; font-size: 12pt; so-language: es-ES }
		H5.cjk { font-family: "Times New Roman"; font-size: 12pt; so-language: es-ES }
		H5.ctl { font-family: "Times New Roman"; font-size: 10pt }
		H6 { margin-left: 3.5in; text-indent: -3.5in; margin-top: 0in; margin-bottom: 0in; direction: ltr; text-align: justify; widows: 2; orphans: 2 }
		H6.western { font-family: "Times New Roman", serif; font-size: 10pt; so-language: es-ES }
		H6.cjk { font-family: "Times New Roman"; font-size: 10pt; so-language: es-ES }
		H6.ctl { font-family: "Times New Roman"; font-size: 10pt; font-weight: normal }
        .table { display: table; width: 100%; border-collapse: collapse; }
        .table-row { display: table-row; }
        .table-cell { display: table-cell; padding: 0em; font-size: 9pt; }
		#header { position: fixed; left: 0px; top: -160px; right: 0px; height: 200px; text-align: center; } 
		#footer { position: fixed; left: 0px; bottom: -160px; right: 0px; height: 180px; } 
		#footer .page:after { content: counter(page, upper-roman); } 
	
	</STYLE>

</HEAD>
<BODY LANG="es-MX" DIR="LTR" STYLE="padding-left: 0.05in; padding-right: 0.05in">
<DIV id="header" >
	<div class="table">
	<div class="table-row">
		@if($cabecera[0]->emp_constructora == 'Grupo Constructor Cumbres')
			<div class="table-cell"  STYLE="padding-left: -0.6in;"><IMG SRC="img/contratos/CONTRATOS_html_7790d2bb.png" ALIGN=BOTTOM STYLE="margin-top:5px"></div>
		@endif
		@if($cabecera[0]->emp_constructora == 'CONCRETANIA')
			<div class="table-cell"  STYLE="padding-left: -0.6in;"><IMG SRC="img/contratos/logoConcretaniaObra.png" ALIGN=BOTTOM STYLE="margin-top:5px"></div>
		@endif
		<div class="table-cell"><br><br><br><br><FONT FACE="Arial Narrow, serif"><FONT SIZE=3><SPAN LANG="es-ES" "><I><B>{{$cabecera[0]->proyecto}}
	&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	{{$cabecera[0]->clave}}</B></I></SPAN></FONT></FONT></div>
		</div>
	</div>

	</SPAN><P LANG="en-GB" ALIGN=RIGHT STYLE="margin-left: 1.8in"><FONT FACE="Arial Narrow, serif"><FONT SIZE=1><SPAN LANG="es-ES"><I>	
		 Contrato referente a {{$cabecera[0]->descripcion_corta}},
	    Monto del Contrato ${{$cabecera[0]->total_importe2}}</I></SPAN></FONT></FONT></P>
	<IMG SRC="img/contratos/CONTRATOS_html_5d82e5a4.png">

</DIV>

<TABLE WIDTH=450 CELLPADDING=5 CELLSPACING=0>
	<COL WIDTH=450>
	<TR>
	<br>
	@if($cabecera[0]->emp_constructora == 'Grupo Constructor Cumbres')
		<TD WIDTH=450 HEIGHT=72 VALIGN=TOP BGCOLOR="#c2c2c2" STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0.2in; padding-left: 0.05in; padding-right: 0.09in">
			<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY STYLE="margin-left: 0.07in">
			</P>
			<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY STYLE="margin-top: 0in; margin-left: 0.05in">
			<FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">CONTRATO
			DE OBRA A PRECIO ALZADO Y TIEMPO DETERMINADO QUE CELEBRAN POR UNA
			PARTE </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>GRUPO
			CONSTRUCTOR CUMBRES, S.A. DE C.V.,</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">
			REPRESENTADA POR EL SR. </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>{{$cabecera[0]->apoderado}},</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">
			EN SU CARACTER DE APODERADO LEGAL Y A QUIEN EN LO SUCESIVO SE LE
			DENOMINA </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>“LA
			CONTRATANTE”</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">,
			Y POR LA OTRA PARTE </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>{{mb_strtoupper($cabecera[0]->contratista)}}
			</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">REPRESENTADA
			POR {{mb_strtoupper($cabecera[0]->representante)}}</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>,
			</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">A
			QUIEN EN LO SUBSECUENTE SE LE IDENTIFICARA COMO </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>“LA
			CONTRATISTA”,</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">
			MISMO QUE SUJETAN AL TENOR DE LAS SIGUIENTES Y CLAUSULAS.</SPAN></FONT></FONT></P>
		</TD>
	@endif
	@if($cabecera[0]->emp_constructora == 'CONCRETANIA')
		<TD WIDTH=450 HEIGHT=72 VALIGN=TOP BGCOLOR="#c2c2c2" STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0.2in; padding-left: 0.05in; padding-right: 0.09in">
			<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY STYLE="margin-left: 0.07in">
			</P>
			<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY STYLE="margin-top: 0in; margin-left: 0.05in">
			<FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">CONTRATO
			DE OBRA A PRECIO ALZADO Y TIEMPO DETERMINADO QUE CELEBRAN POR UNA
			PARTE </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>CONCRETANIA, S.A. DE C.V.,</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">
			REPRESENTADA POR EL SR. </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>{{$cabecera[0]->apoderado}},
			</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">
			EN SU CARACTER DE APODERADO LEGAL Y A QUIEN EN LO SUCESIVO SE LE
			DENOMINA </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>“LA
			CONTRATANTE”</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">,
			Y POR LA OTRA PARTE </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>{{mb_strtoupper($cabecera[0]->contratista)}}
			</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">REPRESENTADA
			POR {{mb_strtoupper($cabecera[0]->representante)}}</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>,
			</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">A
			QUIEN EN LO SUBSECUENTE SE LE IDENTIFICARA COMO </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES"><B>“LA
			CONTRATISTA”,</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="es-ES">
			MISMO QUE SUJETAN AL TENOR DE LAS SIGUIENTES Y CLAUSULAS.</SPAN></FONT></FONT></P>
		</TD>
	@endif
	</TR>
</TABLE>

<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY><BR>
</P>
<H5 LANG="es-ES" CLASS="western" ALIGN=LEFT STYLE="padding-left: 0.05in; padding-right: 0.05in" ><FONT COLOR="#808080">	</FONT>C
L A U S U L A S		</H5>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><SPAN LANG="es-ES"><B>1</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><SPAN LANG="es-ES">.
- Contrato de Obra a Precio Alzado y Por Tiempo Determinado</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=4><SPAN LANG="es-ES">
</SPAN></FONT></FONT>
</P>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><SPAN LANG="es-ES"><B>2</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><SPAN LANG="es-ES">.
- Datos del contratista:</SPAN></FONT></FONT></P>
<H2 LANG="es-ES" CLASS="western"><BR>
</H2>
<H2 LANG="es-ES" CLASS="western"><FONT SIZE=4>{{mb_strtoupper($cabecera[0]->contratista)}}</FONT></H2>
<H2 LANG="es-ES" CLASS="western"><FONT SIZE=2><SPAN STYLE="font-weight: normal">REPRESENTADA
POR EL {{mb_strtoupper($cabecera[0]->representante)}}</SPAN></FONT></H2>
<H2 LANG="es-ES" CLASS="western">	</H2>
<H2 LANG="es-ES" CLASS="western"><FONT SIZE=2><SPAN STYLE="font-weight: normal">Domicilio</SPAN></FONT></H2>

<H2 LANG="en-GB" CLASS="western" ALIGN=JUSTIFY STYLE="margin-left: 2.5in; margin-top:0in; text-indent: 0.5in; padding: 0em;">
<FONT FACE="Arial, serif"><FONT SIZE=2"><SPAN LANG="es-ES">{{$cabecera[0]->direccion}},
 </SPAN></FONT></FONT>
</H2>
<H2 LANG="en-GB" CLASS="western" ALIGN=JUSTIFY STYLE="margin-left: 2.5in; text-indent: 0.5in; padding: 0em;">
<FONT FACE="Arial, serif"><FONT SIZE=2"><SPAN LANG="es-ES">{{$cabecera[0]->colonia}},
C.P. {{$cabecera[0]->codigoPostal}}</SPAN></FONT></FONT></H2>

<H2 LANG="en-GB" CLASS="western" ALIGN=JUSTIFY STYLE="margin-left: 2.5in; text-indent: 0.5in; padding: 0em;">
<FONT FACE="Arial, serif"><FONT SIZE=2"><SPAN LANG="es-ES">{{$cabecera[0]->estado}}</SPAN></FONT></FONT></H2>

<P LANG="en-US" CLASS="western" STYLE="text-indent: 0.5in"><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 11pt">Registro
Federal de Contribuyentes:	{{$cabecera[0]->rfc}}</FONT></FONT></P>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY STYLE="text-indent: 0.5in; padding: 0em;">
<FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><SPAN LANG="es-ES">Instituto
Mexicano del Seguro Social: {{$cabecera[0]->imss}}</SPAN></FONT></FONT></P>

<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY><BR>
</P>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>3</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">.
–</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATISTA. </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">Empresario
y patrón del personal que ocupa para la ejecución de los trabajos
objeto del presente contrato, será el único responsable de las
obligaciones derivadas de las disposiciones legales y demás
ordenamientos en materia de trabajo y seguridad social. </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATISTA</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>
</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">conviene
por lo mismo, en responder de todas las reclamaciones que sus
trabajadores o terceros presenten en contra de </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATANTE</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">.
En relación con los trabajos materia de este contrato.</SPAN></FONT></FONT></P>

<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY><BR>
</P>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>4.
– </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATISTA </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">cuenta
con los conocimientos técnicos y la experiencia necesaria para
cumplir y llevar a cabo la obra del presente contrato.</SPAN></FONT></FONT></P>
<br>
<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>5.
- </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">El
contrato es referente a los trabajos de </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>{{$cabecera[0]->descripcion_corta}}
</B> {{$cabecera[0]->descripcion_larga}},
de la obra </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>{{$cabecera[0]->proyecto}}, 
	
	EN CALLE 
	@if($cabecera[0]->direccion_proy != '' && $cabecera[0]->direccion_proy != '')
		</B>{{$cabecera[0]->direccion_proy}}, 
	@else
		</B>{{$cabecera[0]->calleFracc}}, 
	@endif
	
	{{$cabecera[0]->delegacion}}, 
	{{$cabecera[0]->ciudadFracc}}, {{$cabecera[0]->estadoFracc}}, misma que identifican ambas partes. 
</SPAN></FONT></FONT>
</P>
<br>
<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY> <FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>6</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>.
- </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">El
monto del presente contrato es por </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>
${{$cabecera[0]->totalImporteLetra}}</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
@if ($cabecera[0]->iva == 1)
Este monto incluye el impuesto al valor agregado del 16%.
@else
Trabajos que al estar destinados para casa habitación, de acuerdo a lo dispuesto en el articulo No. 29 del 
R.L. del I.V.A. en vigor, queda exento del Impuesto al Valor Agregado correspondiente. 
Precio que LA CONTRATANTE pagará a LA CONTRATISTA  por la ejecución de los trabajos descritos en la 
cláusula Quinta de este contrato.
@endif</SPAN></FONT></FONT></P>

<br>
<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>7.
- </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATISTA </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">se</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>
</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">compromete
a realizar los trabajos a partir del </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>{{ $cabecera[0]->f_ini}}</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
al </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>{{$cabecera[0]->f_fin}}.</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
</SPAN></FONT></FONT>
</P>
<br>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>8.
- </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATISTA </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">se
responsabiliza de la calidad de los trabajos objeto de este contrato
y se obliga a responder en caso de fallas en estas, debidas a la
falta de cuidado, conocimiento o pericia, o que por negligencia de su
parte se ocasionaran daños en las construcciones, comprometiéndose
en tal caso a la reparación de los daños y al pago inmediato de los
perjuicios ocasionados.</SPAN></FONT></FONT></P>

<br>
<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>9.
- LA CONTRATISTA</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>&nbsp;</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">&nbsp;entregará
a la persona asignada por </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATANTE,</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
el trabajo señalado en este contrato, completamente terminado, le
recibirá por escrito, debiendo firmar de recibido de conformidad.</SPAN></FONT></FONT></P>
<br>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>10.</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">-
</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATANTE</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
se compromete a liquidar de la siguiente forma:</SPAN></FONT></FONT></P>

	

	<FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	<LI style="list-style-type: none; margin-left: 0.2in; text-indent: -0.2in"><P LANG="en-GB" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">a) Mediante
		un anticipo correspondiente al  </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>{{$cabecera[0]->anticipo}}% </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
		del monto del contrato al iniciar las obras. El total del pago es </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>
		</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>${{$cabecera[0]->anticipoLetra}}.</B></SPAN></FONT></FONT></P>

</SPAN></FONT></FONT></FONT></P>
	
	<FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	<LI style="list-style-type: none; margin-left: 0.15in; text-indent: -0.25in""><P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	b) LA CONTRATANTE realizara pagos a LA CONTRATISTA por concepto de estimación, dicho importe será 
		calculado en base al avance de obra, que determine la persona asignada por LA CONTRATANTE. 
		De los pagos por estimación se retendrá el {{$cabecera[0]->anticipo}}% de dicho importe para amortizar el 
		anticipo del contrato y adicionalmente LA CONTRATISTA autoriza que se le retenga el 3% de 
		cada estimación por concepto de retención de fondo de garantía, para cubrir gastos por vicios 
		ocultos al culminar las obras.</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B></B></SPAN></FONT></FONT></P>

</SPAN></FONT></FONT></FONT></P>
<div style="page-break-after:always;"></div>
	<LI style="list-style-type: none; margin-left: 0.1in; text-indent: -0.2in"><P LANG="en-GB" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
		c) LA CONTRATANTE realizara pagos a LA CONTRATISTA por concepto de devolución de fondo de garantía, 
		cuando la persona asignada por LA CONTRATANTE autorice. En caso de existir un vicio oculto en la obra y 
		que LA CONTRATISTA haga caso omiso para su reparación. LA CONTRATANTE reparará los daños generados en 
		las obras por medio de otra empresa y el importe generado por dicha reparación será con cargo al fondo 
		de garantía retenido a LA CONTRATISTA. Para realizar lo anterior no habrá necesidad de algún documento 
		que autorice los trabajos por parte de LA CONTRATISTA a LA CONTRATANTE.</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B></B></SPAN></FONT></FONT></P></LI></LI></LI>

</SPAN></FONT></FONT></FONT></P>

<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY><BR>
</P>


<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>11.-
</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">Ambas
partes acuerdan que en caso de que </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATISTA</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
no concluya en tiempo y en las condiciones establecidas, los trabajos
señalados en el presente contrato y por lo tanto incumpla el
programa de ejecución de obra, se hará acreedor a una sanción por
cada día de retraso 
@if($cabecera[0]->tipo == 'Vivienda')
en la terminación de las casas 0.08 del valor del contrato de la casa que no se entregue en tiempo.
@elseif($cabecera[0]->tipo == 'Urbanización') 
, 0.02 el valor del contrato.
@elseif($cabecera[0]->tipo == 'Casa Club') 
en la terminación de La casa club 0.02 el valor del contrato.
@elseif($cabecera[0]->tipo == 'Caseta') 
en la terminación de La casa caseta 0.08 el valor del contrato.
@elseif($cabecera[0]->tipo == 'Locales') 
en la terminación de Los locales 0.02 el valor del contrato.
@endif
</SPAN></FONT></FONT></P>
<br>
<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">La
sanción especificada iniciará a partir del séptimo día hábil
siguiente a la fecha de terminación de la obra, establecida en el
Programa de Obra.   </SPAN></FONT></FONT>
</P>

<br>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>12.-
LA</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>CONTRATISTA
</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">se
obliga a expedir a favor de </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATANTE</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
los siguientes documentos:</SPAN></FONT></FONT></P>

	<LI style="list-style-type: none; margin-left: 0.2in; text-indent: -0.2in"><P LANG="en-GB" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	a) Un pagare por el monto del anticipo para garantizar el buen uso y
	aplicación del mismo.</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B></B></SPAN></FONT></FONT></P></LI>

</SPAN></FONT></FONT></FONT></P>

	<LI style="list-style-type: none; margin-left: 0.15in; text-indent: -0.2in"><P LANG="en-GB" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	b) Un pagare por el <B>10%</B> del monto total de los trabajos contratados para garantizar el
	cumplimiento en su totalidad de las obligaciones a cargo de <B>“LA CONTRATISTA”</B> así como, para responder por los defectos y 
	vicios ocultos en las obras hasta por un periodo de dos años o de cualquier otra
	obligación contraída por virtud del contrato celebrado o que
	derive del mismo.
	
	</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
	</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B></B></SPAN></FONT></FONT></P></LI>

</SPAN></FONT></FONT></FONT></P>
<br>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">Estos
documentos serán aplicables a partir del día hábil siguiente en el
que sean exigibles cualesquiera de las obligaciones a cargo de </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>“LA
CONTRATISTA”</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
 para cuyo cumplimiento fueron constituidas dichas garantías.</SPAN></FONT></FONT></P>

<br>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">Adicionalmente
</SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>“LA
CONTRATISTA”</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
se obliga a que todos los materiales y equipo que se utilicen en los
trabajos de la obra motivo de este contrato,  cumplan con las normas
de calidad establecidas por </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>“LA
CONTRATANTE”,</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
así como a responder por su cuenta y riesgo de los defectos y vicios
ocultos de los mismos y de los daños y perjuicios que por
inobservancia o negligencia de su parte lleguen a causarse a </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>“LA
CONTRATANTE”</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
o a terceros, en cuyo caso se hará efectiva la garantía otorgada
para el cumplimiento del contrato, hasta por el monto total de la
misma.</SPAN></FONT></FONT></P>

<br>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>13.
- LA CONTRATISTA</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
deberá entregar la factura correspondiente a </SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>LA
CONTRATANTE</B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">
para su revisión y pago.</SPAN></FONT></FONT></P>

<br>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>14.
- </B></SPAN></FONT></FONT><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">Todos
los pagos se efectuarán en Manuel Gutiérrez Nájera # 
@if($cabecera[0]->emp_constructora == 'Grupo Constructor Cumbres')
	190
@else
	180
@endif

Col.
Tequisquiapan, en San Luis Potosí, S.L.P. </SPAN></FONT></FONT>
</P>
<br>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES">Para
cualquier controversia que se suscite con motivo del presente
contrato los contratantes se someten a la Jurisdicción de los
Tribunales de San Luis Potosí, S.L.P.</SPAN></FONT></FONT></P>
<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY><BR>
</P>

<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY><FONT FACE="Arial, serif"><FONT SIZE=3><SPAN LANG="es-ES"><B>Se
firma el presente contrato a los {{$cabecera[0]->f_ini2}}.</B></SPAN></FONT></FONT></P>
<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY><BR>
</P>
<P LANG="es-ES" CLASS="western" ALIGN=JUSTIFY><BR>
</P>
<br>


<div class="table">
<div class="table-row">
      <div class="table-cell"><b>_____________________________</div>
      <div class="table-cell"><b>_____________________________</div>
    </div>
    <div class="table-row">
      <div class="table-cell"><b>CONTRATANTE</div>
      <div class="table-cell"><b>CONTRATISTA</div>
    </div>
    <div class="table-row">
      <div class="table-cell" > <b>APDO. {{$cabecera[0]->apoderado}}</div>
      <div class="table-cell" > <b>REP.{{mb_strtoupper($cabecera[0]->representante)}}</div>
	</div>
	@if($cabecera[0]->emp_constructora == 'Grupo Constructor Cumbres')
    <div class="table-row">
        <div class="table-cell" > <b>GRUPO CONSTRUCTOR CUMBRES, SA DE CV</div>
        <div class="table-cell" > <b>{{mb_strtoupper($cabecera[0]->contratista)}}</div>
	</div>
	<div class="table-row">
        <div class="table-cell" > <b>Manuel Gutiérrez Nájera # 190 Col. Tequisquiapan</div>
        <div class="table-cell" > <b>{{$cabecera[0]->direccion}}</div>
    </div>
	@endif
	@if($cabecera[0]->emp_constructora == 'CONCRETANIA')
    <div class="table-row">
        <div class="table-cell" > <b>CONCRETANIA, SA DE CV</div>
        <div class="table-cell" > <b>{{mb_strtoupper($cabecera[0]->contratista)}}</div>
	</div>
	<div class="table-row">
        <div class="table-cell" > <b>Manuel Gutiérrez Nájera # 180 Col. Tequisquiapan</div>
        <div class="table-cell" > <b>{{$cabecera[0]->direccion}}</div>
    </div>
	@endif
    
    <div class="table-row">
        <div class="table-cell" > <b>San Luis Potosí, S.L.P.</div>
        <div class="table-cell" > <b>{{$cabecera[0]->colonia}}, C.P. {{$cabecera[0]->codigoPostal}}</div>
    </div>
    <div class="table-row">
        <div class="table-cell" > <b>Tel. 01 (444) 8 33 46 83 </div>
        <div class="table-cell" > <b>{{$cabecera[0]->estado}}</div>
    </div>
    <div class="table-row">
        <div class="table-cell" > <b>Tel. 01 (444) 8 33 46 84</div>
        <div class="table-cell" > <b>Tel. {{$cabecera[0]->telefono}}</div>
    </div>
  </div>




<P LANG="en-GB" CLASS="western" ALIGN=JUSTIFY STYLE="margin-left: 3.5in; text-indent: -3.5in">
<SPAN LANG="es-ES"><B>						 </B></SPAN>
</P>

<DIV id="footer">
	<IMG SRC="img/contratos/CONTRATOS_html_5d82e5a4.png">
	@if($cabecera[0]->emp_constructora == 'Grupo Constructor Cumbres')
	<P LANG="en-GB" ALIGN=CENTER><FONT SIZE=1 STYLE="font-size: 8pt; text-align:center;"><SPAN LANG="es-ES">MANUEL
	GUTIERREZ NAJERA #190 COL TEQUIS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TEL Y FAX 01 (4) 833-46-83-85 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; San Luis Potosí, S.L.P.</SPAN></FONT>
	</P>
	@endif
	@if($cabecera[0]->emp_constructora == 'CONCRETANIA')
	<P LANG="en-GB" ALIGN=CENTER><FONT SIZE=1 STYLE="font-size: 8pt; text-align:center;"><SPAN LANG="es-ES">MANUEL
	GUTIERREZ NAJERA #180 COL TEQUIS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TEL Y FAX 01 (4) 833-46-83-85 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; San Luis Potosí, S.L.P.</SPAN></FONT>
	</P>
	@endif
</div>

</BODY>

</HTML>