<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato</title>
</head>

<style>

body{
 font-size: 11 pt;
 text-align: justify;
 font-family: Arial, sans-serif;
}

@page{
    margin: 85px;
    margin-right: 70px;
    margin-left: 70px;
}

.table-cell3 { display: table-cell; padding: 0em; font-size: 10 pt; }
.table3 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row; }
</style>

<body>
    <div>
        <p>
            CONTRATO DE ARRENDAMIENTO QUE CELEBRAN, POR UNA PARTE 
            @if($contrato->tipo_arrendador == 1)
                LA PERSONA MORAL DENOMINADA <strong>“GRUPO CONSTRUCTOR CUMBRES, S. A. DE C.V.”,</strong> 
                <strong>REPRESENTADA EN ESTE ACTO POR EL {{$representante}},</strong>
            @else
                EL <strong>SR. {{mb_strtoupper($contrato->nombre_arrendador)}},</strong>
            @endif
             A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ <strong>“EL ARRENDADOR”</strong>, Y POR LA OTRA PARTE 
            @if($contrato->tipo_arrendatario == 1)
                LA PERSONA MORAL DENOMINADA <strong>"{{mb_strtoupper($contrato->nombre_arrendatario)}}"</strong> 
                <strong>REPRESENTADA EN ESTE ACTO POR EL SR. {{$contrato->representante_arrendatario}},</strong>
            @else
                EL <strong>SR. {{mb_strtoupper($contrato->nombre_arrendatario)}},</strong>
            @endif
             A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ <strong>“EL ARRENDATARIO”</strong> Y POR OTRA PARTE, 
            @if($contrato->tipo_aval == 1)
                LA SOCIEDAD MERCANTIL DENOMINADA <strong>{{mb_strtoupper($contrato->nombre_aval)}},</strong> 
                POR CONDUCTO DE SU REPRESENTANTE LEGAL <strong>SR. {{mb_strtoupper($contrato->representante_aval)}}</strong> COMO DEUDOR SOLIDARIO, 
            @else
                EL <strong>SR. {{mb_strtoupper($contrato->nombre_aval)}},</strong>
            @endif
            Y A QUIEN EN LO SUCESIVO SE LE DESIGNARÁ COMO <strong>“EL FIADOR”</strong>. 
            LAS PARTES SE SUSCRIBEN DE CONFORMIDAD Y CON SUJECIÓN A LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.
        </p>
        
        <br>
        <p align="center">D E C L A R A C I O N E S:</p>
        <br>

        <p>
            I. Declara <strong>EL ARRENDADOR</strong>:
        </p>

        @if($contrato->tipo_arrendador == 1)
            <p style="margin-left: 0.15in; text-indent: -0.2in">
                1.	Que es una persona moral, constituida de conformidad con las leyes de la República Mexicana, 
                con domicilio Fiscal en Manuel Gutiérrez Nájera número 190 Colonia Tequisquiapan, C.P. 78230 en el 
                Municipio de San Luis Potosí, S.L.P. Y que su Registro Federal de Contribuyentes es GCC000106QS6.
            </p>
        @else
            <p style="margin-left: 0.15in; text-indent: -0.2in">
                1.	Que es una persona Física, de nacionalidad Mexicana, con la capacidad jurídica para celebrar el presente contrato.
            </p>
        @endif

        <p style="margin-left: 0.15in; text-indent: -0.2in">
            2.	Que es legítimo propietario y tiene la facultad para arrendar el inmueble que se encuentra ubicado en 
            {{$contrato->calle}} #{{$contrato->numero}} 
                @if($contrato->interior != NULL)
                    Int. {{$contrato->interior}}
                @endif
                {{$contrato->etapa}} Fraccionamiento {{$contrato->proyecto}} C.P. {{$contrato->cp_proyecto}}, 
            {{$contrato->ciudad_proyecto}}, {{$contrato->estado_proyecto}}. 
            A este predio, dentro de este contrato, se le designará como “EL INMUEBLE”. 
        </p>

        <p style="margin-left: 0.15in; text-indent: -0.2in">
            3.	Que no tiene conocimiento alguno de acuerdo con la información y documentación entregada por el  arrendatario, 
            sobre si el  arrendatario se encuentra o han estado involucrados, directa o indirectamente, en la comisión de delitos, 
            particularmente aquellos que establece la Ley Federal de Extinción de Dominio reglamentaria del Artículo veintidós 
            de la Constitución Política de los Estados Unidos Mexicanos, por lo que hasta donde es de su conocimiento el 
            arrendatario se dedican exclusivamente a la realización de actividades licitas, y actúa con absoluta buena fe 
            en la celebración de este contrato. 
        </p>

        <p style="margin-left: 0.15in; text-indent: -0.2in">
            4.	Que para los efectos del presente contrato señala como su domicilio legal el ubicado en 
            @if($contrato->tipo_arrendador == 1)
            Manuel Gutiérrez Nájera No. 190, Colonia Tequisquiapan, C.P. 78230,  de esta ciudad.
            @else
                {{$contrato->direccion_arrendador}}, Colonia {{$contrato->colonia_arrendador}}, 
                C.P. {{$contrato->cp_arrendador}},  de esta ciudad.
            @endif
        </p>

        <br>

        <p>
            II. Declara <strong>EL ARRENDATARIO</strong>:
        </p>

        @if($contrato->tipo_arrendatario == 1)
            <p style="margin-left: 0.15in; text-indent: -0.2in">
                1.	Que es una persona moral, constituida de conformidad con las leyes de la República Mexicana, 
                con domicilio Fiscal en {{$contrato->dir_arrendatario}} 
                Colonia {{$contrato->col_arrendatario}}, C.P. {{$contrato->cp_arrendatario}} en el 
                Municipio de {{$contrato->municipio_arrendatario}}, {{$contrato->estado_arrendatario}}. 
            </p>
        @else
            <p style="margin-left: 0.15in; text-indent: -0.2in">
                1.	Que es una persona Física, de nacionalidad Mexicana, con la capacidad jurídica para celebrar el presente contrato.
            </p>
        @endif

        <p style="margin-left: 0.15in; text-indent: -0.2in">
            2.	Que previo a la firma de este contrato, llenó una solicitud para ocupar EL INMUEBLE, en el cual ingresó datos 
            reales y faculta a EL ARRENDADOR a rescindir este contrato si descubre en cualquier momento una falsedad 
            en la información proporcionada en ese formato.
        </p>
        @if($contrato->tipo_arrendatario == 1)
            <p style="margin-left: 0.15in; text-indent: -0.2in">
                3.	Declara bajo protesta de decir verdad que los poderes otorgados no han sido revocados ni 
                restringidos a la fecha de firma de este contrato.
            </p>
            <p style="margin-left: 0.15in; text-indent: -0.2in">
                4.	Que acepta celebrar el presente contrato con EL ARRENDADOR con el objeto de tomar 
                en arrendamiento EL INMUEBLE, en los términos y condiciones que a continuación se establecerán.
            </p>
        @else
            <p style="margin-left: 0.15in; text-indent: -0.2in">
                3.	Que acepta celebrar el presente contrato con EL ARRENDADOR con el objeto de tomar 
                en arrendamiento EL INMUEBLE, en los términos y condiciones que a continuación se establecerán.
            </p>
        @endif
 
        <br>
        <p>III. Declaran Las Partes:</p>

        <p>
            Única. Que expuesto lo anterior, reconocen mutuamente la personalidad que ostentan y 
            acreditan estar de acuerdo con obligarse, conviniendo en celebrar el presente instrumento al tenor de las siguientes:
        </p>


        <br>
        <p align="center">C L Á U S U L A S:</p>
        <br>

        <p>
            <strong>PRIMERA Objeto.</strong> 
            @if($contrato->muebles == 0)
                EL ARRENDADOR otorga en arrendamiento a EL ARRENDATARIO EL INMUEBLE, mismo que está 
                descrito en la declaración I.3 de este Contrato. Por virtud de este contrato, EL ARRENDADOR se 
                compromete en respetar y, por ende, en no estorbar  la posesión derivada a EL ARRENDATARIO, 
                sin perjuicio de las acciones legales que pueda ejercer ante el incumplimiento de este acto jurídico. 
            @else
                EL ARRENDADOR otorga en arrendamiento a EL ARRENDATARIO EL INMUEBLE, mismo que está  descrito en la declaración I.3 
                de este Contrato, inmueble que  se RENTA AMUEBLADO, por lo que por este instrumento hace las veces de resguardo de las 
                cosas detalladas en el <strong>ANEXO UNO,</strong> el cual forma parte integrante de este contrato y <strong>EL ARRENDATARIO</strong> se hace responsable 
                del buen uso y cuidado de las mismas, respondiéndole AL ARRENDADOR por cualquier daño, desperfecto o sustracción de las 
                mismas del inmueble, a valor nuevo de reposición.  Por virtud de este contrato, <strong>EL ARRENDADOR</strong> se compromete en respetar 
                y, por ende, en no estorbar  la posesión derivada a EL ARRENDATARIO, sin perjuicio de las acciones legales que pueda 
                ejercer ante el incumplimiento de este acto jurídico
            @endif
        </p>

        <p>
            <strong>SEGUNDA.- Destino.</strong> EL ARRENDATARIO se obliga a  utilizar EL INMUEBLE, 
            única y exclusivamente para CASA HABITACION, por lo que tiene estrictamente prohibido destinarlo para un 
            uso distinto al que se prevé aquí. En caso de transgredir esta  prohibición, EL ARRENDADOR, una vez que tenga 
            conocimiento de esa circunstancia,  podrá exigir la rescisión del contrato y la entrega inmediata de EL INMUEBLE. 
        </p>

        <p>
            <strong>TERCERA.- Vigencia.</strong> La vigencia del presente contrato será de 
            {{$contrato->num_meses}} ( {{$contrato->cantMeses}}) meses, que comenzaran a computarse a partir del 
            {{$contrato->fecha_ini}} para terminar el {{$contrato->fecha_fin}}. 
        </p>

        
        <p>
            <strong>CUARTA.- Prórrogas y preferencia.</strong> La vigencia del presente contrato no se prorrogará tácitamente, 
            por lo que ambas partes renuncian a lo dispuesto en el artículo  2317 del Código Civil del Estado. 
            En caso de vencimiento del contrato, si EL ARRENDATARIO quiere continuar ocupando EL INMUEBLE, deberá avisarlo a 
            EL ARRENDADOR, por lo menos treinta días antes de tal evento a efecto de negociar un nuevo contrato, por lo que 
            sin la firma de éste, no será posible continuar en la posesión de EL INMUEBLE. <br>
        </p>

        <p>
            EL ARRENDATARIO también renuncia a lo dispuesto en el artículo 2315 del Código 
            en comento,  por lo que no podrá pedir la prórroga del contrato.
        </p>

        <p>
            EL ARRENDATARIO renuncia al derecho de preferencia que le otorga el artículo 2276 del 
            Código Civil del Estado, por lo que no podrá ejercerlo ni aún en el caso de que estuviera al corriente en el pago de rentas.
        </p>

        <p>
            Si llegado el vencimiento de este contrato, sin que se haya celebrado un nuevo contrato 
            entre las partes, o en el caso de que se de una causal de rescisión del contrato, si 
            EL ARRENDATARIO continúa en posesión de EL INMUEBLE, deberá pagar una renta equivalente a 
            la renta vigente al momento del vencimiento o de la causal de rescisión, aumentada en un 100% cien por ciento.
        </p>

        <p>
            <strong>QUINTA. Renta.</strong> EL ARRENDATARIO se obliga a pagar a EL ARRENDADOR por concepto de renta, la 
            cantidad de ${{$contrato->monto_renta}} incluido el pago de vigilancia y mantenimiento de las áreas 
            verdes del fraccionamiento. A la cual habrá que agregar el Impuesto al Valor Agregado en caso de ser procedente. 
            El pago se hará por meses adelantados.
        </p>

        <p>
            <strong>SEXTA. Lugar y tiempo de pago.</strong> La renta se pagará, sin necesidad de requerimiento previo, dentro de los 
            primeros cinco (5) días naturales después de los días primero de cada  mes en el domicilio de EL ARRENDADOR.
        </p>

        <p>
            Sin perjuicio de lo establecido en el párrafo anterior, EL ARRENDATARIO podrá pagar la renta, 
            dentro del mismo plazo mencionado, mediante depósito ó transferencia a alguna cuenta bancaria de EL ARRENDADOR, 
            siempre y cuando este último autorice a aquél a que se utilice esa modalidad de pago.
        </p>

        <p>
            EL ARRENDADOR podrá, si así lo considera, realizar servicio de cobranza a EL ARRENDATARIO, ya sea mediante mensajero a 
            domicilio ó mediante llamadas telefónicas u otro medio de comunicación, pero ese servicio en ningún modo implica 
            renuncia a lo dispuesto en el primer párrafo de esta cláusula. 
        </p>

        <p>
            La renta se pagará por meses adelantados dentro de los primeros cinco días de cada mes calendario, en esta ciudad y 
            en Moneda Nacional. y <strong>SE HARA EN EL DOMICILIO DEL  ARRENDADOR,</strong> ó por depósito bancario a la 
            cuenta <strong>{{$contrato->cuenta_arrendador}},</strong> con <strong>CLABE {{$contrato->clabe_arrendador}},</strong> 
            de {{$contrato->banco_arrendador}} a nombre de {{$contrato->nombre_arrendador}}, por lo que si EL ARRENDATARIO fallare en su obligación será enteramente su responsabilidad a no ser que se haya llegado a algún otro acuerdo.
        </p>

        <p>
            EL ARRENDATARIO no podrá retener la renta en ningún caso ni bajo ningún título judicial o extrajudicialmente, 
            ni por falta de composturas ni por reparaciones que el ARRENDADOR hiciera o deje de hacer en EL INMUEBLE, sino que 
            la pagará íntegramente en la fecha estipulada, llenando además las obligaciones que provienen de los artículos 
            2254, 2255, 2256 del Código Civil del Estado.
        </p>

        <p>
            <strong>SÉPTIMA. Interés moratorio.-</strong> En caso de no cubrir las rentas en el plazo estipulado, 
            EL ARRENDATARIO pagará a EL ARRENDADOR intereses moratorios a una tasa del 5 % (cinco por ciento) mensual sobre 
            el saldo vencido, mismos que se computaran en base diaria.
        </p>

        <p>
            Para obtener la cantidad a pagar por intereses, se dividirá la tasa entre treinta y se multiplicará por el 
            número de días de mora. La tasa que resulte se aplicará a la renta mensual. 
        </p>

        <p>
            El hecho de que EL ARRENDADOR reciba las rentas, con sus intereses, fuera del plazo establecido en la cláusula sexta, 
            no implica renuncia al plazo mencionado ni afecta el derecho que tiene éste para rescindir este contrato por la falta de pago de rentas.
        </p>

        <p>
            <strong>OCTAVA.- Subarrendamiento.-</strong> EL ARRENDATARIO no podrá subarrendar, traspasar, ceder total o parcialmente 
            ya sea a título gratuito u oneroso ni en general transmitir el uso de EL INMUEBLE ni los derechos de este contrato a 
            terceras personas; en caso de hacerlo EL ARRENDADOR podrá rescindir este contrato, quedando facultado  a exigir la entrega 
            inmediata de EL INMUEBLE AL ARRENDATARIO.
        </p>

        <p>
            <strong>NOVENA.- Conservación de EL INMUEBLE.</strong> El ARRENDATARIO acepta en que EL INMUEBLE, 
            objeto de este contrato, lo recibe en buen estado teniendo todos los servicios sanitarios al corriente, 
            aceptando lo ordenado por el Articulo 2273 del Código Civil del Estado, siendo de su exclusiva cuenta las 
            reparaciones que en dichas instalaciones sean necesarias. 
        </p>

        <p>
            Las instalaciones de luz, gas y agua, son propiedad de EL INMUEBLE y todo agregado quedará a beneficio del mismo. 
            Las instalaciones sanitarias se entregan completas, en servicio y en perfecto estado.
        </p>

        <p>
            Al terminar el Contrato de Arrendamiento, EL ARRENDATARIO entregará EL INMUEBLE en el mismo buen estado en que lo recibe, 
            debiendo cubrir por su cuenta cualquier desperfecto que sufra incluida las instalaciones. Por lo tanto, todos  
            los gastos de mantenimiento de todo tipo que requiera EL INMUEBLE para su conservación, deberán ser cubiertos por 
            EL ARRENDATARIO, sin que tenga éste derecho de exigir retribución a EL ARRENDADOR.
        </p>

        <p>
            Cualquier obra de mantenimiento que implique afectación al EL  INMUEBLE, como la reparación de 
            las instalaciones eléctricas, telefónicas, de agua potable o de gas; o bien la reparación de daños 
            en el repellado ó el revoque,  deberán ponerse en conocimiento de EL ARRENDADOR, quien deberá aprobar los 
            trabajos propuestos por EL ARRENDATARIO, teniendo el derecho de realizar por su cuenta esas reparaciones 
            si así lo estima conveniente.
        </p>

        <p>
            <strong>DECIMA.- Mejoras.-</strong> EL ARRENDATARIO no podrá  hacer ninguna mejora, ya sea útil, necesaria o de ornato, 
            en EL INMUEBLE sin consentimiento de EL ARRENDADOR otorgado por escrito. Para tal efecto, EL ARRENDATARIO deberá 
            entregar a EL ARRENDADOR un proyecto con las mejoras que se pretendan realizar y, en caso de darse la aprobación, 
            las obras deberán sujetarse estrictamente al proyecto aprobado.  Las mejoras que se hicieren, siempre serán con 
            cargo a EL ARRENDATARIO  y no podrá retirarlas salvo que así lo consienta por escrito el ARRENDADOR, a cuyo 
            efecto renuncia al derecho que le concede la segunda parte del Artículo 2270 del Código Civil, quedando dichas 
            mejoras en beneficio de la finca y sin que por ellas tenga derecho el ARRENDATARIO a pretender indemnización 
            alguna, por lo que el contenido de esta cláusula no autoriza al ARRENDATARIO a modificar en forma alguna 
            EL INMUEBLE sin contar previamente con el permiso por escrito del arrendador.
        </p>

        <p>
            <strong>DÉCIMA PRIMERA.- Daños y pérdida.</strong> EL ARRENDATARIO deberá responder por los daños  materiales o la pérdida de 
            EL INMUEBLE que acontezca por cualquier motivo, aún en el caso fortuito si EL ARRENDATARIO dio a EL INMUEBLE 
            un uso distinto al estipulado en este contrato. En esas condiciones, EL ARRENDATARIO deberá reponer a 
            EL ARRENDADOR, el valor comercial que tenga en el momento en que le sea reclamado. Esta obligación, comprende 
            la pérdida o menoscabo de EL INMUEBLE en términos de la Ley de Extinción de Dominio.
            EL ARRENDATARIO, también se obliga  a responder por cuenta propia la destrucción, pérdida, robo, daños, 
            deterioros o perjuicio que sufran el espacio arrendado o los bienes u objetos que introduzca al espacio arrendado.
        </p>

        <p>
            Para responder por las eventualidades señaladas en esta cláusula, EL ARRENDATARIO  podrá optar por contratar 
            un seguro con una compañía aseguradora legalmente constituida y comercialmente reconocida dentro del territorio 
            nacional, durante toda la vigencia del presente contrato, el cual deberá cubrir la responsabilidad  de 
            EL ARRENDATARIO por daños o pérdida que sobrevenga por cualquier causa, incluyendo las establecidas en la 
            Ley de Extinción de dominio y designando como beneficiario a EL ARRENDADOR. También podrá otorgar fianza 
            expedida por institución autorizada,  que cubra el valor de EL INMUEBLE.
        </p>

        <p>La cobertura de otros riesgos será opcional para EL ARRENDATARIO.</p>

        <p>
            EL ARRENDATARIO deberá entregar a EL ARRENDADOR copias de  pólizas contratadas dentro de los 30 (treinta) días naturales 
            siguientes a la celebración del presente contrato.
        </p>

        <p>
            En caso de no contratarse este seguro o la fianza o de proceder a la cancelación del mismo, 
            EL ARRENDADOR podrá rescindir este contrato de pleno derecho sin necesidad de declaración judicial y podrá exigir 
            la entrega inmediata del EL INMUEBLE.
        </p>

        <p>
            Independientemente de la contratación del Seguro o la póliza de fianza, EL ARRENDATARIO deberá asumir la responsabilidad 
            extracontractual, objetiva y subjetiva,  ante  EL ARRENDADOR, o ante TERCEROS, según sea el caso, 
            por cualquier daño originado en el interior de EL INMUEBLE, relevando a EL ARRENDADOR  de cualquier 
            responsabilidad de ese tipo ante terceros.
        </p>

        <p>
            DÉCIMA SEGUNDA.- DEPÓSITO. Para garantizar el cumplimiento de este contrato, entrega el ARRENDATARIO al ARRENDADOR 
            la cantidad de ${{$contrato->monto_renta}}. Por única vez, dicha cantidad 
            se ajustará cada ocasión que se modifique la renta, de manera que el depósito de garantía 
            equivaldrá siempre a UN MES de renta actualizada, misma que será entregada en efectivo, el cual 
            le será devuelto cuando desocupe EL INMUEBLE, siempre que no se deba nada por concepto de renta, servicios 
            de agua, luz, teléfono, o existan daños ocasionados al INMUEBLE arrendado, y de que haya  cumplido con todas 
            las obligaciones que este Contrato impone. Para los efectos de esta Cláusula, el ARRENDATARIO renuncia en su 
            caso al beneficio que otorga el Segundo Párrafo del Artículo 2279 del Código Civil.
        </p>

        <p>
            <strong>DÉCIMA TERCERA.- FIADOR.-</strong> Para seguridad y garantía de todo lo estipulado en el presente Contrato, 
            lo firma de mancomún y solidariamente con el ARRENDATARIO, EL FIADOR 
            @if($contrato->tipo_aval == 1)
                LA EMPRESA {{mb_strtoupper($contrato->nombre_aval)}}, 
                POR CONDUCTO DE SU REPRESENTANTE LEGAL SR. {{mb_strtoupper($contrato->representante_aval)}} 
            @else
                EL SR. {{mb_strtoupper($contrato->nombre_aval)}} 
            @endif
            señalando como domicilio: {{$contrato->dir_aval}} colonia {{$contrato->col_aval}} 
            en {{$contrato->municipio_aval}}, {{$contrato->estado_aval}} CP {{$contrato->cp_aval}},. 
            quien se constituye como FIADOR y principal pagador de todas las obligaciones contraídas por el 
            ARRENDATARIO, haciendo todas las renuncias que el ARRENDATARIO  tiene hechas y de los beneficios de 
            orden y excusión consignados en los Artículos 2650, 2675 Y 2676, así como los beneficios de extinción de la 
            fianza establecida en los Artículos 2318, 2676, 2677, 2678 y 2679 del Código Civil, no cesando la responsabilidad 
            de este, si no hasta cuando el ARRENDADOR de por recibido EL INMUEBLE y todo cuanto se le deba por virtud de este 
            Contrato, aun cuando el ARRENDADOR haya concedido prórroga o espera, subsistiendo la obligación del Fiador a pesar 
            de que no se le notifique y aunque dure mas tiempo del fijado en el Artículo 2308 del Código Civil, por lo que 
            igualmente renuncia a este Artículo obligándose a hacer entrega, si su Fiador no hiciere de lo que este haya 
            recibido en el inventario y reponer lo que faltara, pagando el costo de los desperfectos que por mal uso de 
            EL INMUEBLE fueren causados por el arrendatario.
        </p>

        <p>
            <strong>DÉCIMA CUARTA.- SERVICIOS.-</strong> 
            @if($contrato->servicios == 0)
                Cuando el ARRENDATARIO notifique que va a desocupar EL INMUEBLE o en el caso de vencimiento del contrato o su rescisión, 
                deberá comprobar que está al corriente en el pago por servicio de luz, agua, teléfono, televisión restringida, etc. 
                Obligándose a proporcionar el número de cuenta de cada servicio y copias de los últimos recibos pagados, así como la 
                cancelación de los contratos celebrados para proveerse de esos servicios.
            @else
                Están incluidos en el monto contratado los servicios de Luz, Agua, Gas, Internet, instalación y monitoreo de alarma, 
                topados mensualmente a los siguientes importes: 
                @for($i=0; $i < count($servicios); $i++)
                    @if(count($servicios) > 1)
                        @if($i == count($servicios)-1)
                            y {{$servicios[$i]}}.,
                        @else
                            {{$servicios[$i]}}, 
                        @endif
                    @else
                        {{$servicios[$i]}}.,
                    @endif
                @endfor
                sin incluir los gastos de 
                
                @for($i=0; $i < count($sinServicios); $i++)
                    @if(count($sinServicios) > 1)
                        @if($i == count($sinServicios)-1)
                            y {{$sinServicios[$i]}}.
                        @else
                            {{$sinServicios[$i]}}, 
                        @endif
                    @else
                        {{$sinServicios[$i]}}.
                    @endif
                @endfor
                
                Cuando el ARRENDATARIO notifique que va a desocupar EL INMUEBLE o en el caso de vencimiento del contrato o su rescisión, 
                deberá comprobar que está al corriente en el pago de servicios adicionales a los antes descritos. Obligándose a proporcionar 
                el número de cuenta de cada servicio y copias de los últimos recibos pagados, así como la cancelación de los contratos 
                celebrados para proveerse de esos servicios.
            @endif
        </p>

        <p>
            La negativa a entregar los recibos y comprobantes de pago de esos servicios, facultará a el ARRENDADOR a quedarse 
            con el depósito dado en garantía, independientemente de que con fecha posterior el ARRENDATARIO le muestre esos recibos.
        </p>

        <p>
            <strong>DÉCIMA QUINTA.- Modificaciones al contrato.</strong> El presente contrato sólo podrá ser modificado o adicionado por 
            acuerdo de las partes, mediante la firma del convenio modificatorio respectivo. Dichas modificaciones o 
            adiciones obligarán a las partes, a partir de la fecha de su firma.
        </p>

        <p>
            <strong>DÉCIMA SEXTA.- Causal de rescisión adicional.</strong> EL ARRENDATARIO, previo a la firma de este contrato llenó un 
            formato con la solicitud  para el arrendamiento de EL INMUEBLE. En ese sentido, independientemente de que 
            EL ARRENDADOR ya estuvo en condiciones de verificar la veracidad de  los datos contenidos en esa solicitud, 
            se establece la posibilidad de rescindir este contrato si EL ARRENDADOR descubre, con posterioridad a la 
            firma de este contrato,  que existen datos falsos en dicha solicitud.
        </p>

        <p>
            Convienen las partes contratantes que en caso de incumplimiento del clausulado del presente Contrato, 
            el arrendador podrá exigir una pena convencional equivalente a dos meses de renta.
        </p>

        <p>
            <strong>DÉCIMA SÉPTIMA.- Ley y jurisdicción.-</strong> Para la interpretación y cumplimiento del presente contrato, 
            así como para todo lo no previsto en el mismo, se aplicará supletoriamente todas las disposiciones en materia 
            de arrendamiento de inmuebles urbanos del Código Civil vigente en el Estado de San Luis Potosí. En caso de 
            controversia, las partes se someten expresamente a la jurisdicción de los tribunales competentes en la 
            ciudad de San Luis Potosí, S. L. P. 
        </p>

        <p>
            Leído que fue el presente instrumento, y enteradas las partes de su contenido y alcances, los firman por duplicado en 
            la ciudad de San Luis Potosí, S.L.P., a los {{$contrato->fecha_firma}}.
        </p>

        
        <br><br>

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
                <div colspan="2" class="table-cell3">__________________________________________</div>
                <div style="width: 10%;" class="table-cell3"></div>
                <div colspan="2" class="table-cell3">__________________________________________</div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell3">
                    <center>
                        @if($contrato->tipo_arrendador == 1)
                            GRUPO CONSTRUCTOR CUMBRES, S.A. de C.V.
                            <br> Representada en este acto por 
                            <br> {{mb_strtoupper($representante)}}
                        @else
                            {{mb_strtoupper($contrato->nombre_arrendador)}}
                        @endif
                    </center>
                    
                </div>
                <div style="width: 10%;" class="table-cell3"></div>
                <div colspan="2" class="table-cell3">
                    <center>
                        @if($contrato->tipo_arrendatario == 1)
                            {{mb_strtoupper($contrato->nombre_arrendatario)}}
                            <br> Representada en este acto por
                            <br> {{mb_strtoupper($contrato->representante_arrendatario)}}
                        @else
                            {{mb_strtoupper($contrato->nombre_arrendatario)}}
                        @endif
                    </center>
                </div>
            </div>
        </div>

        <br>
        <br>
        <br>
        <br>

        <div>
            <div class="table3">
                    
                <div class="table-row">
                    <div class="table-cell3"><b> </div>
                    <div colspan="2" class="table-cell3"></div>
                    <div class="table-cell3"><b></div>
                </div>
                <div class="table-row"> 
                    <div colspan="5" class="table-cell3"> <br> <br> </div>
                </div>
                <div class="table-row">
                    <div class="table-cell3"></div>
                    <div colspan="2" class="table-cell3">_________________________________________</div>
                    <div class="table-cell3"></div>
                </div>
                <div class="table-row">
                    <div class="table-cell3"></div>
                    <div colspan="2" class="table-cell3">
                        <center>
                            @if($contrato->tipo_aval == 1)
                                {{mb_strtoupper($contrato->nombre_aval)}}
                                <br> Representada en este acto por
                                <br> {{mb_strtoupper($contrato->representante_aval)}}
                            @else
                                {{mb_strtoupper($contrato->nombre_aval)}}
                            @endif
                        </center>
                    </div>
                    <div class="table-cell3"></div>
                </div>
            </div>
        </div>

        <br>
        <br>
        <br>
        <br>

        <div>
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
                    <div colspan="2" class="table-cell3">
                        <center>
                            TESTIGO
                            <br>{{mb_strtoupper($contrato->nombre)}}
                        </center>
                    </div>
                    <div style="width: 8%;" class="table-cell3"></div>
                    <div colspan="2" class="table-cell3">
                        <center>
                            TESTIGO
                            <br>SR. {{mb_strtoupper($testigo)}}</div>
                        </center>
                </div>
            </div>
        </div>

        
    </div>
</body>
</html>