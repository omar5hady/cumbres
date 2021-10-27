<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTRATO DE PROMESA DE COMPRAVENTA DE DEPARTAMENTO</title>
</head>

<style>

body{
 font-size: 11pt;
 text-align: justify;
}

@page{
    margin: 70px;
    margin-bottom :120px;
    margin-right: 90px;
    margin-left: 90px;
}

.table-cell3 { display: table-cell; padding: 0em; font-size: 10pt; }
.table3 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row; }
</style>

    <body>

        <div>

            <p>
                CONTRATO DE PROMESA DE COMPRAVENTA SUJETO A CONDICION SUSPENSIVA, QUE CELEBRAN, POR UNA PARTE, 
                LA SOCIEDAD MERCANTIL DENOMINADA <strong>GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V.</strong>, REPRESENTADA EN ESTE ACTO POR EL ING. ALEJANDRO F. PEREZ ESPINOSA, 
                A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, SE LE DENOMINARA COMO <strong>CUMBRES</strong>; 
                ASÍ COMO LA DIVERSA PERSONA MORAL DENOMINADA <strong>CONCRETANIA, S. A. DE. C.V</strong>,  REPRESENTADA EN ESTE ACTO POR EL ING. DAVID CALVILLO MARTINEZ, 
                A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, SE LE DENOMINARA COMO <strong>CONCRETANIA </strong>Y A QUIENES EN 
                FORMA CONJUNTA SE LES DESGINARÁ COMO <strong>LOS PROMITENTES VENDEDORES</strong>, 
                Y POR LA OTRA PARTE, POR SU PROPIO DERECHO, <strong>EL SR(A). {{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}}  
                    @if($contratoPromesa[0]->coacreditado == 1)y {{mb_strtoupper($contratoPromesa[0]->nombre_coa)}} {{mb_strtoupper($contratoPromesa[0]->apellidos_coa)}}@endif</strong>, A QUIEN EN LO SUCESIVO 
                Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE LE DENOMINARÁ COMO <strong>EL PROMITENTE COMPRADOR</strong>, AL TENOR DE LAS SIGUIENTES DECLARACIONES 
                Y CLÁUSULAS. 
            </p>

            <h4 align="center">DECLARACIONES</h4>

            <p>I.- Declara <strong>CUMBRES</strong>, por conducto de su representante:</p>

            <p>
                a) Que es una sociedad mercantil constituida de acuerdo a la legislación de la República Mexicana, mediante Escritura Pública número tres,
                del tomo cuarenta y siete, otorgada ante la fe del Licenciado Leopoldo de la Garza Marroquín, Notario Público número 33, 
                con ejercicio en esta ciudad, con fecha ocho de diciembre de mil novecientos noventa y nueve, y cuyo primer testimonio obra inscrito en el 
                Registro Público de la Propiedad y de Comercio, de esta ciudad, bajo la inscripción número tres del folio mercantil y 
                folio de muebles número setenta, desde el día diecinueve de enero del dos mil.
            </p>

            <p>
                b) Que su representante, el Ing. Alejandro Francisco Pérez Espinosa, 
                cuenta con las facultades necesarias para la celebración del presente contrato, mismas que a la fecha no le han sido restringidas, 
                ni revocadas de forma alguna. 
            </p>

            <p>
                c) Que es único y legítimo propietario del Lote de terreno ubicado en la calle {{$contratoPromesa[0]->direccionProyecto}}
                del Fraccionamiento <strong>{{mb_strtoupper($contratoPromesa[0]->proyecto)}}</strong>, 
                en esta ciudad de {{mb_strtoupper($contratoPromesa[0]->ciudad_proy)}}, {{mb_strtoupper($contratoPromesa[0]->estado_proy)}}, 
                para todos los efectos del presente contrato se le denominará como <strong>EL LOTE</strong>. 
            </p>

            <p>
                d) Que sobre <strong>EL LOTE</strong> descrito anteriormente tiene autorizado un edificio en régimen en condominio vertical, que es conocido comercialmente 
                como <strong>{{mb_strtoupper($contratoPromesa[0]->etapa)}}</strong>, cuyas características se encuentran debidamente especificadas en la escritura número 
                26420 del tomo 1318 del Protocolo de la Notaría Pública número 19 de esta ciudad, a cargo del licenciado Alfredo Noyola Robles, 
                al que se le identificará en el presente contrato como <strong>EL RÉGIMEN</strong>.
            </p>

            <p>
                e)	Que para la construcción y edificación de viviendas tiene concertado un acuerdo con <strong>CONCRETANIA</strong>, 
                quien cuenta con los elementos, práctica y servicios de empleados que son necesarios para desarrollar un edificio de 
                departamentos en régimen de condominio vertical, cuyas características se encuentran debidamente especificadas en el 
                anexo “A” del presente contrato, el que debidamente firmado por las partes forma parte integrante del mismo.
            </p>

            <p>
                f)	Que forma parte de EL RÉGIMEN el departamento identificado con el número <strong>interior {{$contratoPromesa[0]->num_lote}}</strong>, con las medidas y 
                colindancias establecidas en el mismo anexo “A” de este contrato, en lo sucesivo <strong>“LA VIVIENDA”</strong> y 
                que incluye la propiedad del cajón de estacionamiento número <strong>{{$contratoPromesa[0]->num_lote}}</strong>.
            </p>

            <p>
                g)  Que es su deseo celebrar el presente contrato preparatorio con <strong>EL PROMINENTE COMPRADOR</strong> a efecto de obligarse a celebrar el 
                contrato definitivo de compraventa respecto del porcentaje pro indiviso del suelo que es propiedad común del régimen en condominio, en el 
                entendido de que ese concepto se refiere a una fracción ideal del terreno sobre el que se construye el edificio en condominio, sin que 
                ello implique la facultad de hacer modificaciones en la parte que corresponde a la unidad exclusiva de los departamentos y/o 
                instalaciones que ocupan la planta baja.
            </p>
            <br>

            <p>II.- Declara <strong>CONCRETANIA</strong> por conducto de su representante:</p>

            <p>
                a) Que es una sociedad mercantil constituida de acuerdo a la legislación de la República Mexicana, mediante Escritura Pública número seiscientos 
                sesenta y cuatro, del volumen veintiuno, otorgada ante la fe del Licenciado Octaviano Gómez y González, Notario Público número Cuatro, 
                con ejercicio en esta ciudad, con fecha veinticinco de julio de dos mil dieciocho, y cuyo primer testimonio obra inscrito en el Registro Público de la 
                Propiedad y de Comercio, de esta ciudad, bajo el Folio Mercantil Eléctronico N-2018073682, desde el día siete de septiembre de dos mil dieciocho.
            </p>

            <p>
                b) Que su representante, el ing. David Calvillo Martínez, cuentan con las facultades necesarias para la celebración del presente contrato, 
                mismas que a la fecha no le han sido restringidas, ni revocadas de forma alguna.  
            </p>

            <p>
                c)	Que cuenta con los elementos, práctica y servicios de empleados que son necesarios para desarrollar un edificio de 
                departamentos en régimen de condominio vertical, cuyas características se encuentran debidamente especificadas en el 
                anexo “B” del presente contrato, el que debidamente firmado por las partes formará parte integrante del mismo y que 
                por lo mismo celebró un acuerdo con <strong>CUMBRES</strong> para construir con su patrimonio el edificio en régimen en condominio y 
                enajenar las áreas privativas y las de uso común, a excepción del suelo, que es propiedad de este último. 
            </p>

            <p>
                d) Que es propietario del departamento identificado con el número <strong>interior {{$contratoPromesa[0]->num_lote}}</strong>, 
                con las medidas y colindancias establecidas en el mismo anexo “A” de este contrato, en lo sucesivo <strong>“LA VIVIENDA”</strong> 
                y que incluye la propiedad del cajón de estacionamiento <strong>{{$contratoPromesa[0]->num_lote}}</strong>.
            </p>

            <p>III.- Declara <strong>EL PROMITENTE COMPRADOR</strong> por su propio derecho:</p>

            <p>
                a) Que es una persona física, mayor de edad, con plena capacidad para contratar y obligarse.
            </p>

            <p>
                b)	Que conoce perfectamente la ubicación, superficie, medidas y colindancias, así como las características generales y 
                especificaciones técnicas con base en las cuáles se llevará a cabo la construcción de <strong>LA VIVIENDA</strong>, 
                por lo que está conforme, y desea celebrar el presente contrato preparatorio con <strong>CUMBRES y CONCRETANIA</strong>, 
                a efecto de obligarse a celebrar el contrato definitivo de compraventa, por medio del cual 
                adquirirá la propiedad de <strong>LA VIVIENDA</strong>.
            </p>

            <p>
                c) Que le fue explicado que <strong>LA VIVIENDA</strong> se encuentra dentro de un régimen de propiedad en 
                condominio vertical y que dicha vivienda quedará sujeta a las disposiciones establecidas en el Régimen de Condominio 
                y el reglamento de condóminos correspondiente, el cual ya se le hizo de su conocimiento.
            </p>

            <p>
                d) Que además de los derechos y obligaciones que adquirirá conforme <strong>AL RÉGIMEN</strong> y el reglamento de condóminos, 
                conoce las concernientes a la copropiedad y medianería  que tendrá con los vecinos de los departamentos con los que 
                colinda, tanto en forma horizontal, en el mismo piso del edificio, como en forma vertical, con los que colindan en 
                la parte inferior y superior de <strong>LA VIVIENDA</strong>.
            </p>

            <p>
                e) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>LA VIVIENDA</strong>, una vez que haya sido designada 
                beneficiaria de un crédito hipotecario otorgado por <strong>{{mb_strtoupper($contratoPromesa[0]->institucion)}} 
                    @if($contratoPromesa[0]->infonavit > 0) e INFONAVIT @elseif($contratoPromesa[0]->fovisste > 0) y FOVISSTE @endif </strong>, 
                mismo que en lo sucesivo y para los efectos del presente contrato será denominado como <strong>Hipotecario</strong>.  
                Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con historial crediticio 
                satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue un crédito por parte de 
                institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
            </p>

            <p>
                f) Que es su deseo celebrar el presente contrato con <strong>LOS PROMITENTES VENDEDORES</strong> con el objeto de obligarse a 
                celebrar un contrato de compraventa respecto de <strong>LA VIVIENDA</strong>. 
            </p>

            <p>Conformes las partes con las declaraciones que anteceden, las cuales forman parte integrante del presente contrato, acuerdan otorgar las siguientes: </p>

            <p align="center">C L A U S U L A S</p>

            <p>
                <strong>PRIMERA.-</strong> Sujeto a la condición que se establece en la cláusula segunda del presente contrato, 
                <strong>CONCRETANIA</strong> se obliga a celebrar con el carácter de promitente  vendedora, un contrato de compraventa respecto 
                de <strong>LA VIVIENDA</strong>  y las áreas comunes, a excepción del suelo,  con <strong>EL PROMITENTE COMPRADOR</strong>, y a su vez, 
                <strong>EL PROMITENTE COMPRADOR</strong>, se obliga a celebrar, con el carácter de comprador, el contrato de 
                compraventa a que esta cláusula se refiere.
            </p>

            <p>
                Por su parte, <strong>CUMBRES</strong> se obliga a celebrar con el carácter de promitente vendedora, un contrato de 
                compraventa respecto del <strong>{{$contratoPromesa[0]->indivisos}}% </strong> pro indiviso del suelo sobre el que está construido el 
                EDIFICIO EN CONDOMINIO,  con <strong>EL PROMITENTE COMPRADOR</strong>, y a su vez, <strong>EL PROMITENTE COMPRADOR</strong>, 
                se obliga a celebrar, con el carácter de comprador, el contrato de compraventa a que esta cláusula se refiere, 
                en el entendido de que ese concepto se refiere a una fracción ideal del terreno sobre el que se construye el 
                edificio en condominio, sin que ello implique la facultad de hacer modificaciones en la parte que 
                corresponde a la unidad exclusiva de los departamentos y/o instalaciones que ocupan la planta baja.
            </p>

            <br>
            <p>
                <strong>SEGUNDA.- </strong> El contrato definitivo de compraventa que las partes se han obligado a celebrar, 
                está sujeto a la condición suspensiva consistente en que <strong>{{mb_strtoupper($contratoPromesa[0]->institucion)}} 
                    @if($contratoPromesa[0]->infonavit > 0) e INFONAVIT @elseif($contratoPromesa[0]->fovisste > 0) y FOVISSTE @endif </strong>,  
                otorgue al <strong>PROMINENTE COMPRADOR</strong>  un crédito con garantía hipotecaria hasta por la cantidad 
                necesaria para cubrir el precio total de la operación de compraventa pactado en la cláusula 
                cuarta, inciso a) y que dicho precio se aplique o libere, en pago directo a <strong>CONCRETANIA</strong>, de tal forma 
                que se encuentre liquidado dentro del plazo de 45 días naturales contados a partir de la fecha de terminación de la 
                construcción de <strong>LA VIVIENDA</strong>. En razón de lo anterior, las partes están de acuerdo para que en caso de que no 
                sea autorizado y liberado para pago, el crédito de referencia dentro de los 45 días naturales siguientes a la fecha de terminación 
                de la obra que se ejecuta sobre <strong>EL LOTE</strong>, sin necesidad de declaración judicial alguna, se tendrá por rescindido 
                de pleno derecho el actual contrato de promesa de compraventa, deslindando desde este momento 
                <strong>EL PROMITENTE COMPRADOR</strong> a <strong>CONCRETANIA</strong> de cualquier responsabilidad civil, penal o de cualquier otra 
                índole legal, en torno al bien inmueble motivo de la presente contratación.
            </p>

            <p>
                En el caso de que dicho crédito no fuere autorizado o liberado para pago, dentro del plazo de 45 días naturales contados a 
                partir de la terminación de la construcción de <strong>LA VIVIENDA, CONCRETANIA</strong>, reembolsará a 
                <strong>EL PROMITENTE COMPRADOR</strong>, en un término de 90 días naturales contados a partir de la fecha de la terminación de la casa habitación, 
                la o las cantidades que hasta esa fecha le haya(n) sido pagada(s) por <strong>EL PROMITENTE COMPRADOR</strong>, menos la 
                cantidad de $ 10,000.00 (Diez Mil Pesos 00/100 Moneda Nacional), ya que esta última cantidad será utilizada para el pago del 
                estudio del mismo crédito y costo de avalúo que exige la Institución Financiera y restando también 
                la cantidad establecida en la cláusula sexta de este contrato; sin que exista obligación alguna por parte de 
                <strong>CONCRETANIA</strong> de cubrir ninguna cantidad extra por concepto de Daños, perjuicios, o indemnización legal de ninguna especie. 
                En caso de no cumplirse la condición suspensiva a que se encuentra sujeto el presente contrato, las partes están de acuerdo 
                en que <strong>LOS PROMITENTES VENDEDORES</strong> podrá enajenar <strong>LA VIVIENDA</strong> a favor de diversos compradores, o bien, 
                darle el uso o destino que crea más conveniente, no existiendo en ello conducta tipificada en la ley penal como ilícito de fraude, 
                ya que las partes están de común acuerdo con la condición suspensiva y sus efectos previstos en la actual cláusula, 
                por lo que no existe dolo o engaño en tal manifestación de voluntad.
            </p>

            <br>
            <p>
                <strong>TERCERA.- EL PROMITENTE COMPRADOR </strong>se obliga a integrar toda la documentación necesaria requerida por la institución 
                crediticia que pudiera otorgarle los recursos para la adquisición de <strong>LA VIVIENDA</strong>, y a entregarla a la propia institución 
                bancaria en un plazo no mayor a 7 días naturales contados a partir de la firma del presente contrato para que tenga acceso 
                al crédito mencionado en la cláusula anterior, ya que será utilizada para integrar el expediente necesario para la obtención del crédito.
            </p>

            <p>
                <strong>CONCRETANIA</strong> podrá ofrecer asesoría e incluso, coadyuvar con <strong>EL PROMITENTE COMPRADOR</strong> para la gestión 
                del crédito y la presentación de documentos, sin que esa circunstancia releva a este último de ser el responsable de realizar esos trámites.
            </p>
            <p>
                <strong>CUARTA.- </strong>El precio total que convienen las partes, será motivo de la operación de compraventa definitiva, 
                es la cantidad que de <strong>${{strtoupper($contratoPromesa[0]->precioVentaLetra)}}</strong>, 
                de los que la cantidad de ${{strtoupper($contratoPromesa[0]->valorTerrenoLetra)}} equivalente al {{$contratoPromesa[0]->porcentaje_terreno}}% 
                del precio correspondiente al pro indiviso del suelo. En tanto que la cantidad 
                de ${{strtoupper($contratoPromesa[0]->valorConstruccionLetra)}} equivalente al {{100-$contratoPromesa[0]->porcentaje_terreno}}% 
                del precio le corresponde al valor de la vivienda. 
            </p>

            <p>
                Ese precio lo deberá pagar <strong>EL PROMITENTE COMPRADOR </strong>
                a <strong>CONCRETANIA</strong>  en los términos establecidos en la cláusula de la siguiente manera:
                a).La cantidad de 
                @if($contratoPromesa[0]->precio_venta >= $contratoPromesa[0]->credito_neto || $contratoPromesa[0]->precio_venta == $contratoPromesa[0]->credito_neto )
                    <strong>${{strtoupper($contratoPromesa[0]->montoTotalCreditoLetra)}},</strong> 
                @elseif( $contratoPromesa[0]->precio_venta < $contratoPromesa[0]->credito_neto )
                    <strong>${{strtoupper($contratoPromesa[0]->precioVentaLetra)}},</strong>
                @endif
                mediante el crédito que le otorgara 
                <strong>{{mb_strtoupper($contratoPromesa[0]->institucion)}}</strong> 
                @if($contratoPromesa[0]->infonavit > 0) 
                    y la cantidad de <strong>${{strtoupper($contratoPromesa[0]->infonavitLetra)}}</strong> que le otorga INFONAVIT 
                @elseif($contratoPromesa[0]->fovisste > 0) 
                    y la cantidad de <strong>${{strtoupper($contratoPromesa[0]->fovissteLetra)}}</strong> que le otorga FOVISSTE 
                @endif,
                misma que deberá ser liquidada dentro de los 45 días naturales siguientes a la 
                conclusión de la construcción de LA VIVIENDA. 
                @if($contratoPromesa[0]->precio_venta != $contratoPromesa[0]->credito_neto && $contratoPromesa[0]->enganche_total >= 10000) 
                b).El resto, mediante 
                    <strong>{{$pagos[0]->totalDePagos}}</strong>pagos, 
                    @for($i=0; $i < count($pagos); $i++) el <strong>{{$pagos[$i]->numeros}}</strong> por la cantidad de 
                        <strong>${{strtoupper($pagos[$i]->montoPagoLetra)}},</strong>
                        que será liquidado a más tardar el día <strong>{{$pagos[$i]->fecha_pago}}</strong>
                    @endfor, respectivamente.
                @elseif($contratoPromesa[0]->enganche_total < 10000 && $contratoPromesa[0]->enganche_total > 0 && $contratoPromesa[0]->enganche_total != $contratoPromesa[0]->avaluo_cliente)
                    
                    b).El resto, mediante <strong>{{$pagos[0]->totalDePagos}}</strong>pagos por la cantidad de 
                    <strong>${{$contratoPromesa[0]->engancheTotalLetra}}</strong> que será liquidado a más tardar el día 
                    <strong>{{$pagos[0]->fecha_pago}}</strong>, respectivamente.
                @elseif($contratoPromesa[0]->enganche_total <=0 )
                    <br>
                @endif
                <br>
            </p>

            <p>
                El pago hecho a <strong>CONCRETANIA</strong>, incluye el porcentaje pro indiviso del suelo que corresponde enajenar a <strong>CUMBRES</strong>, por lo que 
                <strong>CONCRETANIA</strong> se obliga a enterar a <strong>CUMBRES</strong>  la cantidad que le corresponda, una vez recibido el pago.
            </p>

            <p>
                Hasta en tanto no se encuentre totalmente liquidado el precio total de <strong>LA VIVIENDA</strong>, las cantidades que reciba 
                <strong>CONCRETANIA</strong>, serán entregadas por <strong>EL PROMITENTE COMPRADOR</strong>, a  título de depósito, por lo que 
                en caso de que el presente contrato se rescindiera, las mismas deberán ser reembolsadas a <strong>EL PROMITENTE COMPRADOR</strong>, 
                en los términos pactados en las cláusulas segunda y sexta del presente contrato.
            </p>

            <p>
                Asimismo, el precio pactado con antelación, se sujeta a la condición de que el pago total del precio de <strong>LA VIVIENDA</strong>, 
                se verifique a más tardar dentro de los 45 días naturales siguientes a la fecha de terminación de la construcción de 
                <strong>LA VIVIENDA</strong>, en el entendido además, de que transcurrida dicha fecha, sin la celebración del contrato definitivo de compraventa, 
                el presente contrato se rescindirá en forma automática.
            </p>

            <br>

            <p>
                <strong>QUINTA.- EL PROMITENTE COMPRADOR</strong> señala estar de acuerdo con: A). Las características de la vivienda; 
                como lo son ubicación dentro del edificio en condominio, los departamentos colindantes en forma vertical y horizontal, 
                así como los acabados. B). La superficie de construcción que es de <strong>{{$contratoPromesa[0]->construccion}} M2</strong>. C). La fecha de entrega de 
                <strong>LA VIVIENDA</strong> a <strong>EL PROMITENTE COMPRADOR</strong>, se realizara 15 días hábiles posteriores al  momento que 
                quede liberado el precio total de <strong>LA VIVIENDA</strong>. D).- Sus obligaciones de medianería con los departamentos colindantes.  
                Por lo que <strong>EL PROMINENTE COMPRADOR</strong> no se reserva ningún derecho de ejercitar posteriormente sobre este particular, 
                ya que <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong>, se apegan a sus necesidades.
            </p>
            <br>

            <p>
                <strong>SEXTA.- </strong> Este contrato podrá ser rescindido de pleno derecho y sin necesidad de declaración judicial en caso 
                de incumplimiento a lo pactado en este contrato. En forma enunciativa y no limitativa, se mencionan algunas causales en que puede 
                incurrir el comprador: a).- No cubrir cualquiera de los pagos pactados en el inciso b de la cláusula cuarta; b).- No obtener la totalidad 
                del crédito para el pago de la cantidad establecida en el inciso a de la cláusula cuarta, en el plazo de 45 días contados 
                a partir de la terminación de la vivienda; c).- No entregar en forma oportuna la documentación a que se compromete en términos de la cláusula tercera.
            </p>

            <p>
                La falta de pago puntual por parte de <strong>EL PROMITENTE COMPRADOR</strong> faculta a <strong>CONCRETANIA</strong> al cobro de intereses 
                calculados a razón del 5% mensual por cada día de retraso y calculados sobre la cantidad adeudada. El cobro de intereses por parte de 
                <strong>CONCRETANIA</strong>, no implica una renuncia al derecho a ejercer la recisión de pleno derecho y sin necesidad de declaración 
                judicial que en este mismo contrato se prevé. 
            </p>

            <p>
                En caso de rescisión del presente contrato, por causa imputable a <strong>EL PROMITENTE COMPRADOR, CONCRETANIA</strong> deberá 
                reintegrar el importe recibido hasta la fecha por parte de <strong>EL PROMITENTE COMPRADOR</strong>, descontándose la cantidad de 
                $ 25,000.00 (Veinticinco Mil Pesos 00/100 Moneda Nacional), como pena convencional por los daños y perjuicios ocasionados. 
                El importe se reintegrara hasta que <strong>CONCRETANIA</strong> recupere la inversión realizada en la construcción.
            </p>

            <p>
                <strong>EL PROMITENTE COMPRADOR</strong> manifiesta su expresa conformidad y señala que entiende cabalmente el alcance de lo que aquí se detalla. 
            </p>

            <br>
            <p>
                <strong>SEPTIMA.-</strong> Todos los gastos, derechos, impuestos y honorarios que se causen 
                con motivo de este contrato y la celebración del contrato definitivo en Escritura Publica 
                serán cubiertos por <strong>EL PROMITENTE COMPRADOR</strong>, excepto el Impuesto sobre la 
                Renta el cuál será a cargo de <strong>LOS PROMITENTES VENDEDORES.</strong> 
            </p>

            <br>
            <p>
                <strong>OCTAVA.-</strong> Todas las comunicaciones que las partes deban o deseen hacerse en relación con este 
                Contrato se harán por escrito, señalando las partes como sus respectivos domicilios, 
                para recibir toda clase de notificaciones que deban hacérseles, aun las personales 
                en caso de juicio, las siguientes: 
            </p>


            <p>
                <strong>CUMBRES:</strong> MANUEL GUTIERREZ NAJERA 190 Colonia TEQUISQUIAPAM San Luis Potosí, San Luis Potosí 
            </p>

            <p>
                <strong>CONCRETANIA:</strong> MANUEL GUTIERREZ NAJERA 180 Colonia TEQUISQUIAPAM San Luis Potosí, San Luis Potosí 
            </p>

            <p>
                <strong>EL PROMITENTE COMPRADOR:</strong> {{mb_strtoupper($contratoPromesa[0]->direccion)}} 
                Colonia {{mb_strtoupper($contratoPromesa[0]->colonia)}} {{mb_strtoupper($contratoPromesa[0]->ciudad)}}, {{mb_strtoupper($contratoPromesa[0]->estado)}}
            </p>

            <br>
            <p>
                <strong>NOVENA.-</strong> Para todo lo relacionado con la interpretación, cumplimiento y ejecución del presente Contrato, 
                las partes se someten a la jurisdicción y competencia de los tribunales de esta ciudad de San Luis Potosí, San Luis Potosí, 
                renunciando expresamente a cualquier otro fuero que pudiera corresponderles en razón de sus domicilios presentes o futuros o por cualquier otro motivo. 
            </p>

            <p>
                Enteradas las partes, del alcance contenido y fuerza legal del presente instrumento, lo firman de conformidad en la ciudad de SAN LUIS POTOSÍ, SAN LUIS POTOSÍ, 
                en tres ejemplares de un mismo tenor y para un solo efecto, a los <strong>{{$contratoPromesa[0]->fecha}}</strong> 
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
                            <div colspan="2" class="table-cell3">GRUPO CONSTRUCTOR CUMBRES, S.A. de C.V.<br> REPRESENTADA POR EL <br> ING. ALEJANDRO F. PEREZ ESPINOSA</div>
                            <div style="width: 8%;" class="table-cell3"></div>
                            <div colspan="2" class="table-cell3">CONCRETANIA, S.A. de C.V.<br> REPRESENTADA POR EL <br> ING. DAVID CALVILLO MARTÍNEZ</div>
                            {{-- <div colspan="2" class="table-cell3">{{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}}</div> --}}
                        </div>
                        {{-- @if($contratoPromesa[0]->coacreditado == 1)
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

                        @if($contratoPromesa[0]->coacreditado == 0)
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
                                    <div style="text-align: center;" class="table-cell3">_________________________________________</div>
                                    <div colspan="2" class="table-cell3"></div>
                                </div>
                                <div class="table-row">
                                    <div colspan="1" class="table-cell3"></div>
                                    <div colspan="3" style="text-align: center;" class="table-cell3">{{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}}</div>
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
                                    <div colspan="2" class="table-cell3">{{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}}</div>
                                    <div style="width: 8%;" class="table-cell3"></div>
                                    <div colspan="2" class="table-cell3">{{mb_strtoupper($contratoPromesa[0]->nombre_coa)}} {{mb_strtoupper($contratoPromesa[0]->apellidos_coa)}}</div>
                                </div>
                            </div>
                        @endif
                        
                    </div>
                    




        </div>
        
    </body>
</html>