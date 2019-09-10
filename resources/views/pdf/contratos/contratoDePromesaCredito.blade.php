<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTRATO DE PROMESA DE COMPRAVENTA SUJETO A CONDICION SUSPENSIVA</title>
</head>

<style>

body{
 font-size: 12.5pt;
 text-align: justify;
}

@page{
    margin: 65px;
    margin-right: 90px;
    margin-left: 90px;
}

.table-cell3 { display: table-cell; padding: 0em; font-size: 11pt; }
.table3 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row; }
</style>

<body>

<div>

<p>
CONTRATO DE PROMESA DE COMPRAVENTA SUJETO A CONDICION SUSPENSIVA, QUE CELEBRAN, POR UNA PARTE, 
LA SOCIEDAD MERCANTIL DENOMINADA <strong>GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V.</strong>, REPRESENTADA EN ESTE ACTO POR EL 
ING. ALEJANDRO F. PEREZ ESPINOSA O EL ING. DAVID CALVILLO MARTINEZ, A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, 
SE LE DENOMINARA COMO <strong>EL PROMITENTE VENDEDOR</strong>, Y POR LA OTRA PARTE, POR SU PROPIO DERECHO, <strong>EL SR(A). {{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}} y @if($contratoPromesa[0]->coacreditado == 1) {{mb_strtoupper($contratoPromesa[0]->nombre_coa)}} {{mb_strtoupper($contratoPromesa[0]->apellidos_coa)}} @endif</strong>, 
A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE LE DENOMINARÁ COMO EL PROMITENTE COMPRADOR, 
AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS. 
</p>

<h4 align="center">DECLARACIONES</h4>

<p>
I.- Declara <strong>EL PROMITENTE VENDEDOR</strong>, por conducto de su representante:
</p>

<p>
a) Que es una sociedad mercantil constituida de acuerdo a la legislación de la República Mexicana, mediante Escritura Pública número tres,
   del tomo cuarenta y siete, otorgada ante la fe del Licenciado Leopoldo de la Garza Marroquín, Notario Público numero 33, 
   con ejercicio en esta ciudad, con fecha ocho de diciembre de mil novecientos noventa y nueve, y cuyo primer testimonio obra inscrito en el 
   Registro Publico de la Propiedad y de Comercio, de esta ciudad, bajo la inscripción numero tres del folio mercantil y 
   folio de muebles número setenta, desde el día diecinueve de enero del dos mil.
</p>

<p>
b) Que su representante, el Ing. Alejandro Francisco Pérez Espinosa y el Ing. David Calvillo Martínez, 
   cuentan con las facultades necesarias para la celebración del presente contrato, mismas que a la fecha no le han sido restringidas, 
   ni revocadas de forma alguna. 
</p>

<p>
c) Que es única y legítimo propietario del Lote de terreno número <strong>{{$contratoPromesa[0]->num_lote}}</strong>, de la manzana <strong>{{mb_strtoupper($contratoPromesa[0]->manzana)}}</strong> del Fraccionamiento <strong>{{mb_strtoupper($contratoPromesa[0]->proyecto)}}</strong>, 
   en esta ciudad de SAN LUIS POTOSÍ, SAN LUIS POTOSÍ, cuya superficie es de <strong>{{$contratoPromesa[0]->superficie}} M2</strong>, mismo que en lo sucesivo y 
   para todos los efectos del presente contrato se le denominará como <strong>EL LOTE</strong>. 
</p>

<p>
d) Que dentro de su objeto social se incluye la construcción y edificación de casas y cuenta con los elementos, 
   práctica y servicios de empleados que son necesarios para construir en el Lote, una vivienda cuyas características 
   se encuentran debidamente especificadas en el anexo “A” del presente contrato, el que debidamente firmado 
   por las partes formar parte integrante del mismo. 
</p>

<p>
e) Que sobre <strong>EL LOTE</strong> descrito anteriormente construirá una casa habitación, cuyas características se encuentran debidamente especificadas 
   en el anexo “A” del presente contrato, a la que en conjunto con el mismo, en lo sucesivo se le identificará en el presente contrato 
   como <strong>LA VIVIENDA</strong>.
</p>

<p>
f) Que es su deseo celebrar el presente contrato preparatorio con EL PROMITENTE COMPRADOR a efecto de obligarse a 
   celebrar el contrato definitivo de compraventa respecto del terreno antes mencionado y la casa a construir 
   en el mismo identificados como <strong>LA VIVIENDA</strong>. 
</p>

<p>
II.- Declara <strong>EL PROMITENTE COMPRADOR</strong> por su propio derecho:
</p>

<p>
a) Que es una persona física, mayor de edad, con plena capacidad para contratar y obligarse.
</p>

<p>
b) Que conoce perfectamente la ubicación, superficie, medidas y colindancias, así como las características generales 
   y especificaciones técnicas con base en las cuáles se llevará a cabo la construcción de <strong>LA VIVIENDA</strong>, por lo que está conforme, 
   y desea celebrar el presente contrato preparatorio con <strong>EL PROMITENTE VENDEDOR</strong>, a efecto de obligarse a celebrar el 
   contrato definitivo de compraventa, por medio del cual adquirirá la propiedad de <strong>LA VIVIENDA</strong>. 
</p>

@if($contratoPromesa[0]->regimen_condom == 1)

    <p>
    c).- Que le fue explicado que <strong>LA VIVIENDA</strong> se encuentra dentro de un régimen de propiedad en condominio y que dicha vivienda 
        quedará sujeta a las disposiciones establecidas en el Régimen de Condominio  y el reglamento de condóminos correspondiente.
    </p>

    <p>
    d) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>LA VIVIENDA</strong>, una vez que haya sido designada 
    beneficiaria de un crédito hipotecario otorgado por <strong>{{mb_strtoupper($contratoPromesa[0]->institucion)}} @if($contratoPromesa[0]->infonavit > 0) e INFONAVIT @elseif($contratoPromesa[0]->fovisste > 0) y FOVISSTE @endif </strong>, 
    mismo que en lo sucesivo y para los efectos del presente contrato será denominado como <strong>Hipotecario</strong>.  Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con historial crediticio 
    satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue un crédito por parte de 
    institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
    </p>

    <p>
    e) Que es su deseo celebrar el presente contrato con <strong>EL PROMITENTE VENDEDOR</strong> con el objeto de obligarse a 
    celebrar un contrato de compraventa respecto de <strong>LA VIVIENDA</strong>. 
    </p>

@else

    <p>
    c) Que cuenta con los recursos económicos suficientes para adquirir la propiedad de <strong>LA VIVIENDA</strong>, una vez que haya sido designada 
    beneficiaria de un crédito hipotecario otorgado por <strong>{{mb_strtoupper($contratoPromesa[0]->institucion)}} @if($contratoPromesa[0]->infonavit > 0) e INFONAVIT @elseif($contratoPromesa[0]->fovisste > 0) y FOVISSTE @endif </strong>, 
    mismo que en lo sucesivo y para los efectos del presente contrato será denominado como <strong>Hipotecario</strong>.  Para tal efecto, manifiesta bajo protesta de decir verdad, que cuenta con historial crediticio 
    satisfactorio y que por sus actividades e ingresos, resulta viable, financieramente, que se le otorgue un crédito por parte de 
    institución bancaria, que cubra en su totalidad el precio estipulado en este contrato.
    </p>

    <p>
    d) Que es su deseo celebrar el presente contrato con <strong>EL PROMITENTE VENDEDOR</strong> con el objeto de obligarse a 
    celebrar un contrato de compraventa respecto de <strong>LA VIVIENDA</strong>. 
    </p>

@endif

<p>
Conformes las partes con las declaraciones que anteceden, las cuales forman parte integrante del presente contrato, acuerdan otorgar las siguientes: 
</p>

<p align="center">C L A U S U L A S</p>

<p>
<strong>PRIMERA.-</strong> Sujeto a la condición que se establece en la cláusula segunda del presente contrato, <strong>EL PROMITENTE VENDEDOR</strong> se obliga a 
          celebrar con el carácter de vendedora, un contrato de compraventa respecto de <strong>LA VIVIENDA</strong>, con <strong>EL PROMITENTE COMPRADOR</strong>, y a su vez, 
         <strong>EL PROMITENTE COMPRADOR</strong>, se obliga a celebrar, con el carácter de comprador, el contrato de compraventa a que esta cláusula se refiere.
</p>

<p>
<strong>SEGUNDA.-</strong> El contrato definitivo de compraventa que las partes se han obligado a celebrar, está sujeto a la condición suspensiva consistente en que 
          <strong>{{mb_strtoupper($contratoPromesa[0]->institucion)}} @if($contratoPromesa[0]->infonavit > 0) e INFONAVIT @elseif($contratoPromesa[0]->fovisste > 0) y FOVISSTE @endif</strong>, 
          otorgue a <strong>EL PROMITENTE COMPRADOR</strong> un crédito con garantía hipotecaria hasta por la cantidad necesaria para cubrir el 
          precio total de la operación de compraventa pactado en la cláusula cuarta, inciso a)  y que dicho precio se aplique o libere, 
          en pago directo a <strong>EL PROMITENTE VENDEDOR</strong>, de tal forma que se encuentre liquidado dentro del plazo de 45 días naturales 
          contados a partir de la fecha de terminación de la construcción de <strong>LA VIVIENDA</strong>. En razón de lo anterior, las partes están de acuerdo 
          para que en caso de que no sea autorizado y liberado para pago, el crédito de referencia dentro de los 45 días naturales siguientes a la 
          fecha de terminación de la obra que se ejecuta sobre <strong>EL LOTE</strong>, sin necesidad de declaración judicial alguna, se tendrá por rescindido de 
          pleno derecho el actual contrato de promesa de compraventa, deslindando desde este momento <strong>EL PROMITENTE COMPRADOR</strong> a <strong>EL PROMITENTE VENDEDOR</strong> de 
          cualquier responsabilidad civil, penal o de cualquier otra índole legal, en torno al bien inmueble motivo de la presente contratación.
</p>

<p>
En el caso de que dicho crédito no fuere autorizado o liberado para pago, dentro del plazo de 45 días naturales contados a partir 
de la terminación de la construcción de <strong>LA VIVIENDA, EL PROMITENTE VENDEDOR</strong>, reembolsará a <strong>EL PROMITENTE COMPRADOR</strong>, 
en un término de 90 días naturales contados a partir de la fecha de la terminación de la casa habitación, la o las cantidades que 
hasta esa fecha le haya(n) sido pagada(s) por <strong>EL PROMITENTE COMPRADOR</strong>, menos la cantidad de $ 10,000.00 (Diez Mil Pesos 00/100 Moneda Nacional), 
ya que esta última cantidad será utilizada para el pago del estudio del mismo crédito y costo de avaluo que exige la Institución Financiera y 
restando también la cantidad establecida en la cláusula sexta de este contrato;  sin que exista obligación alguna por 
parte de <strong>EL PROMITENTE VENDEDOR</strong> de cubrir ninguna cantidad extra por concepto de Daños, perjuicios, o indemnización legal de ninguna especie. 
En caso de no cumplirse la condición suspensiva a que se encuentra sujeto el presente contrato, las partes están de acuerdo 
en que <strong>EL PROMITENTE VENDEDOR</strong> podrá enajenar <strong>LA VIVIENDA</strong> a favor de diversos compradores, o bien, darle el uso o destino que crea más conveniente, 
no existiendo en ello conducta tipificada en la ley penal como ilícito de fraude, ya que las partes están de común acuerdo con la condición 
suspensiva y sus efectos previstos en la actual cláusula, por lo que no existe dolo o engaño en tal manifestación de voluntad.
</p>


<p>
<strong>TERCERA.- EL PROMITENTE COMPRADOR</strong> se obliga a integrar toda la documentación necesaria requerida por la institución 
          crediticia que pudiera otorgarle los recursos para la adquisición de <strong>LA VIVIENDA</strong>, y a entregarla a la propia institución bancaria 
          en un plazo no mayor a 7 días naturales contados a partir de la firma del presente contrato para que tenga acceso al crédito mencionado 
          en la cláusula anterior, ya que será utilizada para integrar el expediente necesario para la obtención del crédito.
</p>

<p>
<strong>EL PROMITENTE VENDEDOR</strong> podrá ofrecer asesoría e incluso, coadyuvar con <strong>EL PROMITENTE COMPRADOR</strong> para la gestión del crédito y la presentación de documentos, 
sin que esa circunstancia releva a este último de ser el responsable de realizar esos trámites.
</p>

<p>
<strong>CUARTA.-</strong> El precio total que convienen las partes, será motivo de la operación de compraventa definitiva, 
         es la cantidad que de <strong>${{strtoupper($contratoPromesa[0]->precioVentaLetra)}}</strong>, 
         mismo que EL PROMITENTE COMPRADOR se obliga a pagar a EL PROMITENTE VENDEDOR en los términos establecidos en la 
         cláusula de la siguiente manera: a).La cantidad de  
         @if($contratoPromesa[0]->precio_venta >= $contratoPromesa[0]->credito_neto )
          <strong>${{strtoupper($contratoPromesa[0]->montoTotalCreditoLetra)}},</strong> 
        @elseif( $contratoPromesa[0]->precio_venta < $contratoPromesa[0]->credito_neto )
            <strong>${{strtoupper($contratoPromesa[0]->precioVentaLetra)}},</strong>
        @endif
         mediante el crédito que le otorgara {{mb_strtoupper($contratoPromesa[0]->institucion)}}
         @if($contratoPromesa[0]->infonavit > 0) y la cantidad de <strong>${{strtoupper($contratoPromesa[0]->infonavitLetra)}}</strong> que le otorga INFONAVIT @elseif($contratoPromesa[0]->fovisste > 0) y la cantidad de <strong>${{strtoupper($contratoPromesa[0]->fovissteLetra)}}</strong> que le otorga FOVISSTE @endif, 
         al que se refiere la cláusula segunda del presente convenio, misma que deberá ser liquidada dentro de los 45 días naturales siguientes a la conclusión de la construcción de LA VIVIENDA. 
         @if($contratoPromesa[0]->precio_venta != $contratoPromesa[0]->credito_neto && $contratoPromesa[0]->enganche_total >= 10000) b).El resto, mediante <strong>{{$pagos[0]->totalDePagos}}</strong>pagos, @for($i=0; $i < count($pagos); $i++) el <strong>{{$pagos[$i]->numeros}}</strong> por la cantidad de <strong>${{strtoupper($pagos[$i]->montoPagoLetra)}},</strong>
         que será liquidado a más tardar el día <strong>{{$pagos[$i]->fecha_pago}}</strong>@endfor, respectivamente.
         @elseif($contratoPromesa[0]->enganche_total < 10000 && $contratoPromesa[0]->enganche_total > 0 )
         b).El resto, mediante <strong>{{$pagos[0]->totalDePagos}}</strong>pagos por la cantidad de <strong>${{$contratoPromesa[0]->engancheTotalLetra}}</strong> que será liquidado a más tardar el día <strong>{{$pagos[0]->fecha_pago}}</strong>, respectivamente.
         @elseif($contratoPromesa[0]->enganche_total <=0 )
         <br>
         @endif
</p>

<p>
Hasta en tanto no se encuentre totalmente liquidado el precio total de <strong>LA VIVIENDA</strong>, las cantidades que reciba <strong>EL PROMITENTE VENDEDOR</strong>, 
serán entregadas por <strong>EL PROMITENTE COMPRADOR</strong>, a título de depósito, por lo que en caso de que el presente contrato se rescindiera, 
las mismas deberán ser reembolsadas a <strong>EL PROMITENTE COMPRADOR</strong>, en los términos pactados en las cláusulas segunda y sexta del presente contrato.
</p>

<p>
Asimismo, el precio pactado con antelación, se sujeta a la condición de que el pago total del precio de <strong>LA VIVIENDA</strong>, 
se verifique a más tardar dentro de los 45 días naturales siguientes a la fecha de terminación de la construcción de <strong>LA VIVIENDA</strong>, 
en el entendido además, de que transcurrida dicha fecha, sin la celebración del contrato definitivo de compraventa, 
el presente contrato se rescindirá en forma automática.
</p>

<p>
<strong>QUINTA.- EL PROMITENTE COMPRADOR</strong> señala estar de acuerdo con: A). Las características de la urbanización del terreno. B).
         La superficie de construcción que es de <strong>{{$contratoPromesa[0]->construccion}} M2</strong>. C). La fecha de entrega de <strong>LA VIVIENDA</strong> a <strong>EL PROMITENTE COMPRADOR</strong>, 
         se realizara en el momento que quede liberado el precio total de <strong>LA VIVIENDA</strong>. Por lo que <strong>EL PROMINENTE COMPRADOR</strong> no sé reserva 
         ningún derecho de ejercitar posteriormente sobre este particular, ya que <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong>, se apegan a sus necesidades. 
</p>

<p>
<strong>SEXTA.-</strong> Este contrato podrá ser rescindido de pleno derecho y sin necesidad de declaración judicial en caso de incumplimiento a lo pactado en este contrato. 
        En forma enunciativa y no limitativa, se mencionan algunas causales en que puede incurrir el comprador: a).- No cubrir cualquiera de los pagos pactados en los incisos b) y c) de la cláusula cuarta; b).- No obtener la totalidad del crédito para el pago de la cantidad establecida en el inciso 
        a) de la cláusula cuarta, en el plazo de 45 días contados a partir de la terminación de la vivienda; c).- No entregar en forma oportuna la documentación a que se compromete en términos de la cláusula tercera.
</p>

<p>
La falta de pago puntual por parte de <strong>EL PROMITENTE COMPRADOR</strong> faculta a <strong>EL PROMITENTE VENDEDOR</strong> al cobro de intereses calculados 
a razón del 5% mensual por cada día de retraso y calculados sobre la cantidad adeudada. 
El cobro de intereses por parte de <strong>EL PROMITENTE VENDEDOR</strong>, no implica una renuncia al derecho a ejercer la recisión de pleno derecho y sin necesidad de declaración judicial que en este mismo contrato se prevé.
<br>
@if($contratoPromesa[0]->precio_venta != $contratoPromesa[0]->credito_neto)
</p>

<p>
@endif
En caso de rescisión del presente contrato, por causa imputable a <strong>EL PROMITENTE COMPRADOR, EL PROMITENTE VENDEDOR</strong> deberá reintegrar 
el importe recibido hasta la fecha por parte de <strong>EL PROMITENTE COMPRADOR</strong>, descontándose la cantidad de $ 25,000.00 (Veinticinco Mil Pesos 00/100 Moneda Nacional), 
como pena convencional por los daños y perjuicios ocasionados. El importe se reintegrara hasta que <strong>EL PROMITENTE VENDEDOR</strong> recupere la inversión realizada 
en la construcción. 
</p>

<p>
<strong>EL PROMITENTE COMPRADOR</strong> manifiesta su expresa conformidad y señala que entiende cabalmente el alcance de lo que aquí se detalla. 
</p>

<p>
<strong>SÉPTIMA.-</strong> Todos los gastos, derechos, impuestos y honorarios que se causen con motivo de este contrato y 
la celebración del contrato definitivo en Escritura Publica serán cubiertos por <strong>EL PROMITENTE COMPRADOR</strong>, 
excepto el Impuesto sobre la Renta el cuál será a cargo de <strong>EL PROMITENTE VENDEDOR</strong>.
</p>

<p>
<strong>OCTAVA.-</strong> Todas las comunicaciones que las partes deban o deseen hacerse en relación con este Contrato se harán por escrito, 
         señalando las partes como sus respectivos domicilios, para recibir toda clase de notificaciones que deban hacérseles, 
         aun las personales en caso de juicio, las siguientes:
</p>

<p>
<strong>EL PROMITENTE VENDEDOR:</strong> MANUEL GUTIERREZ NAJERA 190 Colonia TEQUISQUIAPAM San Luis Potosí, San Luis Potosí 
</p>

<p>
<strong>EL PROMITENTE COMPRADOR:</strong> {{mb_strtoupper($contratoPromesa[0]->direccion)}} Colonia {{mb_strtoupper($contratoPromesa[0]->colonia)}} {{mb_strtoupper($contratoPromesa[0]->ciudad)}}, {{mb_strtoupper($contratoPromesa[0]->estado)}}
</p>

<p>
<strong>NOVENA.-</strong> Para todo lo relacionado con la interpretación, cumplimiento y ejecución del presente Contrato, las partes se someten a la jurisdicción y 
         competencia de los tribunales de esta ciudad de San Luis Potosí, San Luis Potosí, renunciando expresamente a cualquier otro fuero que 
         pudiera corresponderles en razón de sus domicilios presentes o futuros o por cualquier otro motivo. 
</p>

<p>
Enteradas las partes, del alcance contenido y fuerza legal del presente instrumento, lo firman de conformidad en la ciudad de SAN LUIS POTOSÍ, SAN LUIS POTOSÍ, 
en tres ejemplares de un mismo tenor y para un solo efecto, a los <strong>{{$contratoPromesa[0]->fecha}}</strong> 
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
                    <div colspan="2" class="table-cell3">GRUPO CONSTRUCTOR CUMBRES<br>ING. ALEJANDRO F. PEREZ ESPINOSA<br>ING. DAVID CALVILLO MARTINEZ</div>
                    <div style="width: 8%;" class="table-cell3"></div>
                    <div colspan="2" class="table-cell3">{{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}}</div>
                </div>
            </div>


</div>
    
</body>
</html>