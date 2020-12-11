<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carta de recepción</title>
</head>

<style type="text/css">
div {
  page-break-inside: avoid;
}
body {
    font-size: 12.5pt;
    font-family: Arial;
}

.li-2 {font-family:"DejaVu Sans";}
</style>
    <body>
        <div style="display: inline-block; float: center;">
            <div style="display: inline-block; float: left;" >
                    <IMG SRC="img/logosFraccionamientos/{{ $contratos[0]->logo_fracc }}" width="100" height="100">
                </div>
                <div style="display: inline-block; float: right;" >
                    <IMG SRC="img/contratos/logoContrato.jpg" width="120" height="120">
                </div>
        </div>
       
        <div style="margin: 60px; margin-top: 120px;"> 
            <hr>
            <br>
            <p style="text-align: right;"> San Luis Potosí, S.L.P. a ____ de ____________ del 20_____
            </p>
            <br>
        

            <p style="margin: 10px; line-height: 190%; text-align: justify;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Recibí de conformidad de <b>GRUPO CONSTRUCTOR CUMBRES S.A. DE C.V.</b> la siguiente documentación correspondiente
                a la vivienda ubicada en el <b>LOTE {{$contratos[0]->num_lote}}</b> de la <b>
                    @if($contratos[0]->etapa == 'EXTERIOR')
                        MANZANA {{mb_strtoupper($contratos[0]->manzana)}}
                    @else
                        PRIVADA "{{mb_strtoupper($contratos[0]->etapa)}}"
                    @endif
                </b> en la calle 
                <b>{{mb_strtoupper($contratos[0]->calle)}} No. {{$contratos[0]->numero}}</b> del 
                <b> FRACCIONAMIENTO "{{mb_strtoupper($contratos[0]->proyecto)}}"</b> en la {{ucwords ($contratos[0]->delegacion)}}, en la ciudad de San Luis Potosí:
            </p>

            <ul style="list-style-type:none; margin: 40px;">
                <li class="li-2"> &#10004; Plano Arquitectonico.</li>
                <li class="li-2"> &#10004; Póliza de garantía original</li>
                <li class="li-2"> &#10004; Copia de dictamen de factibilidad de agua</li>
                <li class="li-2"> &#10004; Carta informativa Interapas</li>
                <li class="li-2"> &#10004; Manual de mantenimiento</li>
                <li class="li-2"> &#10004; Tríptico instalación de gas</li>
                <li class="li-2"> &#10004; 1 par de membretes de identificación vehicular </li>
            </ul>

            <br>
            <p style="text-align: center;"> Recibí </p>
            <br>
            <p style="text-align: center;">______________________________________</p>
            <p style="text-align: center;"><b>{{mb_strtoupper($contratos[0]->nombre_cliente)}}</b></p>

            

        </div>


    </body>
</html>