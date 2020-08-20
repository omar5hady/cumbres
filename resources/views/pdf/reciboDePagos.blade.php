<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de pagos</title>
</head>
<style type="text/css">

body {
    font-size: 11pt;
    font-family: sans-serif;
}

.table-row { display: table-row; }
.table-cell1 { display: table-cell; padding: 0em; font-size: 11pt; }
.table-cell2 { display: table-cell; padding: 0em; font-size: 9pt; }
.table { display: table; width: 90%; border-collapse: collapse; table-layout: fixed; }
.color{color: red;}


</style>

<body> 

<div style="border: black 2px solid;">

<div style="clear:both;">
<div style="float: left; margin-top: 5px; margin-left: 20px;">
    @if($depositos[0]->emp_constructora == 'Grupo Constructor Cumbres')
        <IMG SRC="img/contratos/logoContrato.jpg" width="110" height="110" >
    @else 
        <IMG SRC="img/contratos/logoContratoC1.png" width="110" height="110" >
    @endif
        </div>

 <div style="float: right; margin-top: 5px; " class="table" >
      <div class="table-row">
         <div class="table-cell2"></div>
        @if($depositos[0]->emp_constructora == 'Grupo Constructor Cumbres')
            <div colspan="4" class="table-cell2"><b>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</div>
        @else
            <div colspan="4" class="table-cell2"><b>CONCRETANIA, S.A. DE C.V.</div>
        @endif
         <div class="table-cell2"></div>
         <div class="table-cell2"></div>
         <div class="table-cell2"></div>
         <div class="table-cell2 color" ><b>{{mb_strtoupper($depositos[0]->id)}}</div>

    </div>
        @if($depositos[0]->emp_constructora == 'Grupo Constructor Cumbres')
            <div class="table-row">
                <div class="table-cell2"></div>
                <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera no. 190 esquina  </div>  
            </div>
            <div class="table-row">
                <div class="table-cell2"></div>
                <div colspan="8" class="table-cell2"> <b>con Nicolas Zapata Col. Tequisquiapan </div>  
            </div>
        @else
            <div class="table-row">
                <div class="table-cell2"></div>
                <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera no. 180 </div>  
            </div>
            <div class="table-row">
                <div class="table-cell2"></div>
                <div colspan="8" class="table-cell2"> <b>Col. Tequisquiapan </div>  
            </div>
        @endif
        <div class="table-row">
             <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>C.P. 78230 Teléfono (444)8334683 al 85</div>  
        </div>
        <div class="table-row">
            <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>San Luis Potosí, S.L.P.</div>  
        </div>
</div>


<div class="table" style="text-align:left; margin-top: 120px; margin-left: 20px;">
        <div class="table-row">
            <div colspan="4" class="table-cell1"> Recibí de: <b>{{mb_strtoupper($depositos[0]->nombre)}} {{mb_strtoupper($depositos[0]->apellidos)}}</div>  
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">La cantidad de: <b>${{mb_strtoupper($depositos[0]->cantdepLetra)}} </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">Por concepto de: {{mb_strtoupper($depositos[0]->concepto)}}</div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">Correspondiente a la adquisición de la vivienda ubicada en el fraccionamiento: </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1"><b>{{mb_strtoupper($depositos[0]->fraccionamiento)}}</div>
        </div>

        <div class="table-row">
            <div  class="table-cell1">Manzana: <b>{{mb_strtoupper($depositos[0]->manzana)}} </div>
            <div  class="table-cell1">Lote: <b>{{mb_strtoupper($depositos[0]->num_lote)}} </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">En la ciudad de: SAN LUIS POTOSI </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">a los {{mb_strtoupper($depositos[0]->fecha_pago)}} </div>
        </div>

        

        <div class="table" style="margin-left: 20px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell1"> </div>
                    <div class="table-cell1"></div>
                    <div colspan="2" class="table-cell1"></div>
                </div>
                <div class="table-row"> 
                    <div colspan="5" class="table-cell1"> <br> <br> </div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell1">_________________________________</div>
                    <div style="width: 8%;" class="table-cell1"></div>
                    <div colspan="2" class="table-cell1">_________________________________</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell1" style="text-align:center;" >RECIBE (nombre y firma)</div>
                    <div style="width: 8%;" class="table-cell1"></div>
                    <div colspan="2" class="table-cell1" style="text-align:center;">{{mb_strtoupper($depositos[0]->nombre)}} {{mb_strtoupper($depositos[0]->apellidos)}}</div>
                </div>

                <div class="table-row">
                    
                    <div colspan="5" style="text-align:center;"  class="table-cell1"><br></div>
                    
                </div>
                <div class="table-row">

                    <div colspan="5" style="text-align:center;"  class="table-cell1">CONSECUTIVO</div>
                    
                </div>
            </div>

        

        

    </div> 

    </div>

</div>

<br>
<br>
<br>




<div style="border: black 2px solid;">

<div style="clear:both;">
<div style="float: left; margin-top: 5px; margin-left: 20px;">
    @if($depositos[0]->emp_constructora == 'Grupo Constructor Cumbres')
        <IMG SRC="img/contratos/logoContrato.jpg" width="110" height="110" >
    @else 
        <IMG SRC="img/contratos/logoContratoC1.png" width="110" height="110" >
    @endif
        </div>

 <div style="float: right; margin-top: 5px; " class="table" >
      <div class="table-row">
         <div class="table-cell2"></div>
        @if($depositos[0]->emp_constructora == 'Grupo Constructor Cumbres')
            <div colspan="4" class="table-cell2"><b>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</div>
        @else
            <div colspan="4" class="table-cell2"><b>CONCRETANIA, S.A. DE C.V.</div>
        @endif
         <div class="table-cell2"></div>
         <div class="table-cell2"></div>
         <div class="table-cell2"></div>
         <div class="table-cell2 color" ><b>{{mb_strtoupper($depositos[0]->id)}}</div>

    </div>
    @if($depositos[0]->emp_constructora == 'Grupo Constructor Cumbres')
        <div class="table-row">
            <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera no. 190 esquina  </div>  
        </div>
        <div class="table-row">
            <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>con Nicolas Zapata Col. Tequisquiapan </div>  
        </div>
    @else
        <div class="table-row">
            <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera no. 180  </div>  
        </div>
        <div class="table-row">
            <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>Col. Tequisquiapan </div>  
        </div>
    @endif
        <div class="table-row">
             <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>C.P. 78230 Teléfono (444)8334683 al 85</div>  
        </div>
        <div class="table-row">
            <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>San Luis Potosí, S.L.P.</div>  
        </div>
</div>


<div class="table" style="text-align:left; margin-top: 120px; margin-left: 20px;">
        <div class="table-row">
            <div colspan="4" class="table-cell1"> Recibí de: <b>{{mb_strtoupper($depositos[0]->nombre)}} {{mb_strtoupper($depositos[0]->apellidos)}}</div>  
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">La cantidad de: <b>${{mb_strtoupper($depositos[0]->cantdepLetra)}} </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">Por concepto de: {{mb_strtoupper($depositos[0]->concepto)}}</div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">Correspondiente a la adquisición de la vivienda ubicada en el fraccionamiento: </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1"><b>{{mb_strtoupper($depositos[0]->fraccionamiento)}}</div>
        </div>

        <div class="table-row">
            <div  class="table-cell1">Manzana: <b>{{mb_strtoupper($depositos[0]->manzana)}} </div>
            <div  class="table-cell1">Lote: <b>{{mb_strtoupper($depositos[0]->num_lote)}} </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">En la ciudad de: SAN LUIS POTOSI </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">a los {{mb_strtoupper($depositos[0]->fecha_pago)}} </div>
        </div>

        

        <div class="table" style="margin-left: 20px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell1"> </div>
                    <div class="table-cell1"></div>
                    <div colspan="2" class="table-cell1"></div>
                </div>
                <div class="table-row"> 
                    <div colspan="5" class="table-cell1"> <br> <br> </div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell1">_________________________________</div>
                    <div style="width: 8%;" class="table-cell1"></div>
                    <div colspan="2" class="table-cell1">_________________________________</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell1" style="text-align:center;" >RECIBE (nombre y firma)</div>
                    <div style="width: 8%;" class="table-cell1"></div>
                    <div colspan="2" class="table-cell1" style="text-align:center;">{{mb_strtoupper($depositos[0]->nombre)}} {{mb_strtoupper($depositos[0]->apellidos)}}</div>
                </div>

                <div class="table-row">
                    
                    <div colspan="5" style="text-align:center;"  class="table-cell1"><br></div>
                    
                </div>
                <div class="table-row">

                    <div colspan="5" style="text-align:center;"  class="table-cell1">EXPEDIENTE</div>
                    
                </div>
            </div>

        

        

    </div> 

    </div>

</div>

<br>
<br>
<br>
<br>





<div style="border: black 2px solid; margin-top: 80px;">

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
         <div class="table-cell2 color" ><b>{{mb_strtoupper($depositos[0]->id)}}</div>

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
            <div colspan="8" class="table-cell2"> <b>C.P. 78230 Teléfono (444)8334683 al 85</div>  
        </div>
        <div class="table-row">
            <div class="table-cell2"></div>
            <div colspan="8" class="table-cell2"> <b>San Luis Potosí, S.L.P.</div>  
        </div>
</div>


<div class="table" style="text-align:left; margin-top: 120px; margin-left: 20px;">
        <div class="table-row">
            <div colspan="4" class="table-cell1"> Recibí de: <b>{{mb_strtoupper($depositos[0]->nombre)}} {{mb_strtoupper($depositos[0]->apellidos)}}</div>  
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">La cantidad de: <b>${{mb_strtoupper($depositos[0]->cantdepLetra)}} </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">Por concepto de: {{mb_strtoupper($depositos[0]->concepto)}}</div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">Correspondiente a la adquisición de la vivienda ubicada en el fraccionamiento: </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1"><b>{{mb_strtoupper($depositos[0]->fraccionamiento)}}</div>
        </div>

        <div class="table-row">
            <div  class="table-cell1">Manzana: <b>{{mb_strtoupper($depositos[0]->manzana)}} </div>
            <div  class="table-cell1">Lote: <b>{{mb_strtoupper($depositos[0]->num_lote)}} </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">En la ciudad de: SAN LUIS POTOSI </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell1">a los {{mb_strtoupper($depositos[0]->fecha_pago)}} </div>
        </div>

        

        <div class="table" style="margin-left: 20px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell1"> </div>
                    <div class="table-cell1"></div>
                    <div colspan="2" class="table-cell1"></div>
                </div>
                <div class="table-row"> 
                    <div colspan="5" class="table-cell1"> <br> <br> </div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell1">_________________________________</div>
                    <div style="width: 8%;" class="table-cell1"></div>
                    <div colspan="2" class="table-cell1">_________________________________</div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell1" style="text-align:center;" >RECIBE (nombre y firma)</div>
                    <div style="width: 8%;" class="table-cell1"></div>
                    <div colspan="2" class="table-cell1" style="text-align:center;">{{mb_strtoupper($depositos[0]->nombre)}} {{mb_strtoupper($depositos[0]->apellidos)}}</div>
                </div>

                <div class="table-row">
                    
                    <div colspan="5" style="text-align:center;"  class="table-cell1"><br></div>
                    
                </div>
                <div class="table-row">

                    <div colspan="5" style="text-align:center;"  class="table-cell1">CLIENTE</div>
                    
                </div>
            </div>

        

        

    </div> 

    </div>

</div>

</body>
</html>