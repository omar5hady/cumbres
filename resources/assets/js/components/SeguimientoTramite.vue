<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Seguimiento de Tramite

                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="ingresar-tab" data-toggle="tab" href="#ingresar" role="tab" aria-controls="ingresar" aria-selected="true" v-text="'Por Ingresar (' + contadorIngresar +')'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="autorizado-tab" data-toggle="tab" href="#autorizado" role="tab" aria-controls="autorizado" aria-selected="false" v-text="'Autorizados (' + contadorAutorizados +')'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="liquidacion-tab" data-toggle="tab" href="#liquidacion" role="tab" aria-controls="liquidacion" aria-selected="false" v-text="'Liquidación (' + contadorLiquidacion +')'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="programacion-tab" data-toggle="tab" href="#programacion" role="tab" aria-controls="programacion" aria-selected="false" v-text="'Programación de firma'"></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">

                            <!-- Listado por ingresar -->
                            <div class="tab-pane fade active show" id="ingresar" role="tabpanel" aria-labelledby="ingresar-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="contratos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Aviso preventivo</th>
                                                <th>Ingresar expediente</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Institución de Fin.</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Crédito Puente</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="ingresar in arrayPorIngresar" :key="ingresar.id"> 
                                                
                                                <td class="td2" v-text="ingresar.folio"></td>
                                                <td class="td2" v-text="ingresar.nombre_cliente"></td>
                                                <td class="td2" v-text="ingresar.nombre_vendedor"></td>
                                                <td class="td2" v-text="ingresar.proyecto"></td>
                                                <td class="td2" v-text="ingresar.etapa"></td>
                                                <td class="td2" v-text="ingresar.manzana"></td>
                                                <td class="td2" v-text="ingresar.num_lote"></td>
                                                <td class="td2" v-if="ingresar.interior" v-text="ingresar.calle + ' '+ ingresar.numero + ' '+ ingresar.interior"></td>
                                                <td class="td2" v-else v-text="ingresar.calle + ' '+ ingresar.numero"></td>
                                                <td class="td2" v-text="ingresar.avance_lote"></td>
                                                <td class="td2" v-text="ingresar.fecha_status"></td>

                                                <td v-if="ingresar.avaluo_preventivo!='0000-01-01'" class="td2" v-text="'$'+formatNumber(ingresar.resultado)"></td>
                                                <td v-if="ingresar.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>

                                                <td @dblclick="abrirModal('fecha_recibido',ingresar)" v-if="ingresar.aviso_prev!='0000-01-01' && !ingresar.aviso_prev_venc" class="td2" v-text="'Fecha solicitud: ' 
                                                + this.moment(ingresar.aviso_prev).locale('es').format('DD/MMM/YYYY')"></td>

                                                <td  @dblclick="abrirModal('fecha_recibido',ingresar)" v-if="ingresar.aviso_prev!='0000-01-01' && ingresar.aviso_prev_venc" class="td2">
                                                    
                                                    <span v-if = "ingresar.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "ingresar.diferencia < 0 && ingresar.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "ingresar.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>

                                            <td v-if="ingresar.aviso_prev=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                            <td>
                                                <button type="button" @click="abrirModal('ingresar',ingresar)" class="btn btn-primary btn-sm" title="Ingresar">
                                                    <i class="fa fa-send-o"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-text="ingresar.tipo_credito"></td>
                                            <td class="td2" v-text="ingresar.institucion"></td>
                                            <td class="td2" v-text="'$'+formatNumber(ingresar.precio_venta)"></td>
                                            <td class="td2" v-text="ingresar.credito_puente"></td>
                                            <td class="td2">
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal3(ingresar.folio)">Ver Observaciones</button>
                                            </td>
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                            </div>

                            <!-- Listado de autorizados -->
                            <div class="tab-pane fade" id="autorizado" role="tabpanel" aria-labelledby="autorizado-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio_preauto" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="contratos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio_preauto=='lotes.fraccionamiento_id'" v-model="buscar_preauto" @click="selectEtapa(buscar_preauto)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio_preauto=='lotes.fraccionamiento_id'" v-model="b_etapa_preauto"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio_preauto=='lotes.fraccionamiento_id'" v-model="b_manzana_preauto" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio_preauto=='lotes.fraccionamiento_id'" v-model="b_lote_preauto" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar_preauto" @keyup.enter="listarAutorizados(1,buscar_preauto,b_etapa_preauto,b_manzana_preauto,b_lote_preauto,criterio_preauto)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarAutorizados(1,buscar_preauto,b_etapa_preauto,b_manzana_preauto,b_lote_preauto,criterio_preauto)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Aviso preventivo</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Institución de Fin.</th>
                                                <th>Monto autorizado</th>
                                                <th>Fecha vigencia</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Valor escriturar</th>
                                                <th>Crédito Puente</th>
                                                <th>Inscripción Infonavit</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="preautorizados in arrayPreautorizados" :key="preautorizados.id"> 
                                                
                                                <td class="td2" v-text="preautorizados.folio"></td>
                                                <td class="td2" v-text="preautorizados.nombre_cliente"></td>
                                                <td class="td2" v-text="preautorizados.nombre_vendedor"></td>
                                                <td class="td2" v-text="preautorizados.proyecto"></td>
                                                <td class="td2" v-text="preautorizados.etapa"></td>
                                                <td class="td2" v-text="preautorizados.manzana"></td>
                                                <td class="td2" v-text="preautorizados.num_lote"></td>
                                                <td class="td2" v-text="preautorizados.calle + ' '+ preautorizados.numero + ' '+ preautorizados.interior"></td>
                                                <td class="td2" v-text="preautorizados.avance_lote"></td>
                                                <td class="td2" v-text="preautorizados.fecha_status"></td>

                                                <td v-if="preautorizados.avaluo_preventivo!='0000-01-01'" class="td2" v-text="'$'+formatNumber(preautorizados.resultado)"></td>
                                                <td v-if="preautorizados.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>

                                                <td @dblclick="abrirModal('fecha_recibido',ingresar)" v-if="preautorizados.aviso_prev!='0000-01-01' && !preautorizados.aviso_prev_venc" class="td2" v-text="'Fecha solicitud: ' 
                                                + this.moment(preautorizados.aviso_prev).locale('es').format('DD/MMM/YYYY')"></td>

                                                <td  @dblclick="abrirModal('fecha_recibido',ingresar)" v-if="preautorizados.aviso_prev!='0000-01-01' && preautorizados.aviso_prev_venc" class="td2">
                                                    
                                                    <span v-if = "preautorizados.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "preautorizados.diferencia < 0 && preautorizados.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "preautorizados.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>

                                                <td v-if="preautorizados.aviso_prev=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                <td class="td2" v-text="preautorizados.tipo_credito"></td>
                                                <td class="td2" v-text="preautorizados.institucion"></td>
                                                <td class="td2" v-text="'$'+formatNumber(preautorizados.credito_solic)"></td>

                                                <td class="td2">
                                                    
                                                    <span v-if = "preautorizados.vigencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "preautorizados.vigencia < 0 && preautorizados.vigencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "preautorizados.vigencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>
                                                
                                                <td class="td2" v-text="'$'+formatNumber(preautorizados.precio_venta)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(preautorizados.valor_escrituras)"></td>
                                                <td class="td2" v-text="preautorizados.credito_puente"></td>
                                               
                                               <template v-if="preautorizados.fecha_infonavit">
                                                    <td v-if="preautorizados.fecha_infonavit!='0000-01-01'" class="td2" v-text="this.moment(preautorizados.fecha_infonavit).locale('es').format('DD/MMM/YYYY')"></td>
                                                    <td v-if="preautorizados.fecha_infonavit=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                </template>
                                                <template v-else>
                                                    <td class="td2">
                                                        <button type="button" @click="abrirModal('autorizado',preautorizados)" class="btn btn-success btn-sm" title="Inscribir Infonavit">
                                                            <i class="fa fa-calendar-check-o"></i>
                                                        </button>
                                                        <button type="button" @click="noAplicaInfonavit(preautorizados.folio)" class="btn btn-danger btn-sm" title="No aplica">
                                                            <i class="fa fa-times-circle"></i>
                                                        </button>
                                                    </td>
                                                </template> 

                                                <td class="td2">
                                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal3(preautorizados.folio)">Ver Observaciones</button>
                                                </td>
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                            </div>

                            <!-- Listado de liquidacion -->
                            <div class="tab-pane fade" id="liquidacion" role="tabpanel" aria-labelledby="liquidacion-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="g.nombre">Gestor</option>
                                                <option value="contraos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Aviso preventivo</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Institución de Fin.</th>
                                                <th>Monto autorizado</th>
                                                <th>Fecha vigencia</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Valor escriturar</th>
                                                <th>Crédito Puente</th>
                                                <th>Inscripción Infonavit</th>
                                                <th>Liquidación</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="liquidacion in arrayLiquidados" :key="liquidacion.id"> 
                                                
                                                <td class="td2" v-text="liquidacion.folio"></td>
                                                <td class="td2" v-text="liquidacion.nombre_cliente"></td>
                                                <td class="td2" v-text="liquidacion.nombre_vendedor"></td>
                                                <td class="td2" v-text="liquidacion.proyecto"></td>
                                                <td class="td2" v-text="liquidacion.etapa"></td>
                                                <td class="td2" v-text="liquidacion.manzana"></td>
                                                <td class="td2" v-text="liquidacion.num_lote"></td>
                                                <td class="td2" v-text="liquidacion.calle + ' '+ liquidacion.numero + ' '+ liquidacion.interior"></td>
                                                <td class="td2" v-text="liquidacion.avance_lote"></td>
                                                <td class="td2" v-text="liquidacion.fecha_status"></td>

                                                <td v-if="liquidacion.avaluo_preventivo!='0000-01-01'" class="td2" v-text="'$'+formatNumber(liquidacion.resultado)"></td>
                                                <td v-if="liquidacion.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>

                                                <td @dblclick="abrirModal('fecha_recibido',ingresar)" v-if="liquidacion.aviso_prev!='0000-01-01' && !liquidacion.aviso_prev_venc" class="td2" v-text="'Fecha solicitud: ' 
                                                + this.moment(liquidacion.aviso_prev).locale('es').format('DD/MMM/YYYY')"></td>

                                                <td  @dblclick="abrirModal('fecha_recibido',ingresar)" v-if="liquidacion.aviso_prev!='0000-01-01' && liquidacion.aviso_prev_venc" class="td2">
                                                    
                                                    <span v-if = "liquidacion.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "liquidacion.diferencia < 0 && liquidacion.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "liquidacion.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>

                                                <td v-if="liquidacion.aviso_prev=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                <td class="td2" v-text="liquidacion.tipo_credito"></td>
                                                <td class="td2" v-text="liquidacion.institucion"></td>
                                                <td class="td2" v-text="'$'+formatNumber(liquidacion.credito_solic)"></td>

                                                <td class="td2">
                                                    
                                                    <span v-if = "liquidacion.vigencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "liquidacion.vigencia < 0 && liquidacion.vigencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "liquidacion.vigencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>
                                                
                                                <td class="td2" v-text="'$'+formatNumber(liquidacion.precio_venta)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(liquidacion.valor_escrituras)"></td>
                                                <td class="td2" v-text="liquidacion.credito_puente"></td>
                                               
                                               <template v-if="liquidacion.fecha_infonavit">
                                                    <td v-if="liquidacion.fecha_infonavit!='0000-01-01'" class="td2" v-text="this.moment(liquidacion.fecha_infonavit).locale('es').format('DD/MMM/YYYY')"></td>
                                                    <td v-if="liquidacion.fecha_infonavit=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                </template>
                                                <td class="td2">
                                                    <button v-if="liquidacion.fecha_liquidacion == 'NULL'" title="Generar liquidación" type="button" class="btn btn-danger pull-right" 
                                                        @click="abrirModal('liquidacion',liquidacion)">Generar</button>
                                                    
                                                    <button v-if="liquidacion.liquidado == 0 && liquidacion.fecha_liquidacion != 'NULL'" title="Intereses" type="button" class="btn btn-danger pull-right" 
                                                        @click="abrirModal('intereses',liquidacion)">Generar intereses</button>
            
                                                </td>
                                                
                                                <td class="td2">
                                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal3(liquidacion.folio)">Ver Observaciones</button>
                                                </td>
                                            </tr>                               
                                        </tbody>
                                    </table>   
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                            <!-- Listado de programacion de firma -->
                            <div class="tab-pane fade" id="programacion" role="tabpanel" aria-labelledby="programacion-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="g.nombre">Gestor</option>
                                                <option value="contraos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Avaluo Catastral</th>
                                                <th>Aviso preventivo</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Aviso preventivo</th>
                                                <th>Institución</th>
                                                <th>Monto autorizado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="programacion in arrayProgramacion" :key="programacion.id" @dblclick="abrirModal('gestor',programacion)" > 
                                                
                                                <td class="td2" v-text="programacion.folio"></td>
                                                <td class="td2" v-text="programacion.nombre_cliente"></td>
                                                <td class="td2" v-text="programacion.proyecto"></td>
                                                <td class="td2" v-text="programacion.etapa"></td>
                                                <td class="td2" v-text="programacion.manzana"></td>
                                                <td class="td2" v-text="programacion.num_lote"></td>
                                                <td class="td2" v-text="programacion.nombre_gestor"></td>
                                                <td class="td2" v-text="programacion.tipo_credito"></td>
                                                <td class="td2" v-text="programacion.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                        </div>
                        
                        
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio modal-->
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
                            <!-- form para captura de fecha recibido -->
                            <form v-if="tipoAccion == 2" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                        <div class="col-md-4">
                                            <input type="date"  v-model="fecha_ingreso" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Valor a escriturar</label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="10" v-model="valor_escrituras" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Monto">
                                        </div>
                                        <div class="col-md-3">
                                            <h6 v-text="'$'+formatNumber(valor_escrituras)"></h6>
                                        </div>

                                    </div>

                                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                                    
                                    
                            </form>
                           

                            <!-- form para captura de fecha recibido -->
                            <form v-if="tipoAccion == 3" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de recibido</label>
                                    <div class="col-md-4">
                                        <input type="date"  v-model="fecha_recibido" class="form-control" >
                                    </div>
                                </div>

                            </form>

                            <!-- form para captura de fecha recibido -->
                            <form v-if="tipoAccion == 4" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inscripción</label>
                                    <div class="col-md-4">
                                        <input type="date"  v-model="fecha_infonavit" class="form-control" >
                                    </div>
                                </div>

                                 <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                            </form>
                            

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="enviarIngreso()">Ingresar</button>
                            <button type="button" v-if="tipoAccion==4" class="btn btn-primary" @click="inscribirInfonavit()">Inscribir</button>
                            <!-- <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="fechaRecibido()">Enviar</button> -->
                            <a v-bind:href="'/expediente/solicitudPDF/' + id" v-if="tipoAccion==3" type="button" target="_blank" class="btn btn-primary">Imprimir</a>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal -->

              <!--Inicio modal Liquidación-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para captura de fecha recibido -->
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Cliente</label>
                                        <div class="col-md-6">
                                            <input type="text" disabled v-model="cliente" class="form-control" placeholder="Cliente" >
                                        </div>
                                        <label class="col-md-1 form-control-label" for="text-input">Fecha</label>
                                        <div class="col-md-3">
                                            <input type="date" v-model="fecha_liquidacion" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Tipo de Credito</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="credito" class="form-control" >
                                        </div>
                                        <label class="col-md-3 form-control-label" for="text-input">Inst. Financiamiento</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="inst_fin" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="proyecto" class="form-control" >
                                        </div>

                                        <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="etapa" class="form-control">
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="manzana" class="form-control" >
                                        </div>

                                        <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                        <div class="col-md-2">
                                            <input type="text" disabled v-model="lote" class="form-control">
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                       
                                        <label class="col-md-2 form-control-label" for="text-input">Fecha firma de contrato</label>
                                        <div class="col-md-3">
                                            <input type="date" disabled v-model="fecha_firma_contrato" class="form-control">
                                        </div>

                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Valor de venta</label>
                                        <div class="col-md-3">
                                            <h6 v-text="'$'+formatNumber(valor_venta)"></h6>
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Valor de escrituración</label>
                                        <div class="col-md-2">
                                            <input maxlength="10" v-model="valor_escrituras" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(valor_escrituras)"></h6>
                                        </div>

                                    </div>

                                    <div v-if="arrayGastos.length">
                                     <div class="form-group row"  v-for="gasto in arrayGastos" :key="gasto.id">
                                        <label class="col-md-2 form-control-label" for="text-input" v-text="gasto.concepto"></label>
                                        <div class="col-md-3">
                                            <h6>${{ formatNumber(gasto.costo)}}</h6>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Enganche</label>
                                        <div class="col-md-3">
                                            <h6 v-text="'$'+formatNumber(totalEnganghe)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Pagado</label>
                                        <div class="col-md-3">
                                            <h6 v-text="'$'+formatNumber(pagado=totalEnganghe-totalRestante)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Descuento</label>
                                        <div class="col-md-2">
                                            <input maxlength="10" v-model="descuento" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(descuento)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Credito autorizado</label>
                                        <div class="col-md-3">
                                            <h6>${{ formatNumber(netoCredito)}}</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="credito=='Alia2' || credito=='Respalda2'">
                                        <label  class="col-md-2 form-control-label" for="text-input">Fovissste</label>
                                        <div class="col-md-2">
                                            <input type="text" pattern="\d*" v-model="fovissste" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                        </div>
                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(fovissste)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="credito=='Cofinavit'">
                                        <label  class="col-md-2 form-control-label" for="text-input">Infonavit</label>
                                        <div class="col-md-2">
                                            <input type="text" pattern="\d*" v-model="infonavit" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                        </div>
                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(infonavit)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong> Total a liquidar </strong></label>
                                        <div class="col-md-3">
                                            <h6><strong> ${{ formatNumber(total_liquidar=totalLiquidar)}} </strong></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="col-md-12" v-if="arrayPagares.length">
                                        <div class="form-group">
                                            <center> <h5>Pagares pendientes</h5> </center>
                                        </div>
                                    </div>  

                                    <div class="col-md-12">
                                        <div class="form-group row" v-if="arrayPagares.length">
                                            <div class="table-responsive col-md-12">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th># Pago</th>
                                                            <th>Fecha de pago</th>
                                                            <th>Monto</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        <tr v-for="(pago,index) in arrayPagares" :key="pago.fecha_pago">
                                                            <td v-text="'Pago no. ' + parseInt(index+1)"></td>
                                                            <td v-text="this.moment(pago.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                                            <td>
                                                                {{ pago.monto_pago | currency}}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> 

                                    <!--<div class="form-group row line-separator" v-if="total_liquidar != 0"></div>

                                    <div class="form-group row" v-if="total_liquidar != 0">
                                        <label class="col-md-2 form-control-label" for="text-input">Fecha a liquidar</label>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" v-model="fecha_pagarefin">
                                        </div>

                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(total_liquidar)"></h6>
                                        </div>

                                    </div>-->



                                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorLiquidacion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLiquidacion" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                                
                                    
                                    
                            </form>
                            

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="generarLiquidacion()">Generar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal -->

             <!--Inicio modal Intereses (Pagares)-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal4}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal4()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para captura de fecha recibido -->
                            <form enctype="multipart/form-data" class="form-horizontal">

                                    <div class="form-group row line-separator"></div>

                                   
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong> Total a liquidar </strong></label>
                                        <div class="col-md-3">
                                            <h6><strong> ${{ formatNumber(total_liquidar1)}} </strong></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Intereses Ordinarios (%)</label>
                                        <div class="col-md-2">
                                            <input type="text" maxlength="5" v-model="int_oridinario" class="form-control" placeholder="%" >
                                        </div>
                                        <label class="col-md-4 form-control-label" for="text-input">Fecha de inicio de intereses</label>
                                        <div class="col-md-3">
                                            <input type="date" pattern="\d*" maxlength="3" v-on:keypress="isNumber($event)" v-model="fecha_ini_interes" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Intereses Moratorios (%)</label>
                                        <div class="col-md-2">
                                            <input type="text" pattern="\d*" maxlength="3" v-on:keypress="isNumber($event)" v-model="int_moratorio" class="form-control" placeholder="%" >
                                        </div>
                                    </div>
                                    

                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <div class="col-md-12">  
                                            <center> <h5>Pagares</h5> </center>
                                        </div>
                                    </div>  

                                <div class="form-group row" v-if="fecha_pago!=''">
                                    <div class="col-md-12">
                                             <h6 style="text-align: right;">Restante: </h6>
                                             <h4 style="text-align: right;"><strong>${{ formatNumber(restante_pago)}}</strong></h4>
                                    </div> 
                                </div>

                                
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha del pago</label>
                                        <div class="col-md-3">
                                            <input @change="mostrarRestante()" type="date" v-model="fecha_pago" class="form-control" >
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <h6 style="color:#2271b3;" ><strong> Dias </strong></h6>
                                            <h6><strong>{{ dias = dias}}</strong></h6>
                                        </div>
                                         <div class="col-md-3">
                                            <h6 style="color:#2271b3;" ><strong> Intereses </strong></h6>
                                            <h6><strong>${{ formatNumber(interes= interes)}}</strong></h6>
                                        </div> -->
                                </div>

                                <div class="form-group row" v-if="fecha_pago!=''">
                                    <label class="col-md-2 form-control-label" for="text-input">Monto pago</label>
                                        <div class="col-md-3">
                                            <input  type="text" pattern="\d*" v-model="monto_pago" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                        </div>
                                        <div class="col-md-4">
                                            <h6 style="color:#2271b3;" ><strong> Monto pago </strong></h6>
                                            <h6><strong>${{ formatNumber(monto_pago)}}</strong></h6>
                                        </div>
                                </div> 

                        
                                <div class="form-group row" v-if="restante_pago>0">
                                    <div class="col-md-1" v-if="monto_pago!=''">
                                            <button @click="agregarPago()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group row" v-if="arrayPagos.length">
                                        <div class="table-responsive col-md-12">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Opciones</th>
                                                        <th># Pago</th>
                                                        <th>Fecha de pago</th>
                                                        <th>Dias</th>
                                                        <th>Pendiente + Intereses</th>
                                                        <th>Monto</th>
                                                        <th>Restante</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <tr v-for="(pago,index) in arrayPagos" :key="pago.fecha_pago">
                                                        <td>
                                                            <button @click="eliminarPago(index)" type="button" class="btn btn-danger btn-sm">
                                                                <i class="icon-close"></i>
                                                            </button>
                                                           
                                                        </td>
                                                        <td v-text="'Pago no. ' + parseInt(index+1)"></td>
                                                        <td v-text="this.moment(pago.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                                        <td v-text="pago.dias">
                                                            
                                                        </td>
                                                          <td>
                                                            {{pago.restanteAnterior | currency}}
                                                        </td>
                                                        <td>
                                                            {{ pago.monto_pago | currency}}
                                                        </td>
                                                        <td>
                                                            {{pago.restante | currency}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> 

                                    <div class="form-group row line-separator"></div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Aval</h5> </center>
                                        </div>
                                    </div>  

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="nombre_aval" class="form-control" placeholder="Nombre" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="direccion_aval" class="form-control" placeholder="Direccion" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                        <div class="col-md-6">
                                            <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" v-model="telefono_aval" class="form-control" placeholder="Telefono" >
                                        </div>
                                    </div>

                                 
                                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorLiquidacion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLiquidacion" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
 
                                    
                            </form>
                            

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal4()">Cerrar</button>
                            <button v-if="restante_pago == 0" type="button" class="btn btn-primary" @click="generarPagare()">Generar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal -->

            <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered table-striped table-sm" >
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Observacion</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                                            
                                            <td v-text="observacion.usuario" ></td>
                                            <td v-text="observacion.observacion" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
         
     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{
                arrayPreautorizados: [],
                arrayRechazados: [],
                arrayAutorizados: [],
                arrayLiquidados: [],
                arrayProgramacion: [],
                arrayPorIngresar:[],
                arrayObservacion:[],
                arrayPagares:[],
                totalEnganghe:[],
                totalRestante:[],

                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayGastos:[],

                arrayPagos: [],

                errorIngreso:0,
                errorMostrarMsjIngreso:[],

                errorLiquidacion: 0,
                errorMostrarMsjLiquidacion: [],
                
                //variables para filtros de Por ingresar
                criterio:'lotes.fraccionamiento_id',
                buscar:'',
                b_etapa:'',
                b_manzana:'',
                b_lote:'',

                //variables para filtros de Preautorizados
                criterio_preauto:'lotes.fraccionamiento_id',
                buscar_preauto:'',
                b_etapa_preauto:'',
                b_manzana_preauto:'',
                b_lote_preauto:'',

                //variables para filtros de Rechazados
                criterio_rechazados:'lotes.fraccionamiento_id',
                buscar_rechazados:'',
                b_etapa_rechazados:'',
                b_manzana_rechazados:'',
                b_lote_rechazados:'',

                fecha_recibido:'',
                id:0,
                tipoAccion: 0,
                fecha_ingreso:'',
                valor_escrituras:'',
                valor_venta:'',
                totalGastos:0,

                fecha_infonavit:'',
                fecha_liquidacion:'',
                fecha_firma_contrato:'',
                fecha_pagarefin:'',
                monto_pagare:0,
                cliente:'',
                credito:'',
                inst_fin:'',
                proyecto:'',
                etapa:'',
                manzana:'',
                lote:'',
                descuento:0,
                pagado:0,
                monto_credito:0,
                infonavit:0,
                fovissste:0,
                avaluo:0,
                total_liquidar:0,
                total_liquidar1:0,

                int_oridinario:0,
                int_moratorio:0,
                fecha_ini_interes: '',
                fecha_pago: '',
                monto_pago: 0,
                restante_pago: 0,
                nombre_aval: '',
                direccion_aval: '',
                telefono_aval: '',
                dias: 0,
                interes: 0,


                modal:0,
                modal2:0,
                modal3 :0,
                modal4:0,
                tituloModal:'',
                tituloModal3:'Observaciones',
                observacion:'',
                contadorIngresar : 0,
                contadorAutorizados : 0,
                contadorLiquidacion : 0,
                
               
            }
        },
        computed:{
            totalLiquidar: function(){
                var neto_credito =0;
                    neto_credito = parseFloat(this.valor_venta) - parseFloat(this.descuento) + parseFloat(this.totalGastos) - parseFloat(this.monto_credito) - 
                    parseFloat(this.infonavit) - parseFloat(this.fovissste) - parseFloat(this.pagado); 
                return neto_credito;
            },

             netoCredito: function(){
                var total =0;
                    total = parseFloat(this.infonavit) + parseFloat(this.fovissste) + parseFloat(this.monto_credito); 
                return total;
            },

        },

        
        methods : {
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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

            listarObservacion(buscar){
                let me = this;
                var url = '/observacionExpediente?folio=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
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

            selectEtapa(buscar){
                let me = this;
                me.buscar2=""
                
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

            agregarComentario(){
                if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/observacionExpediente/registrar',{
                    'folio': this.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.proceso=false;
                    me.listarObservacion(me.id);
                    me.observacion = '';
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal
                    
                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            mostrarRestante(){
                if(this.arrayPagos.length == 0){
                var Restante = this.total_liquidar1;
                var a = moment(this.fecha_pago);
                var b = moment(this.fecha_ini_interes);
                this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                var intereses = (this.int_oridinario / 100) * (this.dias/30) * (this.total_liquidar1);
                this.interes = Math.round(intereses*100)/100;
                
                
                Restante += this.interes;
                Restante = Math.round(Restante*100)/100;
                this.restante_pago = Restante;

                }else{
                    var b = this.arrayPagos[this.arrayPagos.length-1].fecha_pago;
                    var a = moment(this.fecha_pago);
                    this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                    var Restante =this.restante_pago;
                    var intereses = (this.int_oridinario / 100) * (this.dias/30) * (Restante);
                    this.interes = Math.round(intereses*100)/100;
                   
                    Restante += this.interes;
                    this.restante_pago = Restante;

                }
            },

            calcularRestante(){
                if(this.arrayPagos.length == 0){
                var Restante = this.total_liquidar1;
                var a = moment(this.fecha_pago);
                var b = moment(this.fecha_ini_interes);
                this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                var intereses = (this.int_oridinario / 100) * (this.dias/30) * (this.total_liquidar1);
                this.interes = Math.round(intereses*100)/100;
                
                
                Restante += this.interes;
                Restante = Math.round(Restante*100)/100;
                // this.restante_pago = Restante;
                return Restante;

                }else{
                    var b = this.arrayPagos[this.arrayPagos.length-1].fecha_pago;
                    var a = moment(this.fecha_pago);
                    this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                    var Restante =this.restante_pago;
                    
                    Restante = Math.round(Restante*100)/100;
                    return Restante;

                }
            },

            agregarPago(){
                let me = this;
                if(me.monto_pago == 0 || me.monto_pago=='' || me.fecha_pago==''){

                }else{
                    if(me.monto_pago > me.restante_pago){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'El monto supera al restante',
                        });
                        }
                    if(me.encuentraFecha(me.fecha_pago)){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'La fecha de pago ya se enceuntra o es menor',
                        })
                    }
                    else{
                    me.restante_pago = me.calcularRestante();
                    me.arrayPagos.push({
                    monto_pago: me.monto_pago,
                    fecha_pago: me.fecha_pago,
                    restanteAnterior: me.restante_pago,
                    dias: me.dias,
                    restante: me.restante_pago - me.monto_pago,
                    });
                    me.restante_pago -= me.monto_pago;
                  
                   me.monto_pago = 0;


                    }
                }

            },

            eliminarPago(index){
                let me = this;      
                me.arrayPagos.splice(index,1);

                    if(index != 0 ){
                    for(var i=0;i<me.arrayPagos.length;i++){
                    var b = me.arrayPagos[i].fecha_pago;
                    var a = moment(me.arrayPagos[i+1].fecha_pago);
                    me.arrayPagos[i+1].dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                    var Restante = me.arrayPagos[i].restante;
                    var intereses = (me.int_oridinario / 100) * (me.arrayPagos[i+1].dias/30) * (Restante);
                    var interes1 = Math.round(intereses*100)/100;
                    Restante += interes1;
                    Restante = Math.round(Restante*100)/100;
                    me.arrayPagos[i+1].restanteAnterior = Restante;
                    me.arrayPagos[i+1].restante = me.arrayPagos[i+1].restanteAnterior - me.arrayPagos[i+1].monto_pago;
                    me.restante_pago =  me.arrayPagos[i+1].restante;
                    }
                    }else{
                    var b = me.fecha_ini_interes
                    var a = moment(me.arrayPagos[0].fecha_pago);
                    me.arrayPagos[0].dias = a.diff(b, 'days');
                    var Restante = me.total_liquidar1;
                    var intereses = (me.int_oridinario / 100) * (me.arrayPagos[0].dias/30) * (Restante);
                    var interes1 = Math.round(intereses*100)/100;
                    Restante += interes1;
                    Restante = Math.round(Restante*100)/100;
                    me.arrayPagos[0].restanteAnterior = Restante;
                    me.arrayPagos[0].restante = me.arrayPagos[0].restanteAnterior - me.arrayPagos[0].monto_pago;
                    
                    
                    for(var i=0;i<me.arrayPagos.length;i++){
                        var b = me.arrayPagos[i].fecha_pago;
                        var a = moment(me.arrayPagos[i+1].fecha_pago);
                        me.arrayPagos[i+1].dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                        var Restante = me.arrayPagos[i].restante;
                        var intereses = (me.int_oridinario / 100) * (me.arrayPagos[i+1].dias/30) * (Restante);
                        var interes1 = Math.round(intereses*100)/100;
                        Restante += interes1;
                        Restante = Math.round(Restante*100)/100;
                        me.arrayPagos[i+1].restanteAnterior = Restante;
                        me.arrayPagos[i+1].restante = me.arrayPagos[i+1].restanteAnterior - me.arrayPagos[i+1].monto_pago;
                        me.restante_pago =  me.arrayPagos[i+1].restante;
                    }
                }
           
            },

             encuentraFecha(fecha){
                var sw=0;
                for(var i=0;i<this.arrayPagos.length;i++)
                {
                    if(this.arrayPagos[i].fecha_pago == fecha || this.arrayPagos[i].fecha_pago > fecha)
                    {
                        sw=true;
                    }

                }

                return sw;
            },

            enviarIngreso(){
                if(this.validarIngreso()){
                    return;
                }
                
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/expediente/ingresarExp',{
                    'folio':this.id,
                    'fecha_ingreso' : this.fecha_ingreso,
                    'valor_escrituras' : this.valor_escrituras,
                    
                }).then(function (response){
                   
                    me.cerrarModal();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Expediente ingresado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            generarLiquidacion(){
                if(this.validarLiquidacion()){
                    return;
                }
                
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/expediente/generarLiquidacion',{
                    'folio':this.id,
                    'fecha_liquidacion' : this.fecha_liquidacion,
                    'valor_escrituras' : this.valor_escrituras,
                    'descuento' : this.descuento,
                    'total_liquidar' : this.total_liquidar,
                    'infonavit' : this.infonavit,
                    'fovissste': this.fovissste
                    
                }).then(function (response){
                   
                    me.cerrarModal();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Liquidacion generada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            generarPagare(){
                // if(this.validarPagares()){
                //     return;
                // }
                
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/expediente/generarPagares',{
                    'folio':this.id,
                    'intereses_ordinarios' : this.int_oridinario,
                    'intereses_moratorios' : this.int_moratorio,
                    'fecha_ini_interes' : this.fecha_ini_interes,
                    'nombre_aval' : this.nombre_aval,
                    'direccion_aval' : this.direccion_aval,
                    'telefono_aval': this.telefono_aval,
                    'pagares' : this.arrayPagos
                    
                }).then(function (response){
                   
                    me.cerrarModal4();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Pagares y liquidacion generados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            inscribirInfonavit(){
                if(this.validarInscripcion()){
                    return;
                }
                
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/expediente/inscInfonavit',{
                    'folio':this.id,
                    'fecha_infonavit' : this.fecha_infonavit,
                    
                }).then(function (response){
                   
                    me.cerrarModal();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Expediente ingresado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            listarIngresoExp(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/ingresarIndex?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPorIngresar = respuesta.contratos;
                    me.contadorIngresar = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            listarAutorizados(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/autorizadosIndex?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPreautorizados = respuesta.contratos;
                    me.contadorAutorizados = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            listarLiquidacion(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/liquidacionIndex?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLiquidados = respuesta.contratos;
                    me.contadorLiquidacion = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            mostrarPagares(){
                let me = this;
                var url = '/expediente/pagaresExpediente?folio=' + this.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPagares = respuesta.pagares;
                    me.totalEnganghe = respuesta.calculos[0].enganche;
                    me.totalRestante = respuesta.calculos[0].total_restante;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarGastos(){
                let me = this;
                var url = '/expediente/gastosExpediente?folio=' + this.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGastos = respuesta.gastos;
                    me.totalGastos = respuesta.totalGastos[0].sumGasto;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            validarIngreso(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso=[];

                if(this.fecha_ingreso== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjIngreso.push("Ingresar una fecha.");

                if(this.valor_escrituras== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjIngreso.push("Ingresar el valor a escriturar.");
                              
                if(this.errorMostrarMsjIngreso.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorIngreso = 1;

                return this.errorIngreso;
            },

            validarInscripcion(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso=[];

                if(this.fecha_infonavit== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjIngreso.push("Ingresar la fecha de inscripción.");
                              
                if(this.errorMostrarMsjIngreso.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorIngreso = 1;

                return this.errorIngreso;
            },

            validarLiquidacion(){
                this.errorLiquidacion=0;
                this.errorMostrarMsjLiquidacion=[];

                if(this.fecha_liquidacion== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjLiquidacion.push("Ingresar una fecha.");

                if(this.valor_escrituras== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjLiquidacion.push("Ingresar el valor a escriturar.");

                if(this.descuento== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjLiquidacion.push("Ingresar descuento.");
                              
                if(this.errorMostrarMsjLiquidacion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLiquidacion = 1;

                return this.errorLiquidacion;
            },

            noAplicaInfonavit(id){
                this.id = id;
                swal({
                title: '¿Esta seguro de que la inscripción de Infonavit no aplica para este registro?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/expediente/InfonavitNoAplica',{
                        'folio': me.id
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        swal(
                        'Hecho!',
                        'No aplica.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },

            abrirModal3(folio){
                this.modal3 =1;
                this.tituloModal3='Observaciones';
                this.observacion='';
                this.usuario='';
                this.id=folio;
                this.listarObservacion(folio);
            },

            abrirModal(accion,data =[]){
               
                switch(accion){
                    case 'fecha_recibido': 
                    {
                        this.modal = 1;
                        this.tituloModal='Fecha recibido';
                        this.tipoAccion = 3;
                        this.fecha_recibido = data['aviso_prev_venc'];
                        this.id = data['folio'];
                        break;
                    }
                    case 'ingresar': 
                    {
                        this.modal = 1;
                        this.tituloModal='Ingresar expediente';
                        this.tipoAccion = 2;
                        this.fecha_ingreso = '';
                        this.valor_escrituras= '0';
                        this.id = data['folio'];
                        break;
                    }

                    case 'autorizado': 
                    {
                        this.modal = 1;
                        this.tituloModal='Inscripción a Infonavit';
                        this.tipoAccion = 4;
                        this.fecha_infonavit = '';
                        this.id = data['folio'];
                        break;
                    }

                    case 'liquidacion': 
                    {
                        this.modal2 = 1;
                        this.tituloModal='Generar Liquidación';
                        this.id = data['folio'];
                        this.fecha_firma_contrato=data['fecha_status'];
                        this.cliente=data['nombre_cliente'];
                        this.credito=data['tipo_credito'];
                        this.inst_fin=data['institucion'];
                        this.proyecto=data['proyecto'];
                        this.etapa=data['etapa'];
                        this.manzana=data['manzana'];
                        this.lote=data['num_lote'];
                        this.valor_escrituras = data['valor_escrituras'];
                        this.valor_venta = data['precio_venta'];
                        this.descuento = 0;
                        this.totalEnganghe=0;
                        this.pagado=0;
                        this.monto_credito = data['credito_solic'];
                        this.infonavit = data['infonavit'];
                        this.fovissste = data['fovisste'];
                        this.avaluo = data['resultado'];

                        this.mostrarPagares();
                        this.listarGastos();
                        break;
                    }

                    case 'intereses': 
                    {
                        this.modal4 = 1;
                        this.tituloModal='Intereses';
                        this.id = data['folio'];
                        this.total_liquidar1=data['total_liquidar'];
                        this.int_oridinario = 5;
                        this.int_moratorio = 5;
                        this.fecha_ini_interes = '';
                        this.fecha_pago = '';
                        this.nombre_aval = '';
                        this.direccion_aval = '';
                        this.telefono_aval = '';
                        this.arrayPagos = [];
                        this.restante_pago=this.total_liquidar1;

                        break;
                    }
                }

            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fecha_ingreso='';
                this.valor_escrituras='0';
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso=[];
                this.errorLiquidacion=0;
                this.errorMostrarMsjLiquidacion=[];

                this.modal2 = 0;
                
            },

            cerrarModal4(){
                this.tituloModal = '';
                this.modal4 = 0;                
            },

            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
                this.usuario = '';
                this.observacion = '';
            },

        
        },
       
        mounted() {
            this.selectFraccionamientos();
            this.listarIngresoExp(1, this.buscar, this.b_etapa, this.b_manzana, this.b_lote, this.criterio);
            this.listarAutorizados(1, this.buscar_preauto, this.b_etapa_preauto, this.b_manzana_preauto, this.b_lote_preauto, this.criterio_preauto);
            this.listarLiquidacion(1, this.buscar_preauto, this.b_etapa_preauto, this.b_manzana_preauto, this.b_lote_preauto, this.criterio_preauto);
            
        }
    }
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }


    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 0.85rem;
    color: #27417b;
    }
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
         overflow-y: auto;
        
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

    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 90%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
}

 @media (min-width: 600px){
  .btnagregar{
        margin-top: 2rem;
        }
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
</style>
