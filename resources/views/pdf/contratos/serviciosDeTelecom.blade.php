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
  font-size:12;
  font-style: Book;
  src: url("GothamBook.ttf") format('truetype');
}
body {
    font-family: Gotham-Book, sans-serif;
}
</style>
<body>
<div style="margin-top: 0px; width: 100%; position: fixed; text-align: center;">
<IMG style="margin-top: -20px;" SRC="files/fraccionamientos/plantillasTelecom/{{$serviciosTele[0]->plantilla_telecom}}" width="794" height="1020">
<div style="margin-top: 250px; position: absolute; width: 100%; left:80px; right:80px;">


    <h4 align="left">Estimado cliente: </h4>

    <p align="justify">Agradecemos la confianza depositada en nuestra empresa en tan importante decisión de compra.</p>

    <p align="justify">
   Referente al servicio de telefonía, Internet y tv por cable. Le informamos  que <strong>Grupo Constructor Cumbres, S.A de C.V.</strong> 
   se responsabiliza en dejar los ductos necesarios para que a futuro la empresa(s) {{$serviciosTele[0]->empresas_telecom}}, realice el cableado para llevar los servicios antes mencionados a su nueva casa.
   </p>

   <p align="justify">La fecha en la que podrá disponer con tales servicios,<strong> NO depende de Grupo Constructor Cumbres, S.A de C.V.</strong>
    Es decir, la empresa(s) {{$serviciosTele[0]->empresas_telecom}} definirá en que fecha podrá otorgar estos servicios.</p>

    <p align="justify">Una vez entregada su casa, usted podrá contratar los servicios con empresas que los proporcionen de manera satelital tales como: {{$serviciosTele[0]->empresas_telecom_satelital}} entre otros o bien el de su conveniencia.
        Aclarando además que la disponibilidad del servicio que brindan estas empresas tampoco es responsabilidad de <strong>Grupo Constructor Cumbres, S.A de C.V.</strong></p>

    

<br>
<br>
<br>
<br>
    <p align="center"><u>{{strtoupper($serviciosTele[0]->nombre)}} {{strtoupper($serviciosTele[0]->apellidos)}}</u></p>
    <p align="center">Nombre y firma de conformidad</p>
    <p style="font-size:11;" align="center">(<strong>Fracc:</strong> {{$serviciosTele[0]->proyecto}} <strong>Manzana:</strong> {{$serviciosTele[0]->manzana}} <strong>Lote:</strong> {{$serviciosTele[0]->num_lote}} )</p>
</div>

</div>
</body>
</html>