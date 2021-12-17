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
}

@page{
    margin: 65px;
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
            <strong>CONTRATO DE COMPRAVENTA</strong> CON RESERVA DE DOMINIO, QUE CELEBRAN POR UNA PARTE, 
            LA SOCIEDAD MERCANTIL DENOMINADA <strong>GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V.</strong>, 
            REPRESENTADA EN ESTE ACTO POR EL ING. ALEJANDRO F. PEREZ ESPINOSA, A QUIEN EN LO SUCESIVO Y PARA 
            LOS EFECTOS DEL PRESENTE CONTRATO, SE LE DENOMINARA COMO <strong>CUMBRES;</strong> LA DIVERSA 
            SOCIEDAD DENOMINADA <strong>CONCRETANIA, S.A. DE C.V., </strong> REPRESENTADA EN ESTE ACTO POR EL 
            ING. DAVID CALVILLO MARTINEZ, A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, SE LE 
            DENOMINARA COMO <strong>CONCRETANIA</strong> Y A AMBAS PERSONAS MORALES CUANDO SE LES DESIGNE CONJUNTAMENTE
            SERÁ COMO <strong>LOS VENDEDORES</strong>, Y POR LA OTRA PARTE, POR SU PROPIO DERECHO, 
            <strong>EL SR(A) {{mb_strtoupper($contratosDom[0]->nombre)}} {{mb_strtoupper($contratosDom[0]->apellidos)}} 
                @if($contratosDom[0]->coacreditado == 1) 
                    Y {{mb_strtoupper($contratosDom[0]->nombre_coa)}} {{mb_strtoupper($contratosDom[0]->apellidos_coa)}} 
                @endif</strong>
            A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE DENOMINARAN 
            <strong>EL PROMITENTE COMPRADOR</strong>, AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.
        </p>

        <p>
            I. Declara <strong>CUMBRES</strong>, por conducto de su representante:
        </p>

        <p>
            a) Que es una sociedad mercantil constituida de acuerdo a la legislación de la República Mexicana, 
            mediante Escritura Pública número tres, del tomo cuarenta y siete, otorgada ante la fe del Licenciado Leopoldo de la Garza Marroquín,
            Notario Público Número 33, con ejercicio en esta ciudad, con fecha ocho de diciembre de mil novecientos noventa y nueve, 
            y cuyo primer testimonio obra inscrito en el Registro Público de la Propiedad y 
            de Comercio, de esta ciudad, bajo la inscripción número tres, del folio mercantil desde el dia diecinueve de enero del dos mil. 
        </p>

        <p>
            b) Que su representante, el Ing. Alejandro Francisco Pérez Espinosa, 
            cuenta con las facultades necesarias para la celebración del presente contrato, mismas que a la fecha no le han sido restringidas,
            ni revocadas de forma alguna.
        </p>

        <p>
            c) Que es único y legítimo propietario del <strong>Lote</strong> de terreno número <strong>{{$contratosDom[0]->num_lote}}</strong>, 
            de la <strong>Manzana {{mb_strtoupper($contratosDom[0]->manzana)}}</strong> del Fraccionamiento <strong>{{mb_strtoupper($contratosDom[0]->proyecto)}}</strong>,
            en esta ciudad de {{mb_strtoupper($contratosDom[0]->estado_proy)}}, {{mb_strtoupper($contratosDom[0]->ciudad_proy)}}, 
            cuya superficie es de <strong>{{$contratosDom[0]->superficie}} M2</strong>, mismo que en lo sucesivo y 
            para todos los efectos del presente contrato se le denominará como <strong>EL LOTE</strong>. 
        </p>
        <br>

        <p>
            II. Declara <strong>CONCRETANIA,</strong> por medio de su representante legal:
        </p>

        <p>
            a) Que es una sociedad mercantil constituida de acuerdo a la legislación de la República Mexicana, 
            mediante Escritura Pública número setecientos sesenta y cuatro, del volumen veintiuno, otorgada ante la fe del Licenciado 
            Octaviano Gómez y González,
            Notario Público Número Cuatro, con ejercicio en esta ciudad, con fecha veinticinco de julio de dos mil dieciocho, 
            y cuyo primer testimonio obra inscrito en el Registro Público de la Propiedad y 
            de Comercio, de esta ciudad, bajo la inscripción número tres, del folio mercantil Electrónico N-2018073682, 
            desde el día siete de septiembre de dos mil dieciocho.
        </p>

        <p>
            b) Que su representante, el Ing. David Calvillo Martínez, 
            cuenta con las facultades necesarias para la celebración del presente contrato, mismas que a la fecha no le han sido restringidas,
            ni revocadas de forma alguna.
        </p>

        <p>
            c) Que dentro de su objeto social se incluye el desarrollo de todo tipo de conjuntos habitacionales y comerciales; la edificación de casas, 
            departamentos, multifamiliares y habitacionales y el proyecto y realización de obras de ingeniería civil, hidráulica, mecánica y eléctrica;
            así como la celebración de los contratos que sean necesarios para el cumplimiento de su objeto. 
        </p>

        <p>
            d) Que cuenta con los elementos, práctica y servicios de empleados que son necesarios para construir en el Lote, una vivienda cuyas 
            características se encuentran debidamente especificadas en el anexo "A" del presente contrato, el que debidamente firmado por las partes 
            forma parte integrante del mismo. Que sobre <strong>EL LOTE </strong>descrito en la declaración I, inciso c), construirá una casa habitación,
            cuyas características se encuentran debidamente especificadas en el Anexo "A" del presente contrato, a la que en conjunto con el mismo,
             en lo sucesivo se le identificará en el presente contrato como <strong>LA VIVIENDA</strong>. 
        </p>
 
        <br>
        <p>III.- Declaran conjuntamente <strong>LOS PROMITENTES VENDEDORES:</strong></p>

        <p>
            a) Que es su deseo celebrar el presente contrato preparatorio con <strong>EL PROMITENTE COMPRADOR</strong> a efecto de obligarse a celebrar el contrato 
            definitivo de compraventa respecto del terreno antes mencionado y la casa a construir en el mismo identificados como <strong> VIVIENDA</strong>. 
        </p>

        <p>
            b) Que con fecha 13 de marzo de 2020, celebraron un convenio de <strong>Alianza Estratégica,</strong> que tiene por objeto llevar a cabo
            desarrollos inmobiliarios de una forma operativa, administrativa y comercial más eficiente y con mayor rentabilidad, en donde 
            <strong>CUMBRES</strong> desarrollará y venderá los lotes urbanizados y <strong>CONCRETANIA</strong> construirá y venderá la 
            edificación de las casas habitación o áreas comerciales que edifique sobre los lotes que urbanice <strong>CUMBRES</strong> y con 
            base en ese convenio es que comparecen en forma conjunta a celebrar este contrato.
        </p>

        <br>
        <p>IV.- Declara <strong>EL PROMITENTE COMPRADOR</strong> por su propio derecho:</p>

        <p>
            a) Que es una persona física, mayor de edad, con plena capacidad para contratar y obligarse.
        </p>
        
        <p>
            b) Que conoce perfectamente la ubicación, superficie, medidas y colindancias de <strong>EL LOTE</strong>, así como las características generales y especificaciones 
            técnicas con base en las cuáles se llevará a cabo la construcción de <strong>LA VIVIENDA</strong>, por lo que está conforme, y desea celebrar el presente contrato 
            preparatorio con <strong>LOS PROMITENTES COMPRADORES</strong>, a efecto de obligarse a celebrar el contrato definitivo de compraventa, por medio del cual adquirirá 
            la propiedad de <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong>. 
        </p>

        @if($contratosDom[0]->regimen_condom == 1)
            <p>
                c) Que le fue explicado que <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong> se encuentran dentro de un régimen de propiedad en condominio 
                y que dicha vivienda quedará sujeta a las disposiciones 
                establecidas en el Régimen de Condominio y el reglamento de condóminos correspondiente.
            </p>

            <p>
                d) Que es su deseo celebrar el presente contrato con <strong>LOS PROMITENTES VENDEDORES</strong> con el objeto de obligarse a 
                celebrar un contrato de compraventa respecto de <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong>. 
            </p>

        @else
            <p>
                c) Que es su deseo celebrar el presente contrato con <strong>LOS PROMITENTES VENDEDORES</strong> con el objeto de obligarse a 
                celebrar un contrato de compraventa respecto de <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong>. 
            </p>

        @endif

        <p>
            Conforme las partes con las declaraciones que anteceden, las cuales forman parte integrante del presente contrato, 
            acuerdan otorgar las siguientes:  
        </p>

        <br>

        <p align="center">C L A U S U L A S</p>

        <br>
        <p>
            <strong>PRIMERA.- CUMBRES</strong> enajena, reservándose el dominio y el comprador adquiere, 
            sujeto a tal reserva, <strong>EL LOTE</strong> descrito en la declaración I, inciso c) de este contrato.
        </p>
        
        <br>
        <p>
            <strong>SEGUNDA.-</strong> Por su parte mediante el presente acto <strong>CONCRETANIA</strong> se compromete 
            a construir <strong>LA VIVIENDA</strong> en <strong>EL LOTE</strong> descrito en la declaración I, inciso c) 
            de este contrato y una vez concluida venderla y entregarla a <strong>EL COMPRADOR</strong>, a su entera 
            satisfacción. Esta última se obliga a pagar un precio total por los mismos, en los términos y condiciones 
            que posteriormente se establecen.
        </p>

        <br>
        <p>
            <strong>TERCERA.- El LOTE y LA VIVIENDA</strong> referidos son los descritos en las declaraciones anteriores y en el anexo a este contrato.
            Mismos que se tienen por reproducidas como si literalmente estuviesen aquí insertas a la letra. 
        </p>

        <br>
        <p>
            <strong>CUARTA.-</strong> El precio total que será motivo de la operación de compraventa definitiva, es la suma 
            que de <strong>${{strtoupper($contratosDom[0]->engacheTotalLetra)}}</strong>, 
            de los que la cantidad de  ${{strtoupper($contratosDom[0]->valorTerrenoLetra)}} equivalente al {{$contratosDom[0]->porcentaje_terreno}}% 
            del precio, corresponde al valor de <strong>EL LOTE</strong>, en tanto que la cantidad 
            de ${{strtoupper($contratosDom[0]->valorConstruccionLetra)}} equivalente al {{100-$contratosDom[0]->porcentaje_terreno}}% 
            del precio le corresponde al valor de la vivienda. 
            
            Ese precio sera cubierto mediante <strong>{{$pagos[0]->totalDePagos}}</strong>pagos, 
            @for($i=0; $i < count($pagos); $i++) 
                el <strong>{{$pagos[$i]->numeros}}</strong> por la cantidad de <strong>${{strtoupper($pagos[$i]->montoPagoLetra)}},</strong>
                que será liquidado a más tardar el día <strong>{{$pagos[$i]->fecha_pago}}</strong>, 
            @endfor
            respectivamente. Cada uno de estos pagos, debera entregarse a <strong>CONCRETANIA</strong>, quien enterara la parte correspondiente a 
            <strong>CUMBRES</strong>. 
            Asimismo, el precio pactado con antelación,
            se sujeta a la condición de que el pago total de <strong>LA VIVIENDA</strong> se verifique a más tardar a la fecha de vencimiento del último 
            de los pagos pactados en el presenta contrato.
        </p>

        <p>
            <strong>LOS VENDEDORES</strong> están facultados a no recibir el primer pago, sin responsabilidad de su parte, si se pretende hacer en efectivo. 
            Además prodrán negarse a recibir los pagos en efectivo, sin incurrir en responsabilidad y sin considerarse incumplimiento al contrato, 
            cuando el monto que se quiera entregar en esa forma exceda de 8025 unidades de medida y actualización a que alude la Ley de Operaciones con 
            Recursos de Procedencia Ilícita.
        </p>

        <p>
            Estos pagos se documentarán mediante la firma de dos series de pagarés que entrega EL COMPRADOR a CUMBRES y CONCRETANIA 
            en la proporción de sus respectivos adeudos, mismos que estarán sujetos a la condición de que, de no cubrirse cualqueira de 
            ellos, se vencerán anticipadamente los restantes. La tasa moratoria establecida en estos títulos de crédito será la pactada 
            en la cláusula novena de este acto jurídico. En caso de que CONCRETANIA reciba el pago del adeudo, devolverá los pagarés suscritos 
            a favor de ambas empresas.
        </p>

        <br>
        <p>
            <strong>QUINTA.- LOS VENDEDORES</strong> se comprometen a entregar el lote y la vivienda 15 días hábiles posteriores a la firma de escrituras 
            y una vez que <strong>EL COMPRADOR</strong> liquide el 100% del valor de la misma. En esta fecha se entregará la póliza de garantía correspondiente.
        </p>

        <br>
        <p>
            <strong>SEXTA.- EL COMPRADOR</strong> señala estar de acuerdo con: 
        </p>

        <p>
            Las características de la urbanización del terreno. La Superficie de construcción que es de <strong>{{$contratosDom[0]->construccion}} M2</strong> 
            Las especificaciones de <strong>LA VIVIENDA</strong> según anexo, 
            el cual forma parte integral del presente contrato. El precio y la fecha de entrega.
        </p>

        <p>
            Por lo que <strong>LA COMPRADORA</strong> no se reserva ningún derecho de ejercitar posteriormente sobre este particular, 
            ya que <strong>EL LOTE</strong> y <strong>LA VIVIENDA</strong> se apegan a sus necesidades.
        </p>
        
        <br>
        <p>
            <strong>SÉPTIMA.-</strong>Todos los gastos, derechos, impuestos y honorarios que se causen con motivo de este contrato y 
            de la celebración del mismo en Escritura Pública serán cubiertos por 
            <strong>EL COMPRADOR</strong>, excepto el Impuesto Sobre la Renta el cuál será a cargo de <strong>LOS VENDEDORES.</strong>
        </p>

        <p>
            <strong>OCTAVA.-</strong> Este contrato podrá ser rescindido de pleno derecho y sin necesidad de declaración judicial en caso 
            de incumplimiento a lo pactado en este contrato. En forma enunciativa y no limitativa, se menciona como causal en que puede 
            incurrir <strong>EL COMPRADOR, </strong> no cubrir cualquiera de los pagos pactados en la cláusula cuarta o pretender cubrir en efectivo 
            el primer pago, deslindando desde este momento a <strong>LOS VENDEDORES</strong> de cualquier responsabilidad civil, penal 
            o de cualquier otra índole legal, en torno al bien inmueble motivo del presente contrato. En este sentido, la negativa de 
            <strong>LOS VENDEDORES</strong> a recibir el pago en efectivo, obedece al contenido del artículo 17, fracción V de la Ley Federal para 
            la identificación de Operaciones con Recursos de Procedencia Ilícita, que considera vulnerables las operaciones sobre inmuebles cuando 
            su monto exceda de 8025 unidades de medida y actualización, por lo que ese rechazo hace incurrir en incumplimiento a 
            <strong>EL COMPRADOR</strong> quien incurrirá en mora en caso de no realizar el pago en otra forma a la fecha de vencimiento.
        </p>

        <p>
            En caso de rescisión <strong>LOS VENDEDORES</strong> deberán reintegrar, en la proporción que les corresponda, el importe recibido 
            hasta la fecha por parte de <strong>EL COMPRADOR</strong>, decontándose la cantidad de $ 25,000.00 (Veinticinco Mil Pesos 00/100 Moneda Nacional) 
            como pena por los daños y perjuicios acasionados. El importe se reintegrará hasta que <strong>LOS VENDEDORES</strong> recuperen la inversión 
            realizada en la construcción. <strong>EL COMPRADOR.-</strong> Manifiesta su expresa conformidad y señala que entiende cabalmente el 
            alcance de lo que aquí se detalla.
        </p>
        <br>
      

        <p>
            <strong>NOVENA.-</strong> La falta de pago puntual por parte de <strong>EL COMPRADOR</strong> faculta también a 
            <strong>LOS VENDEDORES</strong> al cobro de intereses a tasa del 5% mensual aplicable sobre los días de retraso calculados sobre 
            la cantidad adeudada. El cobro de intereses, no limita o excluye la facultad de <strong>EL COMPRADOR</strong> de rescindir este contrato en 
            términos de la siguiente cláusula.
        </p>
        <br>


        <p>
            <strong> DÉCIMA.-</strong> Esta compraventa se pacta en la modalidad de reserva de dominio, de conformidad con el artículo 2143 del 
            Código Civil del Estado, por lo que los vendedores se reserva el dominio del inmueble vendido hasta que su precio haya sido cubierto.
        </p>
        <br>


        <p>
            <strong>DÉCIMA PRIMERA.-</strong> Las partes señalan como sus respectivos domicilios para recibir toda clase de notificaciones que deban 
            hacérseles, aun las personales en caso de juicio, los siguientes:
        </p>

        <p>
            <strong>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V. MANUEL GUTIERREZ NAJERA 190 Colonia TEQUISQUIAPAM San Luis Potosí, San Luis Potosí.
        </p>

        <p>
            <strong>CONCRETANIA, S.A. DE C.V. MANUEL GUTIERREZ NAJERA 180 Colonia TEQUISQUIAPAM San Luis Potosí, San Luis Potosí.
        </p>

        <p>
            {{mb_strtoupper($contratosDom[0]->nombre)}} {{mb_strtoupper($contratosDom[0]->apellidos)}} 
                @if($contratosDom[0]->coacreditado == 1) 
                    Y {{mb_strtoupper($contratosDom[0]->nombre_coa)}} {{mb_strtoupper($contratosDom[0]->apellidos_coa)}} 
                @endif ,
            {{mb_strtoupper($contratosDom[0]->direccion)}} Colonia {{mb_strtoupper($contratosDom[0]->colonia)}} 
            {{($contratosDom[0]->ciudad)}}, {{($contratosDom[0]->estado)}}.</strong>
        </p>
        <br>

        <p>
            <strong>DÉCIMA SEGUNDA.-</strong> Para todo lo relativo a la interpretación y cumplimiento del presente contrato, las partes se someten 
            expresamente  a la jurisdicción y competencia de los tribunales de esta ciudad de San Luis Potosí, San Luis Potosí renunciando a cualquier otro fuero 
            que les pudiera corresponder en razón de sus domicilios presentes o futuros. Hecho de conformidad por las partes, el presente instrumento es fiel expresión 
            de voluntad contractual y para constancia lo firman en dos ejemplares de un mismo tenor y para un solo efecto, en esta ciudad de SAN LUIS POTOSÍ, 
            SAN LUIS POTOSÍ <strong> a los {{$contratosDom[0]->fecha}}.</strong>
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
                {{-- <div colspan="2" class="table-cell3">{{mb_strtoupper($contratosDom[0]->nombre)}} {{mb_strtoupper($contratosDom[0]->apellidos)}}</div> --}}
            </div>
            {{-- @if($contratosDom[0]->coacreditado == 1)
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

            @if($contratosDom[0]->coacreditado == 0)
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
                        <div colspan="3" style="text-align: center;" class="table-cell3">{{mb_strtoupper($contratosDom[0]->nombre)}} {{mb_strtoupper($contratosDom[0]->apellidos)}}</div>
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
                        <div colspan="2" class="table-cell3">{{mb_strtoupper($contratosDom[0]->nombre)}} {{mb_strtoupper($contratosDom[0]->apellidos)}}</div>
                        <div style="width: 8%;" class="table-cell3"></div>
                        <div colspan="2" class="table-cell3">{{mb_strtoupper($contratosDom[0]->nombre_coa)}} {{mb_strtoupper($contratosDom[0]->apellidos_coa)}}</div>
                    </div>
                </div>
            @endif
            
        </div>

        
    </div>
</body>
</html>