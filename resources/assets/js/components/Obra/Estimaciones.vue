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
                        <button v-if="listado == 1" type="button" @click="abrirModal('nuevo')" class="btn btn-primary">
                            <i class="icon-plus"></i>&nbsp;Asignar Partidas
                        </button>
                        <button v-if="listado==0" type="button" @click="indexEstimaciones(1),listado=1" class="btn btn-success">
                            <i class="fa fa-mail-reply"></i> Regresar
                        </button>

                        <button v-if="listado == 1" type="button" @click="abrirModal('resumen')" class="btn btn-dark">
                            <i class="icon-share-alt"></i>&nbsp;Resumen de estimaciones
                        </button>

                        <button v-if="listado == 1" type="button" @click="abrirModal('reporte')" class="btn btn-dark">
                            <i class="icon-share-alt"></i>&nbsp;Reporte de finalización de obra
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
                                <div class="col-md-4"></div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <select class="form-control" v-model="b_fin_estimaciones">
                                            <option value="0">En Proceso</option>
                                            <option value="1">Finalizados</option>
                                        </select>
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
                                            <td v-if="contrato.fin_estimaciones == 0">
                                                <button 
                                                    @click="finalizarEstimacion(contrato.id)"
                                                    class="btn btn-danger">Finalizar estimación</button>
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination2.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page2 in pagesNumber2" :key="page2" :class="[page2 == isActived2 ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(page2)" v-text="page2"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                    <!--  VISTA DETALLE  -->
                    <template v-if="listado == 0">
                       <div class="card-body"> 
                            <!--Encabezado-->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <center> <h5 style="color: #153157;">Contrato: {{clave}}</h5> </center>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group">
                                    <center> <h6 style="color: #153157;">Contratista: {{contratista}}</h6> </center>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Importe del Contrato</label>
                                    <div class="col-md-4" v-if="arrayFG.length > 0">
                                            $ {{formatNumber(total_importe)}}
                                    </div>
                                    <div class="col-md-4" v-else-if="arrayFG.length == 0 && edit == 0">
                                            <a href="#" @dblclick="edit = 1, total_impAux = total_importe" title="Doble clic para editar">$ {{formatNumber(total_importe)}}</a>
                                    </div>
                                    <div class="col-md-4" v-else-if="arrayFG.length == 0 && edit == 1">
                                            <input type="text" pattern="\d*" @keyup.esc="cancel()" v-on:keypress="isNumber($event)" v-model="total_impAux" @keyup.enter="updateTotal()">
                                    </div>
                                </div>
                            </div> 


                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Importe del Anticipo</label>
                                    <div class="col-md-4">
                                            $ {{formatNumber(total_anticipo)}}
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Avance Global</label>
                                    <div class="col-md-2">
                                            {{formatNumber((total_acum_actual/total_importe)*100)}}%
                                    </div>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fonde de Garantía a Retención</label>
                                    <div class="col-md-4">
                                            $ {{formatNumber(importe_garantia)}}
                                    </div>

                                    <div class="col-md-1">
                                            
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Número de casas</label>
                                    <div class="col-md-2">
                                            {{num_casas}}
                                    </div>
                                </div>
                            </div>
                            
                            <template v-if="nueva == 0">
                                <div class="col-md-12">
                                    <!-- Selector de estimaciones -->
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Número de estimacion</label>
                                        <div class="col-md-2">
                                            <select class="form-control" :disabled="editarEstimacion == 1" v-model="b_estimacion" @click="getPartidas(aviso_id), n_excel = b_estimacion">
                                                <option value="">Seleccione</option>
                                                <option v-for="estimacion in arrayNumEstim" :key="estimacion.num_estimacion" :value="estimacion.num_estimacion" v-text="estimacion.num_estimacion"></option>
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
                                        <div class="col-md-2" v-if="((total_acum_actual/total_importe)*100) < 100">
                                            <button type="button" @click="nueva = 1"  class="btn btn-primary">
                                                <i class="icon-plus"></i>&nbsp;Nueva estimación
                                            </button>
                                        </div>

                                        <div class="col-md-2" v-if="actual == numero && 
                                            (userName == 'uriel.al' || userName == 'guadalupe.ff' || userName == 'pablo.torrescano' || userName == 'shady' )"
                                        >
                                            <button type="button" @click="editarEstimacion = 1, partidasAct()"  class="btn btn-warning">
                                                <i class="icon-pencil"></i>&nbsp;Editar estimacioón
                                            </button>
                                        </div>
                            
                                        <div class="col-md-2">
                                            <a  :href="'/estimaciones/excelEstimaciones?clave='+aviso_id+'&numero='+numero+'&num_casas='+num_casas"  
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
                                            <button type="button" v-if="periodo1 != '' && periodo2 != ''" @click="storeEstimacion(arrayPartidas)"  class="btn btn-success">
                                                <i class="icon-check"></i>&nbsp;Guardar
                                            </button>
                                        </div>

                                        <div class="col-md-1"></div>
                                        
                                        <div class="col-md-2">
                                            <button type="button" @click="nueva = 0"  class="btn btn-warning">
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
                                        <div class="col-md-2">
                                            <button type="button" @click="updateEstimacion(arrayPartidas)"  class="btn btn-success">
                                                <i class="icon-check"></i>&nbsp;Actualizar
                                            </button>
                                        </div>

                                        <div class="col-md-1"></div>
                                        
                                        <div class="col-md-2">
                                            <button type="button" @click="editarEstimacion = 0, getPartidas(
                                                aviso_id);"  class="btn btn-warning">
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
                                            <th>Paquete</th>
                                            <th>P.U. Prorrateado</th>
                                            <th>No. de Viviendas</th>
                                            <th colspan="2" v-if="numero != 0 && nueva == 0">Estimación No. {{numero}}</th>
                                            <th colspan="2" v-else-if="nueva == 1">Estimación No. {{num_estimacion}}</th>
                                            <th colspan="2">Cantidad Tope</th>
                                            <th colspan="2">Acumulado</th>
                                            <th colspan="2">Por Estimar</th>
                                            <!-- <th>Total por Pagar</th> -->
                                            
                                        </tr>
                                        <tr>
                                            <th colspan="12"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(partida,index) in arrayPartidas" :key="partida.id">
                                            <td class="td2" v-text="index+1"></td>
                                            <td class="td2" v-text="partida.partida"></td>
                                            <td class="td2" v-text="'$'+formatNumber(partida.pu_prorrateado)"></td>
                                            <template><!--Numero de casas -->
                                                <td class="td2" v-if="editarPartida == 0" v-text="partida.num_casas" @dblclick="modoEditar()"></td>
                                                <td class="td2" v-else>
                                                    <input type="number" pattern="\d*" @keyup.enter="editCantTope(partida.id,$event.target.value)" :id="partida.id" :value="partida.num_casas" step="1"  v-on:keypress="isNumber($event)" class="form-control" >     
                                                </td>
                                            </template>
                                            
                                            <template v-if="nueva == 0 && editarEstimacion == 0">
                                                <td class="td2" v-if="numero != 0" v-text="partida.vol"></td>
                                                <td class="td2" v-if="numero != 0" v-text="'$' + formatNumber( partida.costoA )"></td>
                                            </template>
                                            <template v-else-if="nueva == 1">
                                                <td class="td2">
                                                    <input type="number" v-on:change="validar(index)"  v-model="partida.num_estimacion" min="0" step="0.1" class="form-control" placeholder="Núm. volumen">
                                                </td>
                                                <td class="td2" v-text="'$' + formatNumber( partida.costo = partida.num_estimacion * partida.pu_prorrateado )"></td>
                                            </template>
                                            <template v-else-if="editarEstimacion == 1 && nueva == 0">
                                                <td class="td2">
                                                    <input type="number"  v-model="partida.num_estimacion" min="0" step="0.1" class="form-control" placeholder="Núm. volumen">
                                                </td>
                                                <td class="td2" v-text="'$' + formatNumber( partida.costo = partida.num_estimacion * partida.pu_prorrateado )"></td>
                                            </template>
                                            
                                            <td class="td2" v-text="partida.num_casas"></td>
                                            <td class="td2" v-text="'$'+ formatNumber( partida.tope=(partida.pu_prorrateado * partida.num_casas) )"></td>
                                            <td class="td2"> 
                                                {{ partida.acumVolTotal=(parseFloat(partida.acumVol) + parseFloat(partida.num_estimacion)).toFixed(2) }} 
                                            </td>
                                            <td class="td2">
                                                $ {{formatNumber( partida.acumCostoTotal=parseFloat(partida.acumCosto) + parseFloat(partida.costo) )}}
                                            </td>
                                            <td class="td2">
                                                {{ partida.porEstimarVol=(parseFloat(partida.num_casas) - parseFloat(partida.num_estimacion) - parseFloat(partida.acumVol)).toFixed(2) }}
                                            </td>
                                            <td class="td2">
                                                $ {{formatNumber( partida.porEstimarCosto=parseFloat(partida.tope) - parseFloat(partida.acumCosto) - parseFloat(partida.costo) )}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th colspan="2" class="text-right">Gran Total: </th>
                                            <th class="td2">$ {{formatNumber(total1 = totalPU)}}</th>
                                            <template v-if="nueva == 0 && editarEstimacion == 0">
                                                <template v-if="(total2 = totalEst2) > 0">
                                                    <th colspan="2"></th>
                                                    <th class="td2">$ {{formatNumber(total2)}}</th>
                                                </template>
                                                <template v-else>
                                                    <th></th>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <th colspan="2"></th>
                                                <th class="td2">$ {{formatNumber(total2 = totalEst)}}</th>
                                            </template>
                                            <th>Importe: </th>
                                            <th class="td2">$ {{formatNumber(total3 = totalTope)}}</th>
                                            <th></th>
                                            <th class="td2">$ {{formatNumber(to5al4 = totalAcum)}}</th>
                                            <th></th>
                                            <th class="td2">$ {{formatNumber(total3 = totalPorEst)}}</th>

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
                                            <td class="td2" v-text="'$'+formatNumber(total_acum_ant)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(total_estimacion)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(total_acum_actual = total_estimacion + total_acum_ant)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(total_por_estimar = total_importe - total_acum_actual)"></td>
                                        </tr>   
                                        <tr>
                                            <td class="td2" v-text="'AMOR. ANTICIPO'"></td>
                                            <td class="td2" v-text="'$'+formatNumber(amor_total_acum_ant = total_acum_ant * porc_anticipo)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(amor_total_estimacion = total_estimacion * porc_anticipo)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(amor_total_acum_actual = amor_total_acum_ant + amor_total_estimacion)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(amor_total_por_estimar = total_anticipo - amor_total_acum_actual)"></td>
                                        </tr>  
                                        <tr>
                                            <td class="td2" v-text="'F. G.'"></td>
                                            <td class="td2" v-text="'$'+formatNumber(fg_total_acum_ant = total_acum_ant * porc_garantia)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(fg_total_estimacion = total_estimacion * porc_garantia)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(fg_total_acum_actual = fg_total_acum_ant + fg_total_estimacion)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(fg_total_por_estimar = importe_garantia - fg_total_acum_actual)"></td>
                                        </tr>  
                                        <tr>
                                            <td class="td2" v-text="'PAGADO'"></td>
                                            <td class="td2" v-text="'$'+formatNumber(pagado_total_acum_ant = total_acum_ant - ( fg_total_acum_ant + amor_total_acum_ant))"></td>
                                            <td class="td2" v-text="'$'+formatNumber(pagado_total_estimacion = total_estimacion - ( fg_total_estimacion + amor_total_estimacion ))"></td>
                                            <td class="td2" v-text="'$'+formatNumber(pagado_total_acum_actual = total_acum_actual - ( fg_total_acum_actual + amor_total_acum_actual))"></td>
                                            <td class="td2" v-text="'$'+formatNumber(pagado_total_por_estimar = total_por_estimar - ( fg_total_por_estimar + amor_total_por_estimar))"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive" >
                                <br><br>
                            </div>
                            
                            <div class="form-group row">
                                <!-- TABLA ANTICIPOS -->
                                <div class="col-md-5">
                                    <div class="table-responsive"  v-if="nueva == 0 && editarEstimacion == 0" >
                                        <table class="table2 table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Anticipo</th>
                                                    <th v-text="'$'+formatNumber(total_anticipo)"></th>
                                                    <th v-if="arrayNumEstim.length == 0">
                                                        <button title="Añadir" type="button" @click="abrirModal('anticipo')" class="btn btn-success btn-sm">
                                                            <i class="icon-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="anticipo in arrayAnticipos" :key="anticipo.id">
                                                    <td class="td2" v-text="'Pago de Anticipo de Vivienda ('+this.moment(anticipo.fecha_anticipo).locale('es').format('DD/MMM/YYYY')+')'"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(anticipo.monto_anticipo)"></td>
                                                </tr>   
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- MONTO ESTIMACION ACTUAL -->
                                <div class="col-md-2">
                                    <div class="table-responsive"  v-if="nueva == 0 && editarEstimacion == 0" > 
                                        <center>
                                            Esta estimacion: 
                                            <h5 style="color: #153157;">
                                                <strong> ${{formatNumber(pagado_total_estimacion)}} </strong>
                                            </h5> 
                                        </center> 
                                    </div>
                                </div>

                                <!-- TABLA FONDO DE GARANTIA -->
                                <div class="col-md-5">
                                    <div class="table-responsive"  v-if="nueva == 0 && editarEstimacion == 0" >
                                        <table class="table2 table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">Fondo de Garantia</th>
                                                    <th>
                                                        <button title="Añadir" type="button" @click="abrirModal('fg')" class="btn btn-success btn-sm">
                                                            <i class="icon-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="td2" v-text="'Viviendas'"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(importe_garantia)"></td>
                                                    <td class="td2" v-text="num_casas"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(fg_indiv = importe_garantia/num_casas)"></td>
                                                </tr>   
                                                <tr v-for="fg in arrayFG" :key="fg.id">
                                                    <td class="td2" colspan="2" v-text="'Pago de ' + fg.cantidad + ' FG (' + this.moment(fg.fecha_fg).locale('es').format('DD/MMM/YYYY')+')'"></td>
                                                    <td class="td2" v-text="fg.cantidad"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(fg.monto_fg)"></td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-md-6">
                                    <div class="table-responsive"  v-if="nueva == 0 && editarEstimacion == 0" > 
                                        <table class="table2 table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Obra extra</th>

                                                    <th v-if="edit2 == 1">
                                                        <input type="text" pattern="\d*" @keyup.esc="cancel()" 
                                                            v-on:keypress="isNumber($event)" v-model="total_impAux">
                                                    </th>

                                                    <th v-else>
                                                        <a href="#" @dblclick="edit2 = 1, total_impAux = impExtra" 
                                                            title="Doble clic para editar">$ {{formatNumber(impExtra)}}</a>
                                                    </th>

                                                    <th v-if="edit2 == 1">
                                                        <input type="date" @keyup.esc="cancel()" 
                                                            v-on:keypress="isNumber($event)" v-model="dateAux">
                                                    </th>

                                                    <th v-else v-text="fechaExtra"></th>
                                                    
                                                    <th>
                                                        <button v-if="edit2 == 1" title="Guardar" type="button" @click="storeImporteExtra()" class="btn btn-success btn-sm">
                                                            <i class="icon-check"></i>
                                                        </button>
                                                        <button v-if="edit2 == 1" title="Cancelar" type="button" @click="cancel()" class="btn btn-danger btn-sm">
                                                            <i class="icon-close"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            
                                                
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="td2"><strong>No.</strong> </td>
                                                    <td class="td2"><strong>Fecha</strong> </td>
                                                    <td colspan="2" class="td2"><strong>Concepto</strong> </td>
                                                    <td class="td2"><strong>Importe</strong> </td>
                                                        <button v-if="impExtra>0" title="Añadir" type="button" @click="abrirModal('extra')" class="btn btn-success btn-sm">
                                                            <i class="icon-plus"></i>
                                                        </button>
                                                </tr>   
                                                <tr v-for="(extra,index) in arrayExtra" :key="extra.id">
                                                    <td class="td2" v-text="index+1"></td>
                                                    <td class="td2" v-text="this.moment(extra.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                                    <td colspan="2" class="td2" v-text="extra.concepto"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(extra.importe)"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

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
                    </template>
                        
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Inicio Modal Asignar Partidas -->
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
                            <form method="post" @submit="formSubmit"  enctype="multipart/form-data">
                        
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Contrato</label>
                                    <div class="col-md-4">
                                        <v-select 
                                            :on-search="getSinEstimaciones"
                                            label="clave"
                                            :options="arrayContratos"
                                            placeholder="Buscar contrato..."
                                            :onChange="getDatosContrato"
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="total_importe!=0">
                                    <div class="col-md-6">
                                        <h6 style="color: #153157;">Importe del Contrato  
                                            <strong> ${{formatNumber(total_importe)}} </strong>
                                        </h6>
                                        <input type="text" v-on:keypress="isNumber($event)" v-model="total_importe">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">% Garantía a retención</label>
                                    <div class="col-md-2">
                                        <input type="number" min="0" step="0.01" maxlength="5" style="right: 10px;" class="form-control" v-model="porcentaje_garantia">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <h6 >Fondo de Garantia a Retención:  
                                            <strong> ${{formatNumber(garantia_ret=(total_importe*porcentaje_garantia)/100)}} </strong>
                                        </h6>
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                         Selecciona archivo excel xls/csv: <input type="file" v-on:change="onImageChange" class="form-control">
                                    </div>
                                </div>

                                <input v-if="proceso == false && file!=''" type="submit" value="Cargar" class="btn btn-primary" style="margin-top: 3%">
                            
                         </form>


                      
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </div> 
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!-- Inicio Modal FG, Anticipos y Obra extra -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                <template v-if="tipoAccion == 1">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha de anticipo</label>
                                        <div class="col-md-4">
                                            <input type="date" v-model="fecha_anticipo" class="form-control" placeholder="Fecha">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Monto</label>
                                        <div class="col-md-2">
                                            <input type="text" v-on:change="validarAnticipo()" pattern="\d*" v-on:keypress="isNumber($event)" v-model="monto_anticipo" class="form-control" placeholder="Monto" >
                                        </div>
                                        <div class="col-md-2">
                                            <h6><strong> ${{ formatNumber(monto_anticipo)}} </strong></h6>
                                        </div>
                                    </div>
                                </template>
                                <template v-else-if="tipoAccion == 2">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Cantidad</label>
                                        <div class="col-md-4">
                                            <input type="number" min="0" v-on:change="validarFG()" v-model="fg_cantidad" class="form-control" placeholder="Cantidad">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Monto</label>
                                        <div class="col-md-2">
                                            <h6><strong> ${{ formatNumber(monto_fg = fg_indiv * fg_cantidad)}} </strong></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                        <div class="col-md-4">
                                            <input type="date" v-model="fecha_fg" class="form-control" placeholder="Fecha">
                                        </div>
                                    </div>
                                </template>
                                <template v-else-if="tipoAccion == 4">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Importe</label>
                                        <div class="col-md-2">
                                            <input type="text" v-on:change="validarOExtra()" pattern="\d*" v-on:keypress="isNumber($event)" v-model="importe" class="form-control" placeholder="Importe" >
                                        </div>
                                        <div class="col-md-2">
                                            <h6><strong> ${{ formatNumber(importe)}} </strong></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Concepto</label>
                                        <div class="col-md-5">
                                            <input type="text" v-model="concepto" class="form-control" placeholder="Concepto" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                        <div class="col-md-4">
                                            <input type="date" v-model="fecha_extra" class="form-control" placeholder="Fecha">
                                        </div>
                                    </div>
                                </template>
                                <template v-else-if="tipoAccion == 3">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fraccionamiento</label>
                                        <div class="col-md-5">
                                            <select class="form-control" v-model="fraccionamiento"  @change="selectEtapas(fraccionamiento)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                        <div class="col-md-5">
                                            <select class="form-control" v-model="etapa" >
                                                <option value="">Etapa</option>
                                                <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Contratista</label>
                                        <div class="col-md-5">
                                            <select class="form-control" v-model="contratista">
                                                <option value=''> Seleccione </option>
                                                <option v-for="contratista in arrayContratistas" :key="contratista.id" :value="contratista.id" v-text="contratista.nombre"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Emp. Constructora</label>
                                        <div class="col-md-5">
                                            <select class="form-control" v-model="constructora" >
                                                <option value="">Empresa constructora</option>
                                                <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                            </select>
                                        </div>
                                    </div>
                                </template>
                                <template v-else-if="tipoAccion == 5">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Contratista</label>
                                        <div class="col-md-5">
                                            <select class="form-control" v-model="contratista">
                                                <option value=''> Seleccione </option>
                                                <option v-for="contratista in arrayContratistas" :key="contratista.id" :value="contratista.id" v-text="contratista.nombre"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input"></label>
                                        <div class="col-md-4">
                                            <select class="form-control" v-model="mes">
                                                <option value=''> Selec. Mes </option>
                                                <option value='01'> Enero </option>
                                                <option value='02'> Febrero </option>
                                                <option value='03'> Marzo </option>
                                                <option value='04'> Abril </option>
                                                <option value='05'> Mayo </option>
                                                <option value='06'> Junio </option>
                                                <option value='07'> Julio </option>
                                                <option value='08'> Agosto </option>
                                                <option value='09'> Septiembre </option>
                                                <option value='10'> Octubre </option>
                                                <option value='11'> Noviembre </option>
                                                <option value='12'> Diciembre </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" v-model="anio">
                                                <option value=''> Selec. Año </option>
                                                <option value='2021'> 2021 </option>
                                                <option value='2022'> 2022 </option>
                                                <option value='2023'> 2023 </option>
                                                <option value='2024'> 2024 </option>
                                                <option value='2025'> 2025 </option>
                                                <option value='2026'> 2026 </option>
                                                <option value='2027'> 2027 </option>
                                                <option value='2028'> 2028 </option>
                                                <option value='2029'> 2029 </option>
                                                <option value='2030'> 2030 </option>
                                                <option value='2031'> 2031 </option>
                                            </select>
                                        </div>
                                    </div>

                                </template>
                                <template v-else-if="tipoAccion == 6">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Observación</label>
                                        <div class="col-md-5">
                                            <input type="text" v-model="observacion" class="form-control" placeholder="Observación" >
                                        </div>
                                    </div>
                                </template>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button v-if="tipoAccion == 1 && monto_anticipo != 0 && fecha_anticipo != ''" 
                                type="button" class="btn btn-primary" @click="storeAnticipos()">Guardar Anticipo</button>
                            <button v-if="tipoAccion == 2 && fg_cantidad != 0 " 
                                type="button" class="btn btn-primary" @click="storeFondoG()">Guardar FG</button>

                            <button v-if="tipoAccion == 4 && importe != 0 " 
                                type="button" class="btn btn-primary" @click="storeConceptoExtra()">Guardar importe extra</button>
                            
                            <a v-if="tipoAccion == 3" class="btn btn-success" v-bind:href="'/estimaciones/resumen?fraccionamiento='+ fraccionamiento 
                                    + '&constructora='+ constructora + '&contratista='+ contratista + '&etapa=' + etapa">
                                    <i class="fa fa-file-text"></i>&nbsp; Descargar excel
                            </a>

                            <a v-if="tipoAccion == 5" class="btn btn-success" v-bind:href="'/estimaciones/resumenEdoCuenta?contratista='+ contratista 
                                + '&mes='+ mes + '&anio='+ anio">
                                <i class="fa fa-file-text"></i>&nbsp; Descargar excel
                            </a>
                             <button v-if="tipoAccion == 6 && observacion != ''" 
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
                contratista:'',
                fraccionamiento:'',
                etapa : '',
                constructora:'',
                listado:1,
                nueva:0,
                arrayNumEstim:[],
                arrayAnticipos:[],
                arrayFG:[],
                arrayObs:[],
                b_estimacion:'',
                num_estimacion:0,
                n_excel:0,
                id:0,
                aviso_id:0,
                proceso:false,
                arrayEstimaciones:[],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                empresas:[],
                edit:0,
                total_impAux:0,
                periodo1:'',
                periodo2:'',
                fecha_pago:'',
                editarPartida : 0,
                
                arrayContratos:[],
                arrayContratistas:[],
                arrayPartidas:[],
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                buscar:'',
                b_proyecto:'',
                b_fin_estimaciones:0,
                clave:'',
                porcentaje_garantia:0,
                total_importe:0,
                garantia_ret:0,
                importe_garantia:0,
                num_casas:0,
                numero:0,
                actual:0,
                offset2 : 3,
                
                modal: 0,
                tituloModal: '',
                modal1:0,
                tipoAccion : 0,
                monto_anticipo:0,
                fecha_anticipo:'',
                fecha_fg:'',
                monto_fg : 0,
                fg_cantidad : 0,
                fg_indiv : 0,
                file:'',
                proceso:false,
                acumuladoVol:0,
                acumCosto:0,
                porEstimarVol:0,
                porEstimarCosto:0,
                porc_anticipo : 0,
                porc_garantia : 0,
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
                total1:0,
                total2:0,
                total3:0,
                total4:0,
                total5:0,
                edit2:0,
                impExtra:0,
                dateAux:'',
                fechaExtra:'',
                arrayExtra:[],
                fecha_extra:'',
                concepto:'',
                importe:0,
                mes:'01',
                anio:2021,
                observacion : '',
                editarEstimacion : 0,
                fecha_pago_act : ''
            }
        },
        components:{
            vSelect
        },
        computed:{
            isActived2: function(){
                return this.pagination2.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }
                var from = this.pagination2.current_page - this.offset2;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset2 * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
                }
                var pagesArray2 = [];
                while(from <= to){
                    pagesArray2.push(from);
                    from++;
                }
                return pagesArray2;
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
            onImageChange(e){
                console.log(e.target.files[0]);
                this.file = e.target.files[0];
                if(this.file==''){
                    return;
                }
            },
            formSubmit(e) {
                if(this.proceso==true || this.file==''){
                    return;
                }
                this.proceso=true;
                e.preventDefault();
               
               let formData = new FormData();
                formData.append('file', this.file);
                formData.append('contrato', this.clave);
                formData.append('porcentaje_garantia', this.porcentaje_garantia);
                formData.append('garantia_ret', this.garantia_ret);
                formData.append('total_importe', this.total_importe);
                let me = this;
                axios.post('/estimaciones/import',formData)
                .then(function (response) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo cargado correctamente',
                        showConfirmButton: false,
                        timer: 2500
                        })
                    me.proceso=false;
                    me.cerrarModal();
                    me.indexEstimaciones(me.pagination2.current_page)
                    //me.listarLote(1,'','','','lote');
                })
                .catch(function (error) {
                  console.log(error);
                });
            },
            modoEditar(){
                if(this.userName == 'shady' || this.userName == 'uriel.al' || this.userName == 'guadalupe.ff' || this.userName == 'pablo.torrescano')
                    this.editarPartida = 1;
            },
            editCantTope(id, cant_tope){
                let me = this;
                me.editarPartida = 0;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/estimaciones/editCantTope',{
                    'id': id,
                    'cant_tope' : cant_tope
                }).then(function (response){
                    me.editarPartida = 0;
                    me.getPartidas(me.aviso_id);
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
                this.arrayAnticipos = [];
                this.arrayFG = [];
                this.acumuladoVol = 0;
                this.acumCosto = 0;
                this.porEstimarVol = 0;
                this.porEstimarCosto = 0;
                this.porc_anticipo = 0;
                this.porc_garantia = 0;
                this.total_estimacion  = 0;
                this.total_acum_ant = 0;
                this.total_acum_actual = 0;
                this.total_por_estimar = 0;
                this.amor_total_estimacion  = 0;
                this.amor_total_acum_ant = 0;
                this.amor_total_acum_actual = 0;
                this.amor_total_por_estimar = 0;
                this.fg_total_estimacion  = 0;
                this.fg_total_acum_ant = 0;
                this.fg_total_acum_actual = 0;
                this.fg_total_por_estimar = 0;
                this.pagado_total_estimacion  = 0;
                this.pagado_total_acum_ant = 0;
                this.pagado_total_acum_actual = 0;
                this.pagado_total_por_estimar = 0;
                this.total1 = 0;
                this.total2 = 0;
                this.total3 = 0;
                this.total4 = 0;
                this.total5 = 0;
                this.b_estimacion = '';
                var url = '/estimaciones/indexEstimaciones?page=' + page + '&buscar=' + me.buscar  + '&proyecto=' + me.b_proyecto + '&tipo=1' + '&fin_estimaciones=' + me.b_fin_estimaciones;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEstimaciones = respuesta.estimaciones.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            getSinEstimaciones(search, loading){
                let me = this;
                loading(true)
                var url = '/estimaciones/getSinEstimaciones?buscar='+search+'&tipo=Vivienda';
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayContratos = respuesta.contratos;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosContrato(val1){
                let me = this;
                me.loading = true;
                me.clave = val1.id;
                me.total_importe = val1.total_importe;
            },
            selectContratistas(){
                let me = this;
                me.arrayContratistas = [];
                var url = '/select_contratistas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratistas = respuesta.contratista;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                me.buscar=""
                me.buscar2=""
                me.buscar3=""
                }
                
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEtapas(buscar){
                let me = this;
                me.etapa = '';
                
                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.indexEstimaciones(page);
            },       
            cerrarModal(){
                this.modal = 0;
                this.modal1 = 0;
                this.tituloModal = '';
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
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
            getPartidas(id){
                let me = this;
                me.periodo1 = '';
                me.periodo2 = '';
                me.fecha_pago = '';
                me.fecha_pago_act = '';
                me.arrayCreditos=[];
                var url = '/estimaciones/getPartidas?clave='+id+'&numero='+this.b_estimacion+'&total_importe='+this.total_importe;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    var extra= [];
                    me.arrayPartidas = respuesta.estimaciones;
                    me.numero = respuesta.numero;
                    me.fecha_pago_act = respuesta.fecha_pago;
                    me.num_estimacion = respuesta.num_est;
                    me.arrayNumEstim = respuesta.numeros;
                    if(me.arrayNumEstim.length > 0)
                        me.b_estimacion = me.numero;
                    me.actual = respuesta.actual;
                    me.total_estimacion = respuesta.total_estimacion;
                    me.total_acum_ant = respuesta.totalEstimacionAnt;
                    me.arrayAnticipos = respuesta.anticipos;
                    me.arrayFG = respuesta.fondos;

                    me.arrayExtra = respuesta.conceptosExtra;
                    me.arrayObs = respuesta.observaciones;
                    extra = respuesta.importesExtra;
                    me.fechaExtra = extra[0].fechaExtra;
                    me.impExtra = extra[0].impExtra;

                    me.total_anticipo = respuesta.total_anticipo;
                    me.porc_anticipo = respuesta.anticipo/ 100;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validar(index){
                let me = this;
                var acumulado = parseFloat (me.arrayPartidas[index].porEstimarVol);
                var volumen = 0;
                if(acumulado<0){
                    volumen = parseFloat( me.arrayPartidas[index].num_estimacion );
                    me.arrayPartidas[index].num_estimacion = volumen + acumulado;
                }
                
            },
            validarOExtra(){
                let me = this;
                var monto = 0;
                if(me.arrayExtra.length > 0)
                    for(var i=0;i<me.arrayExtra.length;i++){
                        monto += parseFloat(me.arrayExtra[i].importe);
                    }
                
                var porPagar = me.impExtra - monto;
                if(me.importe>porPagar)
                    me.importe = porPagar;
            },
            validarAnticipo(){
                let me = this;
                var monto = 0;
                if(me.arrayAnticipos.length > 0)
                    for(var i=0;i<me.arrayAnticipos.length;i++){
                        monto += parseFloat(me.arrayAnticipos[i].monto_anticipo);
                    }
                
                var porPagar = me.total_importe - monto;
                if(me.monto_anticipo>porPagar)
                    me.monto_anticipo = porPagar;
                
            },
            validarFG(){
                let me = this;
                var num = 0;
                if(me.arrayFG.length > 0)
                    for(var i=0;i<me.arrayFG.length;i++){
                        num += parseFloat(me.arrayFG[i].cantidad);
                    }
                
                var porFG = me.num_casas - num;
                if(me.fg_cantidad>porFG)
                    me.fg_cantidad = porFG;
                
            },
            verDetalle(contrato){
                
                this.clave = contrato['clave'];
                this.total_importe = contrato['total_importe'];
                this.total_anticipo = contrato['total_anticipo'];
                this.importe_garantia = contrato['garantia_ret'];
                this.listado = 0;
                this.porc_garantia = contrato['porc_garantia_ret'] / 100;
                this.porc_anticipo = contrato['anticipo'] / 100;
                this.num_casas = contrato['num_casas'];
                this.aviso_id = (contrato['id']);
                this.contratista = contrato['contratista'];
                this.getPartidas(contrato['id']);
            },
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'nuevo':
                    {
                        this.modal = 1;
                        this.tituloModal='Asignar Partidas';
                        this.total_importe=0;
                        this.arrayContratos=[];
                        this.porcentaje_garantia=0;
                        this.garantia_ret=0;
                        
                        break;
                    }
                    case 'anticipo':
                    {
                        this.modal1 = 1;
                        this.tipoAccion = 1;
                        this.tituloModal = 'Añadir anticipo';
                        this.fecha_anticipo = '';
                        this.monto_anticipo = 0;
                        break;
                    }
                    case 'fg':
                    {
                        this.modal1 = 1;
                        this.tipoAccion = 2;
                        this.tituloModal = 'Fondo de garantia';
                        this.fg_cantidad = 0;
                        this.monto_fg = 0;
                        this.fecha_fg = '';
                        break;
                    }

                    case 'extra':
                    {
                        this.modal1 = 1;
                        this.tipoAccion = 4;
                        this.tituloModal = 'Obra extra';
                        this.importe = 0;
                        this.concepto = 0;
                        this.fecha_extra = '';
                        break;
                    }

                    case 'resumen':
                    {
                        this.selectFraccionamientos();
                        this.getEmpresa();
                        this.selectContratistas();
                        this.etapa = '';
                        this.arrayEtapas = [];
                        this.modal1 = 1;
                        this.tipoAccion = 3;
                        this.tituloModal = 'Resumen de estimaciones';
                        this.fraccionamiento = '';
                        this.contratista = '';
                        this.constructora = '';
                        break;
                    }

                    case 'reporte':
                    {
                        this.selectContratistas();
                        this.modal1 = 1;
                        this.tipoAccion = 5;
                        this.tituloModal = 'Reporte de finalización de obra';
                        this.mes = '';
                        this.contratista = '';
                        this.anio = '';
                        break;
                    }
                    case 'observacion':{
                        this.modal1 = 1;
                        this.tipoAccion = 6;
                        this.tituloModal = 'Reporte de finalización de obra';
                        this.observacion = '';
                        break;
                    }
                    
                }
            },
            finalizarEstimacion(id){
                let me = this

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
                        axios.put('/estimaciones/finalizarEstimacion',{ id }); 
                        me.indexEstimaciones(1)
                        Swal({
                            title: 'Hecho!',
                            text: 'Contrato finalizado correctamente',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })

            },
            storeEstimacion(data){
                let me = this;
                //Con axios se llama el metodo update de LoteController

                let amorEstimacion = me.total2*me.porc_anticipo;
                let totalGarantia = me.total2*me.porc_garantia;

                let totalPagar = me.total2 - (amorEstimacion + totalGarantia);
                let fecha_pago = this.fecha_pago;
                
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
                                    'fecha_pago' : fecha_pago
                                }); 
                                me.nueva = 0;
                                me.b_estimacion = '';
                                me.getPartidas(me.aviso_id);
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
                                }); 
                                me.editarEstimacion = 0;
                                me.b_estimacion = '';
                                me.getPartidas(me.aviso_id);
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
                        me.getPartidas(me.aviso_id);

                        axios.put('/estimaciones/updateImporTotal',{
                            'id' : me.aviso_id,
                            'total_importe' : me.total_importe,
                            'importe_garantia' : me.importe_garantia
                        }); 
                        me.nueva = 0;
                        me.cerrarModal();
                        me.getPartidas(me.aviso_id);
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
                this.edit2 = 0;
                
                this.getPartidas(this.aviso_id);
                this.total_impAux = 0;
            },
            storeAnticipos(){
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
                        axios.post('/estimaciones/storeAnticipo',{
                            'aviso_id' : me.aviso_id,
                            'monto_anticipo' : me.monto_anticipo,
                            'fecha_anticipo' : me.fecha_anticipo
                        }); 
                        me.nueva = 0;
                        me.cerrarModal();
                        me.getPartidas(me.aviso_id);
                        Swal({
                            title: 'Hecho!',
                            text: 'Anticipo guardado correctamente',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
            },
            storeFondoG(){
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
                        axios.post('/estimaciones/storeFG',{
                            'aviso_id' : me.aviso_id,
                            'cantidad' : me.fg_cantidad,
                            'monto_fg' : me.monto_fg,
                            'fecha_fg' : me.fecha_fg
                        }); 
                        me.nueva = 0;
                        me.cerrarModal();
                        me.getPartidas(me.aviso_id);
                        Swal({
                            title: 'Hecho!',
                            text: 'Fonddo de garantia guardado correctamente',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
            },
            storeImporteExtra(){
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
                        axios.post('/estimaciones/storeImporteExtra',{
                            'clave' : me.aviso_id,
                            'impExtra' : me.total_impAux,
                            'fechaExtra' : me.dateAux,
                        }); 
                        me.edit2 = 0;
                        me.cerrarModal();
                        me.getPartidas(me.aviso_id);
                        Swal({
                            title: 'Hecho!',
                            text: 'Fonddo de garantia guardado correctamente',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
            },
            storeConceptoExtra(){
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
                        axios.post('/estimaciones/storeConceptoExtra',{
                            'clave' : me.aviso_id,
                            'fecha' : me.fecha_extra,
                            'concepto' : me.concepto,
                            'importe' : me.importe
                        }); 
                        me.nueva = 0;
                        me.cerrarModal();
                        me.getPartidas(me.aviso_id);
                        Swal({
                            title: 'Hecho!',
                            text: 'Fonddo de garantia guardado correctamente',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
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
                        me.getPartidas(me.aviso_id);
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