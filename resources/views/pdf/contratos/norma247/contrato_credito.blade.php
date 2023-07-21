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
        font-size: 9.5pt;
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
        font-size: 9.5pt;
    }

    .table2 { display: table; width:100%;  border: ridge #0B173B 1px; color:black;}
    .table-cell2 {
        display: table-cell; padding: 0.1em; font-size: 9pt;
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


    ol {
        counter-reset: item;
        list-style-type: none;
    }
    ol > li {
        position: relative;
        margin-bottom: 7px;
    }
    /* li { display: block; } */
    ol > li:before {
        content: counter(item, lower-alpha) ")";
        counter-increment: item;
        position: absolute;
        font-weight:bold;
        left: -28px;
    }

    .ul-custom {
        counter-reset: item;
        list-style-type: none;
    }
    .ul-custom > .li-custom {
        position: relative;
    }
    /* li { display: block; } */
    .ul-custom > .li-custom:before {
        content: "-";
        counter-increment: item;
        position: absolute;
        font-weight:bold;
        left: -28px;
    }



</style>

<body>
    <div>
        <strong>
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                <p>
                    CONTRATO DE PROMESA DE COMPRAVENTA DE BIEN INMUEBLE DESTINADO A CASA HABITACIÓN QUE CELEBRAN, POR UNA PARTE LA SOCIEDAD MERCANTIL
                    DENOMINADA GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V., REPRESENTADA EN ESTE ACTO POR LA SRA. MAYRA JAZMIN SALAZAR ALONSO
                    A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, SE LE DENOMINARÁ COMO CUMBRES; LA DIVERSA SOCIEDAD DENOMINADA
                    CONCRETANIA, S. A. DE. C.V,
                    REPRESENTADA EN ESTE ACTO POR LA SRA. ELIZABETH HERNÁNDEZ LOERA A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO,
                    SE LE DENOMINARA COMO CONCRETANIA Y A AMBAS PERSONAS MORALES CUANDO SE LES DESIGNE CONJUNTAMENTE SERA COMO
                    LOS PROMITENTES VENDEDORES Y POR LA OTRA PARTE, POR SU PROPIO DERECHO
                    EL(A) SR(A).
                        {{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}
                        @if ($contrato->coacreditado == 1)
                            y {{ mb_strtoupper($contrato->nombre_coa) }}
                            {{ mb_strtoupper($contrato->apellidos_coa) }}
                        @endif

                    A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE LE DENOMINARÁ COMO EL PROMITENTE COMPRADOR,
                    AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.
                </p>
            @else

                <p>
                    CONTRATO DE PROMESA DE COMPRAVENTA DE BIEN INMUEBLE DESTINADO A CASA HABITACIÓN QUE CELEBRAN, POR UNA PARTE LA SOCIEDAD MERCANTIL
                    DENOMINADA
                    @if($contrato->emp_constructora == 'Grupo Constructor Cumbres')
                        GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V., REPRESENTADA EN ESTE ACTO POR LA SRA. MAYRA JAZMIN SALAZAR ALONSO
                    @else
                        CONCRETANIA, S. A. DE. C.V, REPRESENTADA EN ESTE ACTO POR LA SRA. ELIZABETH HERNÁNDEZ LOERA
                    @endif
                    A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO, SE LE DENOMINARÁ COMO "EL PROMITENTE VENDEDOR"
                    Y POR LA OTRA PARTE, POR SU PROPIO DERECHO
                    EL(A) SR(A).
                        {{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}
                        @if ($contrato->coacreditado == 1)
                            y {{ mb_strtoupper($contrato->nombre_coa) }}
                            {{ mb_strtoupper($contrato->apellidos_coa) }}
                        @endif

                    A QUIEN EN LO SUCESIVO Y PARA LOS EFECTOS DEL PRESENTE CONTRATO SE LE DENOMINARÁ COMO "EL PROMITENTE COMPRADOR",
                    AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.
                </p>
        </strong>


        @endif

        <h4 align="center">"D E C L A R A C I O N E S"</h4>

        @if($contrato->emp_terreno != $contrato->emp_constructora)

            <p>I.- Declara <strong>CUMBRES</strong>, por conducto de su representante:</p>

            <p>
                a)	Que es una sociedad mercantil constituida en escritura pública número 3 de fecha 8 de diciembre de 1999,
                otorgada ante el Notario Público número 33 del primer distrito judicial del estado de San Luis Potosí,
                Lic. Leopoldo de la Garza Marroquín, inscrita en el Registro    Público de la Propiedad y del
                Comercio en San Luis Potosí, San Luis Potosí,  bajo el folio mercantil 70 que su domicilio fiscal se
                encuentra en Manuel Gutiérrez Nájera
                    {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }}. Colonia Tequisquiapan, San Luis Potosí, San Luis Potosí y su
                Registro Federal de Contribuyentes es <strong>GCC000106QS6</strong>
            </p>

            <p>
                b)	Que las facultades de su representante constan en la escritura pública número 1560
                    de fecha 07 de noviembre del 2022, otorgada ante el Notario Público número 19 del primer distrito judicial de
                    San Luis Potosí, Lic. Alfredo Noyola Robles, inscrita en el Registro Público de la Propiedad y del
                    Comercio en San Luis Potosí, bajo el folio mercantil 123547, mismas atribuciones que no le han sido revocadas a la fecha.
            </p>

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
                lo cual ha sido debidamente exhibido y explicado a EL PROMITENTE COMPRADOR y además se encuentra a su
                disposición en el  domicilio mercantil ubicado en Manuel Gutiérrez Nájera
                    {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }} Col. Tequisquiapan, de la Ciudad de San Luis Potosí, S.L.P.
            </p>

            <br>

            <p>II.- Declara <strong>CONCRETANIA</strong> por medio de su representante:</p>

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

            <p>
                c)	Que su objeto social es la ejecución, administración, construcción, promoción, comercialización y arrendamiento de desarrollos
                inmobiliarios, comerciales y habitacionales; así como la celebración de los contratos mercantiles que sean necesarios para el
                cumplimiento de su objeto.
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
                el domicilio de <strong>“LOS PROMITENTES VENDEDORES”.</strong> De igual forma, el inmueble cuenta
                con las especificaciones técnicas, de seguridad, materiales utilizados,
                servicios básicos y demás características que se indican en el “Anexo A”. La propiedad se acredita en términos
                del Instrumento Notarial, el cual está a su disposición en el domicilio ubicado en	Manuel Gutiérrez Nájera
                    {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }}.
                Colonia Tequisquiapan, San Luis Potosí, San Luis Potosí.
            </p>

            <p>
                d)	Que, con respecto a los planos estructurales, arquitectónicos y de instalaciones:
            </p>

            <p>
                <ul class="ul-custom">
                    <li><template class="cuadrado"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg=="></template> <strong>SI</strong> cuenta con ellos, mismos que le han sido exhibidos
                        y explicados a <strong>“EL PROMITENTE COMPRADOR” </strong>
                        y se encuentran nuevamente a su disposición para consulta en el domicilio de <strong>“LOS PROMITENTENTES VENDEDORES”.</strong></li>
                        <br><br>
                    <li><template class="cuadrado"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg=="></template> NO cuenta con ellos, sin embargo, cuenta con el Dictamen de las Condiciones
                        Estructurales, mismo que le ha sido exhibido
                        y explicado a <strong>“EL PROMITENTE COMPRADOR”</strong> y se encuentra nuevamente a su disposición para consulta en
                        el domicilio de <strong>“LOS PROMITENTENTES VENDEDORES”.</strong> </li>
                        <br><br>
                    <li><template class="cuadrado"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg=="></template> NO cuenta con ellos ni con el dictamen de las condiciones estructurales, en razón de que
                        ___________________________, sin embargo, dicha documentación estará puesta a su disposición a
                        partir del _______________________________________. </li>

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

        @else

            <p><strong>I.- Declara "EL PROMITENTE VENDEDOR": por conducto de su representante:</strong></p>

            <ol>
                @if($contrato->emp_constructora == 'Grupo Constructor Cumbres')
                    <li>
                        Que es una sociedad mercantil constituida en escritura pública número 3 de fecha 8 de diciembre de 1999,
                        otorgada ante el Notario Público número 33 del primer distrito judicial del estado de San Luis Potosí,
                        Lic. Leopoldo de la Garza Marroquín, inscrita en el Registro    Público de la Propiedad y del
                        Comercio en San Luis Potosí, San Luis Potosí,  bajo el folio mercantil 70 que su domicilio fiscal se
                        encuentra en Manuel Gutiérrez Nájera
                            {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }}. Colonia Tequisquiapan, San Luis Potosí, San Luis Potosí y su
                        Registro Federal de Contribuyentes es <strong>GCC000106QS6</strong>
                    </li>
                    <li>
                        Que las facultades de su representante constan en la escritura pública número 1560
                        de fecha 07 de noviembre del 2022, otorgada ante el Notario Público número 19 del primer distrito judicial de
                        San Luis Potosí, Lic. Alfredo Noyola Robles, inscrita en el Registro Público de la Propiedad y del
                        Comercio en San Luis Potosí, bajo el folio mercantil 123547, mismas atribuciones que no le
                        han sido revocadas a la fecha.
                    </li>
                @else
                    <li>
                        Que es una sociedad mercantil debidamente constituida con arreglo a las leyes de la República Mexicana según consta en la
                        escritura pública número 764 volumen 21 de fecha 25 de julio de 2018, otorgada ante la fe del Lic. Octaviano Gómez y González,
                        Notario Público número 4, con ejercicio en la Ciudad  de San Luis Potosí, S.L.P, cuyo testimonio obra inscrito en el Registro
                        Público de la Propiedad y Comercio de esa misma Ciudad, bajo el folio mercantil electrónico N-2018073682, que su domicilio
                        fiscal es Manuel Gutiérrez Nájera #180,  Col Tequisquiapan,  en la Ciudad de San Luis Potosí y su registro federal de Contribuyentes
                        es <strong>CON180725REA.</strong>
                    </li>
                    <li>
                        Que  las facultades de su representante constan en la escritura pública número 1560 de fecha 07 de noviembre del 2022,
                        otorgada ante el Notario Público número 19 del primer distrito judicial de la ciudad de San Luis Potosí, Lic. Alfredo Noyola Robles
                        inscrita en el Registro Público de la Propiedad y del Comercio en San Luis Potosí, S.L.P, bajo el folio mercantil
                        123507, mismas atribuciones que no le han sido revocadas a la fecha.
                    </li>
                @endif
                <li>
                    Que su objeto social es, la ejecución, administración, construcción, promoción, comercialización y arrendamiento
                    de desarrollos inmobiliarios, comerciales y habitacionales dentro del territorio nacional y en el extranjero, así como la celebración de los
                    contratos mercantiles necesarios para el cumplimiento de  su objeto.
                </li>
                <li>
                    Que es única y legitima propietaria del inmueble materia de este contrato, descrito a detalle en el
                    <strong>“Anexo A”,</strong> que firmado por las partes, es integrante de este instrumento y
                    se localiza en la siguiente ubicación;
                    <p>
                        Calle:&nbsp; <u>{{mb_strtoupper($contrato->calle_lote)}}</u> <br>
                        Lote o área privativa:&nbsp; <u>{{$contrato->num_lote}}</u>  <br>
                        Manzana o condominio:&nbsp; <u>{{mb_strtoupper($contrato->manzana)}}</u>  <br>
                        No oficial exterior:&nbsp; <u>{{$contrato->num_oficial}}</u>  <br>
                        @if($contrato->interior != NULL)
                            No oficial interior:&nbsp; <u>{{$contrato->interior}}</u>  <br>
                        @endif
                        Fraccionamiento:&nbsp; <u>{{mb_strtoupper($contrato->proyecto)}}</u> <br>
                        Entidad Federativa:&nbsp; <u>{{mb_strtoupper($contrato->estado_proy)}}</u> <br>
                        Código Postal:&nbsp; <u>{{$contrato->cp_proy}}</u> <br>

                    </p>
                    <p>
                        Y acredita la propiedad con la escritura pública numero {{ $contrato->num_escritura }} de fecha {{$contrato->date_escritura}}
                        otorgada ante la fe del Notario publico número {{$contrato->num_notario}} del distrito de {{$contrato->distrito_notario}},
                        con inscripción en el Registro Publico de la Propiedad bajo el folio real No.{{$contrato->folio_registro}},
                        lo cual ha sido debidamente exhibido y explicado a <strong>"EL PROMITENTE COMPRADOR"</strong> y además se encuentra
                        a su disposición en el domicilio mercantil ubicado en Manuel Gutiérrez Nájera
                            {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }}
                        Col. Tequisquiapan, de la Ciudad de San Luis Potosí, S.L.P.
                    </p>
                    <p>
                        Dicha documentación puede ser consultada por <strong>“EL PROMITENTE COMPRADOR”</strong>
                        en el domicilio del <strong>“EL PROMITENTE VENDEDOR”</strong><br>
                        Calle:&nbsp; Manuel Gutiérrez Nájera
                            {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }} <br>
                        Col.&nbsp; Tequisquiapan <br>
                        C.P.&nbsp; 78230, San Luis Potosí, S.L.P. <br>
                        No telefónico&nbsp; 444 8334683 <br>
                        Correo electrónico:&nbsp; atencion@grupocumbres.com
                    </p>

                    <p>
                        En caso que el inmueble este sujeto al régimen de propiedad en condómino. El inmueble indicado está sujeto
                        al régimen de propiedad en condominio en términos de la escritura pública número: {{$contrato->num_escritura}}, otorgada en fecha
                        {{$contrato->date_escritura}} ante la fe del Notario Público número {{$contrato->num_notario}} con ejercicio en
                        la  ciudad de San Luis Potosí, El {{$contrato->notaria ? $contrato->notaria->titular : '____________' }}, y debidamente inscrita el día, mes y año con
                        inscripción en el Registro Público de la Propiedad bajo el folio real N°.{{$contrato->folio_registro}}, instrumento en el cual están referidas las correspondientes
                        áreas de uso común y porcentaje de indiviso, lo cual ha sido debidamente exhibido y explicado a
                        <strong>EL PROMITENTE COMPRADOR.</strong>
                    </p>
                </li>
                <li>
                    Que es su voluntad celebrar el presente contrato por virtud del cual se establecen los términos y condiciones bajo
                    las que se celebrará el contrato de compraventa por el que “EL PROMITENTE VENDEDOR” venderá y “EL
                    PROMITENTE COMPRADOR” comprará, el inmueble cuya superficie, ubicación, acabados, características,
                    especificaciones técnicas, de seguridad, materiales utilizados, se encuentran descritas en el citado “Anexo A”.
                </li>
                <li>
                    Que el inmueble objeto de la compraventa cuenta con lo descrito en el “Anexo A” que firmado por las contratantes
                    forma partede este instrumento, como aparece en el título de propiedad relacionado en dicho “Anexo A”, y que cuenta
                    con los planos estructurales, arquitectónicos y de instalaciones (o en su defecto indicar que se cuenta con el
                    dictamen de las condiciones estructurales), así como con las autorizaciones, licencias y permisos expedidos por
                    las autoridades competentes para la construcción, seguridad, uso de suelo, y demás relativas al Inmueble, mismas
                    que han sido exhibidas a <strong>“EL PROMITENTE COMPRADOR”</strong> y se encuentran a su disposición en el domicilio de
                    <strong>“EL PROMITENTE VENDEDOR”</strong> De igual forma, el inmueble cuenta con las especificaciones técnicas, de
                    seguridad, materiales utilizados, servicios básicos y demás características que se indican en el “Anexo A”. La
                    propiedad se acredita en términos del Instrumento Notarial, el cual está a su disposición en el domicilio ubicado en
                    Manuel Gutiérrez Nájera
                        {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }} Col. Tequisquiapan de la Ciudad de San Luis Potosí, S.L.P.
                </li>
                <li>
                    Que, con respecto a los planos estructurales, arquitectónicos y de instalaciones: <br><br>
                    <p>
                        <ul class="ul-custom">
                            <li>
                                <template class="cuadrado">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg==">
                                </template>
                                <strong>SI</strong> cuenta con ellos, mismos que le han sido exhibidos
                                y explicados a <strong>“EL PROMITENTE COMPRADOR” </strong>
                                y se encuentran nuevamente a su disposición para consulta en el domicilio de
                                <strong>“El PROMITENTENTE VENDEDOR”.</strong></li>
                                <br><br>
                            <li>
                                <template class="cuadrado">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg==">
                                </template>
                                <strong>NO</strong> cuenta con ellos, sin embargo, cuenta con el Dictamen de las Condiciones
                                Estructurales, mismo que le ha sido exhibido
                                y explicado a <strong>“EL PROMITENTE COMPRADOR”</strong> y se encuentra nuevamente a su disposición para consulta en
                                el domicilio de <strong>“EL PROMITENTENTE VENDEDOR”.</strong> </li>
                                <br><br>
                            <li>
                                <template class="cuadrado">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg==">
                                </template>
                                <strong>NO</strong> cuenta con ellos ni con el dictamen de las condiciones estructurales, en razón de que
                                ___________________________, sin embargo, dicha documentación estará puesta a su disposición a
                                partir del _______________________________________.
                            </li>

                        </ul>
                    </p>
                </li>
                <li>
                    Que cuenta con las autorizaciones, licencias y permisos expedidos por las autoridades competentes para la construcción,
                    seguridad, uso de suelo, y demás relativas al Inmueble y demás documentos que se indican en el “Anexo B”,
                    los cuales le han sido exhibidos y explicados a <strong>“EL PROMITENTE COMPRADOR”</strong> y se encuentran nuevamente a su disposición
                    para consulta en el domicilio de <strong>“EL PROMITENTENTE VENDEDOR”.</strong>
                </li>
                <li>
                    Que al momento de la escrituración que formalice el contrato de compraventa del inmueble destinado a vivienda,
                    dicho inmueble debe estar libre de todo gravamen que afecte la propiedad de
                    <strong>“EL PROMITENTE COMPRADOR”</strong> sobre el mismo.
                </li>
            </ol>

        @endif

        <p>
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                III.-
            @else
                II.-
            @endif
            Declara <strong>"EL PROMITENTE COMPRADOR":</strong></p>

        <ol>
            <li>
                Ser una persona física de nacionalidad {{($contrato->nacionalidad) == 0 ? 'Mexicana': 'Extranjera'}}
                Que se identifica con cualquiera de los
                siguientes documentos, INE {{$contrato->num_ine}} o  Pasaporte No. {{($contrato->num_pasaporte)?$contrato->num_pasaporte : '_____'}},
                llamada como ha quedado escrito, haber nacido en {{$contrato->lugar_nacimiento}}, el {{$contrato->f_nacimiento}},
                estado civil {{$contrato->edo_civil}}, de ocupación {{ucwords($contrato->puesto)}}, con domicilio en
                {{ucwords($contrato->dir_cliente)}} Col. {{ucwords($contrato->col_cliente)}}, con Registro Federal de Contribuyentes {{$contrato->rfc}}-{{$contrato->homoclave}}
                y con capacidad legal y económica para celebrar este contrato.
            </li>
            <li>
                Que es derechohabiente del: <br><br>
                <p>
                    <ul class="ul-custom">
                        <li><template class="cuadrado">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg==">
                            </template> Instituto del Fondo Nacional de la Vivienda para los Trabajadores <strong>(INFONAVIT)</strong>. </li>
                             <br><br>
                         <li><template class="cuadrado">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg==">
                            </template> Instituto de Seguridad y Servicios Sociales para los Trabajadores del Estado.</li>
                            <br><br>
                         <li><template class="cuadrado">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg==">
                            </template>Otro _________________________________ </li>
                    </ul>
                </p>

            </li>
            <li>
                Que gestionará y obtendrá un crédito hipotecario con cualesquiera de las instituciones de crédito autorizadas en el País,
                para ejercerlo con o sin cofinanciamiento con otra institución de crédito, según convenga a sus intereses.
            </li>
        </ol>

        <p>Habiendo declarado lo anterior, las partes se obligan en los términos de las siguientes:</p>



        <p align="center"><strong>C L Á U S U L A S</strong></p>

        <p>
            <strong>PRIMERA.- <u>OBJETO.</u></strong> El presente contrato sólo da origen a obligaciones de hacer a cargo de las partes,
            las cuales prometen celebrar un contrato de compraventa con las formalidades que para este tipo de acto jurídico exige
            la legislación aplicable, respecto del inmueble que se identifica con la superficie, características y especificaciones
            señaladas en el “Anexo A”, siempre y cuando se cumpla con la condición suspensiva referida en la Cláusula Segunda siguiente.
        </p>

        <p>
            <strong>SEGUNDA.- <u>CONDICIÓN A QUE SE SUJETA EL CONTRATO DE PROMESA DE COMPRAVENTA.</u> </strong>
            Las partes establecen que la celebración del Contrato de Compraventa estará condicionada al hecho de
            que a <strong>“EL PROMITENTE COMPRADOR”</strong> le sea aprobado y otorgado el crédito por parte de
            @if($contrato->tipo_credito != 'INFONAVIT-FOVISSSTE' && $contrato->tipo_credito != 'Cofinavit'
                && $contrato->tipo_credito != 'Infonavit Mas Banco'
                && $contrato->tipo_credito != 'COFINAVIT I.A' && $contrato->tipo_credito != 'Apoyo Infonavit')
                {{ mb_strtoupper($contrato->institucion) }}
            @else
                @if($contrato->tipo_credito == 'INFONAVIT-FOVISSSTE' )
                    INFONAVIT y FOVISSTE
                @else
                    {{mb_strtoupper($contrato->institucion)}} e INFONAVIT
                @endif
            @endif
            con el que hará el pago del precio del inmueble.
        </p>

        <p>
            Para la gestión y trámite del crédito hipotecario, <strong>“EL PROMITENTE COMPRADOR”:</strong>
        </p>

        <p>
            <ul class="ul-custom">
                <li><template class="cuadrado"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg=="></template>
                    Solicita a <strong>“EL PROMITENTE VENDEDOR”</strong> realice la gestión correspondiente ante
                    @if($contrato->tipo_credito != 'INFONAVIT-FOVISSSTE' && $contrato->tipo_credito != 'Cofinavit'
                        && $contrato->tipo_credito != 'Infonavit Mas Banco'
                        && $contrato->tipo_credito != 'COFINAVIT I.A' && $contrato->tipo_credito != 'Apoyo Infonavit')
                        {{ mb_strtoupper($contrato->institucion) }}
                    @else
                        @if($contrato->tipo_credito == 'INFONAVIT-FOVISSSTE' )
                            INFONAVIT y FOVISSTE
                        @else
                            {{mb_strtoupper($contrato->institucion)}} e INFONAVIT
                        @endif
                    @endif, para la tramitación de su crédito
                    para adquisición de vivienda, en el entendido que ello no implica que <strong>“EL PROMITENTE VENDEDOR”</strong> asuma la obligación
                    o compromiso de que dicho crédito le sea autorizado a <strong>“EL PROMITENTE COMPRADOR”,</strong> y solo se limita a realizar la gestión
                    y trámite directamente ante
                    @if($contrato->tipo_credito != 'INFONAVIT-FOVISSSTE' && $contrato->tipo_credito != 'Cofinavit'
                        && $contrato->tipo_credito != 'Infonavit Mas Banco'
                        && $contrato->tipo_credito != 'COFINAVIT I.A' && $contrato->tipo_credito != 'Apoyo Infonavit')
                        {{ mb_strtoupper($contrato->institucion) }}
                    @else
                        @if($contrato->tipo_credito == 'INFONAVIT-FOVISSSTE' )
                            INFONAVIT y FOVISSTE
                        @else
                            {{mb_strtoupper($contrato->institucion)}} e INFONAVIT
                        @endif
                    @endif, por cuenta y orden de
                    <strong>“EL PROMITENTE COMPRADOR”</strong>. Para tales efectos <strong>“EL PROMITENTE COMPRADOR”</strong>
                    se obliga a entregar a <strong>“EL PROMITENTE VENDEDOR”</strong>, dentro del plazo de 5 días hábiles siguientes a
                    la firma de este contrato, toda la documentación que le sea requerida y que sea necesaria para
                    realizar dicho trámite, así como a cubrir los montos que
                    @if($contrato->tipo_credito != 'INFONAVIT-FOVISSSTE' && $contrato->tipo_credito != 'Cofinavit'
                        && $contrato->tipo_credito != 'Infonavit Mas Banco'
                        && $contrato->tipo_credito != 'COFINAVIT I.A' && $contrato->tipo_credito != 'Apoyo Infonavit')
                        {{ mb_strtoupper($contrato->institucion) }}
                    @else
                        @if($contrato->tipo_credito == 'INFONAVIT-FOVISSSTE' )
                            INFONAVIT y FOVISSTE
                        @else
                            {{mb_strtoupper($contrato->institucion)}} e INFONAVIT
                        @endif
                    @endif
                    requiera para tal efecto, los cuales
                    no formarán parte del precio. <strong>“EL PROMITENTE COMPRADOR”</strong> podrá solicitar, en cualquier momento,
                    la cancelación de los servicios de gestión.
                    <br><br>

                    Por la prestación de los servicios de gestión solicitados, <strong>“EL PROMITENTE COMPRADOR”</strong> se
                    obliga a pagar, por concepto de contraprestación, la cantidad de $ 0.00 (Cero pesos 00/100 M.N.), más el correspondiente
                    Impuesto al Valor Agregado, el cual deberá realizarse una vez que se obtenga el resultado respectivo, en una sola exhibición,
                    mediante depósito o transferencia electrónica a la cuenta bancaria que para tal efecto le sea proporcionada a
                    <strong>“EL PROMITENTE COMPRADOR”</strong>. En dicho importe no estarán comprendidos los gastos y demás erogaciones que
                    deba cubrir <strong>“EL PROMITENTE COMPRADOR”</strong> tales como comisiones por apertura, avalúo y/o cualquier otro concepto.
                    <strong>“EL PROMITENTE COMPRADOR”</strong> deberá realizar el pago de tales gastos y erogaciones directamente a
                    los interesados. <strong>“EL PROMITENTE VENDEDOR”</strong> deberá entregar a favor de <strong>“EL PROMITENTE COMPRADOR”</strong> el
                    Comprobante Fiscal Digital por Internet correspondiente.
                </li><br><br>
                <li><template class="cuadrado"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAAaklEQVQ4je3Uuw2AMBRD0WuUBRiDzwRImYeWdZgHKRMA65gCOsqHqOLOzZEra+i6RWLig9iUJDEZRtAW5LIE6S7ajvOcI9zQ9yu4bWKr3qlgBStYwX/A5w+d7z+LxBnYk02RANwGx+025QJbXxksupQZ7wAAAABJRU5ErkJggg=="></template>
                    Llevará a cabo por cuenta propia la tramitación de su crédito para adquisición de vivienda, por lo que
                    <strong>“EL PROMITENTE COMPRADOR”</strong> se obliga a realizar todas las gestiones y entregar a
                    @if($contrato->tipo_credito != 'INFONAVIT-FOVISSSTE' && $contrato->tipo_credito != 'Cofinavit'
                        && $contrato->tipo_credito != 'Infonavit Mas Banco'
                        && $contrato->tipo_credito != 'COFINAVIT I.A' && $contrato->tipo_credito != 'Apoyo Infonavit')
                        {{ mb_strtoupper($contrato->institucion) }}
                    @else
                        @if($contrato->tipo_credito == 'INFONAVIT-FOVISSSTE' )
                            INFONAVIT y FOVISSTE
                        @else
                            {{mb_strtoupper($contrato->institucion)}} e INFONAVIT
                        @endif
                    @endif
                    toda la documentación, así como a cubrir todos los gastos que este último le requiera, para llevar a cabo el
                    trámite de autorización y/o aprobación de su crédito.
                </li>
            </ul>
        </p>

        <p>
            Las partes establecen un plazo de 3 días para que <strong>“EL PROMITENTE COMPRADOR”</strong> obtenga y mantenga vigente la autorización o aprobación de su crédito,
            por lo que, si llegado el término del mismo sin que se haya obtenido dicha autorización o aprobación, cualquiera que sea la causa,
            o bien, se obtiene una resolución no favorable, el presente contrato se  dará por terminado de pleno derecho sin necesidad de resolución
            judicial previa alguna. No obstante, lo anterior, si las partes lo estiman conveniente a sus respectivos intereses, podrán pactar
            prórrogas para obtener la resolución relativa al crédito de vivienda de <strong>“EL PROMITENTE COMPRADOR”.</strong>
        </p>

        <p>
            En caso de terminación de este contrato, todos los pagos que <strong>“EL PROMITENTE COMPRADOR”</strong> haya realizado por concepto de gastos
            operativos tales como el avalúo, comisiones por apertura y/o cualquier otro que se haya realizado para iniciar el trámite
            de obtención de su crédito y/o durante el mismo, no serán considerados como parte del precio y deberán ser debidamente
            comprobados a <strong>“EL PROMITENTE COMPRADOR”,</strong> de lo contrario,
            <strong>“EL PROMITENTE VENDEDOR”</strong> deberá devolver a <strong>“EL PROMITENTE COMPRADOR” </strong>
            aquellas cantidades que por concepto de gastos operativos haya pagado.
        </p>

        <p>
            <strong>TERCERA.- <u>PLAZO PARA CELEBRAR EL CONTRATO DE COMPRAVENTA.</u></strong>
            Las partes pactan que la firma del Contrato de Compraventa en escritura pública se llevará a cabo dentro del plazo de
            {{$contrato->diasTramites}} días hábiles
            posteriores a la  fecha en que la institución o en su caso instituciones acreditantes
            hayan notificado ya sea a <strong>“EL PROMITENTE COMPRADOR”</strong> o a
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”,
                @else
                    “EL PROMITENTE VENDEDOR”,
                @endif
            </strong> según sea el caso,
            que le fue aprobado y otorgado el crédito a <strong>“EL PROMITENTE COMPRADOR”.</strong> Las partes podrán convenir las prórrogas
            que estimen convenientes para la firma del contrato de compraventa respectivo.
        </p>

        <p>
            La firma de la escritura pública se llevará a cabo ante el Notario Público que designen las partes , debiendo respetar
            el derecho de <strong>“EL PROMITENTE COMPRADOR”</strong> de poder elegir  a dicho notario público, siempre y
            cuando este se encuentre registrado en el padrón de notarios de la institución acreditante del crédito de vivienda.
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> notificarán a <strong>“EL PROMITENTE COMPRADOR”,</strong> con al menos 1 día hábil de anticipación, la fecha en que
            deberá presentarse ante la Notaría Pública respectiva para la firma de la escritura pública correspondiente.
        </p>

        <p>
            <strong>
                CUARTA.- PRECIO EN EL CONTRATO DE COMPRAVENTA DEFINITIVO.
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong>
            no podrán incrementar injustificadamente el precio por fenómenos naturales, meteorológicos, o contingencias sanitarias.
            En caso de que el importe del crédito no sea suficiente para pagar el precio total,
            <strong>“EL PROMITENTE COMPRADOR”</strong> pagará la diferencia a <strong>“EL PROMITENTE VENDEDOR”</strong> al momento de la
            firma de la escritura pública respectiva.
        </p>

        <p>
            En caso de que <strong>“LAS PARTES”</strong> antes de la entrega del Inmueble acordaran agregar accesorios y
            modificaciones adicionales al Inmueble que no estén contemplados en el <strong>“Anexo A”</strong>, <strong>“EL PROMITENTE COMPRADOR”</strong> se obliga a aceptar
            por escrito y a pagar la diferencia antes de la fecha de firma de la escritura pública que contenga la formalización de este Contrato
            en el precio que <strong>“LAS PARTES”</strong> convengan antes de que <strong>“EL PROMITENTE VENDEDOR”</strong>
            instale y realice dichos accesorios y
            modificaciones para lo cual utilizarán el formato que se indica en el <strong>“Anexo E”</strong> indicando asimismo el plazo para la
            entrega del Inmueble incluyendo dichas modificaciones.
        </p>

        <p>
            <strong>QUINTA.- <u>ESTIPULACIONES QUE CONTENDRÁ EL CONTRATO DE COMPRAVENTA.</u> </strong>
            Las partes establecen que el Contrato de Compraventa contendrá las siguientes disposiciones:
        </p>

        <p>
            <ul class="ul-custom">
                <li class="li-custom">
                    El objeto de la compraventa será el inmueble descrito en el “Anexo A”.
                </li>
                <li class="li-custom">
                    El precio de la operación será la cantidad de $	{{$contrato->precio_venta}}
                    @if($contrato->emp_terreno != $contrato->emp_constructora)
                        Correspondiendo la cantidad de ${{$contrato->valor_terreno}} como precio del terreno y la cantidad
                        de ${{$contrato->valor_const}} como precio de lo edificado
                    @endif
                </li>
                <li class="li-custom">
                    La transmisión de la propiedad del inmueble a favor de <strong></strong>“EL PROMITENTE COMPRADOR”</strong>
                    deberá realizarse libre de todo gravamen y al corriente en el pago de todos sus impuestos,
                    derechos, cuotas de mantenimiento y servicios.
                </li>
                <li class="li-custom">
                    <strong>
                        @if($contrato->emp_terreno != $contrato->emp_constructora)
                            “LOS PROMITENTES VENDEDORES”
                        @else
                            “EL PROMITENTE VENDEDOR”
                        @endif</strong>
                        se obligará al saneamiento para el caso de evicción y a responder por los vicios ocultos en los términos de la legislación aplicable
                </li>
                <li class="li-custom">
                    La entrega de la posesión del inmueble en ningún caso será mayor a 30 días naturales a partir de la fecha de la escritura pública.
                </li>
                <li class="li-custom">
                    <strong>
                        @if($contrato->emp_terreno != $contrato->emp_constructora)
                            “LOS PROMITENTES VENDEDORES”
                        @else
                            “EL PROMITENTE VENDEDOR”
                        @endif
                    </strong> otorgará a <strong>“EL PROMITENTE COMPRADOR”</strong> las garantías que en términos del artículo
                    73 Quáter de la Ley Federal del Consumidor deben considerarse.
                </li>
                <li class="li-custom">
                    Las demás cláusulas que correspondan conforme a la naturaleza de la compraventa.
                </li>

            </ul>
        </p>

        <p>
            <strong>
                SEXTA.- <u>ENTREGA FÍSICA Y MATERIAL DEL INMUEBLE.</u>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif</strong>
            </strong>
            notificarán a <strong>“EL PROMITENTE COMPRADOR”</strong> ya sea al número telefónico, correo electrónico o en el domicilio indicados en la Cláusula Décima Sexta,
            con al menos con 3 (tres) días hábiles de anticipación, la fecha para recibir la posesión material del <strong>inmueble.</strong> En caso de que <strong>“EL PROMITENTE COMPRADOR”
            no concurra en la fecha fijada para recibir la posesión</strong> del inmueble <strong>“EL PROMITENTE VENDEDOR”</strong> le concederá hasta 30 días naturales más para su
            recepción y en caso de nueva inasistencia se levantará acta circunstanciada ante dos testigos que haga constar tal situación, y se entenderá que
            recibió el inmueble a su entera satisfacción para todos los efectos a que haya lugar.
        </p>

        <p>
            En caso de retraso en la entrega del bien inmueble acordado en el presente contrato, dará lugar a la aplicación de la pena convencional
            establecida en la Cláusula Décima Tercera o al reclamo de daños y perjuicios; lo anterior, salvo causas debidamente justificadas e imputables a
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> en cuyo caso las partes pudieran pactar una nueva fecha de entrega.
        </p>

        <p>
            <strong>
                SÉPTIMA.- <u>GARANTÍAS.</u>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong>
             otorgan a <strong>“EL PROMITENTE COMPRADOR”</strong> en cumplimiento al artículo 73 Quáter de la Ley Federal de Protección al Consumidor una
             Póliza de Garantía sobre el Inmueble objeto del presente contrato con una vigencia de cinco años para cuestiones estructurales, de tres años para impermeabilización,
             y para los demás elementos la garantía mínima debe ser de 1 año, dichos  plazos se contarán a partir de la recepción del Inmueble,
             la cual cubre sin costo alguno para <strong>“EL PROMITENTE COMPRADOR”</strong> cualesquier acto tendiente a la reparación de los defectos o
             fallas que presente el Inmueble.
        </p>

        <p>
            El proceso para hacer efectivas las garantías otorgadas a <strong>“EL PROMITENTE COMPRADOR”</strong> será el siguiente:
        </p>

        <p>
            <strong>1.- “EL PROMITENTE COMPRADOR”</strong>, deberá presentar el reporte  en el Centro de Atención a Clientes de
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”,
                @else
                    “EL PROMITENTE VENDEDOR”,
                @endif
            </strong>
            ubicado en Manuel Gutiérrez Nájera {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }} Col. Tequisquiapan de la Ciudad de San Luis Potosí, S.L.P.
            o al teléfono 444 8 33 46 83, 84 al 85 o en su caso al  domicilio electrónico postventa@grupocumbres.com, especificando los elementos de la
            vivienda sobre los que se pretende hacer válida su garantía.
        </p>

        <p>
            <strong>2.-</strong> De encontrarse vigentes las garantías,
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong>
            le informarán a <strong>“EL PROMITENTE COMPRADOR”</strong> el día y hora programado para llevar a cabo la inspección física en las áreas específicas de la
            vivienda que fueron materia del reporte, y de ser procedente su atención, le indicará el día y hora para la realización de los trabajos
            correctivos correspondientes, mismos que se ejecutarán sin ningún costo para <strong>“EL PROMITENTE COMPRADOR”.</strong>
        </p>

        <p>
            <strong>3.-</strong> El día programado para la realización de los trabajos, <strong>“EL PROMITENTE COMPRADOR”</strong>
            deberá permitir el acceso al personal que
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong>
            hayan destinado para efectuar los mismos. Una vez efectuados los trabajos correctivos se dará por concluido el reporte, mediante firma de conformidad de
            <strong>“EL PROMITENTE COMPRADOR”,</strong> o haciendo constar las razones por las cuales no firma de conformidad.
        </p>

        <p>
            El tiempo que duren las reparaciones efectuadas al Inmueble al amparo de la garantía no es computable dentro del plazo de la misma;
            una vez que el Inmueble haya sido reparado se iniciará la garantía respecto de las reparaciones realizadas, así como con relación a
            las piezas o bienes que hubieren sido repuestos y continuará respecto al resto del Inmueble.
        </p>

        <p>
            Las garantías indicadas no serán aplicables en caso de que <strong>“EL PROMITENTE COMPRADOR”</strong> realice ampliaciones o modificaciones al inmueble en condiciones
            que comprometan la estructura del mismo
        </p>

        <p>
            En caso de que
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> contraten o cuenten con mecanismos coadyuvantes para hacer frente a la garantía del inmueble,
            no significará que
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong>
            dejan de ser responsable de la garantía del inmueble ante <strong>“EL PROMITENTE COMPRADOR”. </strong>
            Contando <strong>
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                “LOS PROMITENTES VENDEDORES”
            @else
                “EL PROMITENTE VENDEDOR”
            @endif </strong> con _______________________________, como mecanismo coadyuvante para hacer frente a la garantía del inmueble.
        </p>

        <p>
            Dicho mecanismo coadyuva al cumplimiento de la obligación de
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong>
            a atender el reclamo de <strong>“EL *PROMITENTE COMPRADOR”</strong> afectado de la siguiente manera__________________________________.
            El procedimiento para que <strong>“EL PROMITENTE COMPRADOR”</strong> pueda acceder a dicho mecanismo es: ____________________________________.
            Los documentos necesarios para tal efecto son ____________________________________________, mismos que <strong>“EL PROMITENTE VENDEDOR”</strong> se obliga a entregar a
            <strong>“EL PROMITENTE COMPRADOR”,</strong> en forma simultánea a la entrega física de EL INMUEBLE.
        </p>

        <p>
            <strong>OCTAVA.- <u>AVISO DE PRIVACIDAD.</u> </strong> Para efectos de lo dispuesto en la Ley Federal de Protección de Datos Personales en Posesión de los Particulares y
            demás legislación aplicable, <strong>“EL PROMITENTE COMPRADOR”</strong> manifiesta que el Aviso de Privacidad de
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> le fue dado a conocer por su representante, mismo que manifiesta haber leído, entendido y acordado en los términos expuestos en el mismo,
            por lo que otorga su consentimiento respecto del tratamiento de sus datos personales En el caso de que los datos personales recopilados incluyan datos patrimoniales o financieros,
            mediante la firma del contrato correspondiente, sea en formato impreso, o utilizando medios electrónicos y sus correspondientes procesos para la formación del consentimiento,
            se llevarán a cabo actos que constituyen el consentimiento expreso del titular y que otorga su consentimiento para que <strong>“EL PROMITENTE VENDEDOR”</strong> o
            sus Encargados realicen transferencias y/o remisiones de datos personales en términos del propio Aviso de Privacidad.
        </p>

        <p>
            <strong>“EL PROMITENTE COMPRADOR”</strong>  si (  ) no (  ) acepta que
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    <strong> “LOS PROMITENTES VENDEDORES”</strong> cedan o transmitan
                @else
                    <strong>“EL PROMITENTE VENDEDOR” </strong> ceda o transmita
                @endif
            </strong> a terceros, con fines mercadotécnicos o publicitarios, la información proporcionada por él con motivo del presente Contrato y si (  ) no (  ) acepta que
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                    <strong> “LOS PROMITENTES VENDEDORES”</strong> le envíen
                @else
                    <strong>“EL PROMITENTE VENDEDOR” </strong> le envie
                @endif publicidad sobre bienes y servicios.
        </p>

        @if($contrato->coacreditado == 0)
            <p>
                <div class="table">
                    <div class="table-row">
                        <div colspan="2" class="table-cell"></div>
                        <div colspan="3" class="table-cell"><br><br>_____________________________________________</div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell"></div>
                        <div colspan="3" class="table-cell">
                            {{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}<br>
                                <strong>“EL PROMITENTE COMPRADOR”</strong>
                        </div>
                    </div>
                </div>
            </p>
        @else

            <p>
                <div class="table">
                    <div class="table-row">
                        <div colspan="2" class="table-cell"><br><br>_________________________________</div>
                        <div colspan="1" class="table-cell"></div>
                        <div colspan="2" class="table-cell"><br><br>_________________________________</div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell">
                            {{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}<br>
                        </div>
                        <div colspan="1" class="table-cell"></div>
                        <div colspan="2" class="table-cell">
                            {{mb_strtoupper($contrato->nombre_coa)}} {{mb_strtoupper($contrato->apellidos_coa)}}
                        </div>
                    </div>
                </div>
            </p>

        @endif

        <p>
            <strong>NOVENA.- <u>PROCESO DE CANCELACIÓN.</u> “EL PROMITENTE COMPRADOR”</strong> podrá cancelar el presente contrato, sin responsabilidad alguna, siempre y cuando
            esté dentro de los 5 días hábiles siguientes a su firma, sin menoscabo de los pagos realizados, así como la obligación del
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> de devolver las cantidades que el <strong>“EL PROMITENTE COMPRADOR”</strong> le haya entregado, en su caso, deduciendo de las mismas el
            monto de los gastos operativos debidamente comprobables en que hayan incurrido <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> en ese lapso. Dicha devolución debe darse en un plazo de 5 a 15 días hábiles siguientes a la fecha  en que le sea notificada a
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> por escrito, dicha cancelación por parte del <strong>“EL PROMITENTE COMPRADOR”.</strong>
        </p>

        <p>
            En caso de que dichas cantidades no fueren restituidas dentro del plazo establecido,
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> deberá pagar a <strong>“EL PROMITENTE COMPRADOR”</strong> un interés equivalente al 0.5% ( 0.5 por ciento)
            mensual dividido entre los días de retraso transcurridos de dicha restitución.
        </p>

        <p>
            La cancelación de que trata la presente cláusula podrá realizarla <strong>“EL PROMITENTE COMPRADOR”</strong>
            mediante aviso por escrito entregado a
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> en los domicilios y términos previstos en la cláusula DÉCIMA SEXTA de este contrato.
        </p>

        <p>
            <strong>DÉCIMA.- <u>DERECHOS Y OBLIGACIONES DEL “PROMITENTE COMPRADOR”. </u></strong>
            Con motivo delpresente contrato <strong>“EL PROMITENTE COMPRADOR” adquiere los derechos y asume las obligaciones siguientes:</strong>
        </p>

        <p>
            <ul class="ul-custom">
                <li class="li-custom">
                    <strong>Derechos:</strong>
                    <ol>
                        <li>
                            Recibir la información detallada y puntual por parte de
                            <strong>
                                @if($contrato->emp_terreno != $contrato->emp_constructora)
                                    “LOS PROMITENTES VENDEDORES”
                                @else
                                    “EL PROMITENTE VENDEDOR”
                                @endif
                            </strong> respecto del  inmueble que será objeto del contrato de compraventa.
                        </li>
                        <li>
                            Estar informado sobre el estado que guarde su trámite para el otorgamiento de su crédito, esto cuando haya optado porque sean
                            <strong>
                                @if($contrato->emp_terreno != $contrato->emp_constructora)
                                    “LOS PROMITENTES VENDEDORES”
                                @else
                                    “EL PROMITENTE VENDEDOR”
                                @endif
                            </strong> los que realicen la gestión respectiva.
                        </li>
                        <li>
                            En su caso, que se le informe oportunamente sobre el resultado de la gestión que hayan realizado
                            <strong>
                                @if($contrato->emp_terreno != $contrato->emp_constructora)
                                    “LOS PROMITENTES VENDEDORES”
                                @else
                                    “EL PROMITENTE VENDEDOR”
                                @endif
                            </strong> respecto de su crédito de vivienda.
                        </li>
                        <li>
                            Una vez aprobado su crédito, celebrar el contrato de compraventa en escritura pública.
                        </li>
                    </ol>
                </li>
                <li class="li-custom">
                    <strong>Obligaciones:</strong>
                    <ol>
                        <li>
                            Obtener la aprobación de su crédito hipotecario y mantener vigente la aprobación durante el tiempo
                            necesario para cumplir las obligaciones de este contrato.
                        </li>
                        <li>
                            En su caso, entregar a
                            <strong>
                                @if($contrato->emp_terreno != $contrato->emp_constructora)
                                    “LOS PROMITENTES VENDEDORES”
                                @else
                                    “EL PROMITENTE VENDEDOR”
                                @endif
                            </strong>, a más tardar dentro de los 5 días hábiles posteriores a la firma de este contrato, toda la documentación que sea necesaria
                            para iniciar y realizar el trámite de su crédito.
                        </li>
                        <li>
                            Realizar el pago de los gastos operativos relativos a avalúo, comisiones por apertura y/o cualquier otro que sea
                            necesario para realizar el trámite de su crédito de vivienda.
                        </li>
                        <li>
                            Realizar oportuna y cumplidamente, cualquier aclaración o rectificación de sus datos e información que le sea requerida por la
                            institución acreditante, para continuar con su trámite para la obtención de su crédito.
                        </li>
                        <li>
                            Una vez aprobado su crédito, acudir a la notaría pública correspondiente para celebrar el contrato de compraventa respectivo.
                        </li>
                    </ol>
                </li>
            </ul>
        </p>

        <p>
            Si dentro de los 5 (cinco) días hábiles posteriores a la firma de este contrato <strong>“EL PROMITENTE COMPRADOR”</strong> no hace entrega de la
            documentación e información completa que se requiere para que
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> inicien la gestión para el otorgamiento de su crédito, el presente contrato se tendrá por rescindido de  pleno derecho sin necesidad
            de resolución judicial o administrativa previa.
        </p>

        <p>
            <strong>DÉCIMA PRIMERA.- <u>DERECHOS Y OBLIGACIONES DE
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                “LOS PROMITENTES VENDEDORES”
            @else
                “EL PROMITENTE VENDEDOR”
            @endif.</u> </strong> Con motivo del presente contrato
            <strong>@if($contrato->emp_terreno != $contrato->emp_constructora)
                “LOS PROMITENTES VENDEDORES”</strong> adquieren
            @else
                “EL PROMITENTE VENDEDOR”</strong> adquiere
            @endif los derechos y asumen las obligaciones siguientes:
        </p>

        <p>
            <ul class="ul-custom">
                <li class="li-custom">
                    <strong>Derechos:</strong>
                    <ol>
                        <li>
                            En caso de aplicar, solicitar a <strong>“EL PROMITENTE COMPRADOR”</strong> toda la documentación que sea necesaria para iniciar
                            y realizar el trámite para la gestión de su crédito para vivienda.
                        </li>
                        <li>
                            Solicitar a <strong>“EL PROMITENTE COMPRADOR”</strong> que realice cualquier aclaración o rectificación de sus datos
                            e información que le sea requerida para continuar con su trámite para la obtención y en su caso, renovación de su crédito,
                            en caso de que haya optado por que sea <strong>“EL PROMITENTE COMPRADOR”</strong> el que realice dicha gestión
                        </li>
                    </ol>
                </li>
                <li class="li-custom">
                    <strong>Obligaciones:</strong>
                    <ol>
                        <li>
                            Presentar ante la institución acreditante toda la documentación que le haya sido entregada por <strong>“EL PROMITENTE COMPRADOR”</strong>
                            y llevar de manera diligente la gestión en busca de la aprobación u otorgamiento de su crédito (solo en el caso de que
                            <strong>“EL PROMITENTE COMPRADOR”</strong> haya elegido dicha opción).
                        </li>
                        <li>
                            No cobrar cantidad alguna por concepto de contraprestación u honorarios por la gestión y trámite del
                            crédito hipotecario a favor de <strong>“EL PROMITENTE COMPRADOR”.</strong> Los gastos relativos a avalúo,
                            comisiones por apertura y/o cualquier otro que requiera la institución acreditante, son gastos operativos
                            necesarios para realizar dicho trámite y no constituyen contraprestación alguna a favor de
                            <strong>“LOS PROMITENTES VENDEDORES”.</strong>
                        </li>
                        <li>
                            Brindar a <strong>“EL PROMITENTE COMPRADOR”</strong> la información detallada y puntual respecto del inmueble que será objeto
                            del contrato de compraventa, así como del estado que guarde su trámite para el otorgamiento de su crédito y la resolución del mismo,
                            esto último cuando haya optado porque sean
                            <strong>
                                @if($contrato->emp_terreno != $contrato->emp_constructora)
                                    “LOS PROMITENTES VENDEDORES”
                                @else
                                    “EL PROMITENTE VENDEDOR”
                                @endif
                            </strong> los que realicen dicho trámite
                        </li>
                        <li>
                            Informar a <strong>“EL PROMITENTE COMPRADOR”</strong> sobre alguna aclaración que este deba
                            realizar o documentación adicional que deba presentar, para continuar con la gestión de su crédito,
                            en caso de ser aplicable.
                        </li>
                        <li>
                            Acudir a la notaría pública correspondiente para suscribir el contrato de compraventa respectivo,
                            una vez que le haya sido otorgado a <strong>“EL PROMITENTE COMPRADOR”</strong> su crédito.
                        </li>
                    </ol>
                </li>
            </ul>
        </p>

        <p>
            <strong>DÉCIMA SEGUNDA.- <u>FONDO DE RESERVA Y MANTENIMIENTO.</u></strong> Si el Inmueble objeto del presente contrato
            estuviera sujeto a un régimen de propiedad en condominio <strong>“EL PROMITENTE COMPRADOR”</strong> entregará a <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> a la firma de esta escritura para constituir el fondo de reserva para el mantenimiento y conservación del Conjunto Habitacional
            donde se encuentra el Inmueble la cantidad de $ 0.00 (cero pesos 00/100 M.N.) la cual resulta de aplicar cualesquiera de las siguientes
            opciones, de acuerdo a la legislación aplicable:
            <ol>
                <li>Por metro cuadrado de superficie de construcción, o</li>
                <li>Por porcentaje de indiviso, o</li>
                <li>Por cuota fija.</li>
            </ol>
        </p>

        <p>
            <strong>DÉCIMA TERCERA.- <u>RESCISIÓN Y PENA CONVENCIONAL.</u></strong> Las partes acuerdan que, en caso de incumplimiento a
            cualquiera de las obligaciones pactadas en el presente contrato, dará lugar a la rescisión del mismo, la cual podrá ser
            declarada por la parte afectada por el incumplimiento, sin necesidad de declaración judicial previa alguna,
            bastando el simple aviso que por escrito le sea entregado a la otra parte. La parte cuyo incumplimiento haya dado lugar
            a la rescisión de este contrato, se obliga a pagar por concepto de pena convencional, el equivalente al 0 % (cero por ciento)
            del precio pactado en la cláusula Quinta del presente contrato.
        </p>

        <p>
            <strong>DÉCIMA CUARTA.- <u>FALLECIMIENTO DE “EL PROMITENTE COMPRADOR”.</u></strong> En caso de fallecimiento de
            <strong>
                @if($contrato->coacreditado == 1)
                    “LOS PROMITENTES COMPRADORES”,
                @else
                    “EL PROMITENTE COMPRADOR”,
                @endif
            </strong> el presente contrato subsistirá en todos sus efectos y alcances, transmitiendo los derechos y obligaciones de
            <strong>“EL PROMITENTE COMPRADOR”</strong> a sus sucesores, en los términos que prevea la Legislación Civil del Estado de SLP. Salvo que manifiesten a
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> no desear continuar con la compraventa, en tal caso, se procederá en los términos que prevea la Legislación vigente del Estado SLP
            y en su caso, devolución de los pagos previamente realizados conforme a lo dispuesto en la Cláusula Quinta.
        </p>

        <p>
            <strong>DÉCIMA QUINTA.- <u>CANALES DE ATENCIÓN AL CONSUMIDOR.</u></strong>
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong> pone a disposición de <strong>“EL PROMITENTE COMPRADOR”</strong> el siguiente canal de atención al consumidor, para iniciar y
            dar atención y seguimiento a cualquier queja, reclamación o sugerencia:
        </p>

        <p>
            <ul class="ul-custom">
                <li class="li-custom">Número Telefónico: 444 8334683, 84 al 85, con un horario de atención de las 9:00 horas a las 17:00 horas,
                    los días de lunes a viernes y sábados de 9:00 horas a 13 horas.
                </li>
                <li class="li-custom">
                    Plazo de respuesta:  8 días hábiles.
                </li>
                <li class="li-custom">
                    <strong>Centro de Ventas (personalizada):</strong> Manuel Gutiérrez Nájera
                        {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }}. Colonia Tequisquiapan, con un horario de atención de las
                    9:00 horas a las17:00 horas, los días de lunes a viernes y sábados de 9:00 horas a 13 horas.
                </li>
                <li class="li-custom">
                    <strong>Correo Electrónico: atencion@grupocumbres.com</strong>
                </li>
            </ul>
        </p>

        <p>
            <strong>DÉCIMA SEXTA.- <u>MEDIOS PARA NOTIFICACIONES Y AVISOS.</u></strong> Las notificaciones y avisos que
            <strong>las partes contratantes</strong> deban darse con relación a este Contrato, se harán por escrito
            recabándose constancia de su recepción y deberán ser enviadas en cualquiera de los medios que se señalan a continuación,
            dándose por totalmente válidas siempre y cuando las partes contratantes no hagan cambio o
            designación de otro medio o domicilio para tales efectos:
        </p>

        <p>
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif
            </strong><br>
            <div style="margin-left: 30px;">
                Calle: Manuel Gutiérrez Nájera
                    {{ $contrato->emp_constructora == 'CONCRETANIA' ? '#180' : '#190' }} Col. Tequisquiapan <br>
                C.P. 78230, San Luis Potosí, S.L.P. <br>
                No telefónico 444 8334683 <br>
                Correo electrónico atencion@grupocumbres.com <br>
            </div>
        </p><br>

        <p><strong>“EL PROMITENTE COMPRADOR”: <u>{{ mb_strtoupper($contrato->c_nombre) }} {{ mb_strtoupper($contrato->c_apellidos) }}</u></strong><br>
            <div style="margin-left: 30px;">
                Calle: {{ucwords($contrato->dir_cliente)}} Col. {{ucwords($contrato->col_cliente)}}<br>
                C.P. {{$contrato->cp_cliente}}, {{ucwords($contrato->ciudad_cliente)}}, {{ucwords($contrato->edo_cliente)}} <br>
                No telefónico: {{$contrato->tel_cliente}} &nbsp;No Celular: +{{$contrato->clv_lada}} {{$contrato->cel_cliente}} <br>
                Correo electrónico {{$contrato->cliente_email}} <br>
            </div>
        </p>

        <p>
            <strong>Las partes contratantes</strong> se obligan a notificar por escrito a la otra cualquier cambio en el domicilio,
            teléfono o correo electrónico que se indica anteriormente, conviniendo desde este momento que será al domicilio que
            mantengan vigente en los términos antes indicados el correspondiente para tener como válida cualquier notificación y/o aviso que
            deban darse de conformidad con lo estipulado en este Contrato.
        </p>

        <p>
            <strong>DÉCIMA SEPTIMA.- <u>JURISDICCIÓN.</u></strong> La Procuraduría Federal del Consumidor (PROFECO) es competente en la vía administrativa
            para resolver cualquier controversia que se suscite para la interpretación o cumplimiento del presente contrato. Sin perjuicio de lo anterior,
            las partes se someten a la jurisdicción de los tribunales competentes de la Ciudad de San Luis Potosí, renunciando expresamente a
            cualquier otra jurisdicción que pudiera corresponderles, por razón de sus domicilios presentes o futuros o por cualquier otra razón.
        </p>

        <p>
            <strong>DÉCIMA OCTAVA.- <u>PRESCRIPCIÓN DE ACCIONES.</u></strong> Las acciones civiles derivadas de responsabilidad civil,
            vicios ocultos y en su caso evicción, se resolverán y determinarán con base en las disposiciones legales vigentes del Código Civil del Estado de San Luis Potosí.
        </p>

        <p>
            <strong>DECIMA NOVENA.- <u>MODELO DE CONTRATO PROFECO.</u></strong>
            @if($contrato->emp_constructora == 'CONCRETANIA' && $contrato->difProfeco > 0)
                Este contrato fue presentado  a Registro ante la Procuraduría Federal del Consumidor bajo el número de procedimiento <u>159754</u>,
                el pasado día 18 de MAYO de 2023; por lo que se entiende aprobado por esa dependencia en términos del artículo 87 de la Ley Federal de
                Protección al Consumidor que en lo conducente indica que presentado un contrato a registro ante la Procuraduría, ésta
                emitirá su resolución dentro de los treinta días hábiles siguientes a la fecha de presentación de esa solicitud.
                Transcurrido dicho plazo sin haberse emitido la resolución correspondiente, el modelo de contrato se entenderá aprobado,
                quedando en su caso como prueba de inscripción la solicitud de registro.
            @else
                El modelo de contrato de adhesión que se utiliza para documentar la
                presente operación se encuentra aprobado y registrado por la Procuraduría Federal del Consumidor bajo el
                número <strong>{{ $contrato->emp_constructora == 'CONCRETANIA' ? '________________' : '4444-2023' }}</strong>
                de fecha <strong>{{ $contrato->emp_constructora == 'CONCRETANIA' ? '________________' : '02 DE MAYO DE 2023.' }}</strong>
                Asimismo, el contenido de este contrato se incorporará en escritura pública sin importar el orden
                y forma en que se citen y esto no se considerará como incumplimiento a la Ley, ni modificación al modelo de contrato registrado ante PROFECO.
                Cualquier variación del contenido del presente contrato en perjuicio de <strong>“EL PROMITENTE COMPRADOR”</strong> se tendrá por no puesta.

            @endif

        </p>

        <p>
            Este contrato se da y firma en la Ciudad de San Luis Potosí, S.L.P.	a los {{$contrato->fecha_contrato}}. En dos tantos y
            <strong>“EL PROMITENTE VENDEDOR”</strong> entregará un tanto a <strong>“EL PROMITENTE COMPRADOR”</strong>
            así como los anexos originales y firmados.
        </p>


        <p align="center">
            <strong>
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”:
                @else
                    “EL PROMITENTE VENDEDOR”:
                @endif
            </strong>
        </p>

        <p  align="center">
            @if($contrato->emp_terreno == $contrato->emp_constructora)
                <div class="table">
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><br><br><br><br><center>{{$contrato->emp_constructora}} S.A. DE C.V.</center></div>
                        <div class="table-cell"></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><center>_____________________________________</center></div>
                        <div class="table-cell"></div>
                    </div>
                </div>
            @else
                <div class="table">
                    <div class="table-row">
                        <div colspan="7" class="table-cell"><br><br><br><br><center>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</center></div>

                        <div colspan="5" class="table-cell"><br><br><br><br><center>CONCRETANIA S.A DE C.V.</center></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"><center></center></div>
                        <div colspan="10" class="table-cell"><center>__________________________________________________________________________</center></div>
                        <div class="table-cell"><center></center></div>
                    </div>
                </div>

            @endif
        </p>

        <p align="center"><br><br><br><br>
            <strong>“EL PROMITENTE COMPRADOR”</strong>
        </p>


        <p  align="center">
            @if($contrato->coacreditado == 0)
                <div class="table">
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><br><br><br><br><center>{{$contrato->c_nombre}} {{$contrato->c_apellidos}}</center></div>
                        <div class="table-cell"></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"></div>
                        <div colspan="6" class="table-cell"><center>_____________________________________</center></div>
                        <div class="table-cell"></div>
                    </div>
                </div>
            @else
                <div class="table">
                    <div class="table-row">
                        <div colspan="7" class="table-cell"><br><br><br><br><center>{{$contrato->c_nombre}} {{$contrato->c_apellidos}}</center></div>

                        <div colspan="5" class="table-cell"><br><br><br><br><center>{{$contrato->nombre_coa}} {{$contrato->apellidos_coa}}</center></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"><center></center></div>
                        <div colspan="10" class="table-cell"><center>__________________________________________________________________________</center></div>
                        <div class="table-cell"><center></center></div>
                    </div>
                </div>
            @endif

            <br>
        </p>

        <p>
            <br><br>
            Así mismo, PROFECO pone a su disposición el (REPEP) Registro Público para Evitar Publicidad, como mecanismo para la protección de los
            derechos de los consumidores a no ser molestados con publicidad no deseada y su información no sea utilizada con fines mercadotécnicos
            o publicitarios, mediante llamadas telefónicas y mensajes de texto.
        </p>

        <p>
            La inscripción es gratuita, y la puede realizar vía telefónica llamando desde el número que deseas inscribir al 96 28 00 00 para las áreas
            metropolitanas de la Ciudad de México, Guadalajara y Monterrey o al 800 96 28 000 para el resto de la República o por Internet en la página:
            REPEP las 24 horas del día, los 365 días del año
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
            Superficie de construcción:&nbsp; {{$contrato->sup_construccion}}m&sup2;<br>
            @if($contrato->area_jardin > 0)
            Superficie de jardín:&nbsp; {{$contrato->area_jardin}}m&sup2;<br>
            Superficie de estacionamiento: &nbsp; {{$contrato->area_estacionamiento}}m&sup2;<br>
            @endif
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
            Edad de la construcción en el inmueble:&nbsp; {{$contrato->date_birth}} <br>
            Uso previo del inmueble:&nbsp; {{$contrato->estado_inmueble}} <br>
        </p>


        <p>
            <h4>Prototipo:&nbsp; {{$contrato->modelo}}<br></h4>
            @foreach($contrato->especificaciones as $general)
                <strong>{{ ucwords($general->general)}}:</strong>
                @foreach($general->detalle as $especificacion)
                    <li style="margin-left:40px;" class="ul-custom">-<template class="cuadrado"></template>
                        {{$especificacion->subconcepto}}:&nbsp; {{$especificacion->descripcion}}<br>
                    </li>
                @endforeach
                <br><br>
            @endforeach
            Equipamiento especial:
            @if($contrato->desc_eq_paquete)
                <li style="margin-left:40px;" class="ul-custom">-<template class="cuadrado"></template>
                    {{$contrato->desc_eq_paquete}}
                </li>
            @endif
            @if($contrato->desc_eq_promo)
                <li style="margin-left:40px;" class="ul-custom">-<template class="cuadrado"></template>
                    {{$contrato->desc_eq_promo}}
                </li>
            @endif
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
                Instalación para gas l.p. y preparación para gas estacionario.
                @if($contrato->gas_nat)
                    La vivienda cuenta con preparación para gas natural
                @endif
            </li>
        </p>

        <p>
            Especificaciones técnicas, de seguridad y de los materiales, así como de las características de la estructura,
            instalaciones, instalaciones especiales (discapacitados y/o ecotecnologías cuando aplique) y de los acabados
            (deberá incluir los correspondientes al prototipo adquirido y en caso de que ofrezca algunas adiciones o mejoras
            especificarlas): &nbsp;&nbsp;N/A
        </p>

        <p align="center">
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                “LOS PROMITENTES VENDEDORES”:
            @else
                “EL PROMITENTE VENDEDOR”:
            @endif
        </p>

        <p  align="center">
            @if($contrato->emp_terreno == $contrato->emp_constructora)
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
            @else
                <div class="table">
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><br><br><center>___________________________________</center></div>
                        <div colspan="" class="table-cell"><center></center></div>
                        <div colspan="5" class="table-cell"><br><br><center>___________________________________</center></div>
                    </div>
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><center>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</center></div>
                        <div class="table-cell"></div>
                        <div colspan="5" class="table-cell"><center>CONCRETANIA S.A DE C.V.</center></div>
                    </div>
                </div>

            @endif
        </p>

        <p align="center"><br><br><br><br>
            “EL PROMITENTE COMPRADOR”
        </p>

        <p  align="center">
            @if($contrato->coacreditado == 0)
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
        <h5 align="center">ANEXO B</h5>
    </div>
    <div>
        <p>
            <strong>INFORMACIÓN Y DOCUMENTACIÓN DEL INMUEBLE QUE SE PONE A DISPOSICIÓN DE “EL PROMITENTE COMPRADOR”</strong>
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
                        ¿Le exhibieron el proyecto ejecutivo de construcción completo?
                        (planos del terreno, planos de terracerías o topografías, planos de ubicación y localización, planos de
                        fachadas, cortes y alzados, planos de detalles arquitectónicos, planos de cimentación, columnas, trabes y
                        losas; planos de instalaciones hidrosanitarias, eléctricas, planos de acabados, planos de urbanización,
                        planta de conjunto como: zonas exteriores, aceras, jardines y sus instalaciones; descripción del sistema
                        constructivo y acabados con los que se entregará la vivienda).
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le exhibieron el documento que acredite la propiedad del Inmueble?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le exhibieron la casa muestra o maqueta física o digital?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron sobre la existencia de gravámenes que afecten la propiedad del Inmueble?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le exhibieron los documentos que acrediten la personalidad de “EL VENDEDOR” y la autorización del proveedor para promover la venta del Inmueble?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron sobre las condiciones en que se encuentra el pago de contribuciones y servicios públicos del Inmueble?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le exhibieron las autorizaciones, licencias o permisos expedidos por las autoridades correspondientes para la construcción del Inmueble?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le exhibieron los planos estructurales, arquitectónicos y de instalaciones o un dictamen de las condiciones estructurales del Inmueble aceptado por el Director Responsable de Obra?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le proporcionaron la información sobre las características del Inmueble?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le brindaron información adicional sobre los beneficios ofrecidos por “EL VENDEDOR”, en caso de concretar la operación, tales como acabados especiales,
                        encortinados, azulejos y cocina integral, ¿Entre otros?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le brindaron información sobre las características, uso y mantenimiento de las instalaciones especiales
                        (discapacitados y/o ecotecnologías) de la vivienda / del Conjunto Habitacional? (cuando aplique)
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron respecto de las opciones de pago que puede elegir y sobre el monto total a pagar en cada una de ellas?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿En caso de que la operación sea a crédito, le informaron sobre el tipo de crédito de que se trata?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿De ser el caso, le informaron de los mecanismos para la modificación o renegociación de las opciones de pago,
                        las condiciones bajo las cuales se realizaría y las implicaciones económicas, tanto para “EL VENDEDOR” como para “EL COMPRADOR”?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron de las condiciones bajo las cuales se llevará a cabo el proceso de escrituración, así como las erogaciones distintas del precio de la venta que deba realizar?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron si sobre el Inmueble existe y se ha constituido garantía hipotecaria, fiduciaria o de cualquier otro tipo, así como su instrumentación?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron si el modelo de contrato que va a firmar está previamente registrado ante la Procuraduría Federal del Consumidor?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron que el Inmueble cuenta con una póliza de garantía y la forma de hacerla efectiva?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le informaron que en caso de que usted no esté de acuerdo con el servicio prestado puede presentar su reclamación en el
                        siguiente correo postventa@grupocumbres.com y que debe cumplir con los lineamientos marcados en la garantía emitida a su favor?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición el proyecto ejecutivo de construcción? (planos de ubicación, localización, planos estructurales, planos de acabados, etc.)
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición el programa interno de protección civil?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición el equipamiento urbano existente en la localidad?
                    </div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                </div>
                <div class="table-row">
                    <div colspan="10" class="table-cell2">
                        ¿Le pusieron a su disposición la carta de derechos del consumidor?
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
            Y el plazo de respuesta de
            <strong>
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                “LOS PROMITENTES VENDEDORES”
            @else
                “EL PROMITENTE VENDEDOR”
            @endif</strong>
            es de: 8 días hábiles
        </p>

        <p class="small">
            <strong>
                <u>IMPORTANTE PARA “EL PROMITENTE COMPRADOR”: </u>Antes de que firme como constancia de que tuvo a su disposición la información y documentación
                relativa al Inmueble, cerciórese de que la misma coincida con la que efectivamente le hayan mostrado y/o proporcionado
                @if($contrato->emp_terreno != $contrato->emp_constructora)
                    “LOS PROMITENTES VENDEDORES”
                @else
                    “EL PROMITENTE VENDEDOR”
                @endif.
            </strong>
        </p>

        <p align="center">
            @if($contrato->emp_terreno != $contrato->emp_constructora)
                “LOS PROMITENTES VENDEDORES”:
            @else
                “EL PROMITENTE VENDEDOR”:
            @endif
        </p>

        <p  align="center">
            @if($contrato->emp_terreno == $contrato->emp_constructora)
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
            @else
                <div class="table">
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><br><br><center>___________________________________</center></div>
                        <div colspan="" class="table-cell"><center></center></div>
                        <div colspan="5" class="table-cell"><br><br><center>___________________________________</center></div>
                    </div>
                    <div class="table-row">
                        <div colspan="5" class="table-cell"><center>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</center></div>
                        <div class="table-cell"></div>
                        <div colspan="5" class="table-cell"><center>CONCRETANIA S.A DE C.V.</center></div>
                    </div>
                </div>

            @endif
        </p>

        <p align="center"><br><br><br><br>
            “EL PROMITENTE COMPRADOR”
        </p>

        <p  align="center">
            @if($contrato->coacreditado == 0)
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
        <h5 align="center">ANEXO C</h5>
    </div>

    <div>
        <p align="center">
            <strong>CARTA DE DERECHOS</strong>
        </p>

        <p>
            En este acto se le hace del conocimiento del PROMITENTE COMPRADOR en base a lo que establece la Ley Federal de Protección al Consumidor (en adelante LFPC), su Reglamento y la NOM 247-SE-2021, los siguientes derechos:
        </p>

        <p>
            <strong>1.  </strong>Recibir, respecto de los bienes inmuebles ofertados, información y publicidad veraz, clara y actualizada, sin importar el medio por el que se comunique, incluyendo los medios digitales, de forma tal que le permita al consumidor tomar la mejor decisión de compra conociendo de manera veraz las características del inmueble que está adquiriendo, conforme a lo dispuesto por la LFPC. <br>
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
