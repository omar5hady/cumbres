<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style type="text/css">

body {
    font-size: 8pt;
    font-family: sans-serif;
}

.table-row { display: table-row; }
.table-cell1 { display: table-cell; padding: 0em; font-size: 8pt; }
.table { display: table; width: 90%; border-collapse: collapse; table-layout: fixed; }


</style>
<body>
@for($i=0; $i < count($pagos); $i++)
<div style="clear:both;">

    <div style="float: left; margin-top: 20px; margin-left: 50px;" >
    @if($cliente[0]->emp_constructora == 'CONCRETANIA' && $cliente[0]->emp_terreno == 'CONCRETANIA')
        <IMG SRC="img/contratos/logoContratoC1.png" width="110" height="110">
    @else
        <IMG SRC="img/contratos/logoContrato.jpg" width="110" height="110">
    @endif
    </div>

<div style="text-align: justify; margin:60px">
        <div style="text-align: right; margin-bottom: 0em;  margin-top: 0em;">
            <p style="margin-bottom: 0em;  margin-top: 0em;"> <strong> PAGARE NO. </strong> {{$pagos[$i]->num_pago + 1}}/{{count($pagos)}}</p>
            <p style="margin-bottom: 0em;  margin-top: 0em;"> <strong>BUENO POR </strong>${{$pagos[$i]->monto_pago1}}</p>
            <p style="margin-bottom: 0em;  margin-top: 0em;">EN SAN LUIS POTOSI, SAN LUIS POTOSI, A {{strtoupper($cliente[0]->fecha)}}</p>
        </div>
        <br>
        <br>
        <br>
       
            <div>
            <p style="margin-bottom: 0em;  margin-top: 0em;">DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE POR ESTE PAGARE A LA ORDEN DE GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V., EN SAN LUIS POTOSI, SAN LUIS POTOSI, <strong> EL {{strtoupper($pagos[$i]->fecha_pago)}}</strong></p>
            <p style="margin-bottom: 0em;  margin-top: 0em;">LA CANTIDAD DE: </p>
            <p style="margin-bottom: 0em;  margin-top: 0em;"><strong>${{strtoupper($pagos[$i]->montoPagoLetra)}}</strong></p>
            <p style="margin-bottom: 0em;  margin-top: 0em;">VALOR RECIBIDO A MI (NUESTRA) ENTERA SATISFACCION. ESTE PAGARE FORMA PARTE DE UNA SERIE NUMERADA DEL <strong>1</strong> AL <strong>{{count($pagos)}}</strong> Y TODOS ESTAN SUJETOS A LA CONDICION DE QUE, AL NO PAGARSE CUALQUIERA
                DE ELLOS A SU VENCIMIENTO, SERAN EXIGIBLES TODOS LOS QUE LE SIGAN EN NUMERO, ADEMAS DE LOS YA VENCIDOS, DESDE LA FECHA DE VENCIMIENTO DE ESTE DOCUMENTO HASTA EL DIA DE SU LIQUIDACION,
                CAUSARA INTERESES MORATORIOS AL TIPO DE <strong>5%</strong> MENSUAL, PAGADERO EN ESTA CIUDAD JUNTAMENTE CON EL PRINCIPAL.</p>

            </div>
            <br>
            <br>
    <div class="table" style="text-align:left;">
        <div class="table-row">
            <div style="text-align:center;" colspan="4" class="table-cell1"> <b>CLIENTE</div>
            
        </div>
        <div class="table-row">
            <div colspan="4" class="table-cell1">NOMBRE: {{mb_strtoupper($cliente[0]->nombre)}} {{mb_strtoupper($cliente[0]->apellidos)}}</div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">DIRECCION: {{mb_strtoupper($cliente[0]->direccion)}} {{mb_strtoupper($cliente[0]->colonia)}}</div>
        </div>
        <div class="table-row">
            <div colspan="2" class="table-cell1">CP: {{$cliente[0]->cp}} </div>
            <div class="table-cell1"></div>
            <div class="table-cell1"></div> 
        </div>
        <div class="table-row">
            <div class="table-cell1">TEL: {{$cliente[0]->telefono}}</div>
            <div class="table-cell1"></div>
            <div style="text-align:right;" class="table-cell1">FIRMA CLIENTE: </div>
            <div class="table-cell1">_____________________ </div> 
        </div>
        <div class="table-row">
            <div colspan="4" class="table-cell1">{{mb_strtoupper($cliente[0]->ciudad)}}, {{mb_strtoupper($cliente[0]->estado)}} </div> 
        </div>
    </div>   

    </div>
 
</div>
@endfor
</body>
</html>