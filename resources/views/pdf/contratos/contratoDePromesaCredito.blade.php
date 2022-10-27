<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTRATO DE PROMESA DE COMPRAVENTA SUJETO A CONDICION SUSPENSIVA</title>
</head>

<style>
    body {
        font-size: 11pt;
        text-align: justify;
    }

    @page {
        margin: 55px;
        margin-right: 90px;
        margin-left: 90px;
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

    .table-row {
        display: table-row;
    }

    .myTable {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    .myTable td, .myTable th {
    border: 1px solid #ddd;
    padding: 4px;
    background-color: #ffffff;
    }

    .myTable tr:nth-child(even){background-color: #f2f2f2;}

    .myTable tr:hover {background-color: #ddd;}

    .myTable th {
    padding-top: 8px;
    padding-bottom: 8px;
    text-align: center;

    }

    .myTable thead {
        font-size:11px;
    }

    .myTable tbody {
        font-size:11px;
    }

</style>

<body>

    <div>

        <p>
            CONTRATO DE PROMESA DE COMPRAVENTA SUJETO A CONDICION SUSPENSIVA, QUE CELEBRAN, POR UNA PARTE,
            LA SOCIEDAD MERCANTIL DENOMINADA
            @if ($contratoPromesa[0]->emp_constructora == 'Grupo Constructor Cumbres')
                <strong>GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V.</strong>, REPRESENTADA EN ESTE ACTO POR EL
                ING. ALEJANDRO F. PEREZ ESPINOSA O EL ING. DAVID CALVILLO MARTINEZ,
            @else
                <strong>CONCRETANIA, S.A DE C.V.</strong>, REPRESENTADA EN ESTE ACTO POR EL
                EL ING. DAVID CALVILLO MARTINEZ,
            @endif

            A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO,
            SE LE DENOMINARA COMO <strong>EL PROMITENTE VENDEDOR</strong>, Y POR LA OTRA PARTE, POR SU PROPIO DERECHO,
            <strong>EL SR(A). {{ mb_strtoupper($contratoPromesa[0]->nombre) }}
                {{ mb_strtoupper($contratoPromesa[0]->apellidos) }} y @if ($contratoPromesa[0]->coacreditado == 1)
                    {{ mb_strtoupper($contratoPromesa[0]->nombre_coa) }}
                    {{ mb_strtoupper($contratoPromesa[0]->apellidos_coa) }}
                @endif
            </strong>
            ,
            A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE LE DENOMINARÁ COMO EL PROMITENTE
            COMPRADOR,
            AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.
        </p>

        <h4 align="center">DECLARACIONES</h4>

        <p>
            I.- Declara <strong>EL PROMITENTE VENDEDOR</strong>, por conducto de su representante:
        </p>

        <p>
            @if ($contratoPromesa[0]->emp_constructora == 'Grupo Constructor Cumbres')
                a) Que es una sociedad mercantil constituida de acuerdo a la legislación de la República Mexicana,
                mediante Escritura Pública número tres,
                del tomo cuarenta y siete, otorgada ante la fe del Licenciado Leopoldo de la Garza Marroquín, Notario
                Público número 33,
                con ejercicio en esta ciudad, con fecha ocho de diciembre de mil novecientos noventa y nueve, y cuyo
                primer testimonio obra inscrito en el
                Registro Público de la Propiedad y de Comercio, de esta ciudad, bajo la inscripción número tres del
                folio mercantil y
                folio de muebles número setenta, desde el día diecinueve de enero del dos mil.
            @else
                a) Que es una sociedad mercantil constituida de acuerdo a la legislación de la República Mexicana,
                mediante Escritura Pública número setecientos sesenta y cuatro, del volumen veintiuno, otorgada ante la
                fe
                del Licenciado Octaviano Gómez y González, Notario Público número Cuatro, con ejercicio en esta
                ciudad, con fecha veinticinco de julio de dos mil dieciocho, y cuyo primer testimonio obra inscrito en
                el
                Registro Público de la Propiedad y de Comercio, de esta ciudad, bajo el Folio Mercantil Eléctronico
                N2018073682,
                desde el día siete de septiembre de dos mil dieciocho.
            @endif
        </p>

        <p>
            @if ($contratoPromesa[0]->emp_constructora == 'Grupo Constructor Cumbres')
                b) Que su representante, el Ing. Alejandro Francisco Pérez Espinosa y el Ing. David Calvillo Martínez,
                cuentan con las facultades necesarias para la celebración del presente contrato, mismas que a la fecha
                no le han sido restringidas,
                ni revocadas de forma alguna.
            @else
                b) Que su representante, el Ing. David Calvillo Martínez,
                cuenta con las facultades necesarias para la celebración del presente contrato, mismas que a la fecha no
                le han sido restringidas,
                ni revocadas de forma alguna.
            @endif
        </p>

        <p>
            c) Que es única y legítimo propietario del Lote de terreno número
            <strong>{{ $contratoPromesa[0]->num_lote }}
                @if ($contratoPromesa[0]->sublote != null)
                    {{ $contratoPromesa[0]->sublote }}
                @endif
            </strong>, de la manzana <strong>{{ mb_strtoupper($contratoPromesa[0]->manzana) }}</strong> del
            Fraccionamiento <strong>{{ mb_strtoupper($contratoPromesa[0]->proyecto) }}</strong>,
            en esta ciudad de {{ mb_strtoupper($contratoPromesa[0]->ciudad_proy) }},
            {{ mb_strtoupper($contratoPromesa[0]->estado_proy) }}, cuya superficie es de
            <strong>{{ $contratoPromesa[0]->superficie }} M2</strong>, mismo que en lo sucesivo y
            para todos los efectos del presente contrato se le denominará como <strong>EL LOTE</strong>.
        </p>
        @if($contratoPromesa[0]->modelo != 'Terreno')
            <p>
                d) Que dentro de su objeto social se incluye la construcción y edificación de casas y cuenta con los
                elementos,
                práctica y servicios de empleados que son necesarios para construir en el Lote, una vivienda cuyas
                características
                se encuentran debidamente especificadas en el anexo “A” del presente contrato, el que debidamente firmado
                por las partes formar parte integrante del mismo.
            </p>

            <p>
                e) Que sobre <strong>EL LOTE</strong> descrito anteriormente construirá una casa habitación, cuyas
                características se encuentran debidamente especificadas
                en el anexo “A” del presente contrato, a la que en conjunto con el mismo, en lo sucesivo se le identificará
                en el presente contrato
                como <strong>LA VIVIENDA</strong>.
            </p>

            <p>
                f) Que es su deseo celebrar el presente contrato preparatorio con EL PROMITENTE COMPRADOR a efecto de
                obligarse a
                celebrar el contrato definitivo de compraventa respecto del terreno antes mencionado y la casa a construir
                en el mismo identificados como <strong>LA VIVIENDA</strong>.
            </p>
        @else
            <p>
                d) Que es su deseo celebrar el presente contrato preparatorio con EL PROMITENTE COMPRADOR a efecto de
                obligarse a
                celebrar el contrato definitivo de compraventa respecto del terreno antes mencionado
                identificado como <strong>EL LOTE</strong>.
            </p>
        @endif

        <p>
            II.- Declara <strong>EL PROMITENTE COMPRADOR</strong> por su propio derecho:
        </p>

        <p>
            a) Que es una persona física, mayor de edad, con plena capacidad para contratar y obligarse.
        </p>

        <p>
            @if($contratoPromesa[0]->modelo != 'Terreno')
                b) Que conoce perfectamente la ubicación, superficie, medidas y colindancias, así como las características
                generales
                y especificaciones técnicas con base en las cuáles se llevará a cabo la construcción de <strong>LA
                    VIVIENDA</strong>, por lo que está conforme,
                y desea celebrar el presente contrato preparatorio con <strong>EL PROMITENTE VENDEDOR</strong>, a efecto de
                obligarse a celebrar el
                contrato definitivo de compraventa, por medio del cual adquirirá la propiedad de <strong>LA
                    VIVIENDA</strong>.
            @else
                b)	Que conoce perfectamente la ubicación, superficie, medidas y colindancias de
                <strong>EL LOTE</strong> así como las características generales y especificaciones técnicas de las vías de acceso
                y de los servicios instalados para <strong>EL LOTE</strong> y además, sabe que el uso de suelo es
                exclusivamente <strong>habitacional.</strong>
            @endif
        </p>

        @if ($contratoPromesa[0]->regimen_condom == 1)
            @if($contratoPromesa[0]->modelo != 'Terreno')
                <p>
                    c).- Que le fue explicado que <strong>LA VIVIENDA</strong> se encuentra dentro de un régimen de
                    propiedad en condominio y que dicha vivienda
                    quedará sujeta a las disposiciones establecidas en el Régimen de Condominio y el reglamento de
                    condóminos correspondiente.
                </p>

                <p>
                    d) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>LA
                        VIVIENDA</strong>, una vez que haya sido designada
                    beneficiaria de un crédito hipotecario otorgado por
                    <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }} @if ($contratoPromesa[0]->infonavit > 0 && $contratoPromesa[0]->fovisste == 0)
                            e INFONAVIT
                        @elseif($contratoPromesa[0]->fovisste > 0)
                            y FOVISSTE
                        @endif </strong>,
                    mismo que en lo sucesivo y para los efectos del presente contrato será denominado como
                    <strong>Hipotecario</strong>. Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con
                    historial crediticio
                    satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue
                    un crédito por parte de
                    institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
                </p>

                <p>
                    e) Que es su deseo celebrar el presente contrato con <strong>EL PROMITENTE VENDEDOR</strong> con el
                    objeto de obligarse a
                    celebrar un contrato de compraventa respecto de <strong>LA VIVIENDA</strong>.
                </p>
            @else
                <p>
                    c)	Que le fue explicado que <strong>EL LOTE</strong>  se encuentra dentro de un fraccionamiento cuya normativa de
                    comportamiento y de conservación se encuentra sujeta, además de las leyes aplicables en el Estado, a los
                    Lineamientos de Construcción y Convivencia del Fraccionamiento los cuales se compromete a obedecer.
                </p>

                <p>
                    d) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>EL LOTE</strong>,
                    una vez que haya sido designada beneficiaria de un crédito hipotecario otorgado por
                    <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }} @if ($contratoPromesa[0]->infonavit > 0 && $contratoPromesa[0]->fovisste == 0)
                            e INFONAVIT
                        @elseif($contratoPromesa[0]->fovisste > 0)
                            y FOVISSTE
                        @endif </strong>,
                    mismo que en lo sucesivo y para los efectos del presente contrato será denominado como
                    <strong>Hipotecario</strong>. Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con
                    historial crediticio
                    satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue
                    un crédito por parte de
                    institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
                </p>

                <p>
                    e) Que es su deseo celebrar el presente contrato con <strong>EL PROMITENTE VENDEDOR</strong> con el
                    objeto de obligarse a
                    celebrar un contrato de compraventa respecto de <strong>EL LOTE</strong>.
                </p>
            @endif
        @else
            @if($contratoPromesa[0]->modelo != 'Terreno')
                <p>
                    c) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>LA
                        VIVIENDA</strong>, una vez que haya sido designada
                    beneficiaria de un crédito hipotecario otorgado por
                    <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }} @if ($contratoPromesa[0]->infonavit > 0)
                            e INFONAVIT
                        @elseif($contratoPromesa[0]->fovisste > 0)
                            y FOVISSTE
                        @endif </strong>,
                    mismo que en lo sucesivo y para los efectos del presente contrato será denominado como
                    <strong>Hipotecario</strong>. Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con
                    historial crediticio
                    satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue
                    un crédito por parte de
                    institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
                </p>

                <p>
                    d) Que es su deseo celebrar el presente contrato con <strong>EL PROMITENTE VENDEDOR</strong> con el
                    objeto de obligarse a
                    celebrar un contrato de compraventa respecto de <strong>LA VIVIENDA</strong>.
                </p>
            @else
                <p>
                    c)	Que le fue explicado que <strong>EL LOTE</strong>  se encuentra dentro de un fraccionamiento cuya normativa de
                    comportamiento y de conservación se encuentra sujeta, además de las leyes aplicables en el Estado, a los
                    Lineamientos de Construcción y Convivencia del Fraccionamiento los cuales se compromete a obedecer.
                </p>

                <p>
                    d) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>EL LOTE</strong>,
                    una vez que haya sido designada beneficiaria de un crédito hipotecario otorgado por
                    <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }} @if ($contratoPromesa[0]->infonavit > 0 && $contratoPromesa[0]->fovisste == 0)
                            e INFONAVIT
                        @elseif($contratoPromesa[0]->fovisste > 0)
                            y FOVISSTE
                        @endif </strong>,
                    mismo que en lo sucesivo y para los efectos del presente contrato será denominado como
                    <strong>Hipotecario</strong>. Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con
                    historial crediticio
                    satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue
                    un crédito por parte de
                    institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
                </p>

                <p>
                    e) Que es su deseo celebrar el presente contrato con <strong>EL PROMITENTE VENDEDOR</strong> con el
                    objeto de obligarse a
                    celebrar un contrato de compraventa respecto de <strong>EL LOTE</strong>.
                </p>
            @endif

        @endif

        <p>
            Conformes las partes con las declaraciones que anteceden, las cuales forman parte integrante del presente
            contrato, acuerdan otorgar las siguientes:
        </p>

        <p align="center">C L A U S U L A S</p>

        <p>
            @if($contratoPromesa[0]->modelo != 'Terreno')
                <strong>PRIMERA.-</strong> Sujeto a la condición que se establece en la cláusula segunda del presente
                contrato, <strong>EL PROMITENTE VENDEDOR</strong> se obliga a
                celebrar con el carácter de vendedora, un contrato de compraventa respecto de <strong>LA VIVIENDA</strong>,
                con <strong>EL PROMITENTE COMPRADOR</strong>, y a su vez,
                <strong>EL PROMITENTE COMPRADOR</strong>, se obliga a celebrar, con el carácter de comprador, el contrato de
                compraventa a que esta cláusula se refiere.
            @else
                <strong>PRIMERA. INMUEBLE OBJETO DE LA OPERACIÓN.- CONCRETANIA.-</strong> se obliga a celebrar con el carácter de vendedora,
                un contrato de compraventa elevado a escritura pública y ante notario público, respecto de <strong>EL LOTE</strong>,
                con <strong>EL PROMITENTE COMPRADOR</strong> y a su vez, éste se obliga a celebrar, con el carácter de comprador,
                el contrato de compraventa a que esta cláusula se refiere, respecto de <strong>EL LOTE</strong> descrito en la
                declaración I, inciso c) de este acto jurídico. Esta compraventa será con reserva de dominio,
                por lo que el mismo se transmitirá hasta que se firme la escritura y se realicen en su totalidad los pagos pactados en este acto jurídico.
            @endif
        </p>

        <p>
            <strong>SEGUNDA.-</strong> El contrato definitivo de compraventa que las partes se han obligado a celebrar,
            está sujeto a la condición suspensiva consistente en que
            <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }} @if ($contratoPromesa[0]->infonavit > 0)
                    e INFONAVIT
                @elseif($contratoPromesa[0]->fovisste > 0)
                    y FOVISSTE
                @endif
            </strong>,
            @if($contratoPromesa[0]->modelo != 'Terreno')
                otorgue a <strong>EL PROMITENTE COMPRADOR</strong> un crédito con garantía hipotecaria hasta por la cantidad
                necesaria para cubrir el
                precio total de la operación de compraventa pactado en la cláusula cuarta, inciso a) y que dicho precio se
                aplique o libere,
                en pago directo a <strong>EL PROMITENTE VENDEDOR</strong>, de tal forma que se encuentre liquidado dentro
                del plazo de 45 días naturales
                contados a partir de la fecha de terminación de la construcción de <strong>LA VIVIENDA</strong>. En razón de
                lo anterior, las partes están de acuerdo
                para que en caso de que no sea autorizado y liberado para pago, el crédito de referencia dentro de los 45
                días naturales siguientes a la
                fecha de terminación de la obra que se ejecuta sobre <strong>EL LOTE</strong>, sin necesidad de declaración
                judicial alguna, se tendrá por rescindido de
                pleno derecho el actual contrato de promesa de compraventa, deslindando desde este momento <strong>EL
                    PROMITENTE COMPRADOR</strong> a <strong>EL PROMITENTE VENDEDOR</strong> de
                cualquier responsabilidad civil, penal o de cualquier otra índole legal, en torno al bien inmueble motivo de
                la presente contratación.
            @else
                otorgue a <strong>EL PROMITENTE COMPRADOR</strong> un crédito con garantía hipotecaria hasta por la cantidad
                necesaria para cubrir el
                precio total de la operación de compraventa pactado en la cláusula cuarta, inciso a) y que dicho precio se
                aplique o libere,
                en pago directo a <strong>EL PROMITENTE VENDEDOR</strong>, de tal forma que se encuentre liquidado dentro
                del plazo de 45 días naturales
                contados a partir de la fecha de formalización del terreno. En razón de
                lo anterior, las partes están de acuerdo
                para que en caso de que no sea autorizado y liberado para pago, el crédito de referencia dentro de los 45
                días naturales siguientes a la
                fecha de formalización del terreno que se ejecuta sobre <strong>EL LOTE</strong>, sin necesidad de declaración
                judicial alguna, se tendrá por rescindido de
                pleno derecho el actual contrato de promesa de compraventa, deslindando desde este momento <strong>EL
                    PROMITENTE COMPRADOR</strong> a <strong>EL PROMITENTE VENDEDOR</strong> de
                cualquier responsabilidad civil, penal o de cualquier otra índole legal, en torno al bien inmueble motivo de
                la presente contratación.
            @endif
        </p>

        <p>
            @if($contratoPromesa[0]->modelo != 'Terreno')
                En el caso de que dicho crédito no fuere autorizado o liberado para pago, dentro del plazo de 45 días
                naturales contados a partir
                de la terminación de la construcción de <strong>LA VIVIENDA, EL PROMITENTE VENDEDOR</strong>, reembolsará a
                <strong>EL PROMITENTE COMPRADOR</strong>,
                en un término de 90 días naturales contados a partir de la fecha de la terminación de la casa habitación, la
                o las cantidades que
                hasta esa fecha le haya(n) sido pagada(s) por <strong>EL PROMITENTE COMPRADOR</strong>, menos la cantidad de
                $ 10,000.00 (Diez Mil Pesos 00/100 Moneda Nacional),
                ya que esta última cantidad será utilizada para el pago del estudio del mismo crédito y costo de avaluo que
                exige la Institución Financiera y
                restando también la cantidad establecida en la cláusula sexta de este contrato; sin que exista obligación
                alguna por
                parte de <strong>EL PROMITENTE VENDEDOR</strong> de cubrir ninguna cantidad extra por concepto de Daños,
                perjuicios, o indemnización legal de ninguna especie.
                En caso de no cumplirse la condición suspensiva a que se encuentra sujeto el presente contrato, las partes
                están de acuerdo
                en que <strong>EL PROMITENTE VENDEDOR</strong> podrá enajenar <strong>LA VIVIENDA</strong> a favor de
                diversos compradores, o bien, darle el uso o destino que crea más conveniente,
                no existiendo en ello conducta tipificada en la ley penal como ilícito de fraude, ya que las partes están de
                común acuerdo con la condición
                suspensiva y sus efectos previstos en la actual cláusula, por lo que no existe dolo o engaño en tal
                manifestación de voluntad.
            @else
                En el caso de que dicho crédito no fuere autorizado o liberado para pago, dentro del plazo de 45 días naturales contados
                a partir de la formalización del terreno de <strong>EL LOTE, EL PROMITENTE VENDEDOR,</strong> reembolsará a
                <strong>EL PROMITENTE COMPRADOR,</strong> en un término de 90 días naturales contados a partir de la fecha de la
                formalización del terreno, la o las cantidades que hasta esa fecha le haya(n) sido pagada(s) por
                <strong>EL PROMITENTE COMPRADOR,</strong> menos la cantidad de $ 10,000.00 (Diez Mil Pesos 00/100 Moneda Nacional),
                ya que esta última cantidad será utilizada para el pago del estudio del mismo crédito y costo de
                avaluo que exige la Institución Financiera y restando también la cantidad establecida en la
                cláusula sexta de este contrato; sin que exista obligación alguna por parte de
                <strong>EL PROMITENTE VENDEDOR</strong> de cubrir ninguna cantidad extra por concepto de Daños,
                perjuicios, o indemnización legal de ninguna especie. En caso de no cumplirse la
                condición suspensiva a que se encuentra sujeto el presente contrato, las partes están
                de acuerdo en que <strong>EL PROMITENTE VENDEDOR</strong> podrá enajenar <strong>EL LOTE</strong> a favor de diversos compradores,
                o bien, darle el uso o destino que crea más conveniente, no existiendo en ello conducta tipificada
                en la ley penal como ilícito de fraude, ya que las partes están de común acuerdo con la condición
                suspensiva y sus efectos previstos en la actual cláusula, por lo que no existe dolo o engaño en
                tal manifestación de voluntad.
            @endif
        </p>


        <p>
            <strong>TERCERA.- EL PROMITENTE COMPRADOR</strong> se obliga a integrar toda la documentación necesaria
            requerida por la institución
            crediticia que pudiera otorgarle los recursos para la adquisición de
            @if($contratoPromesa[0]->modelo != 'Terreno')
                <strong>LA VIVIENDA</strong>,
            @else
                <strong>EL LOTE</strong>,
            @endif
                y a entregarla a la propia institución bancaria
            en un plazo no mayor a 7 días naturales contados a partir de la firma del presente contrato para que tenga
            acceso al crédito mencionado
            en la cláusula anterior, ya que será utilizada para integrar el expediente necesario para la obtención del
            crédito.
        </p>

        <p>
            <strong>EL PROMITENTE VENDEDOR</strong> podrá ofrecer asesoría e incluso, coadyuvar con <strong>EL
                PROMITENTE COMPRADOR</strong> para la gestión del crédito y la presentación de documentos,
            sin que esa circunstancia releva a este último de ser el responsable de realizar esos trámites.
        </p>

        <p>
            <strong>CUARTA.-</strong> El precio total que convienen las partes, será motivo de la operación de
            compraventa definitiva,
            es la cantidad que de <strong>${{ strtoupper($contratoPromesa[0]->precioVentaLetra) }},</strong>
            mismo que EL PROMITENTE COMPRADOR se obliga a pagar a EL PROMITENTE VENDEDOR en los términos establecidos en
            la
            cláusula de la siguiente manera: a).La cantidad de
            @if ($contratoPromesa[0]->precio_venta >= $contratoPromesa[0]->credito_neto || $contratoPromesa[0]->precio_venta == $contratoPromesa[0]->credito_neto)
                <strong>${{ strtoupper($contratoPromesa[0]->montoTotalCreditoLetra) }},</strong>
            @elseif($contratoPromesa[0]->precio_venta < $contratoPromesa[0]->credito_neto)
                <strong>${{ strtoupper($contratoPromesa[0]->precioVentaLetra) }},</strong>
            @endif
            mediante el crédito que le otorgara {{ mb_strtoupper($contratoPromesa[0]->institucion) }}
            @if ($contratoPromesa[0]->infonavit > 0) y la cantidad de
                <strong>${{ strtoupper($contratoPromesa[0]->infonavitLetra) }}</strong> que le otorga
                @if ($contratoPromesa[0]->tipo_credito == 'BANJERCITO')
                    BANJERCITO
                @else
                    INFONAVIT
                @endif
            @elseif($contratoPromesa[0]->fovisste > 0)
                y la cantidad de <strong>${{ strtoupper($contratoPromesa[0]->fovissteLetra) }}</strong> que le otorga
                FOVISSTE
            @endif,
            al que se refiere la cláusula segunda del presente convenio, misma que deberá ser liquidada dentro de los 45
            días naturales siguientes
            @if($contratoPromesa[0]->modelo != 'Terreno')
                a la conclusión de la construcción de LA VIVIENDA.
            @else
                a la formalización de escrituras de EL LOTE.
            @endif
            @if ($contratoPromesa[0]->precio_venta != $contratoPromesa[0]->credito_neto && $contratoPromesa[0]->enganche_total >= 10000 && $contratoPromesa[0]->total_pagar > 0)

                b).El resto, mediante
                <strong>{{ $pagos[0]->totalDePagos }}</strong>pagos,
                @for ($i = 0; $i < count($pagos); $i++)
                    el <strong>{{ $pagos[$i]->numeros }}</strong> por la cantidad de
                    <strong>${{ strtoupper($pagos[$i]->montoPagoLetra) }},</strong>
                    que será liquidado a más tardar el día <strong>{{ $pagos[$i]->fecha_pago }}</strong>
                @endfor, respectivamente.
            @elseif($contratoPromesa[0]->enganche_total < 10000 && $contratoPromesa[0]->enganche_total > 0.001 && $contratoPromesa[0]->total_pagar > 0)
                b).El resto, mediante <strong>{{ $pagos[0]->totalDePagos }}</strong>pagos por la cantidad de
                <strong>${{ $contratoPromesa[0]->engancheTotalLetra }}</strong> que será liquidado a más tardar el día
                <strong>{{ $pagos[0]->fecha_pago }},</strong> respectivamente.
            @elseif($contratoPromesa[0]->enganche_total <= 0)
                <br>
            @endif
        </p>

        <p>
            Hasta en tanto no se encuentre totalmente liquidado el precio total de
            @if($contratoPromesa[0]->modelo != 'Terreno')
                <strong>LA VIVIENDA</strong>,
            @else
                <strong>EL LOTE</strong>,
            @endif
            las cantidades que reciba <strong>EL PROMITENTE VENDEDOR,</strong>
            serán entregadas por <strong>EL PROMITENTE COMPRADOR,</strong> a título de depósito, por lo que en caso de
            que el presente contrato se rescindiera,
            las mismas deberán ser reembolsadas a <strong>EL PROMITENTE COMPRADOR,</strong> en los términos pactados en
            las cláusulas segunda y sexta del presente contrato.
        </p>
        @if($contratoPromesa[0]->modelo != 'Terreno')
            <p>
                Asimismo, el precio pactado con antelación, se sujeta a la condición de que el pago total del precio de
                <strong>LA VIVIENDA,</strong>
                se verifique a más tardar dentro de los 45 días naturales siguientes a la fecha de terminación de la
                construcción de <strong>LA VIVIENDA,</strong>
                en el entendido además, de que transcurrida dicha fecha, sin la celebración del contrato definitivo de
                compraventa,
                el presente contrato se rescindirá en forma automática.
            </p>
        @endif

        <p>
            <strong>QUINTA.- EL PROMITENTE COMPRADOR</strong>
            @if($contratoPromesa[0]->modelo != 'Terreno')
                señala estar de acuerdo con: A). Las características de la
                urbanización del terreno. B).
                La superficie de construcción que es de <strong>{{ $contratoPromesa[0]->construccion }} M2</strong>. C). La
                fecha de entrega de <strong>LA VIVIENDA</strong> a <strong>EL PROMITENTE COMPRADOR</strong>,
                se realizara en el momento que quede liberado el precio total de <strong>LA VIVIENDA</strong>. Por lo que
                <strong>EL PROMITENTE COMPRADOR</strong> no sé reserva
                ningún derecho de ejercitar posteriormente sobre este particular, ya que <strong>EL LOTE</strong> y
                <strong>LA VIVIENDA,</strong> se apegan a sus necesidades.
            @else
                señala estar de acuerdo con: A). Las características de la urbanización del terreno.
                B). La superficie del mismo que es de <strong>{{$contratoPromesa[0]->superficie}} m2.</strong>
                C). La fecha de entrega a <strong>EL PROMITENTE COMPRADOR,</strong> que se realizará en el momento que
                quede pagado el precio total del mismo, por lo que <strong>EL PROMITENTE COMPRADOR</strong> no se
                reserva ningún derecho de ejercitar posteriormente sobre este particular,
                ya que <strong>EL LOTE</strong> se apega a sus necesidades.
            @endif
        </p>

        @if($contratoPromesa[0]->modelo != 'Terreno')
            <p>
                <strong>SEXTA.-</strong> Este contrato podrá ser rescindido de pleno derecho y sin necesidad de declaración
                judicial en caso de incumplimiento a lo pactado en este contrato.
                En forma enunciativa y no limitativa, se mencionan algunas causales en que puede incurrir el comprador: a).-
                No cubrir cualquiera de los pagos pactados en el inciso b) de la cláusula cuarta; b).- No obtener la
                totalidad del crédito para el pago de la cantidad establecida en el inciso
                a) de la cláusula cuarta, en el plazo de 45 días contados a partir de la terminación de la vivienda; c).- No
                entregar en forma oportuna la documentación a que se compromete en términos de la cláusula tercera.
            </p>

            <p>
                La falta de pago puntual por parte de <strong>EL PROMITENTE COMPRADOR</strong> faculta a <strong>EL
                    PROMITENTE VENDEDOR</strong> al cobro de intereses calculados
                a razón del 5% mensual por cada día de retraso y calculados sobre la cantidad adeudada.
                El cobro de intereses por parte de <strong>EL PROMITENTE VENDEDOR</strong>, no implica una renuncia al
                derecho a ejercer la recisión de pleno derecho y sin necesidad de declaración judicial que en este mismo
                contrato se prevé.
                <br>
                @if ($contratoPromesa[0]->precio_venta != $contratoPromesa[0]->credito_neto)
            </p>

            <p>
                @endif
                En caso de rescisión del presente contrato, por causa imputable a <strong>EL PROMITENTE COMPRADOR, EL
                    PROMITENTE VENDEDOR</strong> deberá reintegrar
                el importe recibido hasta la fecha por parte de <strong>EL PROMITENTE COMPRADOR</strong>, descontándose la
                cantidad de $ 25,000.00 (Veinticinco Mil Pesos 00/100 Moneda Nacional),
                como pena convencional por los daños y perjuicios ocasionados. El importe se reintegrara hasta que
                <strong>EL PROMITENTE VENDEDOR</strong> recupere la inversión realizada
                en la construcción.
            </p>

            <p>
                <strong>EL PROMITENTE COMPRADOR</strong> manifiesta su expresa conformidad y señala que entiende cabalmente
                el alcance de lo que aquí se detalla.
            </p>

            <p>
                <strong>SÉPTIMA.-</strong> Todos los gastos, derechos, impuestos y honorarios que se causen con motivo de
                este contrato y
                la celebración del contrato definitivo en Escritura Publica serán cubiertos por <strong>EL PROMITENTE
                    COMPRADOR</strong>,
                excepto el Impuesto sobre la Renta el cuál será a cargo de <strong>EL PROMITENTE VENDEDOR</strong>.
            </p>

            <p>
                <strong>OCTAVA.-</strong> Todas las comunicaciones que las partes deban o deseen hacerse en relación con
                este Contrato se harán por escrito,
                señalando las partes como sus respectivos domicilios, para recibir toda clase de notificaciones que deban
                hacérseles,
                aun las personales en caso de juicio, las siguientes:
            </p>

            <p>
                @if ($contratoPromesa[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    <strong>EL PROMITENTE VENDEDOR:</strong> MANUEL GUTIERREZ NAJERA 190 Colonia TEQUISQUIAPAM San Luis
                    Potosí, San Luis Potosí
                @else
                    <strong>EL PROMITENTE VENDEDOR:</strong> MANUEL GUTIERREZ NAJERA 180 Colonia TEQUISQUIAPAM San Luis
                    Potosí, San Luis Potosí
                @endif
            </p>

            <p>
                <strong>EL PROMITENTE COMPRADOR:</strong> {{ mb_strtoupper($contratoPromesa[0]->direccion) }} Colonia
                {{ mb_strtoupper($contratoPromesa[0]->colonia) }} {{ mb_strtoupper($contratoPromesa[0]->ciudad) }},
                {{ mb_strtoupper($contratoPromesa[0]->estado) }}
            </p>

            <p>
                <strong>NOVENA.-</strong> Para todo lo relacionado con la interpretación, cumplimiento y ejecución del
                presente Contrato, las partes se someten a la jurisdicción y
                competencia de los tribunales de esta ciudad de San Luis Potosí, San Luis Potosí, renunciando expresamente a
                cualquier otro fuero que
                pudiera corresponderles en razón de sus domicilios presentes o futuros o por cualquier otro motivo.
            </p>
        @else

            <p>
                <strong>SEXTA. GASTOS.  SERVICIOS. IMPUESTO PREDIAL.-</strong>
                Todos los gastos, derechos, impuestos y honorarios que se causen con motivo de este contrato
                y de la celebración del mismo en Escritura Pública serán cubiertos por
                <strong>EL PROMITENTE COMPRADOR</strong>, excepto el Impuesto Sobre la
                Renta el cuál será a cargo de <strong>CONCRETANIA</strong>.
            </p>

            <p>
                Además, <strong>EL PROMITENTE COMPRADOR</strong> se obliga a pagar a partir de que le sea
                entregado <strong>EL LOTE</strong>, el impuesto predial y la cuota de mantenimiento de las
                áreas verdes, que se generen sobre <strong>EL LOTE</strong>.
            </p>

            <p>
                El lote se entregará  debidamente urbanizado y se ubicarán en la vía pública las instalaciones
                generales de energía eléctrica, agua potable y drenaje, pero queda a cargo de
                <strong>EL PROMITENTE COMPRADOR</strong>, exclusivamente la contratación y los pagos
                de cualquiera de estos servicios.
            </p>

            <p>
                <strong>SÉPTIMA.- ESCRITURACIÓN Y POSESIÓN.- CONCRETANIA </strong>
                otorgará la escritura de compraventa, transmitiendo el dominio y la posesión jurídica de <strong>EL LOTE</strong>,
                una vez que <strong>EL PROMITENTE COMPRADOR</strong> haya cubierto el precio pactado,
                junto con los intereses que queden a su cargo. No obstante lo anterior, la posesión física de
                <strong>EL LOTE</strong> se realizará al terminar la urbanización del mismo y el cliente haya cubierto
                por lo menos el 50% de monto del precio pactado en la cláusula tercera de este contrato.
            </p>

            <p>
                Una vez cubierto el total del precio de la operación, <strong>EL PROMITENTE COMPRADOR</strong> deberá designar,
                dentro de los tres días siguientes, al notario con el que se formalizará la escritura de compraventa,
                a efecto de que <strong>EL PROMITENTE VENDEDOR</strong> haga llegar a ese fedatario la información necesaria
                para elaborar la escritura. Si en el plazo mencionado <strong>EL PROMITENTE COMPRADOR</strong> no
                designa notario, el mismo será elegido por <strong>EL PROMITENTE VENDEDOR</strong>. La negativa de
                cualquiera de las partes a firmar la escritura, provocará la rescisión de este contrato,
                aplicando la pena convencional establecida en la cláusula décima tercera de este contrato
            </p>

            <p>
                <strong>OCTAVA. PERMISO PARA CONSTRUIR.- </strong> Sin que implique transmisión de posesión, a petición
                de <strong>EL PROMITENTE COMPRADOR,</strong> el <strong>PROMITENTE VENDEDOR</strong> podrá otorgar permiso
                por escrito para que aquél inicie la construcción sobre <strong>EL LOTE,</strong> siempre y cuando
                concurra lo siguiente:
            </p>


            <p>
                a).- Que se encuentre al corriente en el pago de las amortizaciones pactadas, tanto de suerte principal como
                de intereses ordinarios y/o moratorios en su caso y que al momento de solicitar el permiso para construir sobre
                <strong>EL LOTE</strong>, se haya cubierto el precio pactado.
            </p>

            <p>
                b).- Se encuentre al corriente en el pago de impuesto predial.
            </p>

            <p>
                c).- Se haga cargo de los permisos y autorizaciones necesarias del Ayuntamiento correspondiente.
            </p>

            <p>
                d).- Que acepte expresamente que, en caso de incurrir en incumplimiento del presente contrato que motive la rescisión
                del mismo, todo lo construido pasará a ser propiedad de <strong>EL PROMITENTE VENDEDOR</strong>, sin que este último deba
                cubrir parte o la totalidad del valor de las construcciones, de conformidad con el artículo 846 del
                Código Civil del Estado de San Luis Potosí, ya que se considera que construye en terreno ajeno.
            </p>

            <p>
                <strong>NOVENA.-  USO DE SUELO.- EL PROMITENTE COMPRADOR </strong>
                se obliga a no variar el uso habitacional en el cual está clasificado <strong>EL LOTE</strong>,
                que es habitacional; por  lo que deberá abstenerse de construir otro tipo de edificaciones comerciales o industriales.
            </p>

            <p>
                <strong>DÉCIMA.- REGLAMENTO DEL FRACCIONAMIENTO. </strong>
                Independientemente de  que <strong>EL LOTE</strong> se encuentre o no sujeto a un régimen de propiedad en
                condominio, <strong>EL PROMITENTE COMPRADOR</strong> se obliga a acatar los Lineamientos de Construcción y
                Convivencia del fraccionamiento, del cual recibe en este acto un ejemplar legible,
                sirviendo esta cláusula como acuse de recibo del mismo.
            </p>

            <p>
                <strong>EL PROMITENTE COMPRADOR</strong> autoriza a <strong>LA PROMITENTE VENDEDORA</strong> a que entregue a la mesa directiva
                que se forme en el fraccionamiento, su nombre, dirección y teléfono, para que estos realicen la cobranza de
                las cuotas de mantenimiento.
            </p>

            <p>
                <strong>DÉCIMA PRIMERA.- CESIÓN O TRANSMISIÓN PROHIBIDAS. EL PROMITENTE COMPRADOR </strong>
                no podrá ceder, enajenar o transmitir bajo cualquier título los derechos que derivan de este contrato, salvo que
                cuente con autorización expresa y previa a la transmisión por parte de <strong>EL PROMITENTE VENDEDOR. </strong>
                El  incumplimiento a lo aquí pactado, será motivo de rescisión del contrato, independientemente de que
                <strong>EL PROMITENTE VENDEDOR</strong> no reconocerá a ningún cesionario como adquirente de <strong>EL LOTE</strong>.
            </p>

            <p>
                En todo caso, para que <strong>EL PROMITENTE VENDEDOR</strong> apruebe la cesión el promitente comprador deberá de
                demostrar que está al corriente de todas las obligaciones fiscales y legales que recaigan sobre el
                inmueble o que deriven del presente contrato.
            </p>

            <p>
                <strong>DÉCIMA SEGUNDA-RESCISIÓN.- </strong>Este contrato podrá ser rescindido de pleno derecho y sin necesidad de declaración
                judicial en caso de incumplimiento a lo pactado en este contrato.
                En forma enunciativa y no limitativa, se mencionan algunas causales en que puede incurrir el comprador: a).-
                No cubrir cualquiera de los pagos pactados en el inciso b) de la cláusula cuarta; b).- No obtener la
                totalidad del crédito para el pago de la cantidad establecida en el inciso
                a) de la cláusula cuarta, en el plazo de 45 días contados a partir de la fecha de formalización del terreno; c).- No
                entregar en forma oportuna la documentación a que se compromete en términos de la cláusula tercera.
            </p>

            <p>
                La falta de pago puntual por parte de <strong>EL PROMITENTE COMPRADOR</strong> faculta a <strong>EL
                    PROMITENTE VENDEDOR</strong> al cobro de intereses calculados
                a razón del 5% mensual por cada día de retraso y calculados sobre la cantidad adeudada.
                El cobro de intereses por parte de <strong>EL PROMITENTE VENDEDOR</strong>, no implica una renuncia al
                derecho a ejercer la recisión de pleno derecho y sin necesidad de declaración judicial que en este mismo
                contrato se prevé.
                <br>
                @if ($contratoPromesa[0]->precio_venta != $contratoPromesa[0]->credito_neto)
            </p>

            <p>
                @endif
                En caso de rescisión del presente contrato, por causa imputable a <strong>EL PROMITENTE COMPRADOR, EL
                    PROMITENTE VENDEDOR</strong> deberá reintegrar
                el importe recibido hasta la fecha por parte de <strong>EL PROMITENTE COMPRADOR</strong>, descontándose la
                cantidad de $ 25,000.00 (Veinticinco Mil Pesos 00/100 Moneda Nacional),
                como pena convencional por los daños y perjuicios ocasionados.
            </p>

            <p>
                <strong>DÉCIMA TERCERA. RESERVA DE DOMINIO.- </strong>
                Esta compraventa se pacta en la modalidad de reserva de dominio, de conformidad con el artículo 2143 del
                Código Civil del Estado, por lo que <strong>CONCRETANIA</strong> se reserva el dominio del inmueble vendido
                hasta que su precio haya sido cubierto y se realice la escritura de compraventa definitiva ante notario público
            </p>

            <br>
            <p>
                <strong>DÉCIMA CUARTA. DOMICILIOS.- </strong>
                Las partes señalan como sus respectivos domicilios para recibir toda clase de notificaciones que deban hacérseles
                en relación con este contrato, aun las personales en caso de juicio, los siguientes:
            </p>

            <br>
            <p>
                <strong>EL PROMITENTE VENDEDOR: CONCRETANIA, S. A. DE C. V. MANUEL GUTIERREZ NAJERA 180
                    Colonia TEQUISQUIAPAM. San Luis Potosí, San Luis Potosí.</strong>
            </p>

            <p>
                <strong>EL PROMITENTE COMPRADOR:
                    {{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}}.
                    {{mb_strtoupper($contratoPromesa[0]->direccion)}} Colonia {{mb_strtoupper($contratoPromesa[0]->colonia)}}.
                    {{$contratoPromesa[0]->ciudad}}, {{$contratoPromesa[0]->estado}}
                </strong>
            </p>

            <p>
                Por lo que toda comunicación se deberá entregar de una parte a la otra, por escrito en los domicilios
                ahí señalados. No obstante lo anterior, Las partes podrán aceptar y darle efectos jurídicos a las
                notificaciones por correo electrónico, siempre y cuando  acuerden por escrito las direcciones de
                correo electrónico específicas en las que podrán intercambiar correspondencia.  En todo caso, el
                emisor del mensaje de datos Srequerirá el acuse de recibo, pues de no contar con éste, la comunicación
                no surtirá efectos. Además, en los mensajes de datos por correo electrónico la información completa
                deberá estar desplegada dentro del mismo correo, sin el uso de vínculos o de archivos adjuntos.
                En caso de que por el tamaño de la información sea necesario el uso de vínculos o archivos adjuntos,
                el acuse de recibo deberá señalar que se pudo acceder a ese  vínculo o  archivo.
            </p>


        @endif

        <p>
            Enteradas las partes, del alcance contenido y fuerza legal del presente instrumento, lo firman de
            conformidad en la ciudad de SAN LUIS POTOSÍ, SAN LUIS POTOSÍ,
            en tres ejemplares de un mismo tenor y para un solo efecto, a los
            <strong>{{ $contratoPromesa[0]->fecha }}</strong>
        </p>



        <br>


        <div class="table3">
            <div class="table-row">
                <div colspan="2" class="table-cell3"><b>LA PARTE VENDEDORA </div>
                <div class="table-cell3"></div>
                <div colspan="2" class="table-cell3"><b>LA PARTE COMPRADORA</div>
            </div>
            <div class="table-row">
                <div colspan="5" class="table-cell3"> <br> <br> </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell3">_________________________________</div>
                <div style="width: 8%;" class="table-cell3"></div>
                <div colspan="2" class="table-cell3">_________________________________</div>
            </div>
            <div class="table-row">
                @if ($contratoPromesa[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    <div colspan="2" class="table-cell3">GRUPO CONSTRUCTOR CUMBRES<br>ING. ALEJANDRO F. PEREZ
                        ESPINOSA<br>ING. DAVID CALVILLO MARTINEZ</div>
                @else
                    <div colspan="2" class="table-cell3">CONCRETANIA<br>ING. DAVID CALVILLO MARTINEZ</div>
                @endif
                <div style="width: 8%;" class="table-cell3"></div>
                <div colspan="2" class="table-cell3">{{ mb_strtoupper($contratoPromesa[0]->nombre) }}
                    {{ mb_strtoupper($contratoPromesa[0]->apellidos) }}</div>
            </div>
            @if ($contratoPromesa[0]->coacreditado == 1)
                <div class="table-row">
                    <div colspan="2" class="table-cell3"></div>
                    <div style="width: 8%;" class="table-cell3"></div>
                    <div colspan="2" class="table-cell3">_________________________________</div>
                </div>
                <div class="table-row">
                    <div colspan="5" class="table-cell3"> <br> </div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell3"></div>
                    <div style="width: 8%;" class="table-cell3"></div>
                    <div colspan="2" class="table-cell3">{{ mb_strtoupper($contratoPromesa[0]->nombre_coa) }}
                        {{ mb_strtoupper($contratoPromesa[0]->apellidos_coa) }}</div>
                </div>
            @endif
        </div>


    </div>

</body>

</html>
