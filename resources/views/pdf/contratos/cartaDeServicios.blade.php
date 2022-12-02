<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carta de servicios</title>
</head>
<style type="text/css">
    @font-face {
        font-family: 'Gotham-Book';
        font-style: Book;
        src: url("GothamBook.ttf") format('truetype');
    }

    body {
        font-size: 10pt;
        font-family: Gotham-Book, sans-serif;
    }

    ul {
        text-align: left;
    }

    .table {
        display: table;
        width: 100%;
        border-collapse: collapse;
    }

    .table-row {
        display: table-row;
    }

    .table-cell {
        display: table-cell;
        padding: 0em;
        font-size: 9pt;
    }
</style>

<body>
    <div style="width: 100%; position: fixed; text-align: center;">
        @if ($datos[0]->modelo == 'Terreno')
            <img style="margin-top: -20px;"
                src="files/etapas/plantillasCartaServicios/{{ $datos[0]->plantilla_carta_servicios2 }}" width="794"
                height="1020">
        @else
            <img style="margin-top: -20px;"
                src="files/etapas/plantillasCartaServicios/{{ $datos[0]->plantilla_carta_servicios }}" width="794"
                height="1020">
        @endif
        <div style="margin-top: 200px; position: absolute; width: 100%; left:60px; right:60px;">

            <p align="right">México, San Luis Potosí, S.L.P, a <u>{{ $datos[0]->fecha_hoy }}</u></p>
            @if ($datos[0]->emp_constructora == 'CONCRETANIA')
                <p align="left">Bienvenido a la Familia Concretania Sr (a) <u>{{ $datos[0]->nombre }}
                        {{ $datos[0]->apellidos }}</u></p>
            @else
                <p align="left">Bienvenido a la Familia Cumbres Sr (a) <u>{{ $datos[0]->nombre }}
                        {{ $datos[0]->apellidos }}</u></p>
            @endif

            @if ($datos[0]->modelo != 'Terreno')
                <p align="center"> <b>¡Muchas felicidades por su nueva Casa!</b> </p>
            @else
                <p align="center"> <b>¡Muchas felicidades por su nuevo Lote!</b> </p>
            @endif

            @if ($datos[0]->num_etapa != 'EXTERIOR')
                <p align="justify">&nbsp; &nbsp; Queremos informarte por la presente que para mantener la Belleza,
                    Tranquilidad y Seguridad de la privada
                    se manejara una cuota de mantenimiento la cual tendrá como objetivo el solventar los gastos comunes
                    de la
                    privada tales como:
                </p>
            @else
                <p align="justify">&nbsp; &nbsp; Queremos informarte por la presente que para mantener la Belleza y
                    Plusvalia del Fraccionamiento
                    se manejara una cuota de mantenimiento la cual tendrá como objetivo el solventar los gastos comunes
                    tales como:
                </p>
            @endif


            <ul>
                @for ($i = 0; $i < count($servicios); $i++)
                    <li>{{ $servicios[$i]->descripcion }}</li>
                @endfor
            </ul>

            @if ($datos[0]->modelo == 'Terreno')
                <p align="justify">&nbsp; &nbsp; La aportacion será de
                    <strong>${{ $datos[0]->costoMantenimientoLetra2 }}</strong> de forma mensual, como costo base;
                    cabe destacar que al momento de construir en el Lote la cuota se incrementa a
                    <strong>${{ $datos[0]->costoMantenimientoLetra }}</strong>,
                    cuota que estarán pagando actualmente las Viviendas del Fraccionamiento. Las cuotas pueden
                    actualizarse conforme a los requerimientos de los costos
                    de los servicios, y pueden ser de carácter fijo y/o provisional con previa notificación.
                </p>
            @else
                <p align="justify">&nbsp; &nbsp; La aportacion será de
                    <strong>${{ $datos[0]->costoMantenimientoLetra }}</strong> de forma mensual, como costo base;
                    cabe destacar que las cuotas pueden actualizarse conforme a los requerimientos de los costos de los
                    servicios,
                    y pueden ser de carácter fijo y/o provisional con previa notificación y autorización por los propios
                    condóminos.
                </p>
            @endif

            <p align="justify">&nbsp; &nbsp;Los días de pago serán del 1 al 10 de cada mes.</p>
            <p style="line-height:0pt;" align="justify">&nbsp; &nbsp;El mes en el que usted recibe su casa no lo paga.
            </p>
            <br>

            <p align="justify">&nbsp; &nbsp;Quedamos a sus órdenes para cualquier comentario y/o aclaración, es de suma
                importancia su Apoyo,
                compromiso y cooperación, con la finalidad de poder solventar los gastos ya previamente pactados y
                que serán un gran beneficio para su tranquilidad, seguridad y armonía.</p>


            <br>

            <p align="center">Atentamente.</p>
            <br>
            <br>
            <br>
            <br>

            <div class="table">
                <div class="table-row">
                    <div class="table-cell"><b>Gerencia Departamento de Post-Venta.</div>
                    <div class="table-cell"><b>Nombre, Firma, Fecha</div>
                </div>
                <div class="table-row">

                    @if ($datos[0]->emp_constructora == 'CONCRETANIA')
                        <div class="table-cell"><b>Concretania S.A. de C.V.</div>
                    @else
                        <div class="table-cell"><b>Grupo Constructor Cumbres S.A. de C.V.</div>
                    @endif

                    <div class="table-cell"><b>(Quedo enterado y he recibido informacion)</div>
                </div>
            </div>


        </div>

    </div>
</body>

</html>
