<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pago de incentivo por venta</title>
</head>
<style type="text/css">

.contenedor {
    clear:both;
    float: left;
    width: 1000px;
}

body {
    font-size: 11pt;
    font-family: sans-serif;
}

.table-row { display: table-row; }
.table-cell1 { display: table-cell; padding: 0em; font-size: 11pt; }
.table-cell3 { display: table-cell; padding: 0em; font-size: 10pt; }
.table-cell2 { display: table-cell; padding: 0em; font-size: 9pt; }
.table { display: table; width: 90%; border-collapse: collapse; table-layout: fixed; }
.color{color: red;}


</style>

<body> 

    <div style="border: black 2px solid;">

        <div style="clear:both;">
                <div style="float: left; margin-top: 5px; margin-left: 20px;">
                        <IMG SRC="img/contratos/logoContrato.jpg" width="110" height="110" >
                </div>

            <div style="float: right; margin-top: 5px; " class="table" >
                <div class="table-row">
                    <div class="table-cell2"></div>
                    <div colspan="4" class="table-cell2"><b>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2 color" ><b>{{mb_strtoupper($bonos[0]->id)}}</div>
                </div>
                <div class="table-row">
                    <div class="table-cell2"></div>
                    <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera no. 190 esquina  </div>  
                </div>
                <div class="table-row">
                    <div class="table-cell2"></div>
                    <div colspan="8" class="table-cell2"> <b>con Nicolas Zapata Col. Tequisquiapan </div>  
                </div>
                <div class="table-row">
                    <div class="table-cell2"></div>
                    <div colspan="8" class="table-cell2"> <b>C.P. 78230 Teléfono (444) 833-46-83 al 85</div>  
                </div>
                <div class="table-row">
                    <div class="table-cell2"></div>
                    <div colspan="8" class="table-cell2"> <b>San Luis Potosí, S.L.P.</div>  
                </div>
            </div>

            <div style="float: right; margin-top: 70; margin-left: 400px;" class="table" >
                <div class="table-row">
                    
                <div class="table-cell1" ><b>{{$bonos[0]->fecha_pago}}</div>
                </div>
                
            </div>


            <div class="table" style="text-align:left; margin-top: 130px; margin-left: 20px;">
                <br><br>
                <div class="table-row">
                    <div colspan="4" class="table-cell1">  Pago de incentivo por cierre de venta a nombre de: <b>{{mb_strtoupper($bonos[0]->cliente)}}</div>  
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell1">Correspondiente a la adquisición de la vivienda ubicada en el fraccionamiento: </div>
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell1"><b>{{mb_strtoupper($bonos[0]->fraccionamiento)}}</div>
                </div>

                <div class="table-row">
                    <div  class="table-cell1">Manzana: <b>{{mb_strtoupper($bonos[0]->manzana)}} </div>
                    <div  class="table-cell1">Lote: <b>{{mb_strtoupper($bonos[0]->num_lote)}} </div>
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell1">Tipo de Crédito: <b>{{mb_strtoupper($bonos[0]->tipo_credito)}}</div>
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell1">Fecha de venta: <b>{{mb_strtoupper($bonos[0]->fecha)}}</div>
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell1"><br></div>
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell1">Por la cantidad de $1'000.00 (Mil Pesos 00/100 M.N.) </div>
                </div>
                @if($bonos[0]->num_bono == 2)
                <div class="table-row">
                    <div colspan="4" class="table-cell1"> <strong>Segundo pago</strong> </div>
                </div>
                @endif


                

                <div class="table" style="margin-left: 20px;">
                    <div class="table-row">
                        <div colspan="2" class="table-cell1"> </div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell1"> </div>
                    </div>
                    <div class="table-row"> 
                        <div colspan="5" class="table-cell1"> <br> <br> </div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell1"></div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell1" style="text-align:center;" ></div>
                    </div>

                    <div class="table-row">
                        
                        <div colspan="5" style="text-align:center;"  class="table-cell3">_________________________________________<br></div>
                        
                    </div>
                    <div class="table-row">

                        <div colspan="5" style="text-align:center;"  class="table-cell3">Recibido por: ({{mb_strtoupper($bonos[0]->asesor)}})</div>
                        
                    </div>
                </div>

                

                

            </div> 

        </div>

    </div>

    <br>
    <br>
    
</body>

</html>