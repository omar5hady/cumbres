<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estado de cuenta</title>
</head>
<style type="text/css">

.table1 { display: table; width: 60%; border-collapse: collapse; table-layout: fixed; }
.table { display: table; width: 105%; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row;  }
.table-cell { display: table-cell; padding: 0.05em; font-size: 9pt;}

</style>
<body>
    
<div style="display: inline-block; float: right;" >
    <IMG SRC="img/contratos/logoContrato.jpg" width="180" height="180">
</div>

<p style="text-align: center;">GRUPO CONSTRUCTOR CUMBRES, S.A DE C.V.</p>
<p style="text-align: center;"> <strong> ESTADO DE CUENTA </strong></p>


    <div style="position: static; margin: 30px;">
        <div class="table1" style="margin-top: 0.3em;">
                <div class="table-row">
                    <div class="table-cell">Referencia:</div>    
                    <div class="table-cell">{{$contratos[0]->folio}}</div>
                    <div class="table-cell"></div>       
                </div>

                <div class="table-row">
                    <div class="table-cell">Proyecto: </div>
                    <div colspan="2" class="table-cell">{{mb_strtoupper($contratos[0]->fraccionamiento)}} </div>
                </div>

                <div class="table-row">
                    <div class="table-cell">Etapa: </div>
                    <div colspan="2" class="table-cell">{{mb_strtoupper($contratos[0]->etapa)}} </div>
                </div>

                <div class="table-row">
                    <div class="table-cell">Manzana/Nivel: </div>
                    <div class="table-cell">{{mb_strtoupper($contratos[0]->manzana)}}</div>
                    <div class="table-cell"></div>   
                </div>

                <div class="table-row">
                    <div class="table-cell">Lote/Depto: </div>
                    <div class="table-cell">{{$contratos[0]->num_lote}}</div>
                    <div class="table-cell"></div>   
                </div>

                <div class="table-row">
                    <div class="table-cell">Cliente: </div>
                    <div colspan="2" class="table-cell">{{mb_strtoupper($contratos[0]->nombre_cliente)}}</div>
                </div>

                <div class="table-row">
                    <div class="table-cell">RFC: </div>
                    <div class="table-cell">{{mb_strtoupper($contratos[0]->rfc)}}-{{mb_strtoupper($contratos[0]->homoclave)}}</div>
                    <div class="table-cell"></div>   
                </div>
               
        </div>
    </div> 

    <div style="position: static; margin: 30px;">
        <div class="table" style="margin-top: 0.3em;">
                <div class="table-row">
                    <div colspan="2" class="table-cell"></div>    
                    <div class="table-cell"><b>Fecha</div>
                    <div class="table-cell"><b>Recibo</div>  
                    <div class="table-cell"><b>Cuenta</div> 
                    <div class="table-cell"></div>  
                    <div class="table-cell"><b>Cargo</div> 
                    <div class="table-cell"><b>Abono</div>   
                </div>

                <div class="table-row">
                    <div class="table-cell"> <b> VENTA</div>
                    <div colspan="7" class="table-cell"></div>       
                </div>

                <div class="table-row">
                    <div colspan="2" class="table-cell">{{mb_strtoupper($contratos[0]->fraccionamiento)}} Mza: {{mb_strtoupper($contratos[0]->manzana)}} Lote: {{$contratos[0]->num_lote}}</div>
                    <div class="table-cell">{{$contratos[0]->fecha}}</div>
                    <div colspan="3" class="table-cell"></div>  
                    <div class="table-cell">${{$contratos[0]->enganche_total}}</div> 
                    <div class="table-cell">$ 0.00</div>   
                </div>

                
                <div class="table-row">
                    <div colspan="4" class="table-cell"></div>
                    <div colspan="2" class="table-cell"><b>TOTAL(VENTA)</div>
                    <div class="table-cell"><b>${{$contratos[0]->enganche_total}}</div>  
                    <div class="table-cell"></div>    
                </div>

                  
                <div class="table-row">
                    <div colspan="8" class="table-cell"></div>    
                </div>

                  
                <div class="table-row">
                    <div colspan="8" class="table-cell"><b>ENGANCHE</div>    
                </div>

              @for($i=0; $i < count($depositos); $i++)
                <div class="table-row">
                    <div class="table-cell">Pago</div>
                    <div class="table-cell"></div>
                    <div class="table-cell">{{$depositos[$i]->fecha_pago}}</div>
                    <div class="table-cell">{{$depositos[$i]->num_recibo}}</div>    
                    <div colspan="2" class="table-cell">{{$depositos[$i]->banco}}</div>
                    <div class="table-cell">$ 0.00</div>  
                    <div class="table-cell">$ {{$depositos[$i]->cant_depo}}</div>
                </div>
              @endfor

                <div class="table-row">
                    <div colspan="4" class="table-cell"></div>   
                    <div colspan="2" class="table-cell"><b>TOTAL(ENGANCHE)</div>
                    <div class="table-cell"></div>  
                    <div class="table-cell"> <b> $ {{$contratos[0]->sumDeposito}}</div>  
                </div>

                <div class="table-row">
                    <div colspan="8" class="table-cell"><b>CARGOS</div>    
                </div>

            @for($i=0; $i < count($gastos_admin); $i++)
                <div class="table-row">
                    <div class="table-cell">{{mb_strtoupper($gastos_admin[$i]->concepto)}}</div>
                    <div class="table-cell"></div>
                    <div class="table-cell">{{$gastos_admin[$i]->fecha}}</div>
                    <div class="table-cell">---</div>    
                    <div colspan="2" class="table-cell">OFICINA - OFICINA</div>
                    <div class="table-cell">$ {{$gastos_admin[$i]->costo}}</div>  
                    <div class="table-cell">$ 0.00</div>  
                </div>
            @endfor

                <div class="table-row">
                    <div colspan="4" class="table-cell"></div>   
                    <div colspan="2" class="table-cell"><b>TOTAL(CARGOS)</div>
                    <div class="table-cell"><b>$ {{$contratos[0]->gastos}}</div>  
                    <div class="table-cell"></div>  
                </div>

                <div class="table-row">
                    <div colspan="8" class="table-cell"><b>CREDITO</div>    
                </div>
            
            @for($i=0; $i < count($depositos_credito); $i++)
                <div class="table-row">
                    <div class="table-cell">{{mb_strtoupper($depositos_credito[$i]->institucion)}}</div>
                    <div class="table-cell"></div>
                    <div class="table-cell">{{$depositos_credito[$i]->fecha_deposito}}</div>
                    <div class="table-cell"></div>    
                    <div colspan="2" class="table-cell">{{$depositos_credito[$i]->banco}}</div>
                    <div class="table-cell">$ 0.00</div>  
                    <div class="table-cell">$ {{$depositos_credito[$i]->cant_depo}}</div>
                </div>
            @endfor

                <div class="table-row">
                    <div colspan="4" class="table-cell"></div>   
                    <div colspan="2" class="table-cell"><b>TOTAL(CREDITO)</div>
                    <div class="table-cell"></div>  
                    <div class="table-cell"><b>$ {{$contratos[0]->sumDepositoCredito}}</div>  
                </div>

                <div class="table-row">
                    <div colspan="8" class="table-cell"></div>    
                </div>

                <div class="table-row">
                    <div colspan="8" class="table-cell"></div>    
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell"></div>   
                    <div colspan="2" class="table-cell"><b>TOTAL</div>
                    <div class="table-cell"><b>$ {{$contratos[0]->totalCargo}}</div>  
                    <div class="table-cell"><b>$ {{$contratos[0]->totalAbono}}</div>
                </div>

                <div class="table-row">
                    <div colspan="4" class="table-cell"></div>   
                    <div colspan="2" class="table-cell"><b>SALDO ACTUAL</div>
                    <div class="table-cell"><b>$ {{$contratos[0]->saldo}}</div>  
                    <div class="table-cell"></div>
                </div>

               
               
        </div>
    </div> 
</body>
</html>