<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style type="text/css">
div {
  page-break-inside: avoid;
}
body {
    font-size: 11pt;
    font-family: sans-serif;
}
</style>
<body>
<div style="display: inline-block; float: left;" >
    <IMG SRC="img/logosFraccionamientos/{{ $contratos[0]->logo_fracc }}" width="100" height="100">
</div>
<div style="margin: 70px; margin-top: 120px;"> 
    <hr>
    <h3 style="text-align: center;">¡BIENVENIDO!</h3> <br>
    <p>Después de saludarles, les damos la más cordial bienvenida a su Condominio y nos ponemos a sus órdenes como administradores de 
        {{mb_strtoupper($contratos[0]->proyecto)}} - {{mb_strtoupper($contratos[0]->etapa)}}. Nos encargaremos de llevar el mantenimiento en general de las áreas comunes del condominio, que incluyen:
    </p>

    <ul>
        <li>Limpieza y Jardinaría</li>
        <li>Coordinación de la Vigilancia</li>
        <li>Recolección de Basura</li>
        <li>Luz de las áreas comunes</li>
        <li>Mantenimiento de la alberca</li>
        <li>Agua para riego de las áreas comunes</li>
        <li>Llevar las finznas del condominio, administración y recaudación de las cuotas de mantenimiento y pago de los servicios</li>
        <li>En general las obligaciones señaladas en el reglamento del Régimen de Propiedad en Condominio</li>
    </ul>

    <p>Nuestras oficinas están en Av. Parque Chapultepec 404 - A, Fracc. Colinas del Parque de esta ciudad en horario corrido de 9:30 a 17:30 hrs.
        de lunes a viernes, los sábados de 10:00 a 13:00 hrs. y/o en el telefono 8 41 79 35. También nos pueden contactar a través del 
        correo electrónico: {{$contratos[0]->email_administracion}}
    </p>

    <p>Ahora que le ha sido enntregada su casa, es necesario comenzar a realizar el pago de la cuota mensual de mantenimiento.
     Esta cuota es de <b>${{$contratos[0]->costo_mantenimiento_letra}}</b> por casa y se deberá pagar antes de los días 10 de cada mes para no generar recargos.
     Estos recargos son del 5% mensual (art. 67 del reglamento del condominio).
    </p>
</div>

<div style="margin: 70px;">
    <p>DESCUENTO POR PAGO ANUAL: En el caso de hacer un pago anual por adelantado, se hará un descuento del 10%. Si tiene alguna duda en este 
        aspecto por favor comuniquese con nosotros.
    </p>
    <p>Las formas de pago son: <br>
        <b>Depósito bancario:</b> En cualquier sucursal de banco SANTANDER se puede hacer el pago en ventanilla. Es una cuenta referenciada
            por lo que necesita dar el número de convenio <b>0291</b> más su referencia que se compone de 
            <b>4 números, comenzando con un 1 más su número oficial</b> (por ejemplo, si su casa es Circuito Modena #200 su referencia es <b>1200</b>)
    </p>

    <p><b>Transferencia Electrónica:</b> Los pagos pueden hacerse de cualquier banco a la cuenta:</p>

    <p>Banco: {{$contratos[0]->banco_admin}}</p>
    <P>Sucursal: {{$contratos[0]->sucursal_admin}}</P>
    <p>Titular: {{$contratos[0]->titular_admin}}</p>
    @if($contratos[0]->clabe_admin != '')
    <p>CLABE: {{$contratos[0]->clabe_admin}}</p>
    @endif
    @if ($contratos[0]->num_cuenta_admin != '')
    <p>Cuenta: {{$contratos[0]->num_cuenta_admin}}</p>
    @endif
    <p>En caso de hacer el pago vía transferencia electrónica es MUY IMPORTANTE que al hacerlo ponga su número de referencia en el concepto,
        ya que de haber depósitos sin referencia no se tendría a quien acreditarlo.
    </p>

    <p>En caso de requerir factura favor de enviar sus datos y se le hará llegar vía correo electrónico.</p>

    <p>Les pedimos actar el reglamento interno del condominio, en caso de no tenerlo por favor avísenos para proporcionarles una copia.</p>

    <p>Nuevamente les damos la bienvenida y nos ponemos a sus órdenes para resolver cualquier duda o comentario.</p>

    <p>Atentamente</p>

    <p>ADMINISTRACION DE FRACCIONAMIENTO {{mb_strtoupper($contratos[0]->proyecto)}} A.C.</p> <br>

    <p style="text-align: right; margin: -1px -1px -1px -1px;"> <b> Av. Parque Chapultepec 404 - A</b></p>
    <p style="text-align: right; margin: -1px -1px -1px -1px;"> <b> Fracc. Colinas del Parque, C.P. 78294</b></p>
    <p style="text-align: right; margin: -1px -1px -1px -1px;"> <b>San Luis Potosí, S.L.P. México</b> </p>

</div>


</body>
</html>