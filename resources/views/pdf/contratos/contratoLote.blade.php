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
 font-size: 11pt;
 text-align: justify;
}

@page{
    margin: 70px;
    margin-bottom :80px;
    margin-right: 90px;
    margin-left: 90px;
}

.table-cell3 { display: table-cell; padding: 0em; font-size: 10pt; }
.table3 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row; }

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
    CONTRATO DE PROMESA DE COMPRAVENTA CON RESERVA DE DOMINIO, QUE CELEBRAN, POR UNA PARTE, 
    LA SOCIEDAD MERCANTIL DENOMINADA <strong>CONCRETANIA, S. A. DE. C.V</strong>,  REPRESENTADA EN ESTE ACTO POR 
    EL ING. DAVID CALVILLO MARTINEZ, A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, 
    SE LE DENOMINARA INDISTINTAMENTE COMO <strong>CONCRETANIA</strong> Y/O <strong>LA PROMITENTE VENDEDORA</strong> 
    Y POR LA OTRA PARTE, POR SU PROPIO DERECHO, 
    EL SR(A). {{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}} 
    @if($contratoPromesa[0]->coacreditado == 1)
        y {{mb_strtoupper($contratoPromesa[0]->nombre_coa)}} {{mb_strtoupper($contratoPromesa[0]->apellidos_coa)}}
    @endif, 
    A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE LE DENOMINARÁ COMO 
    <strong>EL PROMITENTE COMPRADOR</strong>, AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS. 
</p>

<h4 align="center">DECLARACIONES</h4>

<p>I.- Declara <strong>CONCRETANIA</strong>, por medio de su representante legal:</p>

<p>
    a) Que es una sociedad mercantil constituida de acuerdo a la legislación de la República Mexicana, 
    mediante Escritura Pública número setecientos sesenta y cuatro, del volumen veintiuno, otorgada ante la fe 
    del Licenciado Octaviano Gómez y González, Notario Público número Cuatro, con ejercicio en esta ciudad, 
    con fecha veinticinco de julio de dos mil dieciocho, y cuyo primer testimonio obra inscrito en el 
    Registro Público de la Propiedad y de Comercio, de esta ciudad, bajo el Folio Mercantil Electrónico N-2018073682, 
    desde el día siete de septiembre de dos mil dieciocho. 
</p>

<p>
    b) Que su representante, el Ingeniero David Calvillo Martínez, cuentan con las facultades necesarias 
    para la celebración del presente contrato, mismas que a la fecha no le han sido restringidas, 
    ni revocadas de forma alguna. 
</p>

<p>
    c) Que es única y legítima propietaria del Lote de terreno 
    número <strong>{{$contratoPromesa[0]->num_lote}}</strong>, 
    de la manzana <strong>{{mb_strtoupper($contratoPromesa[0]->manzana)}} 
    del Fraccionamiento <strong>{{mb_strtoupper($contratoPromesa[0]->proyecto)}}</strong>, 
    en la ciudad de {{mb_strtoupper($contratoPromesa[0]->ciudad_proy)}}, {{mb_strtoupper($contratoPromesa[0]->estado_proy)}}, 
    cuya superficie es de <strong>{{$contratoPromesa[0]->superficie}} m2</strong>, 
    mismo que en lo sucesivo y para todos los efectos del presente contrato se le denominará como <strong>EL LOTE</strong>. 
</p>
<br>

<p>II.- Declara <strong>EL PROMITENTE COMPRADOR</strong> por su propio derecho:</p>

<p>
    a) Que es una persona física, mayor de edad, con plena capacidad para contratar y obligarse.
</p>

<p>
    b) Que conoce perfectamente la ubicación, superficie, medidas y colindancias de <strong>EL LOTE</strong>, 
    así como las características generales y especificaciones técnicas de las vías de acceso y de los servicios 
    instalados para <strong>EL LOTE</strong> y además, sabe que el uso de suelo es exclusivamente <strong>habitacional</strong>.
</p>

<p>
    c) Que le fue explicado que <strong>EL LOTE</strong>  se encuentra  dentro de un fraccionamiento cuya normativa de 
    comportamiento y de conservación se encuentra sujeta, además de las leyes aplicables en el Estado, 
    a los Lineamientos de Construcción y Convivencia del Fraccionamiento los cuales se compromete a obedecer. 
</p>

<p>
    d) Que es su deseo celebrar el presente contrato con <strong>EL PROMITENTE VENDEDOR </strong>con el objeto de 
    obligarse a celebrar un contrato de compraventa.
</p>

<p>
    Conformes las partes con las declaraciones que anteceden, las cuales forman parte integrante del 
    presente contrato, acuerdan otorgar las siguientes: 
</p>

<p align="center">C L A U S U L A S</p>

<p>
    <strong>PRIMERA. INMUEBLE OBJETO DE LA OPERACIÓN.- CONCRETANIA </strong>
    se obliga a celebrar con el carácter de vendedora, un contrato de compraventa elevado a escritura pública 
    y ante notario público, respecto de <strong>EL LOTE</strong>, con <strong>EL PROMITENTE COMPRADOR</strong>, y a su vez, 
    éste se obliga a celebrar, con el carácter de comprador, el contrato de compraventa a que 
    esta cláusula se refiere, respecto de <strong>EL LOTE</strong> descrito en la declaración I, inciso c) 
    de este acto jurídico. Esta compraventa será con reserva de dominio, por lo que el mismo 
    se transmitirá hasta que se firme la escritura y se realicen en su totalidad los pagos 
    pactados en este acto jurídico.
</p>

<br>
<p>
    <strong>SEGUNDA. CONFORMIDAD CON CONDICIONES.-  EL PROMITENTE COMPRADOR </strong>
    señala estar de acuerdo con: A). Las características de la urbanización del terreno. 
    B). La superficie del mismo que es de <strong>{{$contratoPromesa[0]->superficie}} m2</strong>. 
    C). La fecha de entrega a <strong>EL PROMITENTE COMPRADOR</strong>, que se realizará en el momento que 
    quede pagado el precio total del mismo, por lo que <strong>EL PROMINENTE COMPRADOR</strong> no se 
    reserva ningún derecho de ejercitar posteriormente sobre este particular, 
    ya que <strong>EL LOTE</strong> se apega a sus necesidades. 
</p>

<br>
<p>
    <strong>TERCERA. PRECIO.- </strong> El precio total que  será motivo de la operación 
    de compraventa definitiva, es la suma  que de 
    <strong>${{strtoupper($contratoPromesa[0]->precioVentaLetra)}}</strong>, 
    Ese precio lo deberá pagar<strong> EL PROMITENTE COMPRADOR </strong> a <strong>EL PROMITENTE VENDEDOR</strong> 
    de la siguiente manera:
</p>

<p>
    a).- Un anticipo por la cantidad de 
    {{$pagos[0]->totalLetra}}, 
    equivalente al {{$contratoPromesa[0]->porcentajeValor}}%  
    del valor del lote que deberá cubrirse en el momento de la firma de este contrato, 
    recabándose el recibo correspondiente.
</p>

<p>
    b).- El resto, mediante las amortizaciones mensuales y consecutivas, por capital e intereses, 
    que se establece en el calendario de pagos que, firmado por las partes, forma en anexo único 
    de este contrato.
</p>

<p>
    El promitente comprador podrá hacer pagos anticipados a capital, siempre y cuando no 
    existan mensualidades vencidas, ni deudas por interés ordinario o moratorio, pues en 
    ese caso, los pagos que se realicen se aplicarán primero a tales accesorios. 
</p>

<br>
<p>
    <strong>CUARTA.- INTERESES ORDINARIOS.- EL PROMITENTE COMPRADOR </strong> se obliga a pagar 
    a <strong>CONCRETANIA</strong> sin necesidad de requerimiento previo, intereses ordinarios calculados sobre 
    el saldo insoluto de capital del precio financiado. Los intereses ordinarios se calcularán 
    sobre saldos insolutos diarios del capital que se adeude, a la tasa de interés anual 
    establecida en el calendario de pagos.  Para determinar el monto a pagar por los intereses 
    ordinarios, se dividirá la tasa anual pactada, entre trescientos sesenta  y cinco días, 
    obteniendo así la tasa diaria. El interés se calculará, a partir de la fecha de disposición, 
    multiplicando la tasa obtenida por el saldo insoluto de capital que se adeude en el momento de 
    pago por el número de días que transcurra entre cada amortización de acuerdo a las fechas de pago 
    y montos establecidos en la tabla de pagos anexa a este contrato. 
    
</p>


<br>
<p>
    <strong>QUINTA.- INTERESES MORATORIOS.- </strong> 
    En caso de incumplimiento de <strong>EL PROMITENTE COMPRADOR</strong>, en el pago oportuno de 
    cantidades que deban cubrirse conforme a la tabla de pagos anexa, 
    estará obligado a pagar a <strong>CONCRETANIA</strong> intereses moratorios sobre el 
    monto del capital correspondiente a cada mensualidad, a una tasa de 
    interés moratoria del 3% tres por ciento mensual. Para calcular el monto a pagar, 
    se dividirá la tasa mencionada entre 30 y se multiplicará por el número de días 
    demorados; la tasa resultante se aplicará al capital de la mensualidad correspondiente.
</p>

<br>
<p>
    <strong>SEXTA.- PAGARÉS. </strong> Estos pagos se documentarán mediante la firma de 
    pagarés seriados que entrega <strong>EL PROMITENTE COMPRADOR</strong> a <strong>CONCRETANIA</strong>, 
    mismos que estarán sujetos a la condición de que, de no cubrirse cualquiera de ellos, 
    se vencerán anticipadamente los restantes.
</p>

<br>
<p>
    <strong>SÉPTIMA. GASTOS.  SERVICIOS. IMPUESTO PREDIAL.-</strong> 
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

<br>
<p>
    <strong>OCTAVA.- ESCRITURACIÓN Y POSESIÓN.- CONCRETANIA </strong>
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

<br>
<p>
    <strong>NOVENA. PERMISO PARA CONSTRUIR.- </strong> Sin que implique transmisión de posesión, a petición 
    de <strong>EL PROMITENTE COMPRADOR</strong>, el <strong>PROMITENTE VENDEDOR</strong> podrá otorgar permiso 
    por escrito para que aquél inicie la construcción sobre <strong>EL LOTE</strong>, siempre y cuando 
    concurra lo siguiente:
</p>


<p>
    a).- Que se encuentre al corriente en el pago de las amortizaciones pactadas, tanto de suerte principal como 
    de intereses ordinarios y/o moratorios en su caso y que al momento de solicitar el permiso para construir sobre 
    <strong>EL LOTE</strong>, se haya cubierto más del 50% del precio pactado.
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

<br>
<p>
    <strong>DÉCIMA.-  USO DE SUELO.- EL PROMITENTE COMPRADOR </strong> 
    se obliga a no variar el uso habitacional en el cual está clasificado <strong>EL LOTE</strong>, 
    que es habitacional; por  lo que deberá abstenerse de construir otro tipo de edificaciones comerciales o industriales. 
</p>

<br>
<p>
    <strong>DÉCIMA PRIMERA.- REGLAMENTO DEL FRACCIONAMIENTO. </strong> 
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

<br>
<p>
    <strong>DÉCIMA SEGUNDA.- CESIÓN O TRANSMISIÓN PROHIBIDAS. EL PROMITENTE COMPRADOR </strong> 
    no podrá ceder, enajenar o transmitir bajo cualquier título los derechos que derivan de este contrato, salvo que 
    cuente con autorización expresa y previa a la transmisión por parte de <strong>EL PROMITENTE VENDEDOR. </strong>
    El  incumplimiento a lo aquí pactado, será motivo de rescisión del contrato, independientemente de que 
    <strong>EL PROMITENTE VENDEDOR</strong> no reconocerá a ningún cesionario como adquirente de <strong>EL LOTE</strong>.
</p>

<p>
    En todo caso, para que <strong>EL PROMITTENTE VENDEDOR</strong> apruebe la cesión el promitente comprador deberá de 
    demostrar que está al corriente de todas las obligaciones fiscales y legales que recaigan sobre el 
    inmueble o que deriven del presente contrato.
</p>

<br>
<p>
    <strong>DÉCIMA TERCERA-RESCISIÓN.- </strong> Este contrato podrá ser rescindido de pleno derecho 
    y sin necesidad de declaración judicial o de alguna comunicación en caso de incumplimiento a 
    lo aquí pactado. En forma enunciativa y no limitativa, se menciona como causal en que puede incurrir 
    <strong>EL PROMITENTE COMPRADOR</strong>, la falta de pago de dos o más mensualidades consecutivas pactadas en la 
    cláusula TERCERA, así como el incumplimiento  a lo establecido en las demás cláusulas de este contrato.
</p>

<p>
    En caso de rescisión <strong>CONCRETANIA</strong> deberán reintegrar, el importe recibido hasta la fecha por parte 
    de <strong>EL PROMITENTE COMPRADOR</strong>, a excepción de las cantidades que se hayan aplicado a intereses ordinarios 
    y moratorios; adicionalmente se descontará el porcentaje del valor de la operación, que se establece en el cuadro inmediato 
    a este párrafo, como pena por los daños y perjuicios ocasionados, independientemente de lo establecido en la 
    cláusula novena, inciso d) de este contrato. <strong>EL PROMITENTE COMPRADOR</strong> manifiesta su expresa 
    conformidad y señala que entiende cabalmente el alcance de lo que aquí se detalla.  
</p>
<br>

<p>
    La penalización por cancelación, sobre el valor de operación, en la siguiente forma: 
</p>
<br>

<table class="myTable">
    <thead>
        <tr>
            <th class="text-center">Si la cancelación se realiza:</th>
            <th class="text-center">%</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-left">Antes de 12 meses de la firma de este contrato</td>
            <td class="text-center">10</td>
        </tr>
        <tr>
            <td class="text-left">De 12 a 24 meses de la firma de este contrato</td>
            <td class="text-center">15</td>
        </tr>
        <tr>
            <td class="text-left">Después de 24 meses de la firma de este contrato</td>
            <td class="text-center">20</td>
        </tr>
    </tbody>
</table>

<br>
<p>
    <strong>DÉCIMA CUARTA. RESERVA DE DOMINIO.- </strong> 
    Esta compraventa se pacta en la modalidad de reserva de dominio, de conformidad con el artículo 2143 del 
    Código Civil del Estado, por lo que <strong>CONCRETANIA</strong> se reserva el dominio del inmueble vendido 
    hasta que su precio haya sido cubierto y se realice la escritura de compraventa definitiva ante notario público
</p>

<br>
<p>
    <strong>DÉCIMA QUINTA. DOMICILIOS.- </strong> 
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
    emisor del mensaje de datos requerirá el acuse de recibo, pues de no contar con éste, la comunicación 
    no surtirá efectos. Además, en los mensajes de datos por correo electrónico la información completa 
    deberá estar desplegada dentro del mismo correo, sin el uso de vínculos o de archivos adjuntos. 
    En caso de que por el tamaño de la información sea necesario el uso de vínculos o archivos adjuntos, 
    el acuse de recibo deberá señalar que se pudo acceder a ese  vínculo o  archivo.
</p>

<br>
<p>
    <strong>DÉCIMA SEXTA. JURISDICCION.- </strong> Para todo lo relativo a la interpretación y 
    cumplimiento del presente contrato, las partes se someten expresamente a la jurisdicción y 
    competencia de los tribunales de esta ciudad de San Luis Potosí, San Luis Potosí renunciando a 
    cualquier otro fuero que les pudiera corresponder en razón de sus domicilios presentes o futuros. 
    Hecho de conformidad por las partes, el presente instrumento es fiel expresión de voluntad contractual y para 
    constancia lo firman en dos ejemplares de un mismo tenor y para un solo efecto, en esta ciudad de 
    Villa de Reyes, S.L.P.,<strong>a los {{$contratoPromesa[0]->fecha}}.</strong> 
</p>


        
    <br>
    <br>

    <p align="center"><b>LA PROMITENTE  VENDEDORA</p>
   
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
            <div colspan="3" style="text-align: center;" class="table-cell3">CONCRETANIA, S.A. de C.V.<br> REPRESENTADA POR EL <br> ING. DAVID CALVILLO MARTÍNEZ</div>
            <div colspan="1" class="table-cell3"></div>
        </div>
    </div>
 
    <br>
    <br>
    <br>
    
    <div>
        <p align="center"><b>LA PROMITENTE COMPRADORA</p>

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

    <div style="page-break-after:always;"></div>

            
    <p align="center"><b> <strong>ANEXO ÚNICO <br>TABLA DE PAGOS</strong> </p>
        <br><br>

    <table class="myTable">
        <thead>
            <tr>
                <th class="text-center">Número de pago</th>
                <th class="text-center">Fecha de pago</th>
                <th class="text-center">Capital</th>
                <th class="text-center">Interés ordinario</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagos as $pago)
            <tr>
                <td class="text-left">{{$pago->folio}}</td>
                <td class="text-center">{{$pago->fecha}}</td>
                <td class="text-center">$ {{$pago->cantidad}}</td>
                <td class="text-center">$ {{$pago->interes_monto}}</td>
                <td class="text-center">$ {{$pago->total_a_pagar}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

<br>
<p>
    El plazo para cubrir el adeudo elegido por <strong>EL PROMITENTE COMPRADOR</strong> es de 
    {{$contratoPromesa[0]->mensualidades}} meses, por lo que la tasa de interés ordinario que aplica, conforme a la 
    cláusula cuarta del contrato es del {{$contratoPromesa[0]->interes}} por ciento anual. Los contratos a
     plazo menores de seis meses no generan interés ordinario, pero si el moratorio. 
</p>

<p>
    <strong>Esta tabla de pagos forma parte del contrato de promesa de compraventa celebrado 
        el día {{$contratoPromesa[0]->fecha2}}, entre 
        {{mb_strtoupper($contratoPromesa[0]->nombre)}} {{mb_strtoupper($contratoPromesa[0]->apellidos)}} como 
        PROMITENTE COMPRADOR y CONCRETANIA, S. A. DE C. V., como PROMITENTE VENDEDORA. </strong>
</p>
<br><br>
<br>

<p align="center"><b>LA PROMITENTE  VENDEDORA</p>
   
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
            <div colspan="3" style="text-align: center;" class="table-cell3">CONCRETANIA, S.A. de C.V.<br> REPRESENTADA POR EL <br> ING. DAVID CALVILLO MARTÍNEZ</div>
            <div colspan="1" class="table-cell3"></div>
        </div>
    </div>
 
    <br>
    <br>
    <br>
    
    
    <div>
        <p align="center"><b>LA PROMITENTE COMPRADORA</p>

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
    
</body>
</html>