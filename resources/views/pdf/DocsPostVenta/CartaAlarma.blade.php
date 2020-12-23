<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Carta alarma</title>
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
    .table-cell3 { display: table-cell; padding: 0em; font-size: 10pt; }
    .table3 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
    .table-row { display: table-row; }
    </style>
    <body>
        <div style="margin-top: 0px; width: 100%; position: fixed; text-align: center;">
        {{-- <IMG style="margin-top: -20px;" SRC="files/etapas/plantillasTelecom/{{$serviciosTele[0]->plantilla_telecom}}" width="794" height="1020"> --}}
            <div style="margin-top: 160px; position: absolute; width: 100%; left:60px; right:60px;">


                <p style="text-align: right;"> San Luis Potosí, S.L.P. a ____ de ____________ del 20_____</p><br><br>

                <p align="justify">Bienvenido a la Familia Cumbres Sr (a). <u>{{mb_strtoupper($alarma[0]->nombre_cliente)}}   </u></p><br>

                <p align="justify">¡Muchas felicidades por tu nueva casa!</p>

                <p align="justify">&nbsp;&nbsp;&nbsp;&nbsp;Queremos informarte que tu nueva casa cuenta con una promoción que consta de una
                    alarma básica y dos años de monitoreo gratis; este paquete incluye dos contactos magnéticos
                    y dos sensores de movimiento. Este servicio lo proporciona la empresa Sistemas
                    Digitales de Seguridad Privada, S.A de C.V (SDS).
                </p>

                <p align="justify">&nbsp;&nbsp;&nbsp;&nbsp;Para que puedas realizar la contratación e instalación te solicitamos comunicarte con
                    el <b>Sr. Reynaldo Ramos Castillo</b> asesor de ventas de la empresa de SDS en los números
                    telefónicos <b>444 825 41 03 y 444 848 1284</b>
                </p>

                <p align="justify">
                    &nbsp;&nbsp;&nbsp;&nbsp;Dispones de <b>3 semanas posteriores a la entrega de tu casa como límite</b> para su
                    instalación para hacer válida esta promoción. Además te pedimos nos apoyes a supervisar
                    la calidad que nos ofrece esta empresa, revisando minuciosamente la instalación
                    de tu alarma, comprobando que utilicen material nuevo y que quede instalada a tu
                    entera satisfacción.
                </p><br>

                <p align="center">
                    Atentamente.
                </p>

                

                <br>
                <br>
                <br>
                <br>

                <div class="table3">
                   
                    
                    <div class="table-row">
                        <div colspan="2" class="table-cell3">_________________________________</div>
                        <div style="width: 8%;" class="table-cell3"></div>
                        <div colspan="2" class="table-cell3">_________________________________</div>
                    </div>
                    <div class="table-row">
                        
                        <div colspan="2" class="table-cell3">
                            Departamento de Post-Venta
                            <br>Grupo Constructor Cumbres S.A. de C.V.</div>
                        
                        <div style="width: 8%;" class="table-cell3"></div>
                        <div colspan="2" class="table-cell3">
                            {{mb_strtoupper($alarma[0]->nombre_cliente)}}
                            <br>(Quedo enterado de que he recibido)
                        </div>
                    </div>
                    
                </div>
                
            </div>

        </div>
    </body>
</html>