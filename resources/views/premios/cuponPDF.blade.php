<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cupon</title>
    </head>
    <style type="text/css">
      @page {
           margin: 0px;
        }
      
        @font-face {
        font-family: 'Gotham-Book';
        font-size:12;
        font-style: Book;
        src: url("GothamBook.ttf") format('truetype');
        }
        body {
            font-family: Gotham-Book, sans-serif;
            width: 100%;
            height: 100%;
            padding-top: 100px;
            padding-left: 25%;
            padding-right: 25%;
            background-image: url('img/ruleta/Background.png');
            background-position: center;

        }
   
   
        .img-back{
            background-image: url('img/ruleta/CuponDigitalBase.png');
            background-repeat: no-repeat;
            background-size:contain;
            width: 100%;
            height: 90%;
            z-index: 0;
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
            
            width: 400px; height: 100px;
            top: 280px;
            left: 200px;
            text-align: left;
            color: #1A3455;
            position: absolute;
            font-size: 20px;
            font-style: normal;
            font-weight: bold;
            align-items: center;
            text-align: center;
            
        }
        .texto{
            width: 200;
            height: 200px;
            bottom: 100px;
            left: 300px;
            /* background-color: rgb(255, 255, 255); */
            font-size: 7.5px;
            position: absolute;
        }
        .premio{
            
            /* background-color: violet; */
            top: 410px; 
            left: 230px;
            position: absolute;
        }


    </style>
<body>
            <div class="img-back">
                <div class="folio">
                    {{$lead[0]->folio}}
                </div>
                <div class="datos" >
                    {{$lead[0]->nombre}} {{$lead[0]->apellidos}}
                </div>
                <div class="datos" style="top:310px; font-size: 12px; color:1F92C8;" >
                    Fecha de nacimiento registrada: {{$lead[0]->f_nacimiento}}
                </div>

                    @switch($lead[0]->premio)
                   
                        @case(3000)
                            <div class="premio" >
                                <img src="img/ruleta/C3mil.png" alt=""  width="350px" height="300px" > 
                            </div>
                            @break
                        @case(5000)
                            <div class="premio" >
                                <img src="img/ruleta/C5mil.png" alt=""  width="350px" height="300px" > 
                            </div>
                            @break
                        @case(8000)
                            <div class="premio" >
                                <img src="img/ruleta/C8mil.png" alt=""  width="350px" height="300px" > 
                            </div>
                            @break
                        @case(10000)
                            <div class="premio" >
                                <img src="img/ruleta/C10mil.png" alt=""  width="350px" height="300px" > 
                            </div>
                            
                            @break
                        @case(15000)
                            <div class="premio" >
                                <img src="img/ruleta/C15mil.png" alt=""  width="350px" height="300px" > 
                            </div>
                            @break
                        @default
                            
                    @endswitch


                    <div class="texto">
                        <ul>
                            <li>
                              <template style="color: deeppink"> Válido </template> en la compra de tu casa o departamento, una vez hayas firmado las escrituras de esta.
                
                            </li>
                            <li>
                                Este cupón aplica <template style="color: deeppink"> únicamente </template>en la compra de una casa o depa.
                            </li>
                            <li>
                                <template style="color: deeppink"> No aplica</template> en la compra de terrenos.

                            </li>
                            <li>
                                Canjeable de 2 maneras:
                                <ul style="margin-left: -30px">
                                    <li><template style="color: deeppink"> Efectivo </template> (Una vez hayas firmado escrituras de tu casa) o </li>
                                    <li><template style="color: deeppink"> Descuento</template>   directo al valor de la casa.</li>
                                </ul>
                            </li>
                            <li>
                                Cupón<template style="color: deeppink"> adicional </template>   a cualquier pormoción vigente.
                            </li>
                            <li>
                                Cupón<template style="color: deeppink"> No tranferible </template>  (válido únicamente para el(la) titular al quien está emitido).
                            </li>
                            <li>
                                Cupón válido hasta esta fecha: {{$lead[0]->f_caducidad}}, preséntalo y hazlo válido en la firma de tu contrato de promesa de compra-venta.
                            </li>
                        </ul>
                    </div>
               
         
            </div>
       
                   

                
       
       
    
</body>
</html>