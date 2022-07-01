<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Estimaciones &nbsp;
                        <button v-if="listado==0" type="button" @click="indexEstimaciones(1),listado=1" class="btn btn-success">
                            <i class="fa fa-mail-reply"></i> Regresar
                        </button>
                    </div>

            <!----------------- Listado Contratos ------------------------------>
                    <!-- Div Card Body para listar -->
                    <template v-if="listado == 1">
                       <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control" v-model="b_proyecto">
                                            <option value="">Fraccionamiento</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input  type="text" v-model="buscar" @keyup.enter="indexEstimaciones(1)" class="form-control" placeholder="Texto a buscar">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <button type="submit" @click="indexEstimaciones(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Clave</th>
                                            <th>Contratista</th>
                                            <th>Fraccionamiento</th>
                                            <th>Importe del contrato</th>
                                            <th>Fondo de Garantía a Retener</th>
                                            <!-- <th>Total por Pagar</th> -->
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayEstimaciones" :key="contrato.id">
                                            <td class="td2">
                                                    <button type="button" title="Ver estimaciones" 
                                                        @click="verDetalle(contrato)" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </button>

                                                    <a :href="'/estimaciones/excelEdoCuenta?clave='+contrato.id"  
                                                        class="btn btn-success"><i class="fa fa-file-text" title="Descargar estado de cuenta"></i> Edo. Cuenta
                                                    </a>
                                                </td>
                                            <td class="td2" v-text="contrato.clave"></td>
                                            <td class="td2" v-text="contrato.contratista"></td>
                                            <td class="td2" v-text="contrato.proyecto"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.total_importe)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.garantia_ret)"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page2 in pagesNumber" :key="page2" :class="[page2 == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page2)" v-text="page2"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                    <!--  VISTA DETALLE  -->
                    <template v-if="listado == 0">
                       <div class="card-body"> 
                            <!--Encabezado-->
                            <template>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <center> <h5 style="color: #153157;">Contrato: {{encabezado.clave}}</h5> </center>
                                    </div>
                                </div> 

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <center> <h6 style="color: #153157;">Contratista: {{encabezado.contratista}}</h6> </center>
                                    </div>
                                </div> 

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Importe del Contrato</label>
                                        <div class="col-md-4">
                                                $ {{formatNumber(encabezado.total_importe)}}
                                        </div>
                                    </div>
                                </div> 


                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Importe del Anticipo</label>
                                        <div class="col-md-4">
                                                $ {{formatNumber(encabezado.total_anticipo)}}
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">Avance Global</label>
                                        <div class="col-md-2">
                                                {{formatNumber((resumen.total_acum_actual/encabezado.total_importe)*100)}}%
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Fonde de Garantía a Retención</label>
                                        <div class="col-md-4">
                                                $ {{formatNumber(encabezado.importe_garantia)}}
                                        </div>

                                        <div class="col-md-1">
                                                
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">Número de departamentos</label>
                                        <div class="col-md-2">
                                                {{encabezado.num_casas}}
                                        </div>
                                    </div>
                                </div>

                            </template>
                            
                            <template v-if="nueva == 0">
                                <div class="col-md-12">
                                    <!-- Selector de estimaciones -->
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Número de estimacion</label>
                                        <div class="col-md-2">
                                            <select class="form-control" :disabled="editarEstimacion == 1" 
                                                v-model="b_estimacion" 
                                                @click="getPartidas(aviso_id, encabezado.costo_indirecto_porcentaje), n_excel = b_estimacion"
                                            >
                                                <option value="">Seleccione</option>
                                                <option v-for="estimacion in arrayNumEstim" :key="estimacion.num_estimacion" 
                                                    :value="estimacion.num_estimacion" 
                                                    v-text="estimacion.num_estimacion">
                                                </option>
                                            </select>
                                        </div>

                                        <template v-if="editarEstimacion == 0 && fecha_pago_act != null">
                                            <label class="col-md-2 form-control-label" for="text-input">Fecha de pago</label>
                                            <div class="col-md-3">
                                                <input type="date" disabled v-model="fecha_pago_act" class="form-control" >
                                            </div>
                                        </template>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-3" v-if="((resumen.total_acum_actual/encabezado.total_importe)*100) < 100">
                                            <button type="button" @click="nueva = 1"  class="btn btn-primary">
                                                <i class="icon-plus"></i>&nbsp;Nueva estimación
                                            </button>
                                        </div>

                                        <div class="col-md-3" v-if="actual == numero && 
                                            (userName == 'uriel.al' || userName == 'guadalupe.ff' || userName == 'pablo.torrescano' || userName == 'shady')"
                                        >
                                            <button type="button" @click="editarEstimacion = 1, partidasAct()"  class="btn btn-warning">
                                                <i class="icon-pencil"></i>&nbsp;Editar estimacioón
                                            </button>
                                        </div>
                            
                                        <div class="col-md-3">
                                            <a  :href="'/estimaciones/excelEstimacionesDep?clave='+aviso_id+'&numero='+numero+'&num_casas='+encabezado.num_casas"  
                                                class="btn btn-success"><i class="fa fa-file-text"></i> Excel
                                            </a>
                                        </div>

                                    </div>
                                </div> 
                            </template> 

                            <template v-if="nueva == 1 && editarEstimacion == 0">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Número de estimacion</label>
                                        <div class="col-md-2">
                                            <input type="number" disabled v-model="num_estimacion" min="0" step="1" class="form-control" placeholder="Núm. Estimación">
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Inicio Periodo</label>
                                        <div class="col-md-3">
                                            <input type="date" v-model="periodo1" class="form-control" >
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">Fin Periodo</label>
                                        <div class="col-md-3">
                                            <input type="date" v-model="periodo2" class="form-control" >
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha de pago</label>
                                        <div class="col-md-4">
                                            <input type="date" v-model="fecha_pago" class="form-control" >
                                        </div>
                                        
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <button type="button" v-if="periodo1 != '' && periodo2 != ''" @click="storeEstimacion()"  class="btn btn-success">
                                                <i class="icon-check"></i>&nbsp;Guardar
                                            </button>
                                        </div>

                                        <div class="col-md-1"></div>
                                        
                                        <div class="col-md-2">
                                            <button type="button" @click="nueva = 0, getPartidas(aviso_id, encabezado.costo_indirecto_porcentaje)"  
                                                class="btn btn-warning">
                                                <i class="icon-close"></i>&nbsp;Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template v-if="editarEstimacion == 1">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Número de estimacion</label>
                                        <div class="col-md-2">
                                            <input type="number" disabled v-model="numero" min="0" step="1" class="form-control" placeholder="Núm. Estimación">
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <button type="button" @click="updateEstimacion(arrayPartidas)"  class="btn btn-success">
                                                <i class="icon-check"></i>&nbsp;Actualizar
                                            </button>
                                        </div>

                                        <div class="col-md-1"></div>
                                        
                                        <div class="col-md-3">
                                            <button type="button" @click="editarEstimacion = 0, 
                                                getPartidas(aviso_id, encabezado.costo_indirecto_porcentaje)"  
                                                class="btn btn-warning">
                                                <i class="icon-close"></i>&nbsp;Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <!-- TABLA DE PARTIDAS Y AVANCE -->
                            <div class="table-responsive" >
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Concepto</th>
                                            <th>Importe</th>
                                            <th>%</th>
                                            <th class="td2" colspan="2" v-if="numero != 0 && nueva == 0">Estimación No. {{numero}} 
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;</th>
                                            <th class="td2" colspan="2" v-else-if="nueva == 1">Estimación No. {{num_estimacion}}
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;</th>
                                            <th colspan="2">Cantidad Tope</th>
                                            <th colspan="2">Acumulado</th>
                                            <th colspan="2">Por Estimar</th>
                                            
                                        </tr>
                                        <tr>
                                            <th colspan="12"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(partida,index) in arrayPartidas">
                                            <tr :key ="partida.nivel"
                                                v-if="index != 0 && partida.nivel != arrayPartidas[index-1].nivel"
                                                style="background-color : #80bfff"
                                            >
                                                <td class="text-center" colspan="12"> Fin {{arrayPartidas[index-1].nivel}}</td>
                                            </tr>

                                            <tr :key="partida.id">
                                                <td class="td2" v-text="index+1"></td>
                                                <td class="td2" v-text="partida.partida"></td>
                                                <td class="td2" v-text="'$'+formatNumber(partida.pu_prorrateado)"></td>
                                                <td class="td2" v-text="`${formatNumber(partida.porc)} %`"></td>
                                                
                                                <template v-if="nueva == 0 && editarEstimacion == 0">
                                                    <td class="td2" v-if="numero != 0" v-text="partida.vol"></td>
                                                    <td class="td2" v-if="numero != 0" v-text="'$' + formatNumber( partida.costoA )"></td>
                                                </template>

                                                <template v-else-if="nueva == 1">
                                                    <td class="td2">
                                                        <input type="number" v-on:change="validar(index)"  v-model="partida.num_estimacion" min="0" step="0.1" class="form-control" placeholder="Núm. volumen">
                                                    </td>
                                                    <td class="td2" v-text="'$' + formatNumber( partida.costo = (partida.num_estimacion/100) * partida.pu_prorrateado )"></td>
                                                </template>

                                                <template v-else-if="editarEstimacion == 1 && nueva == 0">
                                                    <td class="td2">
                                                        <input type="number"  v-model="partida.num_estimacion" min="0" step="0.1" class="form-control" placeholder="Núm. volumen">
                                                    </td>
                                                    <td class="td2" v-text="'$' + formatNumber( partida.costo = (partida.num_estimacion/100) * partida.pu_prorrateado )"></td>
                                                </template>
                                                
                                                <td class="td2" v-text="`${partida.num_casas}%`"></td>
                                                <td class="td2" v-text="'$'+ formatNumber( partida.tope = (partida.pu_prorrateado) )"></td>
                                                <td class="td2"> 
                                                    {{ partida.acumVolTotal=(parseFloat(partida.acumVol) + parseFloat(partida.num_estimacion)).toFixed(2) }} %
                                                </td>
                                                <td class="td2">
                                                    $ {{formatNumber( partida.acumCostoTotal=parseFloat(partida.acumCosto) + parseFloat(partida.costo) )}}
                                                </td>
                                                <td class="td2">
                                                    {{ partida.porEstimarVol=(parseFloat(partida.num_casas) - parseFloat(partida.num_estimacion) - parseFloat(partida.acumVol)).toFixed(2) }} %
                                                </td>
                                                <td class="td2">
                                                    $ {{formatNumber( partida.porEstimarCosto=parseFloat(partida.tope) - parseFloat(partida.acumCosto) - parseFloat(partida.costo) )}}
                                                </td>
                                            </tr>
                                            
                                        </template>
                                        
                                        <tr>
                                            <th colspan="2" class="text-right">Costo fabricación: </th>
                                            <th class="td2">$ {{formatNumber(total1 = totalPU)}}</th>
                                            <template v-if="nueva == 0 && editarEstimacion == 0">
                                                <template v-if="(total2 = totalEst2) > 0">
                                                    <th colspan="2"></th>
                                                    <th class="td2 text-right">$ {{formatNumber(total2 = totalEst2)}}</th>
                                                </template>
                                                <template v-else>
                                                    <th></th>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <th colspan="2"></th>
                                                <th class="td2 text-right">$ {{formatNumber(total2 = totalEst2)}}</th>
                                            </template>
                                            <th>Importe: </th>
                                            <th class="td2 text-right">$ {{formatNumber(total3 = totalTope)}}</th>
                                            <th></th>
                                            <th class="td2 text-right">$ {{formatNumber(total4 = totalAcum)}}</th>
                                            <th></th>
                                            <th class="td2 text-right">$ {{formatNumber(total5 = totalPorEst)}}</th>

                                        </tr>     
                                        
                                        <tr>
                                            <th colspan="2" class="text-right">Indirectos y garantias: </th>
                                            <th class="td2">$ {{formatNumber(iTotal1 = total1 * encabezado.costo_indirecto_porcentaje)}}</th>
                                            <template v-if="nueva == 0 && editarEstimacion == 0">
                                                <template v-if="(total2 = totalEst2) > 0">
                                                    <th colspan="2"></th>
                                                    <th class="td2 text-right">$ {{formatNumber(iTotal2 = total2 * encabezado.costo_indirecto_porcentaje)}}</th>
                                                </template>
                                                <template v-else>
                                                    <th></th>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <th colspan="2"></th>
                                                <th class="td2 text-right">$ {{formatNumber(iTotal2 = total2 * encabezado.costo_indirecto_porcentaje)}}</th>
                                            </template>
                                            <th>Importe: </th>
                                            <th class="td2 text-right">$ {{formatNumber(iTotal3 = totalTope * encabezado.costo_indirecto_porcentaje)}}</th>
                                            <th></th>
                                            <th class="td2 text-right">$ {{formatNumber(iTotal4 = total4 * encabezado.costo_indirecto_porcentaje)}}</th>
                                            <th></th>
                                            <th class="td2 text-right">$ {{formatNumber(iTotal5 = total5 * encabezado.costo_indirecto_porcentaje)}}</th>
                                        </tr>   

                                        <tr>
                                            <th colspan="2" class="text-right">Precio de fabricación: </th>
                                            <th class="td2">$ {{formatNumber(iTotal1 + total1 )}}</th>
                                            <template v-if="nueva == 0 && editarEstimacion == 0">
                                                <template v-if="(totalEst2) > 0">
                                                    <th colspan="2"></th>
                                                    <th class="td2 text-right">$ {{formatNumber(iTotal2 + total2)}}</th>
                                                </template>
                                                <template v-else>
                                                    <th></th>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <th colspan="2"></th>
                                                <th class="td2 text-right">$ {{formatNumber(iTotal2 + total2)}}</th>
                                            </template>
                                            <th>Importe: </th>
                                            <th class="td2 text-right">$ {{formatNumber(iTotal3 + total3)}}</th>
                                            <th></th>
                                            <th class="td2 text-right">$ {{formatNumber(iTotal4 + total4 )}}</th>
                                            <th></th>
                                            <th class="td2 text-right">$ {{formatNumber(iTotal5 + total5 )}}</th>
                                        </tr>         
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive" > <br>
                            </div>

                            <!-- TABLA RESUMEN -->
                            <div class="table-responsive"  v-if="nueva == 0 && editarEstimacion == 0" >
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Acum Ant</th>
                                            <th>Esta Estimación</th>
                                            <th>Acum Actual</th>
                                            <th>Por Estimar</th>
                                            <!-- <th>Total por Pagar</th> -->
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="td2" v-text="'ESTIMADO'"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.total_acum_ant )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.total_estimacion )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.total_acum_actual     = resumen.total_estimacion + resumen.total_acum_ant )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.total_por_estimar     = encabezado.total_importe - resumen.total_acum_actual )"></td>
                                        </tr>   
                                        <tr>
                                            <td class="td2" v-text="'AMOR. ANTICIPO'"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.amor_total_acum_ant       = resumen.total_acum_ant * porc_anticipo )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.amor_total_estimacion     = resumen.total_estimacion * porc_anticipo )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.amor_total_acum_actual    = resumen.amor_total_acum_ant + resumen.amor_total_estimacion )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.amor_total_por_estimar    = encabezado.total_anticipo - resumen.amor_total_acum_actual )"></td>
                                        </tr>  
                                        <tr>
                                            <td class="td2" v-text="'F. G.'"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.fg_total_acum_ant     = resumen.total_acum_ant * porc_garantia )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.fg_total_estimacion   = resumen.total_estimacion * porc_garantia )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.fg_total_acum_actual  = resumen.fg_total_acum_ant + resumen.fg_total_estimacion )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.fg_total_por_estimar  = encabezado.importe_garantia - resumen.fg_total_acum_actual )"></td>
                                        </tr>  
                                        <tr>
                                            <td class="td2" v-text="'PAGADO'"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.pagado_total_acum_ant     = resumen.total_acum_ant - ( resumen.fg_total_acum_ant + resumen.amor_total_acum_ant) )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.pagado_total_estimacion   = resumen.total_estimacion - ( resumen.fg_total_estimacion + resumen.amor_total_estimacion ) )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.pagado_total_acum_actual  = resumen.total_acum_actual - ( resumen.fg_total_acum_actual + resumen.amor_total_acum_actual) )"></td>
                                            <td class="td2" v-text="'$'+formatNumber( resumen.pagado_total_por_estimar  = resumen.total_por_estimar - ( resumen.fg_total_por_estimar + resumen.amor_total_por_estimar) )"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive" >
                                <br>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="table-responsive"  v-if="nueva == 0 && editarEstimacion == 0" > 
                                            <table class="table2 table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">Observaciones</th>
                                                        <th>
                                                            <button title="Añadir" type="button" @click="abrirModal('observacion')" class="btn btn-success btn-sm">
                                                                <i class="icon-plus"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Fecha</th>
                                                        <th>Usuario</th>
                                                        <th>Observación</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <tr v-for="obs in arrayObs" :key="obs.id">
                                                        <td class="td2" v-text="this.moment(obs.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                                        <td class="td2" v-text="obs.usuario"></td>
                                                        <td class="td2" v-text="obs.observacion"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                        </div>
                    </template>
                        
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Inicio Modal FG, Anticipos y Obra extra -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observación</label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="observacion" class="form-control" placeholder="Observación" >
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button v-if="observacion != ''" 
                                type="button" class="btn btn-primary" @click="storeObs()">Guardar</button>
                        </div>
                    </div> 
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->


        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import vSelect from 'vue-select';
    export default {
        props:{
            userName:{type: String}
        },
        data(){
            return{
                arrayNumEstim:[],
                arrayObs:[],
                arrayEstimaciones:[],
                arrayFraccionamientos:[],
                arrayPartidas:[],

                //Filtros de busqueda
                buscar:'',
                b_proyecto:'',

                //Encabezado contrato
                encabezado:{
                    clave:'',
                    contratista: '',
                    total_importe: 0,
                    total_anticipo: 0,
                    importe_garantia: 0,
                    num_casas: 0,
                    tipo_proyecto : 0,
                    costo_indirecto_porcentaje : 0
                },

                resumen:{
                    total_estimacion :0,
                    total_acum_ant : 0,
                    total_acum_actual : 0,
                    total_por_estimar : 0,
                    
                    amor_total_estimacion :0,
                    amor_total_acum_ant : 0,
                    amor_total_acum_actual : 0,
                    amor_total_por_estimar : 0,

                    fg_total_estimacion :0,
                    fg_total_acum_ant : 0,
                    fg_total_acum_actual : 0,
                    fg_total_por_estimar : 0,

                    pagado_total_estimacion :0,
                    pagado_total_acum_ant : 0,
                    pagado_total_acum_actual : 0,
                    pagado_total_por_estimar : 0,
                },

                listado:1,
                nueva:0,
                b_estimacion:'',
                num_estimacion:0,
                n_excel:0,
                id:0,
                aviso_id:0,
                proceso:false,
                
                edit:0,
                total_impAux:0,
                periodo1:'',
                periodo2:'',
                fecha_pago:'',
                editarPartida : 0,

                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                
                numero:0,
                actual:0,
                offset2 : 3,
                
                tituloModal: '',
                modal:0,
                tipoAccion : 0,
               
                proceso:false,
                porc_anticipo : 0,
                porc_garantia : 0,

                total1:0,
                total2:0,
                total3:0,
                total4:0,
                total5:0,

                iTotal1:0,
                iTotal2:0,
                iTotal3:0,
                iTotal4:0,
                iTotal5:0,
                
                concepto:'',
                observacion : '',
                editarEstimacion : 0,
                fecha_pago_act : ''
            }
        },
        components:{
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber:function(){
                if(!this.pagination.to){
                    return [];
                }
                var from = this.pagination.current_page - this.offset2;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset2 * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            totalPU: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].pu_prorrateado)
                }
                total = Math.round(total*100)/100;
                return total;
            },
            totalEst2: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].costoA)
                }
                total = Math.round(total*100)/100;
                return total;
            },
            totalEst: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].costo)
                }
                total = Math.round(total*100)/100;
                return total;
            },
            totalTope: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].tope)
                }
                total = Math.round(total*100)/100;
                return total;
            },
            totalAcum: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].acumCostoTotal)
                }
                total = Math.round(total*100)/100;
                return total;
            },
            totalPorEst: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].porEstimarCosto)
                }
                total = Math.round(total*100)/100;
                return total;
            },
          
        },
       
        methods : {
            modoEditar(){
                if(this.userName == 'shady' || userName == 'uriel.al' || userName == 'guadalupe.ff' || userName == 'pablo.torrescano')
                    this.editarPartida = 1;
            },
            editCantTope(id, cant_tope){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/estimaciones/editCantTope',{
                    'id': id,
                    'cant_tope' : cant_tope
                }).then(function (response){
                    me.editarPartida = 0;
                    me.getPartidas(me.aviso_id, me.encabezado.costo_indirecto_porcentaje);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            indexEstimaciones(page){
                let me = this;
                me.b_estimacion = '';
                const url = '/estimaciones/indexEstimaciones?page=' + page + '&buscar=' + me.buscar  + '&proyecto=' + me.b_proyecto + '&tipo=2';
                axios.get(url).then(function (response) {
                    const respuesta = response.data;
                    me.arrayEstimaciones = respuesta.estimaciones.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                me.buscar=""
                me.buscar2=""
                me.buscar3=""
                }
                
                me.arrayFraccionamientos=[];
                const url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    const respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.indexEstimaciones(page);
            },       
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                const charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            partidasAct(){
                let me = this;
                me.b_estimacion = me.actual;
                //me.getPartidas(me.aviso_id);
                let costo = 0;

                me.arrayPartidas.forEach(element => {
                    costo = element.vol * element.pu_prorrateado;
                    element.num_estimacion = element.vol;
                    element.acumVol = element.acumVol - element.vol; 
                    element.acumCosto = element.acumCosto - costo;
                });
            },
            getPartidas(id, porc){
                let me = this;
                me.periodo1 = '';
                me.periodo2 = '';
                me.fecha_pago = '';
                me.fecha_pago_act = '';
                me.arrayCreditos=[];
                const url = '/estimaciones/getEstimacionesDep?clave='+id+'&numero='+this.b_estimacion+'&total_importe='+this.encabezado.total_importe;
                axios.get(url).then(function (response) {
                    const respuesta = response.data;
                    me.arrayPartidas = respuesta.estimaciones;
                    me.numero = respuesta.numero;
                    me.fecha_pago_act = respuesta.fecha_pago;
                    me.num_estimacion = respuesta.num_est;
                    me.arrayNumEstim = respuesta.numeros;
                    
                    (me.arrayNumEstim.length > 0) ? me.b_estimacion = me.numero : '';

                    me.actual = respuesta.actual;
                    const totalEstimacionInd = respuesta.total_estimacion * porc
                    const totalAcumEstimacionInd = respuesta.totalEstimacionAnt * porc
                    me.resumen.total_estimacion = respuesta.total_estimacion + totalEstimacionInd;
                    me.resumen.total_acum_ant = respuesta.totalEstimacionAnt + totalAcumEstimacionInd;

                    me.arrayObs = respuesta.observaciones;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validar(index){
                let me = this;
                const acumulado = parseFloat (me.arrayPartidas[index].porEstimarVol);
                if(acumulado<0){
                    const volumen = parseFloat( me.arrayPartidas[index].num_estimacion );
                    me.arrayPartidas[index].num_estimacion = volumen + acumulado;
                }
                
            },
            verDetalle(contrato){
                this.encabezado.clave = contrato['clave'];
                this.encabezado.total_importe = contrato['total_importe'];
                this.encabezado.total_anticipo = contrato['total_anticipo'];
                this.encabezado.importe_garantia = contrato['garantia_ret'];
                this.encabezado.tipo_proyecto = contrato['tipo_proyecto']
                this.listado = 0;
                this.porc_garantia = contrato['porc_garantia_ret'] / 100;
                this.porc_anticipo = contrato['anticipo'] / 100;
                this.encabezado.num_casas = contrato['num_casas'];
                this.aviso_id = (contrato['id']);
                this.encabezado.contratista = contrato['contratista'];
                this.encabezado.costo_indirecto_porcentaje = contrato['costo_indirecto_porcentaje']/100

                this.getPartidas(contrato['id'], this.encabezado.costo_indirecto_porcentaje);
            },
            abrirModal(accion){
                switch(accion){
                    case 'observacion':{
                        this.modal = 1;
                        this.tipoAccion = 6;
                        this.tituloModal = 'Reporte de finalización de obra';
                        this.observacion = '';
                        break;
                    }
                }
            },
            storeEstimacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController

                const amorEstimacion = me.total2*me.porc_anticipo;
                const totalGarantia = me.total2*me.porc_garantia;

                const totalPagar = me.total2 - (amorEstimacion + totalGarantia);
                const fecha_pago = this.fecha_pago;
                
                Swal({
                    title: '¿Desea continuar?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Estos cambios no se pueden revertir",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, guardar!'
                    }).then((result) => {
                    if (result.value) {
                        me.arrayPartidas.forEach(element => {
                            if(element.num_estimacion != 0){
                                axios.post('/estimaciones/store',{
                                    'estimacion_id' : element.id,
                                    'costo' : element.costo,
                                    'vol' : element.num_estimacion,
                                    'num_estimacion' : this.num_estimacion,
                                    'total_estimacion' : this.total2,
                                    'total_pagado' : totalPagar,
                                    'periodo1' : this.periodo1,
                                    'periodo2' : this.periodo2,
                                    'fecha_pago' : fecha_pago,
                                    'tipo_proyecto' : this.encabezado.tipo_proyecto,
                                    'clave' : this.aviso_id,
                                    'nivel' : element.nivel
                                }); 
                                me.nueva = 0;
                                me.b_estimacion = '';
                                me.getPartidas(me.aviso_id,  me.encabezado.costo_indirecto_porcentaje);
                                Swal({
                                    title: 'Hecho!',
                                    text: 'Estimacion guardada correctamente',
                                    type: 'success',
                                    animation: false,
                                    customClass: 'animated bounceInRight'
                                })
                            }
                        });
                    }
                })
            },
            updateEstimacion(data){
                let me = this;
                //Con axios se llama el metodo update de LoteController

                let amorEstimacion = me.total2*me.porc_anticipo;
                let totalGarantia = me.total2*me.porc_garantia;

                let totalPagar = me.total2 - (amorEstimacion + totalGarantia);
                
                Swal({
                    title: '¿Desea continuar?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Estos cambios no se pueden revertir",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, guardar!'
                    }).then((result) => {
                    if (result.value) {
                        me.arrayPartidas.forEach(element => {
                            if(element.num_estimacion != 0){
                                axios.post('/estimaciones/update',{
                                    'estimacion_id' : element.id,
                                    'costo' : element.costo,
                                    'vol' : element.num_estimacion,
                                    'num_estimacion' : this.actual,
                                    'total_estimacion' : this.total2,
                                    'total_pagado' : totalPagar,
                                    'tipo_proyecto' : this.encabezado.tipo_proyecto,
                                    'clave' : this.aviso_id,
                                    'nivel' : element.nivel
                                }); 
                                me.editarEstimacion = 0;
                                me.b_estimacion = '';
                                me.getPartidas(me.aviso_id,  me.encabezado.costo_indirecto_porcentaje);
                                Swal({
                                    title: 'Hecho!',
                                    text: 'Estimacion actualizada correctamente',
                                    type: 'success',
                                    animation: false,
                                    customClass: 'animated bounceInRight'
                                })
                            }
                        });
                    }
                })
            },
            updateTotal(){         
                let me = this;
                Swal({
                    title: '¿Desea continuar?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Estos cambios no se pueden revertir",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, guardar!'
                    }).then((result) => {
                    if (result.value) {
                        me.edit = 0;
                        me.total_importe = me.total_impAux;
                        me.importe_garantia = me.total_importe * me.porc_garantia;
                        me.getPartidas(me.aviso_id,  me.encabezado.costo_indirecto_porcentaje);

                        axios.put('/estimaciones/updateImporTotal',{
                            'id' : me.aviso_id,
                            'total_importe' : me.total_importe,
                            'importe_garantia' : me.importe_garantia
                        }); 
                        me.nueva = 0;
                        me.cerrarModal();
                        me.getPartidas(me.aviso_id,  me.encabezado.costo_indirecto_porcentaje);
                        Swal({
                            title: 'Hecho!',
                            text: 'Cambios aplicados correctamente',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })

            },
            cancel(){
                this.edit = 0;
                
                this.getPartidas(this.aviso_id, this.encabezado.costo_indirecto_porcentaje);
                this.total_impAux = 0;
            },
            storeObs(){
                let me = this;
                Swal({
                    title: '¿Desea continuar?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Estos cambios no se pueden revertir",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, guardar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.post('/estimaciones/storeObs',{
                            'aviso_id' : me.aviso_id,
                            'observacion' : me.observacion,
                        }); 
                        me.nueva = 0;
                        me.cerrarModal();
                        me.getPartidas(me.aviso_id,  me.encabezado.costo_indirecto_porcentaje);
                        Swal({
                            title: 'Hecho!',
                            text: 'Comentario guardado correctamente',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
            }
        },
        mounted() {          
            this.indexEstimaciones(1);
            this.selectFraccionamientos();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
       
    }
    .modal-body{
        height: 400px;
        width: 100%;
        overflow-y: auto;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    
    .table2 {
        margin: auto;
        border-collapse: collapse;
        overflow-x: auto;
        display: block;
        width: fit-content;
        max-width: 100%;
        box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
    }
    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }
    .td2 {
        white-space: nowrap;
        border-bottom: none;
        color: rgb(20, 20, 20);
    }
    .td2:first-of-type, th:first-of-type {
       border-left: none;
    }
    .td2:last-of-type, th:last-of-type {
       border-right: none;
    } 
    
    .badge2 {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 85%;
        font-weight: bold;
        line-height: 1;
        color: #151b1f;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
</style>