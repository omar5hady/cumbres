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
        font-size: 11pt;
        text-align: justify;
    }

    @page {
        margin: 70px;
        margin-bottom: 120px;
        margin-right: 90px;
        margin-left: 90px;
    }

    .table-cell {
        display: table-cell;
        padding: 0em;
        font-size: 10pt;
    }

    .table {
        display: table;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table-row {
        display: table-row;
    }
    .cuadrado{
        border: 1.5px solid rgb(0, 0, 0);
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
            CONTRATO DE PROMESA DE COMPRAVENTA DE BIEN INMUEBLE DESTINADO A CASA HABITACIÓN QUE CELEBRAN, POR UNA PARTE LA SOCIEDAD MERCANTIL
            DENOMINADA <strong>GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V.</strong>, REPRESENTADA EN ESTE ACTO POR EL SR. ING. ALEJANDRO F. PEREZ ESPINOSA
            A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, SE LE DENOMINARÁ COMO <strong>CUMBRES;</strong> LA DIVERSA SOCIEDAD DENOMINADA
            <strong>CONCRETANIA, S. A. DE. C.V</strong>,
            REPRESENTADA EN ESTE ACTO POR EL SR. ING. DAVID CALVILLO MARTINEZ A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO,
            SE LE DENOMINARA COMO <strong>CONCRETANIA</strong> Y A AMBAS PERSONAS MORALES CUANDO SE LES DESIGNE CONJUNTAMENTE SERA COMO
            <strong>LOS PROMITENTES VENDEDORES</strong> Y POR LA OTRA PARTE, POR SU PROPIO DERECHO
            <strong>EL(A) SR(A).
                {{ mb_strtoupper($contrato->nombre) }} {{ mb_strtoupper($contrato->apellidos) }}
                @if ($contrato->coacreditado == 1)
                    y {{ mb_strtoupper($contrato->nombre_coa) }}
                    {{ mb_strtoupper($contrato->apellidos_coa) }}
                @endif
            </strong>
            A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE LE DENOMINARÁ COMO <strong>EL PROMITENTE COMPRADOR</strong>,
            AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.
        </p>

        <h4 align="center">DECLARACIONES</h4>

        <p>I.- Declara <strong>CUMBRES</strong>, por conducto de su representante:</p>

        <p>
            a)	Que es una sociedad mercantil constituida en escritura pública número 3, 47 de fecha 8 de diciembre de 1999, otorgada
            ante el Notario número 33 del primer distrito judicial del estado de San Luis Potosí,
            Lic. Leopoldo de la Garza Marroquín, inscrita en el Registro Público de la Propiedad y del Comercio en San Luis Potosí,
            bajo el folio mercantil número 3 y folio de muebles número 70, que su domicilio fiscal se encuentra en Manuel Gutiérrez Nájera 190.
            Colonia Tequisquiapan, San Luis Potosí, San Luis Potosí y su Registro Federal de Contribuyentes es GCC000106QS6.
        </p>

        <p>
            b)	Que las facultades de su representante legal constan en la escritura pública número 3, 47 de fecha 8 de diciembre de 1999,
            otorgada ante el Notario número 33 del primer distrito judicial de San Luis Potosí, Lic. Leopoldo de la Garza Marroquín,
            inscrita en el Registro Público de la Propiedad y del Comercio en San Luis Potosí, bajo el folio mercantil 31456*1.
        </p>

        <p>
            c)	Que su objeto social es, la ejecución, administración, construcción, promoción, comercialización y arrendamiento
            de desarrollos inmobiliarios, comerciales y habitacionales dentro del territorio nacional y en el extranjero, así como la celebración de los
            contratos mercantiles necesarios para el cumplimiento de  su objeto.
        </p>

        <p>
            d)	Que es única y legitima propietaria del inmueble materia de este contrato, descrito a detalle en el “Anexo A”,
            que firmado por las partes, es integrante de este instrumento y se localiza en la siguiente ubicación;
        </p>
        <br>

        <p>
            Calle <u>{{mb_strtoupper($contrato->calle_lote)}}</u> <br>
            Lote o área privativa <u>{{$contrato->num_lote}}</u>  <br>
            Manzana o condominio <u>{{mb_strtoupper($contrato->manzana)}}</u>  <br>
            No oficial exterior <u>{{$contrato->num_oficial}}</u>  <br>
            @if($contrato->interior != NULL)
                No oficial interior <u>{{$contrato->interior}}</u>  <br>
            @endif
            Fraccionamiento <u>{{mb_strtoupper($contrato->proyecto)}}</u> <br>
        </p>

        <p>
            Y acredita la propiedad con la escritura pública numero {{ $contrato->num_escritura }} de fecha {{$contrato->date_escritura}}
            otorgada ante la fe del Notario publico número {{$contrato->num_notario}} del distrito de {{$contrato->distrito_notario}},
            con inscripción en el Registro Publico de la Propiedad bajo el folio real No.{{$contrato->folio_registro}},
            lo cual ha sido debidamente exhibido y explicado a EL PROMITENTE COMPRADOR y además se encuentra a su
            disposición en el  domicilio mercantil
            ubicado en {{$contrato->calle_proy}}, colonia {{$contrato->colonia_proy}} de la Ciudad de {{$contrato->ciudad_proy}}, {{$contrato->edo_proy}}.
        </p>

        <br>

        <p>II.- Declara <strong>CONCRETANIA</strong> por medio de su representante legal:</p>

        <p>
            a)	Que es una sociedad mercantil constituida en escritura pública número 764, 21 de fecha 25 de julio del 2018, otorgada
            ante el Notario número 4 del primer distrito judicial del estado de San Luis Potosí,
            Lic. Octaviano Gómez y Gómez, inscrita en el Registro Público de la Propiedad y del Comercio en San Luis Potosí,
            bajo el folio mercantil N-2018073682, que su domicilio fiscal se encuentra en Manuel Gutiérrez Nájera 180.
            Colonia Tequisquiapan, San Luis Potosí, San Luis Potosí y su Registro Federal de Contribuyentes es CON180725REA.
            .
        </p>

        <p>
            b)	Que su representante, cuenta con las facultades necesarias para la celebración del presente contrato,
                mismas que a la fecha no le han sido restringidas, ni revocadas de forma alguna.
        </p>

        <p>
            c)	Que su objeto social es la ejecución, administración, construcción, promoción, comercialización y arrendamiento de
            desarrollos inmobiliarios, comerciales y habitacionales; así como la celebración de los contratos mercantiles que sean
            necesarios para el cumplimiento de su objeto.
        </p>
        <br>

        <p>III.- Declaran conjuntamente <strong>LOS PROMITENTES VENDEDORES:</strong></p>

        <p>
            a)	Que es su voluntad celebrar el presente contrato por virtud del cual se establecen los términos y condiciones
            bajo las que se celebrará el contrato de compraventa por el que <strong>“LOS PROMITENTES VENDEDORES”</strong> venderán y
            <strong>“EL PROMITENTE COMPRADOR”</strong> comprará, el inmueble cuya superficie, ubicación, acabados, características,
            especificaciones técnicas, de seguridad, materiales utilizados, se encuentran descritas en el citado “Anexo A”.
        </p>

        <p>
            b)	Que con fecha 13 de marzo de 2020, celebraron un convenio de Alianza Estratégica, que tiene por objeto llevar
            a cabo desarrollos inmobiliarios de una forma operativa, administrativa y comercial más eficiente y
            con mayor rentabilidad, en donde CUMBRES desarrollara y vendera lotes urbanizados y CONCRETANIA construirá y
            venderá la edificación de las casas habitación o áreas comerciales  que edifique sobre los lotes que
            urbanice CUMBRES y con base en ese convenio es que comparecen en forma conjunta a celebrar este contrato.
        </p>

        <p>
            c)	Que el inmueble objeto de la compraventa y que se integra por el terreno propiedad de CUMBRES y
            lo edificado sobre él, propiedad de CONCRETANIA cuenta con lo descrito en el “Anexo A” que
            firmado por <strong>“LAS PARTES”</strong> es integrante de este instrumento, como aparece en el título de
            propiedad relacionado en dicho “Anexo A”, y que cuenta con los planos estructurales, arquitectónicos y
            de instalaciones (o en su defecto indicar que se cuenta con el dictamen de las condiciones estructurales),
            así como con las autorizaciones, licencias y permisos expedidos por las autoridades competentes para la
            construcción, seguridad, uso de suelo, y demás relativas al Inmueble, mismas que han sido exhibidas
            a <strong>“EL PROMITENTE COMPRADOR”</strong> y se encuentran a su disposición en
            el domicilio de <strong>“LOS PROMITENTES VENDEDORES”.<strong> De igual forma, el inmueble cuenta
            con las especificaciones técnicas, de seguridad, materiales utilizados,
            servicios básicos y demás características que se indican en el “Anexo A”. La propiedad se acredita en términos
            del Instrumento Notarial, el cual está a su disposición en el domicilio ubicado en	Manuel Gutiérrez Nájera 190.
            Colonia Tequisquiapan, San Luis Potosí, San Luis Potosí.
        </p>

        <p>
            d)	Que, con respecto a los planos estructurales, arquitectónicos y de instalaciones:
        </p>

        <p>
            <ul class="ul-custom">
                <li><template class="cuadrado">&nbsp;&nbsp;</template> <strong>SI</strong> cuenta con ellos, mismos que le han sido exhibidos
                    y explicados a <strong>“EL PROMITENTE COMPRADOR” </strong>
                    y se encuentran nuevamente a su disposición para consulta en el domicilio de <strong>“LOS PROMITENTENTES VENDEDORES”.</strong></li>
                     <br><br>
                 <li><template class="cuadrado">&nbsp;&nbsp; </template> NO cuenta con ellos, sin embargo, cuenta con el Dictamen de las Condiciones
                    Estructurales, mismo que le ha sido exhibido
                    y explicado a <strong>“EL PROMITENTE COMPRADOR”</strong> y se encuentra nuevamente a su disposición para consulta en
                    el domicilio de <strong>“LOS PROMITENTENTES VENDEDORES”.</strong> </li>
                     <br><br>
                 <li><template class="cuadrado">&nbsp;&nbsp;</template> NO cuenta con ellos ni con el dictamen de las condiciones estructurales, en razón de que
                     , sin embargo, dicha documentación estará puesta a su disposición a partir del	. </li>

             </ul>
        </p>

        <p>
            e)	Que cuenta con las autorizaciones, licencias y permisos expedidos por las autoridades competentes para la construcción,
            seguridad, uso de suelo, y demás relativas al Inmueble y demás documentos que se indican en el “Anexo B”,
            los cuales le han sido exhibidos y explicados a <strong>“EL PROMITENTE COMPRADOR”</strong> y se encuentran nuevamente a su disposición
            para consulta en el domicilio de <strong>“LOS PROMITENTES VENDEDORES”.</strong>
        </p>

        <p>
            f)	Que al momento de la escrituración que formalice el contrato de compraventa del inmueble destinado a vivienda,
            dicho inmueble debe estar libre de todo gravamen que afecte la propiedad de <strong>“EL PROMITENTE COMPRADOR”</strong> sobre el mismo.
        </p>

        <p>IV.- Declara conjuntamente <strong>“EL PROMITENTE COMPRADOR”:</strong></p>

        <p>
            a) Ser una persona física de nacionalidad <u>{{$contrato->nacionalidad}}</u> Que se identifica con cualquiera de los
            siguientes documentos, INE <u>{{$contrato->num_ine}}</u> o  Pasaporte No. <u>{{$contrato->num_pasaporte}}</u>,
            llamada como ha quedado escrito, haber nacido en <u>{{$contrato->lugar_nac}}</u>, el <u>{{$contrato->fecha_nac}},</u>
            estado civil <u>{{$contrato->edo_civil}}</u>, de ocupación <u>{{$contrato->puesto}},</u> con domicilio en
            <u>{{$contrato->direccion_cliente}}</u>	, con Registro Federal de Contribuyentes <u>{{$contrato->rfc}}-{{$contrato->homoclave}}</u>
            y con capacidad legal y económica para celebrar este contrato.
        </p>

        <p>
            b)	Que es derechohabiente del:
        </p>

        <p>
            <li><template class="cuadrado">
                &nbsp;
                    @if($contrato->info == 1)<strong>X</strong>
                &nbsp;</template> Instituto del Fondo Nacional de la Vivienda para los Trabajadores <strong>(INFONAVIT)</strong>. </li>
                 <br><br>
             <li><template class="cuadrado">
                &nbsp;
                    @if($contrato->isste == 1)<strong>X</strong>
                &nbsp; </template> Instituto de Seguridad y Servicios Sociales para los Trabajadores del Estado.</li>
                <br><br>
             <li><template class="cuadrado">
                &nbsp;

                &nbsp;</template>Otro _________________________________ </li>
        </p>




        <p>
            a) Que es su deseo celebrar el presente contrato preparatorio con <strong>EL PROMITENTE COMPRADOR</strong> a
            efecto de obligarse a celebrar el contrato
            definitivo de compraventa respecto del terreno antes mencionado y la casa a construir en el mismo
            identificados como <strong> VIVIENDA</strong>.
        </p>

        <p>
            b) Que con fecha 13 de marzo de 2020, celebraron un convenio de <strong>Alianza Estratégica,</strong> que
            tiene por objeto llevar a cabo
            desarrollos inmobiliarios de una forma operativa, administrativa y comercial más eficiente y con mayor
            rentabilidad, en donde
            <strong>CUMBRES</strong> desarrollará y venderá los lotes urbanizados y <strong>CONCRETANIA</strong>
            construirá y venderá la
            edificación de las casas habitación o áreas comerciales que edifique sobre los lotes que urbanice
            <strong>CUMBRES</strong> y con
            base en ese convenio es que comparecen en forma conjunta a celebrar este contrato.
        </p>

        <br>
        <p>IV.- Declara <strong>EL PROMITENTE COMPRADOR</strong> por su propio derecho:</p>

        <p>
            a) Que es una persona física, mayor de edad, con plena capacidad para contratar y obligarse.
        </p>

        <p>
            b) Que conoce perfectamente la ubicación, superficie, medidas y colindancias de <strong>EL LOTE</strong>,
            así como las características generales y especificaciones
            técnicas con base en las cuáles se llevará a cabo la construcción de <strong>LA VIVIENDA</strong>, por lo
            que está conforme, y desea celebrar el presente contrato
            preparatorio con <strong>LOS PROMITENTES COMPRADORES</strong>, a efecto de obligarse a celebrar el contrato
            definitivo de compraventa, por medio del cual adquirirá
            la propiedad de <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong>.
        </p>

        @if ($contratoPromesa[0]->regimen_condom == 1)
            <p>
                c) Que le fue explicado que <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong> se encuentran dentro
                de un régimen de propiedad en condominio y que dicha vivienda quedará sujeta a las disposiciones
                establecidas en el Régimen de Condominio y el reglamento de condóminos correspondiente.
            </p>

            <p>
                d) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>EL
                    LOTE</strong> y <strong>LA VIVIENDA</strong>, una vez que haya sido designada
                beneficiaria de un crédito hipotecario otorgado por
                <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }}
                    @if ($contratoPromesa[0]->infonavit > 0)
                        e INFONAVIT
                    @elseif($contratoPromesa[0]->fovisste > 0)
                        y FOVISSTE
                    @endif
                </strong>,
                mismo que en lo sucesivo y para los efectos del presente contrato será denominado como
                <strong>Hipotecario</strong>.
                Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con historial crediticio
                satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue
                un crédito por parte de
                institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
            </p>

            <p>
                e) Que es su deseo celebrar el presente contrato con <strong>LOS PROMITENTES VENDEDORES</strong> con el
                objeto de obligarse a
                celebrar un contrato de compraventa respecto de <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong>.
            </p>
        @else
            <p>
                c) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>EL
                    LOTE</strong> y <strong>LA VIVIENDA</strong>, una vez que haya sido designada
                beneficiaria de un crédito hipotecario otorgado por
                <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }}
                    @if ($contratoPromesa[0]->infonavit > 0)
                        e INFONAVIT
                    @elseif($contratoPromesa[0]->fovisste > 0)
                        y FOVISSTE
                    @endif
                </strong>,
                mismo que en lo sucesivo y para los efectos del presente contrato será denominado como
                <strong>Hipotecario</strong>.
                Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con historial crediticio
                satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue
                un crédito por parte de
                institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
            </p>

            <p>
                d) Que es su deseo celebrar el presente contrato con <strong>LOS PROMITENTES VENDEDORES</strong> con el
                objeto de obligarse a
                celebrar un contrato de compraventa respecto de <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong>.
            </p>

        @endif

        <p>Conformes las partes con las declaraciones que anteceden, las cuales forman parte integrante del presente
            contrato, acuerdan otorgar las siguientes: </p>

        <p align="center">C L A U S U L A S</p>

        <p>
            <strong>PRIMERA.-</strong> Sujeto a la condición que se establece en la cláusula segunda del presente
            contrato, <strong>CUMBRES</strong> se obliga a celebrar con el carácter
            de vendedora, un contrato de compraventa respecto de <strong>EL LOTE</strong> descrito en la declaración I,
            inciso c), en tanto que <strong>CONCRETANIA</strong> se obliga a celebrar con el
            mismo carácter el contrato definitivo de compraventa respecto a <strong>LA VIVIENDA</strong> que se
            construirá sobre el mismo lote, con <strong>EL PROMITENTE COMPRADOR</strong>, y a su
            vez, <strong>EL PROMITENTE COMPRADOR</strong>, se obliga a celebrar, con el carácter de comprador, el
            contrato de compraventa a que esta cláusula se refiere, en el entendido
            de que ambas ventas podrán plasmarse en el mismo contrato por formar <strong>LA VIVIENDA</strong> una
            accesión de <strong>EL LOTE</strong>.
        </p>

        <br>
        <p>
            <strong>SEGUNDA.- </strong> <strong>EL PROMITENTE COMPRADOR</strong> señala estar de acuerdo con: A). Las
            características de la urbanización del terreno.
            B). La superficie de construcción que es de <strong>{{ $contratoPromesa[0]->construccion }}m2.</strong> C).
            La fecha de entrega de <strong>LA VIVIENDA</strong> a <strong>EL PROMITENTE COMPRADOR</strong>,
            se realizara quince días hábiles posteriores a la firma de escrituras. Por lo que <strong>EL PROMINENTE
                COMPRADOR</strong> no sé reserva
            ningún derecho de ejercitar posteriormente sobre este particular, ya que <strong>EL LOTE</strong> y
            <strong>LA VIVIENDA</strong>, se apegan a sus necesidades.
        </p>

        <br>
        <p>
            <strong>TERCERA.- </strong>El precio total que será motivo de la operación de compraventa definitiva, es la
            suma
            que de <strong>${{ strtoupper($contratoPromesa[0]->precioVentaLetra) }}</strong>,
            de los que la cantidad de ${{ strtoupper($contratoPromesa[0]->valorTerrenoLetra) }} equivalente al
            {{ $contratoPromesa[0]->porcentaje_terreno }}%
            del precio, corresponde al valor de <strong>EL LOTE</strong>, en tanto que la cantidad
            de ${{ strtoupper($contratoPromesa[0]->valorConstruccionLetra) }} equivalente al
            {{ 100 - $contratoPromesa[0]->porcentaje_terreno }}%
            del precio le corresponde al valor de la vivienda.
        </p>

        <p>
            Ese precio lo deberá pagar <strong>EL PROMITENTE COMPRADOR </strong>
            a <strong>LOS PROMITENTES VENDEDORES</strong> de la siguiente manera:
            a).La cantidad de
            @if ($contratoPromesa[0]->precio_venta >= $contratoPromesa[0]->credito_neto ||
                $contratoPromesa[0]->precio_venta == $contratoPromesa[0]->credito_neto)
                <strong>${{ strtoupper($contratoPromesa[0]->montoTotalCreditoLetra) }},</strong>
            @elseif($contratoPromesa[0]->precio_venta < $contratoPromesa[0]->credito_neto)
                <strong>${{ strtoupper($contratoPromesa[0]->precioVentaLetra) }},</strong>
            @endif
            mediante el crédito que le otorgara
            <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }}</strong>
            @if ($contratoPromesa[0]->infonavit > 0)
                y la cantidad de <strong>${{ strtoupper($contratoPromesa[0]->infonavitLetra) }}</strong> que le otorga
                INFONAVIT
            @elseif($contratoPromesa[0]->fovisste > 0)
                y la cantidad de <strong>${{ strtoupper($contratoPromesa[0]->fovissteLetra) }}</strong> que le otorga
                FOVISSTE
            @endif,
            misma que deberá ser liquidada dentro de los 45 días naturales siguientes a la
            conclusión de la construcción de LA VIVIENDA.
            @if ($contratoPromesa[0]->precio_venta != $contratoPromesa[0]->credito_neto &&
                $contratoPromesa[0]->enganche_total >= 10000)
                b).El resto, mediante
                <strong>{{ $pagos[0]->totalDePagos }}</strong>pagos,
                @for ($i = 0; $i < count($pagos); $i++)
                    el <strong>{{ $pagos[$i]->numeros }}</strong> por la cantidad de
                    <strong>${{ strtoupper($pagos[$i]->montoPagoLetra) }},</strong>
                    que será liquidado a más tardar el día <strong>{{ $pagos[$i]->fecha_pago }}</strong>
                @endfor, respectivamente.
            @elseif($contratoPromesa[0]->enganche_total < 10000 &&
                $contratoPromesa[0]->enganche_total > 0 &&
                $contratoPromesa[0]->enganche_total != $contratoPromesa[0]->avaluo_cliente)
                b).El resto, mediante <strong>{{ $pagos[0]->totalDePagos }}</strong>pagos por la cantidad de
                <strong>${{ $contratoPromesa[0]->engancheTotalLetra }}</strong> que será liquidado a más tardar el día
                <strong>{{ $pagos[0]->fecha_pago }}</strong>, respectivamente.
            @elseif($contratoPromesa[0]->enganche_total <= 0)
                <br>
            @endif
        </p>

        <p>
            Cada una de esas parcialidades, incluyendo la que se efectúe con el crédito otorgado , deberá entregarse a
            <strong>CONCRETANIA</strong>,
            quien enterará la parte que corresponda a <strong>CUMBRES.</strong>
        </p>

        <p>
            Los pagos señalados en los incisos a) y b) de esta cláusula, se documentan con dos series de pagarés
            que en este acto suscribe <strong>EL PROMITENTE COMPRADOR,</strong>
            a favor de <strong>CONCRETANIA</strong>; documentos que estarán sujetos a la condición de que,
            de no cubrirse cualquiera de ellos, se darán por vencidos anticipadamente
            los restantes. En los pagarés se cubrirá la tasa moratoria pactada en la cláusula séptima de este contrato.
        </p>

        <br>
        <p>
            <strong>CUARTA.- </strong> El contrato definitivo de compraventa que las partes se han obligado a
            celebrar, está sujeto a la condición suspensiva consistente en que
            <strong>{{ mb_strtoupper($contratoPromesa[0]->institucion) }}
                @if ($contratoPromesa[0]->infonavit > 0)
                    @if ($contratoPromesa[0]->tipo_credito == 'BANJERCITO')
                        y BANJERCITO
                    @else
                        e INFONAVIT
                    @endif
                @elseif($contratoPromesa[0]->fovisste > 0)
                    y FOVISSTE
                @endif,
            </strong>
            otorgue a <strong>EL PROMITENTE COMPRADOR</strong>
            un crédito con garantía hipotecaria hasta por la cantidad necesaria para cubrir el precio total de la
            operación de compraventa pactado en la cláusula tercera y que dicho precio se aplique o libere,
            en pago directo a <strong>LOS PROMITENTES VENDEDORES</strong>, de tal forma que se
            encuentre liquidado dentro del plazo de 45 días naturales contados a partir de la fecha de terminación
            de la construcción de <strong>LA VIVIENDA</strong>. En razón de lo anterior, las partes están de acuerdo
            para que en
            caso de que no sea autorizado y liberado para pago, el crédito de referencia dentro de los 45 días
            naturales siguientes a la fecha de terminación de la obra que se ejecuta sobre <strong>EL LOTE</strong>, sin
            necesidad de declaración judicial alguna, se tendrá por rescindido de pleno derecho el actual contrato de
            promesa de compraventa, deslindando desde este momento <strong>EL PROMITENTE COMPRADOR</strong> a
            <strong>LOS PROMITENTES VENDEDORES</strong>
            de cualquier responsabilidad civil, penal o de cualquier otra índole legal, en torno al bien inmueble motivo
            de la presente contratación.
        </p>

        <p>
            En el caso de que dicho crédito no fuere autorizado o liberado para pago, dentro del plazo de 45 días
            naturales contados a partir de la terminación de la construcción de <strong>LA VIVIENDA</strong>, o que el
            monto
            otorgado en crédito por la institución bancaria fuera menor a la cantidad establecida en la cláusula
            tercera inciso a), con lo que se incumple la condición suspensiva, <strong>LOS PROMITENTES
                VENDEDORES</strong>,
            reembolsarán a <strong>EL PROMITENTE COMPRADOR</strong>, en la proporción que les corresponda y en un
            término de
            90 días naturales contados a partir de la fecha de la terminación de la casa habitación, la o
            las cantidades que hasta esa fecha le haya(n) sido pagada(s) por <strong>EL PROMITENTE COMPRADOR</strong>,
            menos
            la cantidad de $1,000.00 (Un Mil Pesos 00/100 M.N.), ya que esta última cantidad
            será utilizada para el pago del estudio del mismo crédito que exige la Institución Financiera y
            restando también la cantidad de
            @if ($contratoPromesa[0]->avaluo_cliente == 0)
                $8,000.00 (Ocho Mil Pesos 00/100 M.N),
            @else
                {{ $contratoPromesa[0]->avaluoLetra }}
            @endif
            que se aplicó al pago del avalúo que solicitó la
            institución bancaria, así como la pena convencional establecida en la cláusula sexta de este
            contrato; sin que exista obligación alguna por parte de <strong>LOS PROMITENTES VENDEDORES</strong> de
            cubrir ninguna
            cantidad extra por concepto de Daños, perjuicios, o indemnización legal de ninguna especie. En caso
            de no cumplirse la condición suspensiva a que se encuentra sujeto el presente contrato, las partes
            están de acuerdo en que <strong>LOS PROMITENTES VENDEDORES</strong> podrán enajenar <strong>EL LOTE</strong>
            y <strong>LA VIVIENDA</strong> a favor de
            diversos compradores, o bien, darle el uso o destino que crea más conveniente, no existiendo en ello
            conducta tipificada en la ley penal como ilícito de fraude, ya que las partes están de común acuerdo
            con la condición suspensiva y sus efectos previstos en la actual cláusula, por lo que no existe
            dolo o engaño en tal manifestación de voluntad.
        </p>

        <br>
        <p>
            <strong>QUINTA.- EL PROMITENTE COMPRADOR</strong> se obliga a integrar toda la
            documentación necesaria requerida por la institución crediticia que pudiera otorgarle
            los recursos para la adquisición de <strong>LA VIVIENDA</strong>, y a entregarla a la propia institución
            bancaria en un plazo no mayor a <strong>7</strong> días naturales contados a partir de la firma del
            presente contrato para que tenga acceso al crédito mencionado en la cláusula anterior,
            ya que será utilizada para integrar el expediente necesario para la obtención del crédito.
        </p>

        <p>
            <strong>LOS PROMITENTES VENDEDORES</strong> podrá ofrecer asesoría e incluso, coadyuvar con <strong>EL
                PROMITENTE COMPRADOR</strong>
            para la gestión del crédito y la presentación de documentos, sin que esa circunstancia releva a
            este último de ser el responsable de realizar esos trámites.
        </p>

        <br>
        <p>
            <strong>SEXTA.-</strong> Independientemente de que el incumplimiento de la condición suspensiva
            establecida en la cláusula cuarta, resuelve este contrato, también podrá ser rescindido
            de pleno derecho y sin necesidad de declaración judicial en caso de incumplimiento a lo
            pactado en su clausulado. En forma enunciativa y no limitativa, se mencionan algunas
            causales en que puede incurrir el comprador: 1).- No cubrir cualquiera de los pagos
            pactados adicionalmente al que deberá cubrirse mediante el crédito bancario, descritos
            en los incisos a) y b) de la cláusula tercera; 2).- No entregar en forma oportuna la
            documentación a que se compromete en términos de la cláusula quinta. Esta última
            causal podrá ejercerse aún antes de los 45 días señalados para el otorgamiento y entrega del crédito.
        </p>

        <p>
            En caso de rescisión del presente contrato, por causa imputable a <strong>EL PROMITENTE COMPRADOR,
                LOS PROMITENTES VENDEDORES</strong> deberán reintegrar
            el importe recibido hasta la fecha por parte de <strong>EL PROMITENTE COMPRADOR</strong>,
            descontándose la cantidad de $ 25,000.00 (Veinticinco Mil Pesos 00/100 Moneda Nacional),
            como pena convencional por los daños y perjuicios ocasionados. El importe se reintegrara hasta
            que <strong>LOS PROMITENTES VENDEDORES</strong> recuperen la inversión realizada
            en la construcción.
        </p>

        <br>
        <p>
            <strong>SÉPTIMA.-</strong> La falta de pago puntual por parte de <strong>EL PROMITENTE COMPRADOR </strong>
            faculta a <strong>LOS PROMITENTES VENDEDORES</strong> al cobro de intereses calculados a
            razón del 5% mensual por cada día de retraso y calculados sobre la cantidad
            adeudada. El cobro de intereses por parte de <strong>LOS PROMITENTES VENDEDORES</strong>,
            no implica una renuncia al derecho a ejercer la recisión de pleno derecho
            y sin necesidad de declaración judicial que en este mismo contrato se prevé.
        </p>

        <p>
            <strong>EL PROMITENTE COMPRADOR</strong> manifiesta su expresa conformidad y señala que entiende cabalmente
            el alcance de lo que aquí se detalla.
        </p>

        <br>
        <p>
            <strong>OCTAVA.-</strong> Todos los gastos, derechos, impuestos y honorarios que se causen
            con motivo de este contrato y la celebración del contrato definitivo en Escritura Publica
            serán cubiertos por <strong>EL PROMITENTE COMPRADOR</strong>, excepto el Impuesto sobre la
            Renta el cuál será a cargo de <strong>EL PROMITENTE VENDEDOR.</strong>
        </p>

        <br>
        <p>
            <strong>NOVENA.-</strong> Todas las comunicaciones que las partes deban o deseen hacerse en relación con
            este
            Contrato se harán por escrito, señalando las partes como sus respectivos domicilios,
            para recibir toda clase de notificaciones que deban hacérseles, aun las personales
            en caso de juicio, las siguientes:
        </p>


        <p>
            <strong>CUMBRES:</strong> MANUEL GUTIERREZ NAJERA 190 Colonia TEQUISQUIAPAM San Luis Potosí, San Luis Potosí
        </p>

        <p>
            <strong>CONCRETANIA:</strong> MANUEL GUTIERREZ NAJERA 180 Colonia TEQUISQUIAPAM San Luis Potosí, San Luis
            Potosí
        </p>

        <p>
            <strong>EL PROMITENTE COMPRADOR:</strong> {{ mb_strtoupper($contratoPromesa[0]->direccion) }} Colonia
            {{ mb_strtoupper($contratoPromesa[0]->colonia) }} {{ mb_strtoupper($contratoPromesa[0]->ciudad) }},
            {{ mb_strtoupper($contratoPromesa[0]->estado) }}
        </p>

        <br>
        <p>
            <strong>DECIMA.-</strong> Para todo lo relacionado con la interpretación, cumplimiento y ejecución del
            presente Contrato, las partes se someten a la jurisdicción y
            competencia de los tribunales de esta ciudad de San Luis Potosí, San Luis Potosí, renunciando expresamente a
            cualquier otro fuero que
            pudiera corresponderles en razón de sus domicilios presentes o futuros o por cualquier otro motivo.
        </p>

        <p>
            Enteradas las partes, del alcance contenido y fuerza legal del presente instrumento, lo firman de
            conformidad en la ciudad de SAN LUIS POTOSÍ, SAN LUIS POTOSÍ,
            en tres ejemplares de un mismo tenor y para un solo efecto, a los
            <strong>{{ $contratoPromesa[0]->fecha }}</strong>
        </p>

        <br>

        <p align="center"><b>LA PARTE VENDEDORA</p>


        <div class="table3">
            <div class="table-row">
                <div colspan="2" class="table-cell3"><b> </div>
                <div class="table-cell3"></div>
                <div colspan="2" class="table-cell3"><b></div>
            </div>
            <div class="table-row">
                <div colspan="5" class="table-cell3"> <br> <br> </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell3">____________________________________________</div>
                <div style="width: 8%;" class="table-cell3"></div>
                <div colspan="2" class="table-cell3">____________________________________________</div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell3">GRUPO CONSTRUCTOR CUMBRES, S.A. de C.V.<br> REPRESENTADA POR EL
                    <br> ING. ALEJANDRO F. PEREZ ESPINOSA</div>
                <div style="width: 8%;" class="table-cell3"></div>
                <div colspan="2" class="table-cell3">CONCRETANIA, S.A. de C.V.<br> REPRESENTADA POR EL <br> ING.
                    DAVID CALVILLO MARTÍNEZ</div>
                {{-- <div colspan="2" class="table-cell3">{{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}}</div> --}}
            </div>
            {{-- @if ($contratoPromesa[0]->coacreditado == 1)
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
                        <div colspan="2" class="table-cell3"></div>
                    </div>
                @endif --}}
        </div>
        <br>
        <br>
        <br>
        <br>
        <div>
            <p align="center"><b>LA PARTE COMPRADORA</p>

            @if ($contratoPromesa[0]->coacreditado == 0)
                <div class="table3">

                    <div class="table-row">
                        <div colspan="2" class="table-cell3"><b> </div>
                        <div class="table-cell3"></div>
                        <div colspan="2" class="table-cell3"><b></div>
                    </div>
                    <div class="table-row">
                        <div colspan="5" class="table-cell3"> <br> <br> </div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell3"></div>
                        <div style="text-align: center;" class="table-cell3">_________________________________________
                        </div>
                        <div colspan="2" class="table-cell3"></div>
                    </div>
                    <div class="table-row">
                        <div colspan="1" class="table-cell3"></div>
                        <div colspan="3" style="text-align: center;" class="table-cell3">
                            {{ mb_strtoupper($contratoPromesa[0]->nombre) }}
                            {{ mb_strtoupper($contratoPromesa[0]->apellidos) }}</div>
                        <div colspan="1" class="table-cell3"></div>
                    </div>
                </div>
            @else
                <div class="table3">

                    <div class="table-row">
                        <div colspan="2" class="table-cell3"><b> </div>
                        <div class="table-cell3"></div>
                        <div colspan="2" class="table-cell3"><b></div>
                    </div>
                    <div class="table-row">
                        <div colspan="5" class="table-cell3"> <br> <br> </div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell3">_________________________________________</div>
                        <div style="width: 8%;" class="table-cell3"></div>
                        <div colspan="2" class="table-cell3">_________________________________________</div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell3">{{ mb_strtoupper($contratoPromesa[0]->nombre) }}
                            {{ mb_strtoupper($contratoPromesa[0]->apellidos) }}</div>
                        <div style="width: 8%;" class="table-cell3"></div>
                        <div colspan="2" class="table-cell3">{{ mb_strtoupper($contratoPromesa[0]->nombre_coa) }}
                            {{ mb_strtoupper($contratoPromesa[0]->apellidos_coa) }}</div>
                    </div>
                </div>
            @endif

        </div>





    </div>

</body>

</html>
