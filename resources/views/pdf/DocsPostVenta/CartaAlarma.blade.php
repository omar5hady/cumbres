<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Carta alarma</title>
    </head>
    <style type="text/css">
    @page{
        margin: 0cm 0cm 0cm 0cm;
    }

    body {
        font-family: 'AvenirNextLTPro','Muli', Helvetica;
    }

    .contenido{
        top: 70px;
        position: fixed;
        color: #001e2c;
        width: 100%;
        margin-left: 30px;
        margin-right: 160px;
        font-size: 9.5pt;
    }

    .info{
        top: 30% !important;
        margin-left: 146.54px;
        margin-right: 146.54px;
        text-align: justify;
    }

    .fecha{
        top: 25.5% !important;
        text-align: right;
    }

    .firma{
        position: fixed;
        color: #001e2c;
        width: 100%;
        top: 84.5%;
        width: 100%;
    }

    .firma p{
        font-size: 9pt;
        line-height: 5%;
    }

    </style>

    <body>
        <img style="margin-top: 0px; position:fixed;"
                src="img/Alarma1.png" width="100%">


        <img style="margin-top: 19.9cm; position:fixed;"
                src="img/Alarma2.png" width="100%">

        <div class="contenido fecha">
            <p>San Luis Potosí, S.L.P. A {{mb_strtoupper($contrato->entrega_real)}} </p>
        </div>

        <div class="contenido info">
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp; QUEREMOS INFORMARTE QUE TU NUEVA CASA CUENTA CON UNA PROMOCIÓN QUE CONSTA DE
                <b>UNA ALARMA BÁSICA Y DOS AÑOS DE MONITOREO GRATIS;</b> ESTE PAQUETE INCLUYE DOS CONTACTOS MAGNÉTICOS Y DOS
                SENSORES DE MOVIMIENTO. ESTE SERVICIO LO PROPORCIONA LA EMPRESA SISTEMAS DIGITALES DE SEGURIDAD PRIVADA, S.A DE C.V (SDS).
            </p>
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp; PARA QUE PUEDAS REALIZAR LA CONTRATACIÓN E INSTALACIÓN TE SOLICITAMOS COMUNICARTE CON EL
                <b>SR. REYNALDO RAMOS CASTILLO</b> ASESOR DE VENTAS DE LA EMPRESA DE SDS A LOS NÚMEROS TELEFÓNICOS
                <b>(444) 825 4103 Y (444) 848 1284.</b>
            </p>
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp; DISPONES DE 3 SEMANAS POSTERIORES A LA ENTREGA DE TU CASA COMO LÍMITE PARA SU INSTALACIÓN
                PARA HACER VÁLIDA ESTA PROMOCIÓN. ADEMÁS TE PEDIMOS NOS APOYES A SUPERVISAR LA CALIDAD QUE NOS OFRECE ESTA EMPRESA,
                <b>REVISANDO MINUCIOSAMENTE LA INSTALACIÓN DE TU ALARMA, COMPROBANDO QUE UTILICEN MATERIAL NUEVO Y
                    QUE QUEDE INSTALADA A TU ENTERA SATISFACCIÓN.
                </b>
            </p>
        </div>

        <div class="firma">
            <center>
                <strong>
                    <p>{{mb_strtoupper($contrato->nombre_cliente)}} </p>
                </strong>
            </center>
        </div>

    </body>
</html>
