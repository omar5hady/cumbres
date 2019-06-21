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
                                <a class="nav-link active show" id="ingresar-tab" data-toggle="tab" href="#ingresar" role="tab" aria-controls="ingresar" aria-selected="true" v-text="'Por Ingresar'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="preautorizados-tab" data-toggle="tab" href="#preautorizados" role="tab" aria-controls="preautorizados" aria-selected="true" v-text="'Preautorizados'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="rechazados-tab" data-toggle="tab" href="#rechazados" role="tab" aria-controls="rechazados" aria-selected="false" v-text="'Rechazados'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="autorizado-tab" data-toggle="tab" href="#autorizado" role="tab" aria-controls="autorizado" aria-selected="false" v-text="'Autorizados'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="liquidacion-tab" data-toggle="tab" href="#liquidacion" role="tab" aria-controls="liquidacion" aria-selected="false" v-text="'Liquidación'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="programacion-tab" data-toggle="tab" href="#programacion" role="tab" aria-controls="programacion" aria-selected="false" v-text="'Programación de firma'"></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">
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
                                            <tr v-for="ingresar in arrayPorIngresar" :key="ingresar.id" @dblclick="abrirModal('gestor',ingresar)" > 
                                                
                                                <td class="td2" v-text="ingresar.folio"></td>
                                                <td class="td2" v-text="ingresar.nombre_cliente"></td>
                                                <td class="td2" v-text="ingresar.nombre_vendedor"></td>
                                                <td class="td2" v-text="ingresar.proyecto"></td>
                                                <td class="td2" v-text="ingresar.etapa"></td>
                                                <td class="td2" v-text="ingresar.manzana"></td>
                                                <td class="td2" v-text="ingresar.num_lote"></td>
                                                <td class="td2" v-text="ingresar.calle + ' '+ ingresar.numero + ' '+ ingresar.interior"></td>
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
                                <nav>
                                
                                </nav>
                            </div>

                            <div class="tab-pane fade" id="preautorizados" role="tabpanel" aria-labelledby="preautorizados-tab">
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
                                            <tr v-for="preautorizados in arrayPreautorizados" :key="preautorizados.id" @dblclick="abrirModal('gestor',preautorizados)" > 
                                                
                                                <td class="td2" v-text="preautorizados.folio"></td>
                                                <td class="td2" v-text="preautorizados.nombre_cliente"></td>
                                                <td class="td2" v-text="preautorizados.proyecto"></td>
                                                <td class="td2" v-text="preautorizados.etapa"></td>
                                                <td class="td2" v-text="preautorizados.manzana"></td>
                                                <td class="td2" v-text="preautorizados.num_lote"></td>
                                                <td class="td2" v-text="preautorizados.nombre_gestor"></td>
                                                <td class="td2" v-text="preautorizados.tipo_credito"></td>
                                                <td class="td2" v-text="preautorizados.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                            <div class="tab-pane fade" id="rechazados" role="tabpanel" aria-labelledby="rechazados-tab">
                                Aqui puro rechazado
                            </div>

                            <div class="tab-pane fade" id="autorizado" role="tabpanel" aria-labelledby="autorizado-tab">
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
                                            <tr v-for="autorizados in arrayAutorizados" :key="autorizados.id" @dblclick="abrirModal('gestor',autorizados)" > 
                                                
                                                <td class="td2" v-text="autorizados.folio"></td>
                                                <td class="td2" v-text="autorizados.nombre_cliente"></td>
                                                <td class="td2" v-text="autorizados.proyecto"></td>
                                                <td class="td2" v-text="autorizados.etapa"></td>
                                                <td class="td2" v-text="autorizados.manzana"></td>
                                                <td class="td2" v-text="autorizados.num_lote"></td>
                                                <td class="td2" v-text="autorizados.nombre_gestor"></td>
                                                <td class="td2" v-text="autorizados.tipo_credito"></td>
                                                <td class="td2" v-text="autorizados.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

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
                                            <tr v-for="liquidados in arrayLiquidados" :key="liquidados.id" @dblclick="abrirModal('gestor',liquidados)" > 
                                                
                                                <td class="td2" v-text="liquidados.folio"></td>
                                                <td class="td2" v-text="liquidados.nombre_cliente"></td>
                                                <td class="td2" v-text="liquidados.proyecto"></td>
                                                <td class="td2" v-text="liquidados.etapa"></td>
                                                <td class="td2" v-text="liquidados.manzana"></td>
                                                <td class="td2" v-text="liquidados.num_lote"></td>
                                                <td class="td2" v-text="liquidados.nombre_gestor"></td>
                                                <td class="td2" v-text="liquidados.tipo_credito"></td>
                                                <td class="td2" v-text="liquidados.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

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
                            <!-- fin del form para captura de fecha recibido -->

                            <!-- form para captura de fecha recibido -->
                            <form v-if="tipoAccion == 3" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de recibido</label>
                                    <div class="col-md-4">
                                        <input type="date"  v-model="fecha_recibido" class="form-control" >
                                    </div>
                                </div>
                                
                                
                            </form>
                            <!-- fin del form para captura de fecha recibido -->

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="enviarIngreso()">Ingresar</button>
                            <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="fechaRecibido()">Enviar</button>
                            <a v-bind:href="'/expediente/solicitudPDF/' + id" v-if="tipoAccion==3" type="button" target="_blank" class="btn btn-primary">Imprimir</a>
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
                arrayAutorizados: [],
                arrayLiquidados: [],
                arrayProgramacion: [],
                arrayPorIngresar:[],
                arrayObservacion:[],

                arrayFraccionamientos:[],
                arrayEtapas:[],

                errorIngreso:0,
                errorMostrarMsjIngreso:[],
                
                criterio:'lotes.fraccionamiento_id',
                buscar:'',
                b_etapa:'',
                b_manzana:'',
                b_lote:'',
                fecha_recibido:'',
                id:0,
                tipoAccion:0,
                fecha_ingreso:'',
                valor_escrituras:'',

                modal:0,
                tituloModal:'',
                modal3 :0,
                tituloModal3:'Observaciones',
                observacion:'',

               
            }
        },
        computed:{


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
                    me.arrayPorIngresar = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
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
                }

            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fecha_ingreso='';
                this.valor_escrituras='0';
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso=[];
                
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
            
        }
    }
</script>
<style>
    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
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

    /*th {
    text-align: left;
    background-color: rgb(190, 220, 250);
    text-transform: uppercase;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-bottom: rgb(50, 50, 100) solid 2px;
    border-top: none;
    }*/

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
