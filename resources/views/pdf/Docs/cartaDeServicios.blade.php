<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carta de servicios</title>
</head>
<style type="text/css">

@page{
    margin: 0px;
}
@font-face {
  font-family: 'Gotham-Book';
  font-style: Book;
  src: url("GothamBook.ttf") format('truetype');
}
body {
    font-size: 10pt;
    font-family: Gotham-Book, sans-serif;
}
ul{
  text-align: left;
}

.table { display: table; width: 100%; border-collapse: collapse; }
.table-row { display: table-row; }
.table-cell { display: table-cell; padding: 0em; font-size: 8pt; }
</style>
<body>
<div style="width: 100%; position: fixed; text-align: center;">
    <img src="files/etapas/plantillasCartaServicios/{{ $archivos[0]->plantilla_carta_servicios }}" width="105%">
    @if($archivos[0]->proyecto != 'PRIVADA NEDEN')
        <div style="margin-top: 200px; position: absolute; width: 100%; left:80px; right:80px;">
    @else
        <div style="margin-top: 200px; position: absolute; width: 100%; left:200px; right:60px;">
    @endif

        <p align="right">México, San Luis Potosí, S.L.P, a <u>{{$archivos[0]->fecha_hoy}}</u></p>
        <br>
        <p align="left">Bienvenido a la Familia Cumbres Sr (a) </p>
        <p align="center"><b>¡Muchas felicidades por tu nueva casa!</b></p>

        <p align="justify">&nbsp; &nbsp; Queremos informarte por la presente que para mantener la belleza, tranquilidad y seguridad
        @if($archivos[0]->num_etapa == 'EXTERIOR')
            del fraccionamiento
        @else
            de la privada
        @endif
                        se manejara una cuota de mantenimiento la cual tendrá como objetivo el solventar los gastos comunes de la
                        privada tales como:
        </p>


        <ul>
        @for($i=0; $i<count($servicios);$i++)
            <li>{{$servicios[$i]->descripcion}}</li>
        @endfor
        </ul>

        <p align="justify">&nbsp; &nbsp; La aportación será de <strong>${{$archivos[0]->costoMantenimientoLetra}}</strong> de forma mensual, como costo base;
                        cabe destacar que las cuotas pueden actualizarse conforme a los requerimientos de los costos de los servicios,
                        y pueden ser de carácter fijo y/o provisional con previa notificación y autorización por los propios condóminos.
        </p>

        <p  align="justify">&nbsp; &nbsp;Los días de pago serán del 1 al 10 de cada mes.</p>
        <p style="line-height:0pt;" align="justify">&nbsp; &nbsp;El mes en el que usted recibe su casa no lo paga.</p>
        <br>

        <p align="justify">&nbsp; &nbsp;Quedamos a sus órdenes para cualquier comentario y/o aclaración, es de suma importancia su apoyo,
                        compromiso y cooperación, con la finalidad de poder solventar los gastos ya previamente pactados y
                        que serán un gran beneficio para su tranquilidad, seguridad y la armonía de su nuevo hogar.</p>


        <br>

        <p align="center">Atentamente.</p>
        <br>
        <br>
        <br>

        <div class="table">
            <div class="table-row">
                <div class="table-cell"><b>Gerencia Departamento de Post-Venta.</div>
                <div class="table-cell"><b>Nombre, Firma, Fecha.</div>
            </div>
            <div class="table-row">
                <div class="table-cell"><b>Grupo Constructor Cumbres S.A. de C.V.</div>
                <div class="table-cell"><b>(Quedo enterado y he recibido información)</div>
            </div>
        </div>


    </div>

</div>
</body>
</html>
