<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style type="text/css">
.contenedor {
clear:both;
float: left;
width: 1000px;
}

body {
    font-family: sans-serif;
}

.table { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-cell { display: table-cell; padding: 0.4em; font-size: 8.5pt; }
.table-row { display: table-row; }

.table2 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; border: ridge #0B173B 1px; color:black; }
.table-cell2 { display: table-cell; padding: 0.1em; font-size: 7.5pt; border: ridge #0B173B 1px; color:black; }
.table-row { display: table-row; }
</style>
<body>

<div class="contenedor">
    <div style="width: 720px;">
        <div style="display: inline-block; float: left;" >
        <IMG SRC="img/logo2.png" width="250" height="50">
        </div>
        <div style="display: inline-block; float: right;" >
        <IMG SRC="img/recepcionCocina.png" width="200" height="50">
        </div>

        <div class="table" style="margin-top:60px; border: ridge #0B173B 1px; color:black;">
            <div class="table-row">
                <div colspan="2" class="table-cell"><b>Fraccionamiento: <u>{{$resultados[0]->proyecto}}</u></b></div>
                <div class="table-cell"><b> Fecha de revision: <u>{{$resultados[0]->fecha_revision}}</u></b></div>
            </div>
            <div class="table-row">
                <div class="table-cell"><b>Manzana: <u>{{$resultados[0]->manzana}}</u></b></div>
                <div class="table-cell"><b>Lote: <u>{{$resultados[0]->num_lote}}</u></b></div>
                <div class="table-cell"><b>Prototipo: <u>{{$resultados[0]->modelo}}</u></b></div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell"><b>Proveedor: <u>{{$resultados[0]->proveedor}}</u></b></div>
                <div class="table-cell"><b>Folio Factura: __________</b></div>
            </div>
            <div class="table-row">
            @if($resultados[0]->casa_muestra == 1)
                <div class="table-cell"><b>Casa Muestra: <u>Si</u></b></div>
            @else
                <div class="table-cell"><b>Casa Muestra: <u>No</u></b></div>
            @endif
                <div  colspan="2" class="table-cell"><b>Cliente: <u>{{$resultados[0]->nombre_cliente}}</u></b></div>
            </div>
        </div>
       
        <div class="table2">
            <div class="table-row">
                <div colspan="5" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>SUPERVICIÓN DE ACABADOS</b></div>
            </div>
    
            <div class="table-row">
                <div class="table-cell2"><b>+Cubierta:</b></div>
                <div colspan="4" class="table-cell2"></div>
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Uniones:</div>
                @if($resultados[0]->cubierta_acab_uniones == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Uso silicón:</div>
                @if($resultados[0]->cubierta_acab_silicon == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Cortes:</div>
                @if($resultados[0]->cubierta_acab_cortes == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
          
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Puertas:</b></div>
                <div colspan="4" class="table-cell2"></div>
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Alineadas:</div>
                @if($resultados[0]->puerta_acab_alineados == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif

            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Cantos:</div>
                @if($resultados[0]->puerta_acab_cantos == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
            
            </div>
            
            
        </div>
        <div class="table2">
            <div class="table-row">
                <div colspan="5" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>REVISIÓN DE PUERTAS, ALACENAS Y CAJONES</b></div>
            </div>
        
            <div class="table-row">
                <div class="table-cell2"><b>+Puertas:</b></div>
                <div colspan="4" class="table-cell2"></div>
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Daños:</div>
                @if($resultados[0]->puerta_danos == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Tornillos aju:</div>
                @if($resultados[0]->puerta_tornillos == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Abatimiento:</div>
                @if($resultados[0]->puerta_abatimiento == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Limpieza:</div>
                @if($resultados[0]->puerta_limpieza == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Jaladeras:</div>
                @if($resultados[0]->puerta_jaladera == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Gomas cierr:</div>
                @if($resultados[0]->puerta_gomas == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Cajones:</b></div>
                <div colspan="4" class="table-cell2"></div>
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Uniones:</div>
                @if($resultados[0]->cajones_uniones == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Silicón/Past:</div>
                @if($resultados[0]->cajones_silicon == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Limpieza:</div>
                @if($resultados[0]->cajones_limpieza == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
             
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Jaladeras:</div>
                @if($resultados[0]->cajones_jaladeras == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Cantos:</div>
                @if($resultados[0]->cajones_cantos == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Rieles:</div>
                @if($resultados[0]->cajones_rieles == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
             
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Estantes:</div>
                @if($resultados[0]->cajones_estantes == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Pzas compl:</div>
                @if($resultados[0]->cajones_pzas_comp == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Alacenas:</b></div>
                <div colspan="4" class="table-cell2"></div>
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Entrepaños:</div>
                @if($resultados[0]->alacena_entrepano == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Pistones:</div>
                @if($resultados[0]->alacena_pistones == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Jaladeras:</div>
                @if($resultados[0]->alacena_jaladeras == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Hoyo micro:</div>
                @if($resultados[0]->alacena_micro == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Cantos:</div>
                @if($resultados[0]->alacena_cantos == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Limpieza:</div>
                @if($resultados[0]->alacena_limpieza == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Parches tor:</div>
                @if($resultados[0]->alacena_parches == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
        
            
        </div>

        <div class="table2">
            <div class="table-row">
                <div colspan="5" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>OTRAS REVISIONES</b></div>
            </div>
       
            <div class="table-row">
                <div class="table-cell2"><b>+Estufa:</b></div>
                <div colspan="4" class="table-cell2"></div>
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Instalacion:</div>
                @if($resultados[0]->estufa_instalacion == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Pzas extra:</div>
                @if($resultados[0]->estufa_pzas_extra == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Manuales:</div>
                @if($resultados[0]->estufa_manuales == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Daños:</div>
                @if($resultados[0]->estufa_danos == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Tarja:</b></div>
                <div colspan="4" class="table-cell2"></div>

            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Daños:</div>
                @if($resultados[0]->tarja_danos == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
             
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Pzas extra:</div>
                @if($resultados[0]->tarja_pzas_extra == 1)
                <div colspan="4" class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div colspan="4" class="table-cell2"></div>
                @endif
               
            </div>
           
            
        </div>
       <p><b>Observaciones: </b>{{$resultados[0]->observacion}}</p>

       <div class="table" style="border: ridge #0B173B 1px; color:black;">
            <div class="table-row">
                <div colspan="2" class="table-cell"><b>Supervisor: {{$resultados[0]->supervisor}}</b></div>
                <div class="table-cell"><b>Firma:</b></div>
            </div>
        </div>

    </div>
</div>
    
</body>
</html>