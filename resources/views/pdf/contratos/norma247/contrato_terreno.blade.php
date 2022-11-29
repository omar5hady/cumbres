<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTRATO DE PROMESA DE COMPRAVENTA</title>
</head>

<style>
    body {
        font-size: 9.2pt;
        text-align: justify;
        font-family: Verdana, Tahoma, sans-serif;
    }

    @page {
        margin: 45px;
        margin-bottom: 70px;
        margin-right: 75px;
        margin-left: 75px;
    }

    .table-cell {
        display: table-cell;
        padding: 0em;
        font-size: 11pt;
    }

    .table {
        display: table;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    .small{
        font-size: 9pt;
    }

    .table2 { display: table; width:100%;  border: ridge #0B173B 1px; color:black;}
    .table-cell2 {
        display: table-cell; padding: 0.1em; font-size: 9.2pt;
        border: ridge; border-width:0.5px; #0B173B 1px; color:black;
    }

    .table-row {
        display: table-row;
    }
    .cuadrado{
        position: absolute;
        margin-left: -40px;
        margin-right: 15px;
    }
    .ul-custom{
        list-style-type:none;
    }

</style>

<body>
    <div>

        <p>
            CONTRATO DE COMPRAVENTA DE TERRENO DESTINADO A USO HABITACIONAL AL QUE EN LO SUCESIVO SE LE DENOMINARÁ,
            EL <strong>“CONTRATO DE COMPRAVENTA”,</strong> QUE CELEBRAN POR UNA PARTE
            @if($contrato->emp_terreno == 'Grupo Constructor Cumbres')
                <strong>GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V.</strong>, REPRESENTADA EN ESTE ACTO POR LA SRA. MAYRA JAZMIN SALAZAR ALONSO
            @else
                <strong>CONCRETANIA, S. A. DE. C.V</strong>, REPRESENTADA EN ESTE ACTO POR LA SRA. ELIZABETH HERNANDEZ LOERA
            @endif
            A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, SE LE DENOMINARÁ COMO <strong>EL PROMITENTE VENDEDOR</strong>
            Y POR LA OTRA PARTE, POR SU PROPIO DERECHO
            <strong>EL(A) SR(A).
                {{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}
                @if ($contrato->coacreditado == 1)
                    y {{ mb_strtoupper($contrato->nombre_coa) }}
                    {{ mb_strtoupper($contrato->apellidos_coa) }}
                @endif
            </strong>
            A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE LE DENOMINARÁ COMO <strong>EL PROMITENTE COMPRADOR</strong>,
            AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.
        </p>

        <h4 align="center">DECLARACIONES</h4>

        <p>I.- Declara <strong>EL PROMITENTE VENDEDOR</strong>, por conducto de su representante:</p>

        @if($contrato->emp_terreno == 'Grupo Constructor Cumbres')
            <p>
                a)	Que es una sociedad mercantil constituida en escritura pública número 3 de fecha 8 de diciembre de 1999,
                otorgada ante el Notario Público número 33 del primer distrito judicial del estado de San Luis Potosí,
                Lic. Leopoldo de la Garza Marroquín, inscrita en el Registro    Público de la Propiedad y del
                Comercio en San Luis Potosí, San Luis Potosí,  bajo el folio mercantil 70 que su domicilio fiscal se
                encuentra en Manuel Gutiérrez Nájera 190. Colonia Tequisquiapan, San Luis Potosí, San Luis Potosí y su
                Registro Federal de Contribuyentes es <strong>GCC000106QS6</strong>
            </p>

            <p>
                b)	Que las facultades de su representante constan en la escritura pública número 1560
                    de fecha 07 de noviembre del 2022, otorgada ante el Notario Público número 19 del primer distrito judicial de
                    San Luis Potosí, Lic. Alfredo Noyola Robles, inscrita en el Registro Público de la Propiedad y del
                    Comercio en San Luis Potosí, bajo el folio mercantil 123547, mismas atribuciones que no le han sido revocadas a la fecha.
            </p>
        @else

            <p>
                a)	Que es una sociedad mercantil debidamente constituida con arreglo a las leyes de la República Mexicana según consta en la
                escritura pública número 764 volumen 21 de fecha 25 de julio de 2018, otorgada ante la fe del Lic. Octaviano Gómez y González,
                Notario Público número 4, con ejercicio en la Ciudad  de San Luis Potosí, S.L.P, cuyo testimonio obra inscrito en el Registro
                Público de la Propiedad y Comercio de esa misma Ciudad, bajo el folio mercantil electrónico N-2018073682, que su domicilio
                fiscal es Manuel Gutiérrez Nájera #180,  Col Tequisquiapan,  en la Ciudad de San Luis Potosí y su registro federal de causantes
                es <strong>CON180725REA.</strong>
            </p>

            <p>
                b)	Que  las facultades de su representante constan en la escritura pública número 1560 de fecha 07 de noviembre del 2022,
                otorgada ante el Notario Público número 19 del primer distrito judicial de la ciudad de San Luis Potosí, Lic. Alfredo Noyola Robles
                inscrita en el Registro Público de la Propiedad y del Comercio en San Luis Potosí, S.L.P, bajo el folio mercantil
                123507, mismas atribuciones que no le han sido revocadas a la fecha.
            </p>

        @endif


        <p>
            c)	Que su objeto social es, la ejecución, administración, construcción, promoción, comercialización y arrendamiento
            de desarrollos inmobiliarios, comerciales y habitacionales dentro del territorio nacional y en el extranjero, así como la celebración de los
            contratos mercantiles necesarios para el cumplimiento de  su objeto.
        </p>

        <p>
            d)	Que es única y legitima propietaria del inmueble materia de este contrato, descrito a detalle en el
            <strong>“Anexo A”,</strong> que firmado por las partes, es integrante de este instrumento y se localiza en la siguiente ubicación;
        </p>

        <p>
            Calle:&nbsp; <u>{{mb_strtoupper($contrato->calle_lote)}}</u> <br>
            Lote o área privativa:&nbsp; <u>{{$contrato->num_lote}}</u>  <br>
            Superficie del terreno:&nbsp; <u>{{$contrato->sup_terreno}}m&sup2;</u>  <br>
            Manzana o condominio:&nbsp; <u>{{mb_strtoupper($contrato->manzana)}}</u>  <br>
            No oficial exterior:&nbsp; <u>{{$contrato->num_oficial}}</u>  <br>
            @if($contrato->interior != NULL)
                No oficial interior:&nbsp; <u>{{$contrato->interior}}</u>  <br>
            @endif
            Fraccionamiento:&nbsp; <u>{{mb_strtoupper($contrato->proyecto)}}</u> <br>
        </p>

        <p>
            Y acredita la propiedad con la escritura pública numero {{ $contrato->num_escritura }} de fecha {{$contrato->date_escritura}}
            otorgada ante la fe del Notario publico número {{$contrato->num_notario}} del distrito de {{$contrato->distrito_notario}},
            con inscripción en el Registro Publico de la Propiedad bajo el folio real No.{{$contrato->folio_registro}},
            lo cual ha sido debidamente exhibido y explicado a <strong>EL PROMITENTE COMPRADOR</strong> y además se encuentra a su
            disposición en el  domicilio mercantil ubicado en Manuel Gutiérrez Nájera #190 Col. Tequisquiapam, de la Ciudad de San Luis Potosí, S.L.P.
        </p>

        <p>
            e)	Que el TERRENO cuenta con la infraestructura para la adecuada conexión de los servicios de suministro de agua potable,
            drenaje y alcantarillado y demás obras de equipamiento urbano.
        </p>

        <p>
            f)	Que cuenta con el plano de medidas y colindancias, licencia de uso de suelo, autorizaciones y permisos respectivos emitidos por
            autoridad competente, mismas que han sido exhibidas a <strong>“EL COMPRADOR”</strong> y se encuentran a su disposición
            en el domicilio de <strong>“EL VENDEDOR”.</strong>
        </p>

        <p>
            g)	El TERRENO cuenta con autorización de uso de suelo destinado para uso habitacional, de conformidad con la
            licencia de uso de suelo expedida por	_________de fecha         de	de____.
        </p>

        <p>
            h)	El TERRENO objeto del contrato, no se encuentra sujeto algún régimen especial, se puede escriturar
            de inmediato y no está sujeto a régimen ejidal o comunal
        </p>

        <p>
            i)	En su caso, el TERRENO reporta ciertos gravámenes en virtud del crédito que obtuvo <strong>“EL VENDEDOR”</strong>
            como acreditada, gravámenes de los cuales quedará liberado el TERRENO a más tardar en la fecha de firma de la escritura
            pública de compraventa correspondiente.
        </p>

        <p>
            j)	Que, para el pago del precio de compraventa del TERRENO, aceptará montos derivados de créditos que
            <strong>“EL COMPRADOR”</strong> reciba de cualquier institución acreditante autorizada para dichos efectos.
        </p>

        <p>
            k)	Que puso a disposición de <strong>“EL COMPRADOR”</strong> la información y documentación
            relativa a el TERRENO, que se especifica en el “Anexo C” del presente contrato, el cual firmado por ambas partes
            forma parte integrante del mismo.
        </p>

        <p>
            l)	Informó a <strong>“EL COMPRADOR”</strong> el costo total del TERRENO, así como las restricciones que, en su caso,
            son aplicables a la comercialización del bien objeto de este Contrato.
        </p>

        <br>

        <p>
            <strong>II.- Declara “EL COMPRADOR”  que:</strong>
        </p>

        <p>
            a) Es una persona física de nacionalidad {{($contrato->nacionalidad) == 0 ? 'Mexicana': 'Extranjera'}}
            Que se identifica con cualquiera de los
            siguientes documentos, INE {{$contrato->num_ine}} o  Pasaporte No. {{($contrato->num_pasaporte)?$contrato->num_pasaporte : '_____'}},
            llamada como ha quedado escrito, haber nacido en {{$contrato->lugar_nacimiento}}, el {{$contrato->f_nacimiento}},
            estado civil {{$contrato->edo_civil}}, de ocupación {{ucwords($contrato->puesto)}}, con domicilio en
            {{ucwords($contrato->dir_cliente)}} Col. {{ucwords($contrato->col_cliente)}}, con Registro Federal de Contribuyentes {{$contrato->rfc}}-{{$contrato->homoclave}}
            y con capacidad legal y económica para celebrar este contrato.
        </p>

        <p>
            b)	Que contando con la información suficiente a que se refiere el articulo 73 bis de la Ley Federal de Protección al Consumidor,
            ha convenido con <strong>“EL VENDEDOR”</strong> en comprarle el TERRENO en los términos que establecen en el presente contrato.
        </p>

        <p>
            c)	Conoce el TERRENO y manifiesta que la información y documentación relativa a la misma que se le proporcionó,
            es la que se especifica en el “Anexo C” del presente contrato, el cual firmado por ambas partes forma parte integrante del mismo.
        </p>

        <p>
            <strong>III.- Declaran “LAS PARTES”  que:</strong>
        </p>

        <p>
            Única. Es su espontánea voluntad celebrar el presente Contrato al tenor de las siguientes:
        </p>

        <p align="center"><strong>C L Á U S U L A S</strong></p>

        <p>
            <strong>PRIMERA. OBJETO DE LA COMPRAVENTA.-</strong> En virtud de este acuerdo de voluntades,
            <strong>“EL VENDEDOR”</strong> vende a <strong>“EL COMPRADOR”,</strong> quien adquiere para sí, el TERRENO especificado en
            la declaración I inciso d) anterior. EL TERRENO tiene las mismas medidas y colindancias y la infraestructura para
            la adecuada conexión de los servicios de suministro de agua potable, drenaje y alcantarillado señalados en el
            <strong>“A”</strong> del presente contrato, el cual firmado por ambas partes forma parte integrante del mismo.
        </p>

        <p>
            En caso de que <strong>INMUEBLE</strong> cuente con servicios adicionales, los cuales son opcionales a solicitud de
            <strong>“EL COMPRADOR”,</strong> los mismos se especificarán en el <strong>“Anexo I”,</strong> de este contrato.
        </p>

        <p>
            <strong>SEGUNDA. PRECIO Y FORMA DE PAGO.- </strong>
            Las partes convienen que el precio total de esta compraventa será la cantidad de $ {{$contrato->precio_venta}},
            precio total que <strong>“EL COMPRADOR”</strong> se obliga a pagar a <strong>“EL VENDEDOR”</strong> de la siguiente forma:
        </p>

        <p>
            El precio por la compraventa es en Moneda Nacional, en caso de realizarse la Compraventa en moneda extranjera, se estará
            al tipo de cambio que rija en el lugar en que se realice el pago, de conformidad con la legislación aplicable.
        </p>

        <p>
            <ul class="ul-custom">
                <li><template class="cuadrado">a)</template>
                    La cantidad de ${{$contrato->pagos[0]->monto_pago}} a la firma del presente Contrato como enganche de la compraventa,
                    cantidad que “EL VENDEDOR” en este acto recibe a su entera satisfacción y se aplicará como parte del precio del TERRENO,
                    expidiendo “EL VENDEDOR” al COMPRADOR el recibo que ampare la cantidad pagada.
                    <br><br>
                </li>
                @if(count($contrato->pagos) > 2)
                    <li><template class="cuadrado">b)</template>
                        La cantidad de $ {{$contrato->restoPago}} en las siguientes fechas: <br>
                        @for ($i = 1; $i < (count($contrato->pagos)-1); $i++)
                            {{$contrato->pagos[$i]->fecha_pago}},
                        @endfor
                        y con los siguientes importes respectivamente; <br>
                        @for ($i = 1; $i < (count($contrato->pagos)-1); $i++)
                           $ {{$contrato->pagos[$i]->monto_pago}},
                        @endfor
                        <br><br>
                    </li>
                    <li><template class="cuadrado">c)</template>
                        En la fecha de firma de la escritura pública de compraventa, la cantidad de $ {{$contrato->finPago}}.
                        <br>
                    </li>
                @else
                    <li><template class="cuadrado">b)</template>
                        En la fecha de firma de la escritura pública de compraventa, la cantidad de $ {{$contrato->finPago}}.
                        <br>
                    </li>
                @endif

            </ul>
        </p>

        <p>
            El precio de compraventa es en Moneda Nacional, en caso de expresarse en moneda extranjera, se estará al tipo de cambio
            que rija en el lugar y fecha que se realice el pago, de conformidad con la legislación aplicable.
        </p>

        <p>
            Los conceptos de pago a cargo de <strong>“EL COMPRADOR”,</strong> deben ser cubiertos a través del sistema financiero nacional,
            mediante depósitos o transferencias a
            @if($contrato->emp_terreno == 'Grupo Constructor Cumbres')
                la cuenta No. 0107059795 que EL VENDEDOR tiene con la institución bancaria BBVA
            @else
                la cuenta No. 0107059795 que EL VENDEDOR tiene con la institución bancaria BBVA
            @endif

        </p>

        <p>
            En caso de que <strong>“EL COMPRADOR”</strong> pague a través de un crédito,
            pagará a “EL VENDEDOR” a través del: _______________________ .
        </p>

        <p>
            Por acuerdo expreso de las partes, la falta de pago de alguna de las cantidades pactadas en éste Cláusula,
            imputable a cualquier institución acreditante, será causa de rescisión del presente Contrato sin necesidad de
            resolución judicial y sin responsabilidad alguna para cualquiera de las partes, restituyéndose las partes las
            prestaciones que se hubieren hecho conforme al presente Contrato y volviendo las cosas al estado que tenían
            antes de la celebración del mismo. Las cantidades que resultaran a favor de <strong>“EL COMPRADOR”,</strong> deberán ser
            devueltas por <strong>“EL VENDEDOR”</strong> dentro de los	días siguientes a la fecha de rescisión del presente Contrato de
            Compraventa (no podrán ser más de 15 días hábiles). En caso de que dichas cantidades no fueren restituidas dentro
            del plazo establecido, <strong>“EL VENDEDOR”</strong> deberá pagar a <strong>“EL COMPRADOR”</strong> un interés equivalente al	% (	por ciento)
            por cada día transcurrido de retraso en dicha restitución. Para los efectos de la presente cláusula deberán
            entenderse como “Gastos Operativos” aquellas erogaciones distintas del precio de la venta que deba realizar
            <strong>“EL COMPRADOR”,</strong> tales como gastos de escrituración, impuestos, avalúo, administración, apertura de crédito y
            gastos de investigación, según lo establecido en el “Anexo E” del presente contrato, el cual firmado por ambas
            partes forma parte integrante del mismo.
        </p>

        <p>
            Cuando aplique la devolución de la cantidad pagada, ésta se efectuará utilizando la misma forma de pago con la que se realizó
            la compra, pudiendo hacerse por una forma de pago distinta si <strong>“EL COMPRADOR”</strong> lo acepta al momento en que se efectúe la devolución.
        </p>

        <p>
            El importe señalado en esta cláusula contempla todas las cantidades y conceptos referentes al objeto del presente
            Contrato; por lo que <strong>“EL VENDEDOR”</strong> se obliga a respetar en todo momento dicho costo sin poder cobrar
            otra cantidad no estipulada en el presente Contrato, salvo que <strong>“EL COMPRADOR”</strong> autorice de manera escritura
            algún otro cobro no estipulado en el presente Contrato.
        </p>

        <p>
            <strong>TERCERA. INFORMACIÓN PARA GESTIONAR CRÉDITO. “EL VENDEDOR”</strong>
            en este acto se obliga a entregar a <strong>“EL COMPRADOR”</strong> toda la información del TERRENO que se requiera con el fin
            de que <strong>“EL COMPRADOR”</strong> cumpla con los requisitos que cualquier institución acreditante establezca
            para el otorgamiento del crédito, en su caso.
        </p>

        <p>
            <strong>
                CUARTA. CANCELACIÓN ANTICIPADA. “EL COMPRADOR”
            </strong>
            cuenta con un plazo de 5 (cinco) días hábiles a partir de la firma del presente instrumento para cancelar el presente contrato,
            sin responsabilidad alguna, sin menoscabo de los pagos realizados, así como la obligación del
            <strong>“EL VENDEDOR”</strong> de devolver las cantidades que el <strong>“EL COMPRADOR”</strong> le haya entregado,
            en su caso, deduciendo de las mismas el monto de los gastos operativos debidamente comprobables en que haya incurrido
            <strong>“EL VENDEDOR”</strong> en ese lapso. Dicha devolución debe darse en un plazo de 5 a 15 días hábiles siguientes a la
            fecha en que le sea notificada al <strong>“EL VENDEDOR”</strong> por escrito. En caso de que dichas cantidades no fueren
            restituidas dentro del plazo establecido, <strong>“EL VENDEDOR”</strong> deberá pagar a <strong>“EL COMPRADOR”</strong> un interés
            equivalente al   % (     por ciento) mensual por cada día transcurrido de retraso en dicha restitución.
        </p>

        <p>
            La cancelación de que trata la presente cláusula podrá realizarla <strong>“EL COMPRADOR”</strong> mediante simple aviso por escrito
            entregado a <strong>“EL VENDEDOR”</strong> en los términos previstos en la cláusula Décima Segunda de este contrato _____ o mediante ___.
        </p>

        <p>
            <strong>QUINTA. FIRMA DE ESCRITURA PÚBLICA.- </strong>
            Las partes acuerdan que dentro de los _____	días hábiles siguientes a la fecha de firma del presente Contrato de Compraventa,
            concurrirán ante el notario público que en su momento sea designado por <strong>“EL COMPRADOR”,</strong> en su caso con el fin de otorgar
            y formalizar la escritura pública de compraventa, acto en el cual <strong>“EL VENDEDOR”</strong> entregará a <strong>“EL COMPRADOR”</strong>
            todos aquellos documentos relativos al TERRENO que sea requerido a entregar de conformidad con la legislación
            aplicable. Las partes acuerdan que el costo del avalúo inmobiliario (comercial o fiscal), así como los honorarios,
            impuestos y derechos, que se causen con motivo de dicho acto correrán a cargo de <strong>“EL COMPRADOR”,</strong> con excepción
            del impuesto sobre la renta que por ley corresponde pagar a <strong>“EL VENDEDOR”,</strong> quien a partir de dicha formalización
            se obliga ante <strong>“EL COMPRADOR”</strong> a responder por el saneamiento para el caso de evicción.
        </p>

        <p>
            <strong>SEXTA.- OBLIGACIONES VARIAS DE “EL VENDEDOR”. </strong>
            Para el cumplimiento del presente Contrato, “EL VENDEDOR” se obliga a que:
        </p>

        <p>
            <li style="margin-left:40px;" class="ul-custom">
                <strong>Derechos:</strong>
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                a)	&nbsp;El inmueble objeto del presente Contrato tenga las características técnicas de seguridad y de la estructura,
                así como los materiales, instalaciones y acabados especificadas en el citado <strong>“Anexo A”.</strong>
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                b)	&nbsp;El Inmueble se encuentre al corriente en el pago de todos sus impuestos, derechos, cuotas de mantenimiento y
                demás, así como libre de gravámenes y adeudos de cualquier naturaleza.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                c)	&nbsp;Entregar a <strong>“EL COMPRADOR”</strong> el inmueble en los términos establecidos en la Cláusula Octava del presente Contrato.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                d)	&nbsp;Poner a disposición de <strong>“EL COMPRADOR”</strong> la información y documentación relativa al Inmueble.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                e)	&nbsp;Informar a <strong>“EL COMPRADOR”</strong> si el Inmueble materia del presente Contrato se encuentra
                sujeto a un régimen de propiedad específico.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                f)	&nbsp;Hacer del conocimiento a <strong>“EL COMPRADOR”,</strong> cuando ya existan, las cuotas de mantenimiento fijadas para
                la conservación y funcionamiento del Conjunto Habitacional al que pertenece el inmueble materia del presente Contrato.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                g)	&nbsp;Así como cumplir con todo aquello a que se encuentre obligado en los términos del presente Contrato.
            </li><br>
        </p>


        <p>
            <strong>SÉPTIMA.- OBLIGACIONES VARIAS DE “EL COMPRADOR”.</strong>
            Para el cumplimiento del presente Contrato, <strong>“EL COMPRADOR”</strong> se obliga, a:
        </p>

        <p>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                a)	&nbsp;Recibir de <strong>“EL VENDEDOR”</strong> el inmueble en los términos establecidos en la Cláusula
                OCTAVA del presente Contrato.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                b)	&nbsp;Llevar a cabo el pago total de la operación convenida en los términos establecidos en la Cláusula Segunda del presente Contrato.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                c)	&nbsp;Revisar la información y documentación que le ponga a su disposición <strong>“EL VENDEDOR”,</strong> relativa al Inmueble.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                d)	&nbsp;Preservar el entorno urbanístico y arquitectónico del Conjunto Habitacional donde se encuentra el Inmueble
                objeto de este contrato, para lo cual (i) se abstendrá de construir o edificar obra alguna distinta de la que autorice
                la licencia de construcción otorgada para dicho Conjunto Habitacional, respetando el uso del Inmueble, así como (ii)
                respetar y conservar el diseño y plan maestro del Conjunto Habitacional
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                e)	&nbsp;Observar por sí, por sus familiares y visitantes el Reglamento del Régimen de Propiedad en
                Condominio a que está sujeto el Inmueble materia de esta operación.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                f)	&nbsp;Cubrir las cuotas de mantenimiento que se fijen para la conservación y funcionamiento del Conjunto
                Habitacional al que pertenece el terreno materia del presente contrato.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                g)	&nbsp;Así como cumplir con todo aquello a que se encuentre obligado en los términos del presente Contrato.
            </li><br>
        </p>

        <p>
            <strong>OCTAVA. ENTREGA DE POSESIÓN Y RECEPCIÓN DEL TERRENO.- “EL VENDEDOR” </strong>
            se obliga a entregar a <strong>“EL COMPRADOR”,</strong> sus causahabientes o representantes legales, la propiedad y posesión material
            del TERRENO libre de todo gravamen y limitación de dominio, a más tardar el día	de        de__        , salvo que
            acredite fehacientemente que por caso fortuito o fuerza mayor que la afectó directamente o al TERRENO no pudiera
            entregar el TERRENO en dicha fecha, caso en el cual las partes deberán pactar una nueva fecha de entrega.
            Del mismo modo, las partes podrán de común acuerdo pactar una nueva fecha de entrega del TERRENO por así convenir
            a sus intereses, misma que no será considerada como incumplimiento para efectos de la presente cláusula. En caso de una
            nueva inasistencia se levantará acta circunstanciada ante dos testigos que haga constar tal situación y se entenderá que
            recibió el inmueble a su entera satisfacción para todos los efectos a que haya lugar.
        </p>

        <p>
            En caso de que <strong>“EL VENDEDOR”</strong> no acredite plenamente las causales de fuerza mayor o caso fortuito,
            en los términos señalados en el párrafo que antecede de esta cláusula, se considerará como incumplimiento de contrato y,
            por lo tanto, se hará acreedora a la pena convencional que se establece en la cláusula Novena del presente contrato.
        </p>

        <p>
            El momento de la entrega del TERRENO, <strong>“EL VENDEDOR”,</strong> conjuntamente con <strong>“EL COMPRADOR”</strong> realizarán
            una revisión ocular de las condiciones e infraestructura pactadas por las partes en el “Anexo A” del presente contrato. En caso de
            que <strong>“EL COMPRADOR”</strong> esté de acuerdo con dichas características, las partes firmarán un acta entrega y
            recepción del TERRENO, de acuerdo al “Anexo B” del presente contrato.
        </p>

        <p>
            <strong>NOVENA DESTINO Y MODIFICACIÓN DEL INMUEBLE.</strong> A fin de preservar el entorno urbanístico y arquitectónico del
            Fraccionamiento donde se encuentra el TERRENO, en su caso, <strong>“EL COMPRADOR”</strong> se obliga a obtener de las autoridades
            correspondientes, las autorizaciones necesarias a efecto de realizar cualquier construcción sobre el TERRENO. Asimismo
            <strong>“EL COMPRADOR”</strong> se obliga a respetar el uso habitacional del TERRENO, <strong>“EL COMPRADOR”</strong> asimismo,
            está obligado a respetar el reglamento de construcción de dicho Fraccionamiento el cual se encuentra a su disposición en el
            domicilio de <strong>“EL VENDEDOR”.</strong>
        </p>

        <p>
            <strong>DÉCIMA.- RESTRICCIONES OFICIALES APLICABLES A LA CONSTRUCCIÓN DEL TERRENO.</strong> En su caso, el TERRENO objeto del
            presente contrato está sujeto a las siguientes restricciones oficiales aplicables a la construcción, tales como:
        </p>

        <p>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                -	&nbsp;Restricciones Ambientales______________________.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                -	&nbsp;Colindancias con zonas ecológicas, reservas forestales y reservas federales ______________________.
            </li><br>
            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                -	&nbsp;Cualquier otra limitación decretada por autoridades competentes y/o previstas en la legislación aplicable_____________________.
            </li><br>
        </p>

        <p>
            <strong>DÉCIMA PRIMERA. RESCISIÓN DEL CONTRATO.</strong> En caso de incumplimiento a las obligaciones contraídas por el presente
            contrato por cualquiera de las partes y toda vez que el mismo no haya sido subsanado dentro del término de 30 días posteriores a la
            notificación que realice la contraparte, el presente contrato podrá darse por rescindido y podrá hacerle exigible la pena convencional
            a que hace referencia el presente contrato.
        </p>

        <p>
            <strong>DÉCIMA SEGUNDA. PENA CONVENCIONAL.-</strong> En caso de incumplimiento, la parte afectada podrá declarar la rescisión
            del presente contrato sin necesidad de resolución judicial previa alguna, bastando para ello el simple aviso que se le entregue
            a la otra parte. En el caso de rescisión, la parte que incumplió deberá pagar a la otra parte una pena convencional equivalente
            a______ % del precio pactado.
        </p>

        <p>
            Si el incumplimiento es imputable a <strong>“EL COMPRADOR”,</strong> el pago de la penalización se realizará con cargo a las
            cantidades pagadas por concepto de precio, por lo que <strong>“EL VENDEDOR”</strong> estará facultada para descontar el importe
            respectivo, debiendo devolver el remanente a este último, dentro de los días ____ hábiles siguientes a la rescisión del presente contrato.
        </p>

        <p>
            Si el incumplimiento es imputable a <strong>“EL VENDEDOR”,</strong> el pago de la pena convencional deberá efectuarlo
            conjuntamente con la devolución del importe pagado por concepto de precio, dentro de los días _______ hábiles siguientes a la
            rescisión del presente contrato.
        </p>

        <p>
            <strong>DÉCIMA TERCERA.- FALLECIMIENTO DE “EL COMPRADOR”.</strong> En caso de fallecimiento de <strong>“EL COMPRADOR”, </strong>
            el presente contrato subsistirá en todos sus efectos y alcances, transmitiéndose los derechos y obligaciones de
            <strong>“EL COMPRADOR”</strong> a sus sucesores, en los términos que prevea la Legislación Civil del Estado de San Luis Potosí. Salvo
            que manifiesten a <strong>“EL VENDEDOR”</strong> no desear continuar con la compraventa, en tal caso, se procederá en los términos que
            prevea la Legislación vigente del Estado de San Luis Potosí y en su caso, devolución de los pagos previamente realizados
            conforme a lo dispuesto en la Cláusula Décima Segunda.
        </p>

        <p>
            <strong>DÉCIMA CUARTA.- AVISO DE PRIVACIDAD.</strong> Para efectos de lo dispuesto en la Ley Federal de Protección de Datos Personales
            en Posesión de los Particulares y demás legislación aplicable, <strong>“EL COMPRADOR”</strong> manifiesta que el Aviso de Privacidad
            de <strong>“EL VENDEDOR”</strong> le fue dado a conocer por su representante, mismo que manifiesta haber leído, entendido y acordado los
            términos expuestos en el mismo, por lo que otorga su consentimiento respecto del tratamiento de sus datos personales.
            En el caso de que los datos personales recopilados incluyan datos patrimoniales o financieros, mediante la firma del
            contrato correspondiente, sea en formato impreso, o utilizando medios electrónicos y sus correspondientes procesos para la
            formación del consentimiento, se llevarán a cabo actos que constituyen el consentimiento expreso del titular y que otorga su
            consentimiento para que <strong>“EL VENDEDOR”</strong> o sus Encargados realicen transferencias y/o remisiones de datos personales
            en términos del propio Aviso de Privacidad.
        </p>

        <p>
            <strong>“EL CONSUMIDOR”</strong> si ( ) no ( ) acepta que <strong>“EL VENDEDOR”</strong> ceda o transmita a terceros, con fines mercadotécnicos
            o publicitarios, la información proporcionada por él con motivo del presente Contrato y si ( ) no ( ) acepta que
            <strong>“EL VENDEDOR”</strong> le envíe publicidad sobre bienes y servicios.
        </p>

        <p>
            <div class="table">
                <div class="table-row">
                    <div colspan="2" class="table-cell"></div>
                    <div colspan="3" class="table-cell"><br><br>_____________________________________________</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell"></div>
                    <div colspan="3" class="table-cell">
                        <center>{{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}<br>
                            <strong>“EL CONSUMIDOR”</strong></center></div>
                </div>
            </div>
        </p>



        <p>
            <strong>DÉCIMA QUINTA. DOMICILIOS PARA NOTIFICACIONES.-</strong> Las notificaciones y avisos que <strong>“LAS PARTES”</strong> deban
            darse con relación a este contrato, se harán por escrito y deberán ser enviadas.
        </p>

        <p>
            <strong>“EL VENDEDOR”:</strong> -:<br>
            <div style="margin-left: 30px;">
                @if($contrato->emp_terreno == 'Grupo Constructor Cumbres')
                    Calle: Manuel Gutiérrez Nájera #190 Col. Tequisquiapam <br>
                @else
                    Calle: Manuel Gutiérrez Nájera #180 Col. Tequisquiapam <br>
                @endif
                C.P. 78230, San Luis Potosí, S.L.P. <br>
                No telefónico 444 8334683 <br>
                Correo electrónico atencion@grupocumbres.com <br>
            </div>
        </p><br>

        <p><strong>“EL COMPRADOR”:</strong> -:<br>
            <div style="margin-left: 30px;">
                Calle: {{ucwords($contrato->dir_cliente)}} Col. {{ucwords($contrato->col_cliente)}}<br>
                C.P. {{$contrato->cp_cliente}}, {{ucwords($contrato->ciudad_cliente)}}, {{ucwords($contrato->edo_cliente)}} <br>
                No telefónico {{$contrato->tel_cliente}} &nbsp;No Celular +{{$contrato->clv_lada}} {{$contrato->cel_cliente}} <br>
                Correo electrónico {{$contrato->cliente_email}} <br>
            </div>
        </p>

        <p>
            Todo aviso, comunicación o notificación realizada en cualquiera de los medios antes señalados, se tendrá por
            totalmente válida hasta en tanto las partes no hagan una nueva designación de algún otro medio o domicilio para tales efectos.
        </p>

        <p>
            <strong>“LAS PARTES”</strong> se obligan a notificar por escrito a la otra cualquier cambio en el domicilio que se indica anteriormente,
            conviniendo desde este momento que será al domicilio que mantengan vigente en los términos antes indicados el correspondiente para tener
            como válida cualquier notificación y/o aviso que deban darse de conformidad con lo estipulado en este Contrato.
        </p>

        <p>
            <strong>DÉCIMA SEXTA.- PRESCRIPCIÓN DE ACCIONES Y TRANSMISIÓN DE DERECHOS DE “EL COMPRADOR”.</strong>
            Las acciones civiles derivadas de responsabilidad civil, vicios ocultos y en su caso evicción, se resolverán y determinarán con base
            en las disposiciones legales vigentes del Código Civil del Estado de San Luis Potosí.
        </p>

        <p>
            <strong>DÉCIMA SÉPTIMA. COMPETENCIA EN CASO DE CONTROVERSIA.</strong>
            La Procuraduría Federal del Consumidor (Profeco) es competente en la vía administrativa para resolver cualquier controversia que se
            suscite para la interpretación o cumplimiento del presente Contrato.
        </p>

        <p>
            Sin perjuicio de lo anterior, “LAS PARTES” se someten a la jurisdicción de los tribunales competentes de la Ciudad de San Luis Potosí,
            Estado de San Luis Potosí renunciando expresamente a cualquier otra jurisdicción que pudiera corresponderles, por razón de
            sus domicilios presentes o futuros o por cualquier otra razón
        </p>

        <p>
            DÉCIMA OCTAVA.- MODELO DE CONTRATO DE PROFECO.- El modelo del presente Contrato de Adhesión se encuentra registrado en
            el Registro Público de Contratos de Adhesión de PROFECO bajo el número _________________, de fecha _______________________.
            Cualquier variación del presente Contrato en perjuicio de <strong>“EL COMPRADOR”,</strong> frente al contrato de adhesión registrado
            ante PROFECO, se tendrá por no puesta.
        </p>

        <p>
            Queda informado <strong>“EL COMPRADOR”</strong> que de conformidad con el artículo 18 de la Ley Federal de Protección al
            Consumidor se ha implementado el Registro Público de Consumidores (RPC), también llamado Registro Público Para Evitar Publicidad
            (REPEP) como un mecanismo de protección a los consumidores para no ser molestados con publicidad no deseada por proveedores,
            mediante llamadas telefónicas y mensajes de texto o que su información sea utilizada para fines mercadotécnicos o publicitarios.
            Este registro es gratuito, a efectos de que los consumidores determinen si es su deseo o no comunicar a la Procuraduría Federal
            del Consumidor su solicitud de inscripción a dicho registro, pudiendo para ello acceder a la página de internet
            https://repep.profeco.gob.mx/. y la puede realizar vía telefónica llamando desde el número que deseas inscribir al
            96 28 00 00 para las áreas metropolitanas de la Ciudad de México, Guadalajara y Monterrey o al 800 96 28 000 para el resto de la República
        </p>

        <p>
            Leído que fue por las partes el contenido del presente Contrato y sabedoras de su alcance legal, lo firman por duplicado en la Ciudad
            de San Luis Potosí, S.L.P.	a los {{$contrato->fecha_contrato}}. Entregándose una copia del mismo al <strong>“EL COMPRADOR”.</strong>
        </p>



        <p  align="center">
            <br>
            <div class="table">
                <div class="table-row">
                    <div colspan="5" class="table-cell"><center>“EL VENDEDOR”</center></div>
                    <div colspan="2" class="table-cell"></div>
                    <div colspan="5" class="table-cell"><center>“EL COMPRADOR</center></div>
                </div>
                <div class="table-row">
                    <div colspan="5" class="table-cell">
                        <br><br><br>
                        <center>__________________________________ <br>
                            @if($contrato->emp_terreno == 'Grupo Constructor Cumbres')
                                Grupo Constructor Cumbres S.A. de C.V.
                            @else
                                Concretania, S.A. de C.V.
                            @endif
                        </center>
                    </div>
                    <div colspan="2" class="table-cell"></div>
                    <div colspan="5" class="table-cell"><br><br><br>
                        <center>__________________________________ <br>
                            {{$contrato->c_nombre}} {{$contrato->c_apellidos}}
                        </center>
                    </div>
                </div>
            </div>
        </p>
    </div>

    <div style="page-break-after:always;"></div>

    <div>
        <h5 align="center">ANEXO A</h5>
    </div>

    <div>
        <p>
            Ciudad de ubicacion:&nbsp; {{$contrato->ciudad_proy}} <br>
            Nombre del Fraccionamiento:&nbsp; {{$contrato->proyecto}} <br>
            Calle:&nbsp; {{$contrato->calle_lote}} <br>
            Lote o área privativa:&nbsp; {{$contrato->lote}}{{($contrato->sublote)?'-'.$contrato->sublote:''}} <br>
            Manzana o condominio:&nbsp; {{$contrato->manzana}} <br>
            Número oficial exterior:&nbsp; {{$contrato->num_oficial}} <br>
            Número oficial interior:&nbsp; {{($contrato->interior)?$contrato->interior:'N/A'}} <br>
            Superficie del lote o área privativa&nbsp; {{$contrato->sup_terreno}}m&sup2;<br>
            Indiviso:&nbsp; {{$contrato->indivisos}}
        </p>
        <p>

            Medidas y colindancias del lote o área privativa:

            <li style="margin-left:40px;" class="ul-custom"><template class="cuadrado"></template>
                {{$contrato->colindancias}}
            </li><br>
        </p>
        <p>
            <strong>Datos de título de propiedad</strong><br><br>
            Escritura Numero:&nbsp; {{$contrato->num_escritura}} <br>
            Fecha de escritura:&nbsp; {{$contrato->date_escritura}} <br>
            Notaria que celebro la escritura:&nbsp; No. {{$contrato->num_notario}} <br>
            Folio real:&nbsp; {{$contrato->folio_registro}} <br>
            Cuenta predial:&nbsp; {{$contrato->clv_catastral}} <br>
        </p>

        <p>
            <h4>Fraccionamiento</h4>
            @foreach($contrato->amenidades as $amenidad)
                <div style="margin-left:40px;" class="ul-custom">- <template class="cuadrado"></template>
                    {{$amenidad->amenidad}}
                </div>
            @endforeach
        </p>

        <p>
            <strong>Equipamiento urbano</strong><br>
            @foreach($contrato->equip_urbano as $e)
                @if($e->categoria != 'Transportes')
                    {{$e->categoria}}:
                    @foreach($e->detalle as $detalle)
                        <li style="margin-left:40px;" class="ul-custom">- <template class="cuadrado"></template>
                            {{$detalle->nombre}}: &nbsp;{{ucwords($detalle->descripcion)}}
                        </li>
                    @endforeach
                    <br><br>
                @endif
            @endforeach
            <br><br>
            <strong>Sistemas de transporte para llegar al inmueble:</strong><br>
            @foreach($contrato->equip_urbano as $e)
                @if($e->categoria == 'Transportes')
                    {{$e->categoria}}:
                    @foreach($e->detalle as $detalle)
                        <li style="margin-left:40px;" class="ul-custom">- <template class="cuadrado"></template>
                            {{$detalle->nombre}}: &nbsp;{{ucwords($detalle->descripcion)}}
                        </li>
                    @endforeach
                    <br><br>
                @endif
            @endforeach
        </p>

        <p>
            <strong>Instalaciones para Servicios Básicos:</strong> <br>
            <li style="margin-left:40px;" class="ul-custom">
                <template class="cuadrado">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAAfklEQVRIie3QOwrCQBRA0SP+9qGuwlKIe3AZbsrGT7AI4n5sxNpOwSIKKUIEJ2nknWoYHpeZRwh/b4xp00A/MX7ABOeETq0Rjthh0DT47Qdr9HCp3A2xxRMrPH5+JjJcMa/E98jf51ZkuGGBDU7K/bdqiTuKLuIfsy7jIYQELy0QD0bA3JEyAAAAAElFTkSuQmCC">
                </template>
                Agua Potable
            </li>
            <li style="margin-left:40px;" class="ul-custom">
                <template class="cuadrado">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAAfklEQVRIie3QOwrCQBRA0SP+9qGuwlKIe3AZbsrGT7AI4n5sxNpOwSIKKUIEJ2nknWoYHpeZRwh/b4xp00A/MX7ABOeETq0Rjthh0DT47Qdr9HCp3A2xxRMrPH5+JjJcMa/E98jf51ZkuGGBDU7K/bdqiTuKLuIfsy7jIYQELy0QD0bA3JEyAAAAAElFTkSuQmCC">
                </template>
                Drenaje
            </li>
            <li style="margin-left:40px;" class="ul-custom">
                <template class="cuadrado">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAAfklEQVRIie3QOwrCQBRA0SP+9qGuwlKIe3AZbsrGT7AI4n5sxNpOwSIKKUIEJ2nknWoYHpeZRwh/b4xp00A/MX7ABOeETq0Rjthh0DT47Qdr9HCp3A2xxRMrPH5+JjJcMa/E98jf51ZkuGGBDU7K/bdqiTuKLuIfsy7jIYQELy0QD0bA3JEyAAAAAElFTkSuQmCC">
                </template>
                Luz &nbsp;
            </li>
            <li style="margin-left:40px;" class="ul-custom">
                <template class="cuadrado">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAAfklEQVRIie3QOwrCQBRA0SP+9qGuwlKIe3AZbsrGT7AI4n5sxNpOwSIKKUIEJ2nknWoYHpeZRwh/b4xp00A/MX7ABOeETq0Rjthh0DT47Qdr9HCp3A2xxRMrPH5+JjJcMa/E98jf51ZkuGGBDU7K/bdqiTuKLuIfsy7jIYQELy0QD0bA3JEyAAAAAElFTkSuQmCC">
                </template>
                Sistema para calentar agua y cocinar alimentos (gas l.p. / gas natural / eléctrico / otros combustibles)
            </li>
        </p>

        <p  align="center">
            <br>
            <div class="table">
                <div class="table-row">
                    <div colspan="5" class="table-cell"><center>“EL VENDEDOR”</center></div>
                    <div colspan="2" class="table-cell"></div>
                    <div colspan="5" class="table-cell"><center>“EL COMPRADOR</center></div>
                </div>
                <div class="table-row">
                    <div colspan="5" class="table-cell">
                        <br><br><br>
                        <center>__________________________________ <br>
                            @if($contrato->emp_terreno == 'Grupo Constructor Cumbres')
                                Grupo Constructor Cumbres S.A. de C.V.
                            @else
                                Concretania, S.A. de C.V.
                            @endif
                        </center>
                    </div>
                    <div colspan="2" class="table-cell"></div>
                    <div colspan="5" class="table-cell"><br><br><br>
                        <center>__________________________________ <br>
                            {{$contrato->c_nombre}} {{$contrato->c_apellidos}}
                        </center>
                    </div>
                </div>
            </div>
        </p>
    </div>

    <div style="page-break-after:always;"></div>

    <div>
        <h5 align="center">ANEXO B</h5>
    </div>

    <div>
        <h4>ACTA ENTREGA-RECEPCIÓN</h4>

        <p>
            En la Ciudad de San Luis Potosí	a ______ de _______________________ de ___________,
            <strong>“EL COMPRADOR”</strong> acude a recibir de <strong>“EL VENDEDOR”,</strong> el lote de
            TERRENO {{$contrato->lote}}{{($contrato->sublote)?'-'.$contrato->sublote:''}} de la manzana {{$contrato->manzana}}
            ubicado en el Fraccionamiento {{$contrato->proyecto}}, que pertenece al
            Municipio de {{$contrato->ciudad_proy}}, en el Estado de {{$contrato->estado_proy}}. Mismo que he inspeccionado y
            he constatado que cuenta con las especificaciones estipuladas en el “Anexo A”.
        </p>
    </div>

    <div style="page-break-after:always;"></div>

    <div>
        <h5 align="center">ANEXO C</h5>
    </div>
    <div>
        <p>
            <strong>INFORMACIÓN Y DOCUMENTACIÓN DEL TERRENO QUE SE PONE A DISPOSICIÓN DEL COMPRADOR</strong>
        </p>

        <p>
            <div class="table2">
                <div class="table-row">
                    <div colspan="10" class="table-cell2"></div>
                    <div class="table-cell2"><center>&nbsp;SI&nbsp;</center></div>
                    <div class="table-cell2"><center>&nbsp;NO&nbsp;</center></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a disposición el documento que acredite la propiedad del Terreno?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron sobre la existencia de gravámenes que afecten la propiedad del terreno?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a disposición los documentos que acrediten la personalidad de la vendedora y la autorización
                        del proveedor para promover la venta del Terreno?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron que el TERRENO NO se encuentra bajo el régimen ejidal o comunal?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron sobre las condiciones en que se encuentra el pago de contribuciones del Terreno?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a disposición el proyecto ejecutivo y ventas del fraccionamiento?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a disposición las autorizaciones, licencias o permisos expedidos por las
                        autoridades correspondientes del Terreno?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le proporcionaron la información sobre las medidas y colindancias del Terreno y su precio?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le brindaron información adicional sobre los beneficios ofrecidos por el
                        vendedor, en caso de concretar la operación?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le brindaron información adicional sobre las características, y uso y mantenimiento de las instalaciones
                        especiales (discapacitados) del terreno / del Fraccionamiento? (Cuando aplique)
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron respecto de las opciones de pago que puede elegir y sobre el monto total a pagar en cada
                        una de ellas, así como el precio del inmueble en operaciones de contado y el precio total que
                        determinará en función de los montos variables de conceptos de crédito y notariales?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿De ser el caso, le informaron de los mecanismos para la modificación o renegociación de las opciones de
                        pago, las condiciones bajo las cuales se realizaría y las implicaciones económicas, tanto para
                        el vendedor como para el comprador?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron de las condiciones bajo las cuales se llevará a cabo el proceso de escrituración,
                        así como las erogaciones distintas del precio de la venta que deba realizar?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron de las condiciones bajo las cuales puede cancelar la operación?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron si sobre el terreno existe y se ha constituido garantía hipotecaria, fiduciaria o de cualquier
                        otro tipo, así como su instrumentación?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron si el modelo de contrato que va a firmar esta previamente registrado ante la Procuraduría
                        Federal del Consumidor?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a disposición el Programa de Protección Civil del fraccionamiento?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron acerca de los canales y mecanismos de atención a quejas, así como los horarios de atención
                        con la que cuenta “EL VENDEDOR”?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición el Reglamento de Condominio (si aplica) y del Fraccionamiento?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición el Reglamento de Construcción?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición el equipamiento urbano existente en la localidad donde se encuentra el Fraccionamiento?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición el plan de desarrollo urbano?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición la Licencia de uso de suelo?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición la factibilidad de agua?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron si existe alguna restricción para la construcción en virtud de una restricción ambiental o por
                        colindar con alguna zona ecológica, reserva forestal o federal?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron acerca de la manera de consultar al aviso de privacidad?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
            </div>
        </p>

        <p class="small">
            La información y documentación antes descrita la puede verificar y consultar en nuestro domicilio ubicado en Manuel Gutierrez Nájera #190,  Col. Tequisquiapan,  San Luis Potosí, S.L.P.
        </p>

        <p class="small">
            Se le hace del conocimiento a <strong>“EL PROMITENTE COMPRADOR”:</strong> que contamos con canales de atención de quejas y solicitudes gratuitos y accesibles,
            para cualquier queja o sugerencia, los cuales pueden hacerlos llegar vía correo electrónico al correo: postventa@grupocumbres.com y/o atencion@grupocumbres.com o
            vía telefónica al número de atención: 444 8334683, 84 al 85,  con  un horario de lunes a viernes de 9:00 horas a 17:00 horas y sábados de 9:00 horas a 13:00 horas.
            Y el plazo de respuesta de <strong>“EL PROMITENTE VENDEDOR”</strong> es de: 8 días hábiles
        </p>

        <p class="small">
            <strong>
                <u>IMPORTANTE PARA “EL PROMITENTE COMPRADOR”: </u>Antes de que firme como constancia de que tuvo a su disposición la información y documentación
                relativa al Terreno, cerciórese de que la misma coincida con la que efectivamente le hayan mostrado y/o proporcionado “EL VENDEDOR”.
            </strong>
        </p>

        <p align="center">“EL VENDEDOR”:</p>

        <p  align="center">
            <div class="table">
                <div class="table-row">
                    <div class="table-cell"></div>
                    <div colspan="6" class="table-cell"><br><br><center>_____________________________________</center></div>
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                    <div class="table-cell"></div>
                    <div colspan="6" class="table-cell"><center>{{$contrato->emp_constructora}} S.A. DE C.V.</center></div>
                    <div class="table-cell"></div>
                </div>
            </div>
        </p>

        <p align="center"><br><br><br><br>
            “EL COMPRADOR”
        </p>

        <p  align="center">
            @if($contrato->coacreditaod == 0)
                <div class="table">
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><center><br><br><br><br>_____________________________________</center></div>
                        <div class="table-cell"></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><center>{{$contrato->c_nombre}} {{$contrato->c_apellidos}}</center></div>
                        <div class="table-cell"></div>
                    </div>

                </div>
            @else
                <div class="table">
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><br><br><br><br><center>___________________________________</center></div>
                        <div colspan="" class="table-cell"><center></center></div>
                        <div colspan="5" class="table-cell"><br><br><br><br><center>___________________________________</center></div>
                    </div>
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><center>{{$contrato->c_nombre}} {{$contrato->c_apellidos}}</center></div>
                        <div colspan="" class="table-cell"><center></center></div>
                        <div colspan="5" class="table-cell"><center>{{$contrato->nombre_coa}} {{$contrato->apellidos_coa}}</center></div>
                    </div>

                </div>
            @endif

            <br>
        </p>

    </div>

    <div style="page-break-after:always;"></div>

    <div>
        <h5 align="center">ANEXO D</h5>
    </div>

    <div>
        <p align="center">
            <strong>CARTA DE DERECHOS</strong>
        </p>

        <p>
            <strong>
                En este acto se le hace del conocimiento de EL CONSUMIDOR en base a lo que establece Ley Federal de Protección al Consumidor
                (en adelante LFPC), su Reglamento y la NOM 247-SE-2021, los siguientes derechos:
            </strong>
        </p>

        <p>
            <strong>1.  </strong>Recibir, respecto de los bienes inmuebles ofertados, información y publicidad veraz, clara y actualizada, sin importar el medio por el que se comunique, incluyendo los medios digitales, de forma tal que le permita al consumidor tomar la mejor decisión de compra conociendo de manera veraz las características del inmueble que está adquiriendo, conforme a lo dispuesto por la LFPC.<br>
            <strong>2.	</strong>Conocer la información sobre las características del inmueble, entre éstas: la extensión del terreno, superficie construida, tipo de estructura, instalaciones, acabados, accesorios, lugar de estacionamiento, áreas de uso común, servicios con que cuenta y estado general físico del inmueble. <br>
            <strong>3.	</strong>Elegir libremente el inmueble que mejor satisfaga sus necesidades y se ajuste a su capacidad de compra. <br>
            <strong>4.	</strong>No realizar pago alguno hasta que conste por escrito la relación contractual, exceptuando los referentes a anticipos y gastos operativos, en los términos previstos por la LFPC. <br>
            <strong>5.	</strong>Firmar un contrato de adhesión bajo el modelo inscrito en la Procuraduría Federal del Consumidor, en el que consten los términos y condiciones de la compraventa del bien inmueble. Posterior a su firma, el proveedor tiene la obligación de entregar una copia del contrato firmado al consumidor. <br>
            <strong>6.	</strong>Adquirir un inmueble que cuente con las características de seguridad y calidad que estén contenidas en la normatividad aplicable y plasmadas en la información y publicidad que haya recibido. <br>
            <strong>7.	</strong>Recibir el bien inmueble en el plazo y condiciones acordados con el proveedor en el contrato de adhesión respectivo. <br>
            <strong>8.	</strong>En su caso, ejercer las garantías sobre bienes inmuebles previstas en la LFPC, considerando las especificaciones previstas en el contrato de adhesión respectivo. <br>
            <strong>9.	</strong>Que en su caso las garantías a ejercer se regulan en términos de la LFPC, en específico por lo determinado en el artículo 73 Quater.       <br>
            <strong>10.	</strong>Contar con canales y mecanismos de atención gratuitos y accesibles para consultas, solicitudes, reclamaciones y sugerencias al proveedor, y conocer el domicilio señalado por el proveedor para oír y recibir notificaciones. <br>
            <strong>11.	</strong>Derecho a la protección por parte de las autoridades competentes y conforme a las leyes aplicables, incluyendo el derecho a presentar denuncias y reclamaciones ante las mismas. <br>
            <strong>12.	</strong>Tener a su disposición un Aviso de Privacidad para conocer el tratamiento que se dará a los datos personales que proporcione y consentirlo, en su caso; que sus datos personales sean tratados conforme a la normatividad aplicable y, conocer los mecanismos disponibles para realizar el ejercicio de sus Derechos de Acceso, Rectificación, Cancelación y Oposición. <br>
            <strong>13.	</strong>Recibir un trato libre de discriminación, sin que se le pueda negar o condicionar la atención o venta de una vivienda por razones de género, nacionalidad, étnicas, preferencia sexual, religiosas o cualquiera otra particularidad en los términos de la legislación aplicable. <br>
            <strong>14.	</strong>Elegir libremente al notario público para realizar el trámite de escrituración. <br>

        </p>
    </div>
</body>

</html>
