<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTRATO MODIFICATORIO AL CONTRATO DE COMPRAVENTA</title>
</head>

<style>

body{
 font-size: 11pt;
 text-align: justify;
 font-family: arial, verdana, sans-serif;
}

@page{
    margin: 60px;
    margin-bottom :80px;
    margin-right: 90px;
    margin-left: 90px;
}

.table-cell3 { display: table-cell; padding: 0em; font-size: 11.5pt; }
.table3 { display: table; width:460px; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row; }
</style>

    <body>

        <div>

            <font size="14">
                <table>
                    <tr>
                        <td width=460 bgcolor="#c2c2c2"
                            style="border: 1px solid #00000a; padding-top: 0.05in; padding-bottom: 0.05in; ">
                                <strong><center>CONVENIO MODIFICATORIO AL CONTRATO DE COMPRAVENTA</center></strong>
                        </td>
                    </tr>
                </table>
            </font>

            @if($contrato->emp_constructora == $contrato->emp_terreno)
                <p>
                    Convenio Modificatorio al <strong>CONTRATO DE COMPRAVENTA</strong> que celebran por una parte la
                    sociedad
                    <strong>{{$contrato->emp_constructora}}, S.A. de C.V., </strong>
                    representada en este acto por su representante
                    @if($contrato->emp_constructora == 'Grupo Constructor Cumbres')
                        la Sra. Mayra Jazmín Salazar Alonso
                    @else
                        La Sra. Elizabeth Hernández Loera
                    @endif
                    a quien en lo sucesivo se le
                    denominará <strong>“LA PARTE VENDEDORA”</strong>; y por otra parte el Sr. (a) {{mb_strtoupper($contrato->nombre_completo)}}
                    @if($contrato->coacreditado == 1)
                        y el Sr (a) {{$contrato->nombre_coa}} {{$contrato->apellidos_coa}}
                    @endif
                    , a
                    quien en lo sucesivo se le denominará  <strong>“LA PARTE COMPRADORA”</strong>, acordando los contratantes,
                    que el presente acto se realice bajo el tenor de las siguientes:
                </p>
            @else
                <p>
                    Convenio Modificatorio al <strong>CONTRATO DE COMPRAVENTA</strong> que celebran por una parte la
                    sociedad <strong>{{$contrato->emp_constructora}}, S.A. de C.V.</strong>,
                    representada en este acto por su representante la Sra. Mayra Jazmín Salazar Alonso y la sociedad
                    <strong>{{mb_strtoupper($contrato->emp_terreno)}},S.A. de C.V.</strong>, representada en este acto por su representante legal
                    La Sra. Elizabeth Hernández Loera a quien en lo sucesivo se les
                    denominará <strong>“LA PARTE VENDEDORA”</strong>; y por otra parte el Sr. (a) {{mb_strtoupper($contrato->nombre_completo)}}
                    @if($contrato->coacreditado == 1)
                        y el Sr (a) {{$contrato->nombre_coa}} {{$contrato->apellidos_coa}}
                    @endif
                    , a
                    quien en lo sucesivo se le denominará  <strong>“LA PARTE COMPRADORA”</strong>, acordando los contratantes,
                    que el presente acto se realice bajo el tenor de las siguientes:
                </p>
            @endif
            <br><br>

            <font size="11.5">
                <table>
                    <tr>
                        <td width=460 bgcolor="#c2c2c2"
                            style="border: 1px solid #00000a; padding-top: 0.05in; padding-bottom: 0.1in; ">
                                <strong><center>DECLARACIONES</center></strong>
                        </td>
                    </tr>
                </table>
            </font>
            <br>

            @if($contrato->emp_constructora == $contrato->emp_terreno)
                <p>
                    <strong>I.- </strong>Declara <strong>“LA PARTE VENDEDORA”</strong> por conducto de su representante que:
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">A. Es una sociedad mercantil constituida
                        bajo las leyes de la República Mexicana, con domicilio convencional en el Número
                        @if($contrato->emp_constructora == 'CONCRETANIA')
                            180
                        @else
                            190
                        @endif
                        de la calle de Manuel Gutiérrez Nájera de la Colonia Tequisquiapan, de esta ciudad capital. <br><br>
                    </li>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">B.	Su representante cuenta con las facultades
                        suficientes para contratar y obligarse en los términos del presente convenio y que a la fecha de la firma del
                        mismo no le han sido revocadas o restringidas dichas facultades.
                    </li>
                </p>
                <br><br><br>

                <p>
                    <strong>II.- </strong>Declara <strong>“LA PARTE COMPRADORA"</strong>, por su propio derecho:
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">A.  Ser persona física con capacidad para contratar y obligarse
                        en los términos del presente convenio modificatorio. <br><br>
                    </li>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">B.	 Que con fecha {{$contrato->fecha}},
                        celebró con <strong>LA PARTE VENDEDORA</strong>, un <strong>CONTRATO COMPRAVENTA </strong>
                        (En lo sucesivo se le denominará <strong>El CONTRATO</strong>) respecto de una vivienda ubicada en
                        la manzana {{$contrato->manzana}}, número de lote
                        {{$contrato->num_lote}}, con dirección oficial en Calle {{$contrato->calle}}
                        Número {{$contrato->numero}}@if($contrato->interior != NULL)-{{$contrato->interior}}@endif,  Fraccionamiento {{$contrato->proyecto}},
                        Municipio de {{$contrato->ciudad_proy}}.
                        <br><br>
                        En lo sucesivo a este bien se le denominará <strong>“EL INMUEBLE”</strong>.<br><br>
                    </li>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">D.	Que es su interés de firmar el
                        presente convenio a fin de modificar la clausula CUARTA de <strong>EL CONTRATO</strong> previamente firmado.
                    </li>
                </p>
                <br><br>

                <p>
                    <strong>III.- </strong>Declaración conjunta de las partes:
                </p>

                <p>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">A. Las partes reconociéndose mutuamente su personalidad y
                        capacidad legal y de ejercicio, señalan que no existe error, dolo, lesión, presión o intimidación,
                        o cualquier otra circunstancia que llegue a afectar o invalidar su vigencia, declarando su voluntad
                        de formalizar el presente Convenio Modificatorio.
                    </li>
                </p>
            @else
                <p>
                    <strong>I.- </strong>Declara <strong>“CUMBRES”</strong> por conducto de su representante que:
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">A.  Es una sociedad mercantil constituida
                        bajo las leyes de la República Mexicana, con domicilio convencional en el Número 190
                        de la calle de Manuel Gutiérrez Nájera de la Colonia Tequisquiapan, de esta ciudad capital. <br><br>
                    </li>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">B.	 Su representante cuenta con las facultades
                        suficientes para contratar y obligarse en los términos del presente convenio y que a la fecha de la firma del
                        mismo no le han sido revocadas o restringidas dichas facultades.
                    </li>
                </p>
                <br><br><br>

                <p>
                    <strong>II.- </strong>Declara <strong>“CONCRETANIA”</strong> por conducto de su representante que:
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">A.  Es una sociedad mercantil constituida
                        bajo las leyes de la República Mexicana, con domicilio convencional en el Número 180
                        de la calle de Manuel Gutiérrez Nájera de la Colonia Tequisquiapan, de esta ciudad capital. <br><br>
                    </li>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">B.	 Su representante cuenta con las facultades
                        suficientes para contratar y obligarse en los términos del presente convenio y que a la fecha de la firma del
                        mismo no le han sido revocadas o restringidas dichas facultades.
                    </li>
                </p>
                <br><br><br>

                <p>
                    <strong>III.- </strong>Declara <strong>“LA PARTE COMPRADORA"</strong>, por su propio derecho:
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">A. Ser persona física con capacidad para contratar y obligarse
                        en los términos del presente convenio modificatorio. <br><br>
                    </li>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">B.	Que con fecha {{$contrato->fecha}},
                        celebró con <strong>LA PARTE VENDEDORA</strong>, un <strong>CONTRATO COMPRAVENTA </strong>
                        (En lo sucesivo se le denominará <strong>El CONTRATO</strong>) respecto de una vivienda ubicada en
                        la manzana {{$contrato->manzana}}, número de lote
                        {{$contrato->num_lote}}, con dirección oficial en Calle {{$contrato->calle}}
                        Número {{$contrato->numero}}@if($contrato->interior != NULL)-{{$contrato->interior}}@endif,  Fraccionamiento {{$contrato->proyecto}},
                        Municipio de {{$contrato->ciudad_proy}}.
                        <br><br>
                        En lo sucesivo a este bien se le denominará <strong>“EL INMUEBLE”</strong>.<br><br>
                    </li>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">D.	Que es su interés de firmar el
                        presente convenio a fin de modificar la clausula
                        @if($contrato->tipo_credito == 'Crédito Directo' || $contrato->tipo_credito == 'Apartado')
                            CUARTA
                        @else
                            TERCERA
                        de <strong>EL CONTRATO</strong> previamente firmado.
                        @endif
                    </li>
                </p>
                <br><br>

                <p>
                    <strong>III.- </strong>Declaración conjunta de las partes:
                </p>

                <p>
                    <li style="list-style-type: none; margin-left: 0.25in; text-indent: -0.25in">A. Las partes reconociéndose mutuamente su personalidad y
                        capacidad legal y de ejercicio, señalan que no existe error, dolo, lesión, presión o intimidación,
                        o cualquier otra circunstancia que llegue a afectar o invalidar su vigencia, declarando su voluntad
                        de formalizar el presente Convenio Modificatorio.
                    </li>
                </p>
            @endif
            <br><br><br>

            <font size="11.5">
                <table>
                    <tr>
                        <td width=460 bgcolor="#c2c2c2"
                            style="border: 1px solid #00000a; padding-top: 0.05in; padding-bottom: 0.1in; ">
                                <strong><center>CLÁUSULAS</center></strong>
                        </td>
                    </tr>
                </table>
            </font>
            <br>

            <p>
                <strong>PRIMERA.-</strong> Ambas partes acuerdan <strong>MODIFICAR</strong> la cláusula
                @if($contrato->emp_constructora != $contrato->emp_terreno && ($contrato->tipo_credito != 'Crédito Directo'  && $contrato->tipo_credito != 'Apartado'))
                    TERCERA
                @else
                    CUARTA
                @endif
                de <strong>EL CONTRATO</strong>, para quedar como sigue: <br>
            </p>

            @if($contrato->emp_constructora != $contrato->emp_terreno &&  ($contrato->tipo_credito != 'Crédito Directo' && $contrato->tipo_credito != 'Apartado'))
                <p >
                    “El precio total que será motivo de la operación de compraventa definitiva, es la suma que de
                    <strong>${{$contrato->escriturasLetra}}</strong>, de los que la cantidad de ${{$contrato->terrenoLetra}}
                    equivalente al {{$contrato->porcentaje_terreno}}% del precio,
                    corresponde al valor de EL LOTE, en tanto que la cantidad de ${{$contrato->constLetra}} equivalente al
                    {{$contrato->porcentaje_construccion}} del precio
                    le corresponde al valor de la vivienda.”
                    <br>
                </p>
                <p>
                    "Ese precio lo deberá pagar <strong>EL PROMITENTE COMPRADOR</strong> a <strong>LOS PROMITENTES</strong>
                    VENDEDORES de la siguiente manera: a).La cantidad de <strong>${{$contrato->creditoLetra}}</strong>, mediante el
                    crédito que le otorgara <strong>{{$contrato->institucion}}</strong>
                    @if($contrato->infonavit > 0 || $contrato->tipo_credito == 'INFONAVIT' || $contrato->tipo_credito == 'COFINAVIT I.A')
                        y la cantidad de <strong>${{strtoupper($contrato->segnundocreditoLetra)}}</strong> que le otorga
                        @if($contrato->tipo_credito == 'BANJERCITO')
                            BANJERCITO
                        @else
                            INFONAVIT
                        @endif
                    @elseif($contrato->fovisste > 0)
                        y la cantidad de <strong>${{strtoupper($contrato->segnundocreditoLetra)}}</strong> que le otorga FOVISSTE
                    @endif.
                    @if($contrato->numPagos > 0)
                        b).El resto, mediante
                        <strong>{{$contrato->totalPagos}}</strong> pagos,
                        @for($i=0; $i < count($contrato->depositos); $i++) el <strong>{{$contrato['depositos'][$i]['numeros']}}</strong> por la cantidad de
                            <strong>${{strtoupper($contrato['depositos'][$i]->montoPagoLetra)}},</strong>
                            que será liquidado a más tardar el día <strong>{{$contrato['depositos'][$i]->fecha_pago}}</strong>
                        @endfor, respectivamente.
                    @endif
                    ”
                </p>

            @elseif($contrato->emp_constructora != $contrato->emp_terreno && ($contrato->tipo_credito == 'Crédito Directo' || $contrato->tipo_credito == 'Apartado'))
                <p>
                    El precio total que será motivo de la operación de compraventa definitiva, es la suma
                    que de <strong>${{strtoupper($contrato->escriturasLetra)}}</strong>,
                    de los que la cantidad de  ${{strtoupper($contrato->terrenoLetra)}}
                    equivalente al {{$contrato->porcentaje_terreno}}%
                    del precio, corresponde al valor de <strong>EL LOTE</strong>, en tanto que la cantidad
                    de ${{strtoupper($contrato->constLetra)}} equivalente al {{$contrato->porcentaje_construccion}}%
                    del precio le corresponde al valor de la vivienda. <br>
                </p>

                <p>
                   " Ese precio sera cubierto mediante <strong>{{$contrato->totalPagos}}</strong>pagos,
                    @for($i=0; $i < count($contrato->depositos); $i++)
                        el <strong>{{$contrato['depositos'][$i]['numeros']}}</strong> por la cantidad de
                        <strong>${{strtoupper($contrato['depositos'][$i]->montoPagoLetra)}},</strong>
                        que será liquidado a más tardar el día <strong>{{$contrato['depositos'][$i]->fecha_pago}}</strong>,
                    @endfor
                    respectivamente. Cada uno de estos pagos, debera entregarse a <strong>CONCRETANIA</strong>, quien enterara la parte correspondiente a
                    <strong>CUMBRES</strong>. "
                </p>

            @elseif($contrato->emp_constructora == $contrato->emp_terreno && ($contrato->tipo_credito != 'Crédito Directo' && $contrato->tipo_credito != 'Apartado'))
                <p>
                    "El precio total que convienen las partes, será motivo de la operación de compraventa definitiva,
                    es la cantidad que de <strong>${{strtoupper($contrato->escriturasLetra)}}</strong>,
                    mismo que EL PROMITENTE COMPRADOR se obliga a pagar a EL PROMITENTE VENDEDOR en los términos establecidos en la
                    cláusula de la siguiente manera: a) La cantidad de
                        <strong>${{strtoupper($contrato->creditoLetra)}},</strong>
                    mediante el crédito que le otorgara {{mb_strtoupper($contrato->institucion)}}
                    @if($contrato->infonavit > 0) y la cantidad de <strong>${{strtoupper($contrato->segnundocreditoLetra)}}</strong> que le otorga
                        @if($contrato->tipo_credito == 'BANJERCITO')
                            BANJERCITO
                        @else
                            INFONAVIT
                        @endif
                    @elseif($contrato->fovisste > 0) y la cantidad de <strong>${{strtoupper($contrato->segnundocreditoLetra)}}</strong> que le otorga FOVISSTE
                    @endif."
                    <br>
                </p>
                <p>
                    @if($contrato->numPagos > 0)
                        " b) El resto, mediante
                        <strong>{{$contrato->totalPagos}}</strong>pagos,
                        @for($i=0; $i < count($contrato->depositos); $i++) el <strong>{{$contrato['depositos'][$i]['numeros']}}</strong> por la cantidad de
                            <strong>${{strtoupper($contrato['depositos'][$i]->montoPagoLetra)}},</strong>
                            que será liquidado a más tardar el día <strong>{{$contrato['depositos'][$i]->fecha_pago}}</strong>
                        @endfor, respectivamente. "
                    @endif
                </p>
            @elseif($contrato->emp_constructora == $contrato->emp_terreno && ($contrato->tipo_credito == 'Crédito Directo' || $contrato->tipo_credito == 'Apartado'))
                <p>
                    "El precio total que convienen las partes motivo de esta operación será de
                    <strong> ${{strtoupper($contrato->escriturasLetra)}} </strong> el precio Convenido
                    por el <strong>LOTE</strong> y <strong>LA VIVIENDA</strong>
                    será pagado por <strong>EL COMPRADOR</strong> de la siguiente forma:"
                </p>

                <p>
                    "* Mediante <strong>{{$contrato->totalPagos}}</strong>pagos,
                    @for($i=0; $i < count($contrato->depositos); $i++)
                        el <strong>{{$contrato['depositos'][$i]['numeros']}}</strong>
                        por la cantidad de <strong>${{strtoupper($contrato['depositos'][$i]->montoPagoLetra)}},</strong>
                        que será liquidado a más tardar el día <strong>{{$contrato['depositos'][$i]->fecha_pago}}</strong>
                    @endfor,
                    respectivamente. "
                </p>
            @endif
            <br>

            <p>
                <strong>SEGUNDA.- </strong>Las partes convienen y pactan que en este convenio no existe error, dolo,
                lesión, intimidación, falta de forma o capacidad ni ninguna otra causa que pueda motivar la rescisión o nulidad
                y convienen que surta sus efectos a partir del día de su firma, siendo producto de la buena fe,
                por lo que todo conflicto que resulte del mismo en cuanto a su interpretación y cumplimiento será resuelto
                de común acuerdo y para todo lo no especificado en su contenido, se someten a solucionar las diferencias que
                surjan mediante una amigable composición, y en caso necesario para su interpretación y cumplimiento, pactan su
                voluntad para someterse a los Tribunales Jurisdiccionales de la Ciudad de San Luis Potosí.
            </p>
            <br>

            <p>
                <strong>TERCERA.- </strong>Ambas partes están de acuerdo en que la firma del presente convenio no implica
                novación de contrato, manteniéndose, salvo las modificaciones mencionadas en la cláusulas
                @if($contrato->emp_constructora != $contrato->emp_terreno && ($contrato->tipo_credito != 'Crédito Directo' && $contrato->tipo_credito != 'Apartado'))
                    TERCERA,
                @else
                    CUARTA,
                @endif
                en los mismos términos y condiciones en los cuales se firmó <strong>EL CONTRATO</strong>.
            </p>
            <br>

            <p>
                <strong>CUARTA.-</strong>Las partes convienen en que este convenio contiene su voluntad expresa en cuanto a lo que en el
                mismo se especifica, por consiguiente, cualquier otro convenio, contrato o arreglo en forma verbal o escrita que se haya
                elaborado o que tácitamente pudiera interpretarse como manifestación de voluntad al respecto queda desde ahora sin efecto.
                Las posteriores modificaciones que afecten  a este documento, deberán hacerse por escrito y ser firmados por ambas partes.
            </p>
            <br>

            <p>
                Leído que fue el presente convenio por las partes e impuestas de su alcance y valor legal, expresaron su conformidad
                por no existir actos o vicios que puedan invalidarlo o restarle los requisitos de formalidad y legalidad que la ley establece,
                lo ratificaron y estamparon sus firmas a {{$contrato->fechaFin}}, en el Municipio y
                Estado de San Luis Potosí.
            </p>
            <br>
            <br>
            @if($contrato->emp_constructora == $contrato->emp_terreno)
                <br><br><br><br>
            @endif

            <font size="11.5">
                <table>
                    <tr>
                        <td width=460 bgcolor="#c2c2c2"
                            style="border: 1px solid #00000a; padding-top: 0.05in; padding-bottom: 0.1in; ">
                                <strong><center>POR LOS CONTRATANTES</center></strong>
                        </td>
                    </tr>
                </table>
            </font>
            <br><br>

            <p align="center"><b>LA PARTE VENDEDORA</p>

            <br><br><br>

            @if($contrato->emp_constructora != $contrato->emp_terreno)
                <table style="margin-left: -0.15in;">
                    <tr>
                        <td width=220 colspan="5"><center>__________________________________</center></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td width=220 colspan="5"><center>__________________________________</center></td>
                    </tr>
                    <tr>
                        <td width=220 colspan="5"><center>GRUPO CONSTRUCTOR CUMBRES, S.A. de C.V.
                            <br> REPRESENTADA POR
                            <br> LA SRA. MAYRA JAZMIN SALAZAR ALONSO</center></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td width=220 colspan="5"><center>CONCRETANIA, S.A. de C.V.
                            <br> REPRESENTADA POR
                            <br> LA SRA. ELIZABETH HERNÁNDEZ LOERA</center></td>
                    </tr>
                </table>
            @else
                <center>
                    <table>
                        <tr>
                            <td width=460 colspan="5"><center>_____________________________________</center></td>
                        </tr>
                        <tr>
                            <td width=460 colspan="5">
                                <center>
                                    {{mb_strtoupper($contrato->emp_constructora)}}, S.A. de C.V.
                                    @if($contrato->emp_constructora == 'CONCRETANIA')
                                        <br> REPRESENTADA POR
                                        <br> LA SRA. ELIZABETH HERNÁNDEZ LOERA
                                    @else
                                        <br> REPRESENTADA POR
                                        <br> LA SRA. MAYRA JAZMIN SALAZAR ALONSO
                                    @endif
                                </center>
                            </td>
                        </tr>
                    </table>
                </center>
            @endif

            <br><br>

            <p align="center"><b>LA PARTE COMPRADORA</p>

            <br><br><br>
            @if($contrato->coacreditado == 0)
                <center>
                    <table>
                        <tr>
                            <td width=460 colspan="5"><center>_____________________________________</center></td>
                        </tr>
                        <tr>
                            <td width=460 colspan="5"><center>Sr(a). {{$contrato->nombre_completo}}</center></td>
                        </tr>
                    </table>
                </center>
            @else
                <table style="margin-left: -0.15in;">
                    <tr>
                        <td width=220 colspan="5"><center>__________________________________</center></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td width=220 colspan="5"><center>__________________________________</center></td>
                    </tr>
                    <tr>
                        <td width=220 colspan="5"><center>Sr(a). {{$contrato->nombre_completo}}</center></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td width=220 colspan="5"><center>Sr(a). {{$contrato->nombre_coa}} {{$contrato->apellidos_coa}}</center></td>
                    </tr>
                </table>
            @endif


        </div>

    </body>
</html>
