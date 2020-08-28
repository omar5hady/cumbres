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
.table-cell2 { display: table-cell; padding: 0em; font-size: 7.5pt; border: ridge #0B173B 1px; color:black; }
.table-row { display: table-row; }
</style>
<body>

<div class="contenedor">
    <div style="width: 720px;">
        <div style="display: inline-block; float: left;" >
        @if($resultados[0]->emp_constructora == 'CONCRETANIA' && $resultados[0]->emp_terreno == 'CONCRETANIA' )
            <IMG SRC="img/logo2C.png" width="250" height="50">
        @else 
            <IMG SRC="img/logo2.png" width="250" height="50">
        @endif
        </div>
        <div style="display: inline-block; float: right;" >
        <IMG SRC="img/recepcionCloset.png" width="200" height="50">
        </div>

        <div class="table" style="margin-top:60px; border: ridge #0B173B 1px; color:black;">
            <div class="table-row">
                <div colspan="2" class="table-cell"><b>Fraccionamiento: <u>{{$resultados[0]->proyecto}}</u></b></div>
                <div class="table-cell"><b> Fecha de revision: <u>{{$resultados[0]->fecha_revision}}</u></b></div>
            </div>
            <div class="table-row">
                <div class="table-cell"><b>Manzana: <u>{{$resultados[0]->manzana}}</u></b></div>
                <div class="table-cell"><b>Lote: <u>{{$resultados[0]->num_lote}}</u> </b></div>
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
                <div  colspan="2" class="table-cell"><b>Cliente: <u>{{$resultados[0]->nombre_cliente}}</u> </b></div>
            </div>
        </div>
       
        <div class="table2">
            <div class="table-row">
            @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div colspan="5" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>SUPERVICIÓN DE ACABADOS</b></div>
            @else
                <div colspan="4" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>SUPERVICIÓN DE ACABADOS</b></div>
            @endif
            </div>
            <div class="table-row">
                <div class="table-cell2"></div>
                <div class="table-cell2"><b> Rec. Dr:</b></div>
                <div class="table-cell2"><b> Rec. Iz:</b></div>
                <div class="table-cell2"><b> Rec. Ppal:</b></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"><b> Rec. Pbaj:</b></div>
                @endif
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Puertas:</b></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"></div>
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Alineadas:</div>
                @if($resultados[0]->p_ali_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_ali_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_ali_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->p_ali_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Limpieza:</div>
                @if($resultados[0]->p_limp_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_limp_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_limp_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->p_limp_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Uso silicón:</div>
                @if($resultados[0]->p_sil_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_sil_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_sil_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->p_sil_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
          
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Cajones:</b></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Alineados:</div>
                @if($resultados[0]->c_ali_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_ali_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_ali_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_ali_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif

            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Cantos:</div>
                @if($resultados[0]->c_cant_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_cant_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_cant_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_cant_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
            
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Uniones:</div>
                @if($resultados[0]->c_union_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_union_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_union_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_union_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Silicón/Past:</div>
                @if($resultados[0]->c_sil_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_sil_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_sil_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_sil_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Limpieza:</div>
                @if($resultados[0]->c_limp_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_limp_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_limp_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_limp_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Tornillos aju:</div>
                @if($resultados[0]->c_torn_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_torn_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_torn_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_torn_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Parches tor:</div>
                @if($resultados[0]->c_parch_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_parch_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_parch_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_parch_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            
        </div>
        <div class="table2">
            <div class="table-row">
            @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div colspan="5" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>SUPERVICIÓN DE INTERIORES</b></div>
            @else
                <div colspan="4" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>SUPERVICIÓN DE INTERIORES</b></div>
            @endif
            </div>
            <div class="table-row">
                <div class="table-cell2"></div>
                <div class="table-cell2"><b> Rec. Dr:</b></div>
                <div class="table-cell2"><b> Rec. Iz:</b></div>
                <div class="table-cell2"><b> Rec. Ppal:</b></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"><b> Rec. Pbaj:</b></div>
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Puertas:</b></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"></div>
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Tiradores:</div>
                @if($resultados[0]->p_tira_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_tira_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_tira_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->p_tira_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Funcionam:</div>
                @if($resultados[0]->p_func_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_func_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->p_func_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->p_func_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Cajones:</b></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"></div>
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Jaladeras:</div>
                @if($resultados[0]->c_jalad_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_jalad_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_jalad_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_jalad_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Rieles:</div>
                @if($resultados[0]->c_riel_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_riel_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_riel_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_riel_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Estantes:</div>
                @if($resultados[0]->c_estant_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_estant_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_estant_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_estant_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
             
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Entrepaños:</div>
                @if($resultados[0]->c_entr_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_entr_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_entr_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_entr_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Tubos colga:</div>
                @if($resultados[0]->c_tubos_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_tubos_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_tubos_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_tubos_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Daños:</div>
                @if($resultados[0]->c_danos_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_danos_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_danos_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_danos_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
             
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Abre correct:</div>
                @if($resultados[0]->c_correct_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_correct_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_correct_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_correct_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Pzas compl:</div>
                @if($resultados[0]->c_pzasc_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_pzasc_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_pzasc_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_pzasc_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Abatimiento:</div>
                @if($resultados[0]->c_abatim_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_abatim_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_abatim_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_abatim_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
            
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Visagras:</div>
                @if($resultados[0]->c_visagras_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_visagras_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->c_visagras_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->c_visagras_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
                
            </div>
            
        </div>

        <div class="table2">
            <div class="table-row">
            @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div colspan="5" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>OTRAS REVISIONES</b></div>
            @else
                <div colspan="4" class="table-cell2" style="text-align: center; background-color: black; color: white;"><b>OTRAS REVISIONES</b></div>
            @endif
            </div>
            <div class="table-row">
                <div class="table-cell2"></div>
                <div class="table-cell2"><b> Rec. Dr:</b></div>
                <div class="table-cell2"><b> Rec. Iz:</b></div>
                <div class="table-cell2"><b> Rec. Ppal:</b></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"><b> Rec. Pbaj:</b></div>
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Paredes:</b></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"></div>
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Daños:</div>
                @if($resultados[0]->pared_dan_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->pared_dan_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->pared_dan_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->pared_dan_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Limpieza:</div>
                @if($resultados[0]->pared_limp_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->pared_limp_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->pared_limp_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->pared_limp_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2"><b>+Clóset's:</b></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                <div class="table-cell2"></div>
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                <div class="table-cell2"></div>
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Cenefa sup:</div>
                @if($resultados[0]->clo_censup_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_censup_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_censup_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->clo_censup_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
             
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Cenefas inf:</div>
                @if($resultados[0]->clo_ceninf_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_ceninf_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_ceninf_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->clo_ceninf_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Color Mader:</div>
                @if($resultados[0]->clo_madera_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_madera_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_madera_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->clo_madera_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
               
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Alinec jalad:</div>
                @if($resultados[0]->clo_alin_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_alin_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_alin_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->clo_alin_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
                
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Pandeadura:</div>
                @if($resultados[0]->clo_pande_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_pande_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_pande_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->clo_pande_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
                @endif
              
            </div>
            <div class="table-row">
                <div class="table-cell2">&nbsp;-Soport ajust:</div>
                @if($resultados[0]->clo_soporte_der == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_soporte_izq == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->clo_soporte_princ == 1)
                <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                @else
                <div class="table-cell2"></div>
                @endif
                @if($resultados[0]->modelo == 'SAN FERNANDO')
                    @if($resultados[0]->clo_soporte_baja == 1)
                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">&#10004;</div>
                    @else
                    <div class="table-cell2"></div>
                    @endif
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