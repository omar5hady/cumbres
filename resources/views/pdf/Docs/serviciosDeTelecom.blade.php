<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Servicios de telecomunicaciones</title>
</head>
<style type="text/css">
    @font-face {
        font-family: 'Gotham-Book';
        font-size: 12;
        font-style: Book;
        src: url("GothamBook.ttf") format('truetype');
    }

    body {
        font-family: Gotham-Book, sans-serif;
    }

</style>

<body>
    <div style="margin-top: 0px; width: 100%; position: fixed; text-align: center;">
        <IMG style="margin-top: -20px;" SRC="files/etapas/plantillasTelecom/{{ $archivos[0]->plantilla_telecom }}"
            width="794" height="1020">
        @if ($archivos[0]->num_etapa == 'PRIVADA VILLA DEL REY')
            <div style="margin-top: 250px; position: absolute; width: 100%; left:130px; right:70px;">
        @else
            <div style="margin-top: 250px; position: absolute; width: 100%; left:80px; right:80px;">
        @endif


        <h4 align="left">Estimado cliente: </h4>

        <p align="justify">Agradecemos la confianza depositada en nuestra empresa en tan importante decisión de compra.
        </p>

        <p align="justify">
            Referente al servicio de telefonía, Internet y tv por cable. Le informamos que <strong>Grupo Constructor
                Cumbres, S.A de C.V.</strong>
            se responsabiliza en dejar los ductos para que a futuro la empresa(s)
            {{ $archivos[0]->empresas_telecom }}, realice el cableado para llevar los servicios antes mencionados a su
            nueva casa.
        </p>

        <p align="justify">La fecha en la que podrá disponer con tales servicios,<strong> NO depende de Grupo
                Constructor Cumbres, S.A de C.V.</strong>
            Es decir, la empresa(s) {{ $archivos[0]->empresas_telecom }} definirá en que fecha podrá otorgar estos
            servicios.</p>

        <p align="justify">Una vez entregada su casa, usted podrá contratar los servicios con empresas que los
            proporcionen de manera satelital tales como: {{ $archivos[0]->empresas_telecom_satelital }} entre otros o
            bien el de su conveniencia.
            Aclarando además que la disponibilidad del servicio que brindan estas empresas tampoco es responsabilidad de
            <strong>Grupo Constructor Cumbres, S.A de C.V.</strong></p>



        <br>
        <br>
        <br>
        <br>
    </div>

    </div>
</body>

</html>
