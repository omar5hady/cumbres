<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Invitación evento</title>
    </head>
    <style type="text/css">
      @page {
           margin: 0px;
           size: 1080px 1920px;
        }

        @font-face {
        font-family: 'Gotham-Book';
        font-size:12;
        font-style: Book;
        src: url("GothamBook.ttf") format('truetype');
        }
        body{
            font-family: Gotham-Book, sans-serif;
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
            /* background-size: 100% 100%; */
        }
        .cliente{
            background-image: url('img/invitacion/Boleto_digital-01.png');
        }
        .invitado{
            background-image: url('img/invitacion/Boleto_digital-02.png');
        }



        .folio{
            right: 210px;
            top: 140px;
            text-align: left;
            color: rgb(237, 28, 0);
            width: 70px;
            height: 20px;
            position: absolute;

        }
        .datos{

            width: 1080px;
            top: 930px;
            color: #1A3455;
            position: absolute;
            font-size: 50px;
            font-style: normal;
            font-weight: bold;
            align-items: center;
            text-align: center;
        }
        .qr{
            top: 1200px;
            left: 335px;
            height: 416px;
            width: 416px;
            position: absolute;
        }
        .footer{
            background-color: rgb(0,0,0, 0.3);
            top: 1800px;
            height: auto;
            width: 1080px;
            position: absolute;
            padding: 15px;
            color: #fff;
        }


    </style>
    @if($invitado->is_cliente == 1)
        <body class="cliente">
    @else
        <body class="invitado">
    @endif
        <div class="datos" >
            {{ucwords($invitado->nombre)}} {{ucwords($invitado->ap_paterno)}} {{ucwords($invitado->ap_materno)}}
        </div>
        <div class="qr">
            <img src="data:image/png;base64, {!! base64_encode(QrCode::size(416)->generate('https://siicumbres.com/evento?id='.$invitado->id)) !!} ">
        </div>
        <div class="footer">
            <center>
                <h3
                    style="margin: 10px;"
                >Este boleto debe ser presentado y escaneado para acceder al evento. Se verificará su validez al ingreso.</h3>
            </center>
        </div>
    </body>
</html>
