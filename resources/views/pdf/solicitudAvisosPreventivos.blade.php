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

.table-row { display: table-row;  }
.table-cell1 { display: table-cell;  font-size: 11pt; text-align:center; }
.table-cell2 { display: table-cell;  font-size: 9pt; text-align:center;  }
.table-cell3 { display: table-cell;  font-size: 9pt; text-align:left;  }
.table-cell4 { display: table-cell;  font-size: 9pt; text-align:right; }
.table { display: table; width: 90%; border-collapse: collapse; table-layout: fixed; }



</style>
<body>
<div style="border: black 2px solid;" >
    <div style="clear:both;">
        
        <div style="float: left; margin-top: 5px; margin-left: 20px;">
            @if(!$solicitud[0]->emp_constructora == 'Grupo Constructor Cumbres')
                <IMG SRC="img/contratos/logoContrato.jpg" width="130" height="130" >
            @else
                <IMG SRC="img/contratos/logoContratoC1.png" width="130" height="130" >
            @endif
        </div>  

        <div  class="table" >
            <div class="table-row">
                <div  class="table-cell1"></div>
                <div colspan="7" class="table-cell1"><b>SOLICITUD DE AVISOS PREVENTIVOS</div>
            </div> 

            <div class="table-row">
                <div colspan="8" class="table-cell1"><br></div>
            </div>

            @if(!$solicitud[0]->emp_constructora == 'Grupo Constructor Cumbres')
                <div class="table-row">
                    <div  class="table-cell1"></div>
                    <div colspan="7" class="table-cell2"> <b>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</div>
                </div>
            @else
                <div class="table-row">
                    <div  class="table-cell1"></div>
                    <div colspan="7" class="table-cell2"> <b>CONCRETANIA, S.A. DE C.V.</div>
                </div>
            @endif

        @if(!$solicitud[0]->emp_constructora == 'Grupo Constructor Cumbres')
            <div class="table-row">
                <div class="table-cell2"></div>
                    <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera No. 190 esquina  </div>  
                </div>

            <div class="table-row">
                <div class="table-cell2"></div>
                    <div colspan="8" class="table-cell2"> <b>con Nicolas Zapata Col. Tequisquiapan </div>  
                </div>
        @else
            <div class="table-row">
                <div class="table-cell2"></div>
                    <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera No. 180  </div>  
                </div>

            <div class="table-row">
                <div class="table-cell2"></div>
                    <div colspan="8" class="table-cell2"> <b>Col. Tequisquiapan </div>  
                </div>
        @endif
            <div class="table-row">
                <div class="table-cell2"></div>
                <div colspan="8" class="table-cell2"> <b>C.P. 78230 Teléfono (444) 8-33-46-83 al 85</div>  
            </div>
            <div class="table-row">
                <div class="table-cell2"></div>
                <div colspan="8" class="table-cell2"> <b>San Luis Potosí, S.L.P.</div>  
            </div>
        </div>

        <div class="table">
        
            <div class="table-row">
                <div class="table-cell3"></div>
                <div class="table-cell3"></div>
                <div class="table-cell3"></div>
                <div class="table-cell3"></div>
                <div colspan="4" class="table-cell4"> <b>{{mb_strtoupper($solicitud[0]->aviso_prev)}} </div>  
            </div>

        </div>

    <div class="table" style="text-align:left; margin-top: 30px; margin-left: 20px;">
    
        <div class="table-row">
            <div colspan="4" class="table-cell3"> TIPO DE CREDITO:<b> {{mb_strtoupper($solicitud[0]->tipo_credito)}} ({{mb_strtoupper($solicitud[0]->institucion)}}) </div>  
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3">CLIENTE:<b> {{mb_strtoupper($solicitud[0]->nombre_cliente)}} </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3">FRACCIONAMIENTO:<b> {{mb_strtoupper($solicitud[0]->proyecto)}}</div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3">MANZANA:<b> {{mb_strtoupper($solicitud[0]->manzana)}}</div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3">LOTE: <b>{{mb_strtoupper($solicitud[0]->num_lote)}}</div>
        </div>

        <div class="table-row">
            <div colspan="3" class="table-cell3">DIRECCIÓN:  <b>{{mb_strtoupper($solicitud[0]->calle)}} </div>
            <div class="table-cell3">NUM. <b>{{mb_strtoupper($solicitud[0]->numero)}}</div>
            @if($solicitud[0]->interior)
                <div class="table-cell3">INT. <b>{{mb_strtoupper($solicitud[0]->interior)}}</div>
            @endif
            @if(!$solicitud[0]->interior)
                <div class="table-cell3"> </div>
            @endif
            <div class="table-cell3"></div>
            <div class="table-cell3"></div>
            <div class="table-cell3"></div>
        </div>
    </div> 

    <div class="table" style="text-align:left; margin-top: 15px; margin-left: 20px;">
        <div class="table-row">
            <div colspan="4" class="table-cell3"> <b>DATOS DEL NOTARIO</b> </div>  
        </div>
        <div class="table-row">
            <div colspan="4" class="table-cell3"> <br> </div>  
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3"> NOMBRE: <b>{{mb_strtoupper($solicitud[0]->titular)}}</div>  
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3">NOTARIA NUMERO:<b> {{mb_strtoupper($solicitud[0]->notaria)}} </div>
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3">CREDITO PUENTE: <b>{{mb_strtoupper($solicitud[0]->credito_puente)}} </div>
        </div>

        
    </div>

    <div class="table" style="text-align:left; margin-top: 15px; margin-left: 20px;">
        <div class="table-row">
            <div colspan="4" class="table-cell3"> <b>Recibio:</b> </div>  
        </div>
        <div class="table-row">
            <div colspan="4" class="table-cell3"> <br> </div>  
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3"> Nombre: __________________________________________ </div>  
        </div>

        <div class="table-row">
            <div colspan="4" class="table-cell3">Fecha: ___________________ </div>
        </div> 
        <div class="table-row">
                    
                    <div colspan="5" style="text-align:center;"  class="table-cell1"><br></div>
                    
                </div>
    </div> 
    

    </div>
</div>
<br>
<br>
<br>
<div style="border: black 2px solid;" >
        <div style="clear:both;">
            
                <div style="float: left; margin-top: 5px; margin-left: 20px;">
                @if(!$solicitud[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    <IMG SRC="img/contratos/logoContrato.jpg" width="130" height="130" >
                @else
                    <IMG SRC="img/contratos/logoContratoC1.png" width="130" height="130" >
                @endif
                </div>
        <div  class="table" >
            <div class="table-row">
                <div  class="table-cell1"></div>
                <div colspan="7" class="table-cell1"><b>SOLICITUD DE AVISOS PREVENTIVOS</div>
                
            </div> 
    
            <div class="table-row">
                <div colspan="8" class="table-cell1"><br></div>
            </div>
    
          <div class="table-row">
                <div  class="table-cell1"></div>
                @if(!$solicitud[0]->emp_constructora == 'Grupo Constructor Cumbres')
                <div colspan="7" class="table-cell2"> <b>GRUPO CONSTRUCTOR CUMBRES, S.A. DE C.V.</div>
                @else
                <div colspan="7" class="table-cell2"> <b>CONCRETANIA, S.A. DE C.V.</div>
                @endif
        </div>
    
        <div class="table-row">
            <div class="table-cell2"></div>
                @if(!$solicitud[0]->emp_constructora == 'Grupo Constructor Cumbres')
                <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera No. 190 esquina  </div>  
                @else
                <div colspan="8" class="table-cell2"> <b>Manuel Gutiérrez Najera No. 180 </div>  
                @endif
            </div>
    
    <div class="table-row">
            <div class="table-cell2"></div>
                @if(!$solicitud[0]->emp_constructora == 'Grupo Constructor Cumbres')
                <div colspan="8" class="table-cell2"> <b>con Nicolas Zapata Col. Tequisquiapan </div>  
                @else 
                <div colspan="8" class="table-cell2"> <b>Col. Tequisquiapan </div>  
                @endif
            </div>
            <div class="table-row">
                 <div class="table-cell2"></div>
                 <div colspan="8" class="table-cell2"> <b>C.P. 78230 Teléfono (444) 8-33-46-83 al 85</div>  
            </div>
            <div class="table-row">
                <div class="table-cell2"></div>
                <div colspan="8" class="table-cell2"> <b>San Luis Potosí, S.L.P.</div>  
            </div>
        </div>
    
        <div class="table">
        
            <div class="table-row">
                <div class="table-cell3"></div>
                <div class="table-cell3"></div>
                <div class="table-cell3"></div>
                <div class="table-cell3"></div>
                <div colspan="4" class="table-cell4"> <b>{{mb_strtoupper($solicitud[0]->aviso_prev)}} </div>  
            </div>
    
        </div>
    
        <div class="table" style="text-align:left; margin-top: 30px; margin-left: 20px;">
        
            <div class="table-row">
                <div colspan="4" class="table-cell3"> TIPO DE CREDITO:<b> {{mb_strtoupper($solicitud[0]->tipo_credito)}} ({{mb_strtoupper($solicitud[0]->institucion)}}) </div>  
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3">CLIENTE:<b> {{mb_strtoupper($solicitud[0]->nombre_cliente)}} </div>
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3">FRACCIONAMIENTO:<b> {{mb_strtoupper($solicitud[0]->proyecto)}}</div>
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3">MANZANA:<b> {{mb_strtoupper($solicitud[0]->manzana)}}</div>
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3">LOTE: <b>{{mb_strtoupper($solicitud[0]->num_lote)}}</div>
            </div>
    
            <div class="table-row">
                <div colspan="3" class="table-cell3">DIRECCIÓN:  <b>{{mb_strtoupper($solicitud[0]->calle)}} </div>
                <div class="table-cell3">NUM. <b>{{mb_strtoupper($solicitud[0]->numero)}}</div>
                @if($solicitud[0]->interior)
                    <div class="table-cell3">INT. <b>{{mb_strtoupper($solicitud[0]->interior)}}</div>
                @endif
                @if(!$solicitud[0]->interior)
                    <div class="table-cell3"> </div>
                @endif
                <div class="table-cell3"></div>
                <div class="table-cell3"></div>
                <div class="table-cell3"></div>
            </div>
        </div> 
    
        <div class="table" style="text-align:left; margin-top: 15px; margin-left: 20px;">
            <div class="table-row">
                <div colspan="4" class="table-cell3"> <b>DATOS DEL NOTARIO</b> </div>  
            </div>
            <div class="table-row">
                <div colspan="4" class="table-cell3"> <br> </div>  
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3"> NOMBRE: <b>{{mb_strtoupper($solicitud[0]->titular)}}</div>  
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3">NOTARIA NUMERO:<b> {{mb_strtoupper($solicitud[0]->notaria)}} </div>
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3">CREDITO PUENTE: <b>{{mb_strtoupper($solicitud[0]->credito_puente)}} </div>
            </div>
    
            
        </div>
    
        <div class="table" style="text-align:left; margin-top: 15px; margin-left: 20px;">
            <div class="table-row">
                <div colspan="4" class="table-cell3"> <b>Recibio:</b> </div>  
            </div>
            <div class="table-row">
                <div colspan="4" class="table-cell3"> <br> </div>  
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3"> Nombre: __________________________________________ </div>  
            </div>
    
            <div class="table-row">
                <div colspan="4" class="table-cell3">Fecha: ___________________ </div>
            </div> 
            <div class="table-row">
                        
                        <div colspan="5" style="text-align:center;"  class="table-cell1"><br></div>
                        
                    </div>
        </div> 
        
    
        </div>
    </div>
</body>
</html>