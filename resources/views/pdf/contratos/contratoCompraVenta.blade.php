<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato de compra-venta</title>
</head>
<style type="text/css">

body {
    font-size: 7pt;
    font-family: sans-serif;
}

.table1 { display: table; width: 84%; border-collapse: collapse; }
.table-row { display: table-row; }
.table-cell1 { display: table-cell; padding: 0.5em; font-size: 8pt; }

.table { display: table; width: 116%; border-collapse: collapse; table-layout: fixed; }
.table-cell { display: table-cell; padding: 0.5em; font-size: 7pt; }

.table-cell2 { display: table-cell; padding-left: 3em;  font-size: 7pt;}
.table-cell3 { display: table-cell; padding: 0em; font-size: 10pt; }
.table3 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
</style>
<body>
<div style="margin-right: 100px;  margin-top: -40px; position: absolute;">
<div style="position: static;"> 
<p align="center" style="border: ridge #0B173B 1px; font-size:16pt; color:white; margin-right: 100px; background-color: #0B173B;">CONTRATO DE COMPRA-VENTA</p>

    <div class="table1" style="margin-top: -12px;">
        <div class="table-row">
            <div  style="border: ridge #0B173B 1px; padding-right:-30px; color:white; font-size:10pt; background-color: #0B173B;" class="table-cell1">FECHA</div>
            <div  style="border: ridge #000000 1px; color:black; " class="table-cell1">{{strtoupper($contratos[0]->fecha)}}</div>
            <div  style="border: ridge #000000 1px; color:black; " class="table-cell1">#Ref: {{$contratos[0]->prospecto_id}} - {{$contratos[0]->avance_lote}}%</div>
        </div>
    </div>
</div> 
<div style="position: static;  margin-top: -20px;">
<p align="left" style="border: ridge #0B173B 1px; font-size:10pt; color:white; margin-right: 470px; background-color: #0B173B;">DATOS DEL CREDITO</p>
    <div class="table1" style="border: ridge #0B173B 1px; color:black;  margin-top: -10px;">
        <div class="table-row">
            <div class="table-cell1">TIPO DE CREDITO: <u>{{mb_strtoupper($contratos[0]->tipo_credito)}}</u> </div>
            <div class="table-cell1">INSTITUCION: <u>{{strtoupper($contratos[0]->institucion)}}</u> </div>
        </div>
        <div class="table-row">
            <div class="table-cell1">PLAZO: <u>{{$contratos[0]->plazo}} AÑOS</u> </div>
            <div class="table-cell1">DENOMINACION: <u>PESOS</u> </div>
        </div>
    </div>
</div>
    
    <div style="position: static;  margin-top: -20px;">
    <p align="left" style="border: ridge #0B173B 1px; font-size:10pt; color:white; margin-right: 450px; background-color: #0B173B;">DATOS DEL COMPRADOR</p>
        <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: -10px;">
                <div class="table-row">
                    <div colspan="4" class="table-cell">NOMBRE: <u>{{mb_strtoupper($contratos[0]->apellidos)}} {{mb_strtoupper($contratos[0]->nombre)}}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell">N.S.S. <u>{{$contratos[0]->nss}}</u></div>
                    <div class="table-cell">R.F.C. <u>{{mb_strtoupper($contratos[0]->rfc)}} - {{mb_strtoupper($contratos[0]->homoclave)}}</u></div>
                    <div class="table-cell">C.U.R.P. <u>{{mb_strtoupper($contratos[0]->curp)}}</u></div>
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell">LUGAR DE NACIMIENTO: <u>{{strtoupper($contratos[0]->lugar_nacimiento)}}</u></div>
                    <div class="table-cell">FECHA DE NACIMIENTO: <u>{{$contratos[0]->f_nacimiento}}</u></div>
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                @if ($contratos[0]->edo_civil === 1)
                    <div class="table-cell">ESTADO CIVIL: <u>CASADO</u></div>
                    <div class="table-cell"></div>
                    <div class="table-cell" colspan="2">REGIMEN DE MATRIMONIO: <u>SEPARACION DE BIENES</u></div>
                @elseif ($contratos[0]->edo_civil === 2)
                    <div class="table-cell">ESTADO CIVIL: <u>CASADO</u></div>
                    <div class="table-cell"></div>
                    <div class="table-cell" colspan="2">REGIMEN DE MATRIMONIO: <u>SOCIEDAD CONYUGAL</u></div>
                @elseif ($contratos[0]->edo_civil === 3)
                    <div class="table-cell">ESTADO CIVIL: <u>DIVORICIADO</u></div>
                    <div class="table-cell"></div>
                    <div class="table-cell" colspan="2"></div>
                @elseif ($contratos[0]->edo_civil === 4)
                    <div class="table-cell">ESTADO CIVIL: <u>SOLTERO</u></div>
                    <div class="table-cell"></div>
                    <div class="table-cell" colspan="2"></div>
                @elseif ($contratos[0]->edo_civil === 5)
                    <div class="table-cell">ESTADO CIVIL: <u>UNION LIBRE</u></div>
                    <div class="table-cell"></div>
                    <div class="table-cell" colspan="2"></div>
                @elseif ($contratos[0]->edo_civil === 6)
                    <div class="table-cell">ESTADO CIVIL: <u>VIUDO</u></div>
                    <div class="table-cell"></div>
                    <div class="table-cell" colspan="2"></div>
                @elseif ($contratos[0]->edo_civil === 6)
                    <div class="table-cell">ESTADO CIVIL: <u>OTRO</u></div>
                    <div class="table-cell"></div>
                    <div class="table-cell" colspan="2"></div>
                @endif 
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="2">DOMICILIO ACTUAL: <u>{{mb_strtoupper($contratos[0]->direccion)}}</u></div>
                    <div colspan="2" class="table-cell">COLONIA: <u>{{mb_strtoupper($contratos[0]->colonia)}}</u></div> 
                    
                </div>
                <div class="table-row">
                    <div class="table-cell">MUNICIPIO: <u>{{mb_strtoupper($contratos[0]->ciudad)}}</u></div>
                    <div class="table-cell">ESTADO: <u>{{mb_strtoupper($contratos[0]->estado)}}</u></div> 
                    <div class="table-cell">C.P. <u>{{$contratos[0]->cp}}</u></div> 
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                    <div class="table-cell">TELEFONO: <u>{{$contratos[0]->telefono}}</u></div>
                    <div class="table-cell">CELULAR: <u>{{$contratos[0]->celular}}</u></div> 
                    <div colspan="2" class="table-cell">EMAIL PERSONAL: <u>{{strtoupper($contratos[0]->email)}}</u></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell">EMPRESA: <u>{{mb_strtoupper($contratos[0]->empresa)}}</u></div>
                    <div colspan="2" class="table-cell">EMAIL EMPRESA: <u>{{strtoupper($contratos[0]->email_institucional)}}</u></div>
                </div>
                <div class="table-row">
                    <div colspan="4" class="table-cell">DOMICILIO DE EMPRESA: <u>{{mb_strtoupper($contratos[0]->direccion_empresa)}} {{strtoupper($contratos[0]->colonia_empresa)}}  C.P. {{$contratos[0]->cp_empresa}} {{strtoupper($contratos[0]->ciudad_empresa)}}, {{strtoupper($contratos[0]->estado_empresa)}}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="2">OCUPACION: <u>{{strtoupper($contratos[0]->puesto)}}</u></div>
                    <div class="table-cell">TEL. DEL TRABAJO: <u>{{$contratos[0]->telefono_empresa}}</u></div> 
                    <div class="table-cell">EXTENSION: <u>{{$contratos[0]->ext_empresa}}</u></div> 
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="2">NOMBRE DE REFERENCIA 1: <u>{{mb_strtoupper($contratos[0]->nombre_primera_ref)}}</u></div>
                    <div class="table-cell">TELEFONO: <u>{{$contratos[0]->telefono_primera_ref}}</u></div> 
                    <div class="table-cell">CELULAR: <u>{{$contratos[0]->celular_primera_ref}}</u></div> 
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="2">NOMBRE DE REFERENCIA 2: <u>{{mb_strtoupper($contratos[0]->nombre_segunda_ref)}}</u></div>
                    <div class="table-cell">TELEFONO: <u>{{$contratos[0]->telefono_segunda_ref}}</u></div> 
                    <div class="table-cell">CELULAR: <u>{{$contratos[0]->celular_segunda_ref}}</u></div> 
                </div>
        </div>
</div>
@if($contratos[0]->coacreditado == 1)
    <div style="position: static; margin-top: -20px;">
    <p align="left" style="border: ridge #0B173B 1px; font-size:10pt; color:white; margin-right: 350px; background-color: #0B173B;">DATOS DEL CONYUGE O COACREDITADO</p>        
        <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: -10px;">
                <div class="table-row">
                    <div colspan="2" class="table-cell">NOMBRE: <u>{{mb_strtoupper($contratos[0]->nombre_coa)}} {{mb_strtoupper($contratos[0]->apellidos_coa)}}</u></div>
                    <div class="table-cell">R.F.C. <u>{{mb_strtoupper($contratos[0]->rfc_coa)}} - {{mb_strtoupper($contratos[0]->homoclave_coa)}}</u></div>
                    <div class="table-cell">C.U.R.P. <u>{{mb_strtoupper($contratos[0]->curp_coa)}}</u></div>  
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell">LUGAR DE NACIMIENTO: <u>{{strtoupper($contratos[0]->lugar_nacimiento_coa)}}</u></div>
                    <div class="table-cell">FECHA DE NACIMIENTO: <u>{{$contratos[0]->f_nacimiento_coa}}</u></div>
                    <div class="table-cell">N.S.S. <u>{{$contratos[0]->nss_coa}}</u></div>     
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell">DOMICILIO ACTUAL: <u>{{mb_strtoupper($contratos[0]->direccion_coa)}}</u></div>
                    <div colspan="2" class="table-cell">COLONIA: <u>{{mb_strtoupper($contratos[0]->colonia_coa)}}</u></div>
                    
                </div>
                <div class="table-row">
                
                    <div class="table-cell">MUNICIPIO: <u>{{strtoupper($contratos[0]->ciudad_coa)}}</u></div>
                    <div class="table-cell">ESTADO: <u>{{strtoupper($contratos[0]->estado_coa)}}</u></div>  
                    <div class="table-cell">C.P. <u>{{$contratos[0]->cp_coa}}</u></div> 
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                    <div colspan="4" class="table-cell">EMPRESA DONDE TRABAJA: <u>{{mb_strtoupper($contratos[0]->empresa_coa)}}</u></div>
                </div>
                <div class="table-row">
                    <div colspan="4" class="table-cell">DOMICILIO DE EMPRESA: <u>{{mb_strtoupper($contratos[0]->direccion_empresa_coa)}} {{strtoupper($contratos[0]->colonia_empresa_coa)}}  C.P. {{$contratos[0]->cp_empresa_coa}} {{strtoupper($contratos[0]->ciudad_empresa_coa)}}, {{strtoupper($contratos[0]->estado_empresa_coa)}}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell">TEL.: <u>{{$contratos[0]->telefono_coa}}</u></div>
                    <div class="table-cell">CEL.: <u>{{$contratos[0]->celular_coa}}</u></div> 
                    <div class="table-cell">TEL.TRABAJO: <u>{{$contratos[0]->telefono_empresa_coa}}</u></div> 
                    <div class="table-cell"></div> 
                </div>
                <div class="table-row">
                <div colspan="4" class="table-cell">EMAIL: <u>{{mb_strtoupper($contratos[0]->email_coa)}}</u></div>
                </div>
                
             </div>
        </div>
    
@endif

<div style="position: static; margin-top: -20px;">
    <p align="left" style="border: ridge #0B173B 1px; font-size:10pt; color:white; margin-right: 450px; background-color: #0B173B;">DATOS DE LA VIVIENDA</p>        
        <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: -10px;">
                <div class="table-row">
                    <div class="table-cell">FRACCIONAMIENTO: <u>{{mb_strtoupper($contratos[0]->proyecto)}}</u></div>
                    <div class="table-cell">MANZANA: <u>{{mb_strtoupper($contratos[0]->manzana)}}</u></div>
                    <div class="table-cell">LOTE: <u>{{$contratos[0]->num_lote}}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell">CALLE: <u>{{mb_strtoupper($contratos[0]->calle)}}</u></div>
                    <div class="table-cell">NO. OFICIAL: <u>{{$contratos[0]->numero}} INT. {{strtoupper($contratos[0]->interior)}}</u></div>
                    <div class="table-cell">MODELO: <u>{{mb_strtoupper($contratos[0]->modelo)}}</u></div>
                </div>
                <div class="table-row">
                    <div class="table-cell">SUPERFICIE DE TERRENO: <u>{{$contratos[0]->terreno}} M2</u></div>
                    <div class="table-cell">SUPERFICIE DE CONSTRUCCION: <u>{{$contratos[0]->construccion}} M2</u></div>
                    <div class="table-cell"></div>
                </div> 
                <div class="table-row">
                    <div colspan="3" class="table-cell">PAQUETE: {{mb_strtoupper($contratos[0]->descripcion_paquete)}} </div>
                </div>     
                <div class="table-row">
                    <div colspan="3" class="table-cell">PROMOCION: {{mb_strtoupper($contratos[0]->descripcion_promocion)}} </div>
                </div>                
        </div>
</div>

    <div style="position: static; margin-top: -20px;">
    <p align="left" style="border: ridge #0B173B 1px; font-size:10pt; color:white; margin-right: 500px; background-color: #0B173B;">PRESUPUESTO</p>        
        <div class="table" style="border: ridge #0B173B 1px; color:black; margin-top: -10px;">
               
                <div class="table-row">
                @if($contratos[0]->infonavit > 0)
                    <div class="table-cell2">INFONAVIT: </div>
                    <div class="table-cell2"><u>${{$contratos[0]->infonavit}}</u></div>
                @elseif($contratos[0]->fovisste > 0)
                    <div class="table-cell2">FOVISSTE: </div>
                    <div class="table-cell2"><u>${{$contratos[0]->fovisste}}</u></div> 
                @else  
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                @endif  
                    <div class="table-cell2">PRECIO DE LA VIVIENDA: </div>
                    <div class="table-cell2">${{$contratos[0]->precio_base}}</div>
                </div>
                 <div class="table-row">
                    <div class="table-cell2">{{mb_strtoupper($contratos[0]->institucion)}}: </div>
                    <div class="table-cell2">${{$contratos[0]->credito_solic}}</div> 
                    <div class="table-cell2"><u>{{$contratos[0]->terreno_excedente}} M2</u> TERR. EXCEDENTE: </div>
                    <div class="table-cell2">${{$contratos[0]->precio_terreno_excedente}}</div>
                </div>
                <div class="table-row">
                    <div class="table-cell2">COMISION X APERTURA: </div>
                    <div class="table-cell2">${{$contratos[0]->comision_apertura}}</div>
                    <div class="table-cell2">OBRA EXTRA: </div>
                    <div class="table-cell2">${{$contratos[0]->precio_obra_extra}}</div>
                </div>
                <div class="table-row">
                    <div class="table-cell2">INVESTIGACION: </div>
                    <div class="table-cell2">${{$contratos[0]->investigacion}}</div>
                    <div class="table-cell2">SOBREPRECIO: </div>
                    <div class="table-cell2">${{$contratos[0]->sobreprecio}}</div>
                    
                </div>
                <div class="table-row">
                   <div class="table-cell2">AVALUO: </div>
                    <div class="table-cell2">${{$contratos[0]->avaluo}}</div>
                    <div class="table-cell2">PAQUETE: </div>
                    <div class="table-cell2">${{$contratos[0]->costo_paquete}}</div>
                </div>
                
                <!--<div class="table-row">
                    <div class="table-cell2">PRIMA UNICA: </div>
                    <div class="table-cell2">${{$contratos[0]->prima_unica}}</div>
                    <div class="table-cell2">PROMOCION: </div>
                    <div class="table-cell2">${{$contratos[0]->descuento_promocion}}</div>
                </div>-->
                <div class="table-row">
                    <div class="table-cell2">GASTOS DE ESCRITURACION: </div>
                    <div class="table-cell2">${{$contratos[0]->escrituras}}</div>
                    <div class="table-cell2">VALOR TOTAL CASA: </div>
                    <div class="table-cell2"><u>${{$contratos[0]->precio_venta}}</u></div>            

                </div>
                <div class="table-row">
                    <div class="table-cell2">CREDITO NETO {{strtoupper($contratos[0]->institucion)}}: </div>
                    <div class="table-cell2"><u>${{$contratos[0]->credito_neto}}</u></div>
                    <div class="table-cell2">MONTO NETO CREDITO: </div>
                    <div class="table-cell2"><u>${{$contratos[0]->monto_total_credito}}</u></div>

                </div>           
                <div class="table-row">
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2">TOTAL A PAGAR: </div>
                    <div class="table-cell2"><u>${{$contratos[0]->total_pagar}}</u></div>

                </div>   
                <div class="table-row">
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2">AVALUO: </div>
                    <div class="table-cell2">${{$contratos[0]->avaluo_cliente}}</div>
                </div>  
                <div class="table-row">
                    <div class="table-cell2"></div>
                    <div class="table-cell2"></div>
                    <div class="table-cell2"><b> ENGANCHE TOTAL: </div>
                    <div class="table-cell2"><b><u>${{$contratos[0]->enganche_total}}</u></div>
                </div>           
             </div>
        </div>

<div style="position: static; margin-top: -2px;">          
        <div class="table" style="color:black; margin-top: -1px;">
                <div class="table-row">
                @for($i=0; $i < count($pagos); $i++)
                    <div class="table-cell">{{$pagos[$i]->fecha_pago}} PAGO NO.{{$pagos[$i]->num_pago + 1}}: <u>${{$pagos[$i]->monto_pago}}</u></div>
                @endfor
                    
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell">ASESOR DE VENTAS: <u>{{mb_strtoupper($contratos[0]->vendedor_nombre)}} {{mb_strtoupper($contratos[0]->vendedor_apellidos)}}</u></div>
                    <div class="table-cell">FIRMA: ___________________</div>
                    <div colspan="2" class="table-cell">FIRMA DEL COMPRADOR: ___________________</div>
                </div>
                <div class="table-row">
                    <div colspan="5" class="table-cell">NOTA: ESTE DOCUMENTO PIERDE SU EFECTO SI EN EL LAPSO DE 5 DIAS NATURALES NO ES LIQUIDADO EL PAGO #1.</div>
                </div>    
                <div class="table-row">
                @if ($contratos[0]->medio_publicidad === 'Recomendado')
                    <div colspan="5" class="table-cell">MEDIO POR EL CUAL SE ENTERO: RECOMENDADO. (NOMBRE: {{mb_strtoupper($contratos[0]->nombre_recomendado)}})</div>
                @else
                    <div colspan="5" class="table-cell">MEDIO POR EL CUAL SE ENTERO: {{mb_strtoupper($contratos[0]->medio_publicidad)}}</div>
                @endif
                </div>   
                <div class="table-row">
                    <div colspan="5" class="table-cell">OBSERVACIONES: {{mb_strtoupper($contratos[0]->observacion)}}</div>
                </div>          
                <!--<div class="table-row">
                    <div colspan="5" class="table-cell"></div>
                </div>  -->    
                <div class="table-row">
                <div colspan="5" class="table-cell" style="text-align:center;"><hr style="border-color:gray;">  www.casascumbres.mx</div>
                </div>    
        </div>
</div>
        
    </div>
</div>


<div style="display: inline-block; float: right;" >
    <IMG SRC="img/contratos/logoContrato1.png" width="180" height="180">
</div>

<div style="page-break-after:always;"></div>

<div style="margin: 60px; font-size: 14pt; text-align: justify;">
<u><h4>CUENTA PARA REALIZAR LA TRANSFERENCIA DE LOS PAGOS CORRESPONDIENTES A ENGANCHE.</h4></u>

<br>

<div class="table3">
<div class="table-row">
      <div colspan="2" class="table-cell3"><b>TRANSFERENCIA A NOMBRE DE: </div>
      <div colspan="2" class="table-cell3"><b>GRUPO CONSTRUCTOR CUMBRES S.A DE C.V</div>
    </div>
    <div class="table-row">
      <div class="table-cell3"><b>BANCO:</div>
      <div class="table-cell3"></div>
      <div class="table-cell3"><b>BANCOMER</div>
      <div class="table-cell3"></div>
    </div>
    <div class="table-row"> 
      <div class="table-cell3"><b>NO. DE CUENTA:</div>
      <div class="table-cell3"></div>
      <div class="table-cell3"><b>0107059795</div>
      <div class="table-cell3"></div>
    </div>
    <div class="table-row">
      <div colspan="2" class="table-cell3"><b>CLABE PARA TRANSFERENCIA:</div>
      <div colspan="2" class="table-cell3"><b>012700001070597953</div>
    </div>
    <div class="table-row">
      <div class="table-cell3"><b>RFC:</div>
      <div class="table-cell3"></div>
      <div class="table-cell3"><b>GCC000106QS6</div>
      <div class="table-cell3"></div>
    </div>
  </div>

  <br>
  <br>
 
 <h5>EN ASUNTO DE TRANSFERENCIA FAVOR DE ESCRIBIR SU NOMBRE COMPLETO.</h5>

 <br>

 <u><H4>INDICACIONES: </H4></u>

 <ul> 
  <li align="justify">TODAS LAS TRANSFERENCIAS DEBERAN PROVENIR DE LA CUENTA DE LA PERSONA QUE ESTA ADQUIRIENDO LA CASA.</li>
  <li align="justify">EL TOTAL DE LOS DEPOSITOS EN EFECTIVO DEBERAN SER INFERIORES A $100,000.00.</li>
  <li align="justify">EN CASO DE REALIZAR TU PAGO ELECTRONICO EN UNA SUCURSAL BANCARIA ENTREGA TU COMPROBANTE EN NUESTRAS OFICINAS
      CENTRALES O VIA CORREO ELECTRONICO (escaneado o foto digital del comprobante), AL EMAIL: <u>cobranza@grupocumbres.com</u> 
      <strong>ES REQUISITO INDISPENSABLE SE ANEXE EL NUMERO DE CUENTA Y BANCO DE LA CUENTA DE LA QUE PROVIENEN LOS RECURSOS EN CADA 
      MOVIMIENTO.</strong>
  </li>
</ul>
</div>

</body>
</html>