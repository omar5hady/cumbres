<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<style type="text/css">

body {
    font-size: 7pt;
    font-family: sans-serif;
}

.table { display: table; width: 100%; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row;  }
.table-cell { display: table-cell; padding: 0.5em; font-size: 7pt; border: ridge #0B173B 1px; color:black; }
.table-cell1 { display: table-cell; padding: 0em; font-size: 8pt; }

.table2 { display: table; width: 45%; }

.table3 { display: table; width: 45%; border-collapse: collapse; table-layout: fixed; }



</style>

<div style="clear:both;">
    <div style="float: left; margin-top: 10px; margin-left: 20px;" >
         <IMG SRC="img/contratos/logoContrato.jpg" width="110" height="110">
    </div>
    <p style="text-align:right;">San Luis Potosí, San Luis Potosí, a {{$liquidacion[0]->fecha_liquidacion}}</p>
    <div style="position: static; margin: 30px;">
        <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: 8em;">
                <div class="table-row">
                    <div class="table-cell">NOMBRE DEL SOLICITANTE: </div>
                    <div colspan="2" class="table-cell">{{mb_strtoupper($liquidacion[0]->nombre_cliente)}}</div>
                    <div class="table-cell">RFC: {{mb_strtoupper($liquidacion[0]->rfc_cliente)}} - {{mb_strtoupper($liquidacion[0]->homoclave_cliente)}}</div>        
                </div>
            @if($liquidacion[0]->coacreditado == 1)
                <div class="table-row">
                    <div class="table-cell">COACREDITADO: </div>
                    <div colspan="2" class="table-cell"> {{mb_strtoupper($liquidacion[0]->nombre_conyuge)}}</div>
                    <div class="table-cell">RFC: {{mb_strtoupper($liquidacion[0]->rfc_conyuge)}} - {{mb_strtoupper($liquidacion[0]->homoclave_conyuge)}}</div>
                </div>
            @endif
        </div>
    </div>  

    <div style="position: static; margin: 30px;">
        <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: 0.3em;">
                <div class="table-row">
                    <div colspan="4" class="table-cell">Valor de Escrituración: ${{$liquidacion[0]->valor_escrituras}}</div>       
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell">Proyecto: {{mb_strtoupper($liquidacion[0]->proyecto)}}</div>
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell">Modelo: {{mb_strtoupper($liquidacion[0]->modelo)}} </div>
                </div>

                <div class="table-row">
                    <div colspan="2" class="table-cell">Manzana: {{mb_strtoupper($liquidacion[0]->manzana)}} </div>
                    <div colspan="2" class="table-cell">Lote: {{$liquidacion[0]->num_lote}} </div>
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell">Dirección: {{mb_strtoupper($liquidacion[0]->calle)}} Num. {{$liquidacion[0]->numero}} </div>
                </div>
        </div>
    </div> 

    <!-- tabla de la suma de los montos -->
    <div style="position: static; margin: 30px; ">
        <div class="table2" style="border: ridge #0B173B 1px; color:black; margin-top: 0.3em; float:left;">
                <div class="table-row">
                    <div colspan="2" class="table-cell">PRECIO DE VENTA </div> 
                    <div class="table-cell">${{$liquidacion[0]->precio_venta}}</div>
                    <div class="table-cell">M.N.</div>      
                </div>

                <div class="table-row">
                    <div colspan="2" class="table-cell">INTERESES ORDINARIOS </div> 
                    <div class="table-cell">${{$liquidacion[0]->interes_ord}}</div>
                    <div class="table-cell">M.N.</div>      
                </div>

                <div class="table-row">
                    <div style="text-align: center;" colspan="4" class="table-cell">CREDITO NETO </div>   
                </div>

                <div class="table-row">
                    <div colspan="2" class="table-cell">{{mb_strtoupper($liquidacion[0]->institucion)}}</div> 
                    <div class="table-cell">${{$liquidacion[0]->credito_solic}}</div>
                    <div class="table-cell">M.N.</div>      
                </div>

                <div class="table-row">
                    <div colspan="2" class="table-cell">DEPOSITOS DE ENGANCHE</div> 
                    <div class="table-cell">${{$liquidacion[0]->sumaDepositos}}</div>
                    <div class="table-cell">M.N.</div>      
                </div>

            @if($liquidacion[0]->fovissste > 0)
                <div class="table-row">
                    <div colspan="2" class="table-cell">FOVISSSTE</div> 
                    <div class="table-cell">${{$liquidacion[0]->fovissste}}</div>
                    <div class="table-cell">M.N.</div>      
                </div>
            @endif

            @if($liquidacion[0]->infonavit > 0)
                <div class="table-row">
                    <div colspan="2" class="table-cell">INFONAVIT</div> 
                    <div class="table-cell">${{$liquidacion[0]->infonavit}}</div>
                    <div class="table-cell">M.N.</div>      
                </div>
            @endif

            @if($gastos)
                <div class="table-row">
                    <div style="text-align: center;" colspan="4" class="table-cell">GASTOS ADMINISTRATIVOS</div>   
                </div>
            @endif
               
            @for($i=0; $i < count($gastos); $i++)
                <div class="table-row">
                    <div colspan="2" class="table-cell">{{mb_strtoupper($gastos[$i]->concepto)}}</div> 
                    <div class="table-cell">${{$gastos[$i]->costo}}</div>
                    <div class="table-cell">M.N.</div>      
                </div> 
            @endfor

                <div class="table-row">
                    <div colspan="2" class="table-cell">DESCUENTO </div> 
                    <div class="table-cell">${{$liquidacion[0]->descuento}}</div>
                    <div class="table-cell">M.N.</div>      
                </div>

                <div class="table-row">
                    <div colspan="2" class="table-cell">TOTAL A LIQUIDAR </div> 
                    <div class="table-cell">$ {{$liquidacion[0]->totalRestante}}</div>
                    <div class="table-cell">M.N.</div>      
                </div>
        </div>
    @if($pagares)
        <div class="table2" style="border: ridge #0B173B 1px; color:black; margin-top: 0.3em; float:right;">
                    
            <div class="table-row">
                <div colspan="4" class="table-cell">PAGARES PENDIENTES</div>   
            </div>

        @for($i=0; $i < count($pagares); $i++)
            <div class="table-row">
                <div colspan="2" class="table-cell">{{$pagares[$i]->fecha_pago}}</div> 
                <div colspan="2" class="table-cell">${{$pagares[$i]->restante1}} </div>    
            </div>
        @endfor
    @endif

        </div>
    </div> 

    <div style="position: static; margin: 30px; clear:both;">
        <div class="table3" style="border: ridge #0B173B 1px; color:black; margin-top: 1.5em;">
                <div class="table-row">
                    <div class="table-cell">GESTOR</div> 
                    <div colspan="3" class="table-cell">{{mb_strtoupper($liquidacion[0]->nombre_gestor)}}</div>
                          
                </div>

                <div class="table-row">
                    <div colspan="2" class="table-cell">NOTARIA </div> 
                    <div colspan="2" class="table-cell">{{mb_strtoupper($liquidacion[0]->notaria)}}</div>   
                </div>

                <div class="table-row">
                    <div colspan="2" class="table-cell">FIRMA DE ESCRITURA </div> 
                    <div colspan="2" class="table-cell">{{$liquidacion[0]->fecha_firma_esc}}</div>   
                </div>
       
        </div>

    </div> 

    <div style="position: static; margin: 30px;">
        <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: 0.3em;">
                <div class="table-row">
                    <div colspan="4" class="table-cell">Observaciones: LA EMPRESA NO ENTREGA LA CASA AMENOS QUE HAYA SIDO LIQUIDADO EL SALDO DE DIFERENCIA RESTANTE</div>        
                </div>
        </div>
    </div> 

    <p style="text-align:center; margin-top: 0.3em;"><strong>Nombre y firma de conformidad del Solicitante</strong></p>

</div>
@if($pagares)
@for($u=0; $u < count($pagares); $u++)
        <div style="clear:both;">

        <div style="float: left; margin-top: 160px; margin-left: 100px;" >
            <IMG SRC="img/contratos/logoContrato.jpg" width="110" height="110">
        </div>

        <div style="text-align: justify; margin:80px">
            <div style="text-align: right; margin-bottom: 0em;  margin-top: 0em;">
                <p style="margin-bottom: 0em;  margin-top: 0em;"> <strong> PAGARE NO. </strong> {{$pagares[$u]->num_pago + 1}}/{{count($pagares)}}</p>
                <p style="margin-bottom: 0em;  margin-top: 0em;"> <strong>BUENO POR </strong>${{$pagares[$u]->restante1}}</p>
                <p style="margin-bottom: 0em;  margin-top: 0em;">EN SAN LUIS POTOSI, SAN LUIS POTOSI, A {{strtoupper($liquidacion[0]->fecha_liquidacion)}}</p>
            </div>
            <br>
            <br>
            <br>
            <br>
            
                <div>
                <p style="margin-bottom: 0em;  margin-top: 0em;">DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE POR ESTE PAGARE A LA ORDEN DE GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V., EN SAN LUIS POTOSI, SAN LUIS POTOSI, <strong> EL {{strtoupper($pagares[$u]->fecha_pago_letra)}}</strong></p>
                <p style="margin-bottom: 0em;  margin-top: 0em;">LA CANTIDAD DE: </p>
                <p style="margin-bottom: 0em;  margin-top: 0em;"><strong>${{strtoupper($pagares[$u]->montoPagoLetra)}}</strong></p>
                <p style="margin-bottom: 0em;  margin-top: 0em;">VALOR RECIBIDO A MI (NUESTRA) ENTERA SATISFACCION. ESTE PAGARE FORMA PARTE DE UNA SERIE NUMERADA DEL <strong>1</strong> AL <strong>{{count($pagares)}}</strong> Y TODOS ESTAN SUJETOS A LA CONDICION DE QUE, AL NO PAGARSE CUALQUIERA
                    DE ELLOS A SU VENCIMIENTO, SERAN EXIGIBLES TODOS LOS QUE LE SIGAN EN NUMERO, ADEMAS DE LOS YA VENCIDOS, DESDE LA FECHA DE VENCIMIENTO DE ESTE DOCUMENTO HASTA EL DIA DE SU LIQUIDACION,
                    CAUSARA INTERESES MORATORIOS AL TIPO DE <strong>5%</strong> MENSUAL, PAGADERO EN ESTA CIUDAD JUNTAMENTE CON EL PRINCIPAL.</p>

                </div>
                <br>
                <br>
        <div class="table" style="text-align:left;">
            <div class="table-row">
                <div style="text-align:left;" colspan="2" class="table-cell1"> <b>CLIENTE</div>
                <div style="text-align:right;" colspan="2" class="table-cell1"> <b>AVAL</div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1">NOMBRE: {{mb_strtoupper($liquidacion[0]->nombre_cliente)}}</div>
                <div colspan="2" style="text-align:right;" class="table-cell1">NOMBRE: {{mb_strtoupper($liquidacion[0]->nombre_aval)}}</div>
            </div>

            <div class="table-row">
                <div colspan="3" class="table-cell1">DIRECCION: {{mb_strtoupper($liquidacion[0]->direccion)}} {{mb_strtoupper($liquidacion[0]->colonia)}}</div>
                <div style="text-align:right;" class="table-cell1">TEL: {{mb_strtoupper($liquidacion[0]->telefono_aval)}}</div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1">CP: {{$liquidacion[0]->cp}} </div>
               @if($liquidacion[0]->nombre_aval2 != '') 
               <div colspan="2" style="text-align:right;" class="table-cell1">NOMBRE: {{mb_strtoupper($liquidacion[0]->nombre_aval2)}}</div>
               @else
               <div colspan="2" class="table-cell1"></div>
               @endif
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell1">TEL: {{$liquidacion[0]->telefono}}</div>
                @if($liquidacion[0]->telefono_aval2 != '')
                <div colspan="2" style="text-align:right;" class="table-cell1">TEL: {{mb_strtoupper($liquidacion[0]->telefono_aval2)}}</div>
                @else
                <div colspan="2" class="table-cell1"></div>
                @endif
            </div>
         
            <div class="table-row">
                <div colspan="4" class="table-cell1">{{mb_strtoupper($liquidacion[0]->ciudad)}}, {{mb_strtoupper($liquidacion[0]->estado)}} </div> 
            </div>
            <div class="table-row">
                <div colspan="4" class="table-cell1"> <BR></div>
             
            </div>
            <div class="table-row">
               
                <div colspan="2" style="text-align:left;" class="table-cell1">FIRMA CLIENTE: ______________________</div>
                <div style="text-align:right;" class="table-cell1">FIRMA AVAL: </div>
                <div class="table-cell1">_______________________ </div> 
            </div>
        </div>   

        </div>

        </div>
@endfor
@endif
</body>
</html>