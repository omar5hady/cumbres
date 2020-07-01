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
                        <i class="fa fa-align-justify"></i>Solicitud de Equipamiento
                        <!--   Boton descargar excel    -->
                            <button type="submit" v-if="historial == 0" class="btn btn-dark" @click="listarHistorial(1, buscar2, b_etapa2, b_manzana2, b_lote2, criterio2)">
                                <i class="fa fa-list-alt"></i> Historial de equipamientos
                            </button>
                        <!---->
                        <!--   Boton regresar  -->
                            <button type="submit" v-if="historial == 1" class="btn btn-light" @click="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)">
                                <i class="icon-action-undo"></i> Regresar
                            </button>
                        <!---->
                    </div>
                <!-------------------  Div para Contratos que tienen paquete o promoción  --------------------->
                    <div class="card-body" v-if="historial == 0">
                        <div class="form-group row">

                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>

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
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Equipamiento</th>
                                        <th>Avance</th>
                                        <th>Tipo de crédito</th>
                                        <th>Fecha firma de escritura</th>
                                        <th>Fecha avaluo</th>
                                        <th>Depositado</th>
                                        <th>Status</th>
                                        <th>Fecha entrega (obra)</th>
                                        <th>Solicitar</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" @dblclick="historial = 1, listarHistorial(1,contratos.folio,'','','','contratos.id');" :key="contratos.folio">
                                        <template>
                                            <td class="td2" v-text="contratos.folio"></td>
                                            <td class="td2" v-text="contratos.nombre_cliente"></td>
                                            <td class="td2" v-text="contratos.proyecto"></td>
                                            <td class="td2" v-text="contratos.etapa"></td>
                                            <td class="td2" v-text="contratos.manzana"></td>
                                            <td class="td2" v-text="contratos.num_lote"></td>
                                            <td class="td2" v-text="'Paquete: '+contratos.paquete + ', Promoción: ' + contratos.promocion"></td>
                                            <td class="td2" v-text="contratos.avance_lote + '%'"></td>
                                            <td class="td2" v-text="contratos.tipo_credito"></td>
                                            <td class="td2" v-text="this.moment(contratos.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-if="contratos.visita_avaluo" v-text="this.moment(contratos.visita_avaluo).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-else v-text="'Sin fecha'"></td>
                                            <td v-text="'$'+formatNumber(contratos.totPagare - contratos.totRest)"></td>
                                            <template>
                                                <td class="td2" v-if="contratos.status == '1'">
                                                    <span class="badge badge-warning">Pendiente</span>
                                                </td>
                                                <td class="td2" v-else-if="contratos.status == '3' && !contratos.fecha_firma_esc">
                                                    <span class="badge badge-success">Firmado</span>
                                                </td>
                                                <td class="td2" v-else-if="contratos.status == '3' && contratos.fecha_firma_esc">
                                                    <span class="badge badge-success"> Individualizada </span>
                                                </td>
                                            </template>
                                            <td class="td2" v-if="contratos.fecha_entrega" v-text="this.moment(contratos.fecha_entrega).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-else v-text="'Sin fecha'"></td>
                                            <td class="td2">
                                                <button type="button" @click="abrirModal('solicitar',contratos)" class="btn btn-default btn-sm"> Solicitar </button>
                                                <button v-if="contratos.equipamiento != 2" type="button" @click="terminarSolicitud(contratos.folio)" class="btn btn-success btn-sm" title="Finalizar">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </td>
                                           
                                        </template>
                                        
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,b_etapa,b_manzana,b_lote,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,b_etapa,b_manzana,b_lote,criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->

                <!-------------------  Div historial equipamientos  --------------------->
                    <div class="card-body" v-if="historial == 1">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio2" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id"># Folio</option>
                                        <option value="proveedores.proveedor">Proveedor</option>
                                    </select>

                                    
                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="buscar2" @click="selectEtapa(buscar2)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_etapa2"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>

                                    
                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_manzana2" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_lote2" class="form-control" placeholder="Lote a buscar">

                                    <input v-else type="text"  v-model="buscar2" @keyup.enter="listarHistorial(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-4" v-model="status">
                                        <option value="">Todos</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">En proceso</option>
                                        <option value="3">Revisión</option>
                                        <option value="4">Aprobados</option>
                                        <option value="0">Rechazados</option>
                                    </select>
                                    <button type="submit" @click="listarHistorial(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                       
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th></th>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Avance de obra</th>
                                        <th>Proveedor</th>
                                        <th>Equipamiento</th>
                                        <th>&nbsp; Costo del equipamiento &nbsp;</th>
                                        <th>Anticipo</th>
                                        <th>Fecha programada</th>
                                        <th>Fecha fin de instalación</th>
                                        <th>Status</th>
                                        <th># Dias de inst.</th>
                                        <th>Total pagado</th>
                                        <th>Pendiente</th>
                                        <th>Liquidacion</th>
                                        <th>Imprimir Recepción</th>
                                        <th>Observaciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="equipamientos in arrayHistorialEquipamientos" :key="equipamientos.id">
                                        <template>
                                            <td v-if="equipamientos.control == 0">
                                                <i class="btn btn-success btn-sm fa fa-check"></i>
                                            </td>
                                            <td v-else-if="equipamientos.control == 1">
                                                <button title="Reasignar" type="button" @click="abrirModal('reasignar',equipamientos)" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-exchange"></i>
                                                </button>
                                            </td>
                                            <td v-else-if="equipamientos.control == 3">
                                                <i class="btn btn-warning btn-sm fa fa-exclamation-triangle"></i>
                                            </td>
                                            <td v-else>
                                                <i title="Cancelado" class="btn btn-danger btn-sm fa fa-exclamation-triangle"></i>
                                            </td>
                                            <td class="td2" v-text="equipamientos.folio"></td>
                                            <td class="td2" v-text="equipamientos.nombre_cliente"></td>
                                            <td class="td2" v-text="equipamientos.proyecto"></td>
                                            <td class="td2" v-text="equipamientos.etapa"></td>
                                            <td class="td2" v-text="equipamientos.manzana"></td>
                                            <td class="td2" v-text="equipamientos.num_lote"></td>
                                            <td class="td2" v-text="equipamientos.avance + '%'"></td>
                                            <td class="td2" v-text="equipamientos.proveedor"></td>
                                            <td class="td2" v-text="equipamientos.equipamiento"></td>
                                            <td class="td2" style="width:40%">
                                                <input type="text" pattern="\d*" @keyup.enter="actCosto(equipamientos.id,$event.target.value)" :id="equipamientos.id" :value="equipamientos.costo|currency" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                            </td>
                                            <template>
                                                <td @click="abrirModal('anticipo', equipamientos)" v-if="equipamientos.fecha_anticipo && equipamientos.anticipo_cand==0" class="td2" v-text=" this.moment(equipamientos.fecha_anticipo).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+formatNumber(equipamientos.anticipo)"></td>
                                                <td v-else-if="equipamientos.fecha_anticipo && equipamientos.anticipo_cand==1" class="td2" v-text=" this.moment(equipamientos.fecha_anticipo).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+formatNumber(equipamientos.anticipo)"></td>
                                                <td @click="abrirModal('anticipo', equipamientos)" v-else class="td2" v-text="'Sin anticipo'"></td>    
                                            </template>
                                            <template>
                                                <td @click="abrirModal('colocacion', equipamientos)" v-if="equipamientos.fecha_colocacion" class="td2" v-text=" this.moment(equipamientos.fecha_colocacion).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td @click="abrirModal('colocacion', equipamientos)" v-else class="td2" v-text="'Sin fecha'"></td>    
                                            </template>
                                            <template>
                                                <td v-if="equipamientos.fin_instalacion" class="td2" v-text=" this.moment(equipamientos.fin_instalacion).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td v-else class="td2" v-text="'Sin fecha'"></td>    
                                            </template>
                                            <template>
                                                <td v-if="equipamientos.status == '0'" class="td2">
                                                    <span class="badge badge-warning">Rechazado</span>
                                                </td>
                                                <td v-if="equipamientos.status == '1'" class="td2">
                                                    <span class="badge badge-primary">Pendiente</span>
                                                </td>
                                                <td v-if="equipamientos.status == '2'" class="td2">
                                                    <span class="badge badge-primary">En proceso de colocación</span>
                                                </td>
                                                <td v-if="equipamientos.status == '3'" class="td2">
                                                    <span class="badge badge-primary">En Revisión</span>
                                                </td>
                                                <td v-if="equipamientos.status == '4'" class="td2">
                                                    <span class="badge badge-success">Aprobado</span>
                                                </td>  
                                                <td v-if="equipamientos.status == '5'" class="td2">
                                                    <span class="badge badge-danger">Cancelado</span>
                                                </td>    
                                            </template>
                                            <td v-if="!equipamientos.fin_instalacion && equipamientos.fecha_anticipo || equipamientos.fin_instalacion && equipamientos.fecha_anticipo && equipamientos.status != '4'">
                                                <span class="badge badge-warning" v-text="equipamientos.diferenciaIni"></span>
                                            </td>
                                            <td v-else-if="equipamientos.fin_instalacion && equipamientos.fecha_anticipo && equipamientos.status == '4'">
                                                <span class="badge badge-success" v-text="equipamientos.diferenciaFin"></span>
                                            </td>
                                            <td v-else>
                                                Sin anticipo
                                            </td>
                                            <td class="td2" v-text="'$'+formatNumber(equipamientos.anticipo + equipamientos.liquidacion)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(equipamientos.costo - equipamientos.anticipo - equipamientos.liquidacion)"></td>
                                            <template>
                                                <td v-if="equipamientos.fecha_liquidacion && equipamientos.liquidacion_cand == 0"  @click="abrirModal('liquidacion', equipamientos)" class="td2" v-text=" this.moment(equipamientos.fecha_liquidacion).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+formatNumber(equipamientos.liquidacion)"></td>
                                                <td v-else-if="equipamientos.fecha_liquidacion && equipamientos.liquidacion_cand == 1" class="td2" v-text=" this.moment(equipamientos.fecha_liquidacion).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+formatNumber(equipamientos.liquidacion)"></td>
                                                <td v-else-if="equipamientos.status == 4">
                                                    <button title="Realizar recepcion" type="button" 
                                                        @click="abrirModal('liquidacion', equipamientos)" class="btn btn-success pull-right">
                                                        <i class="fa fa-check-square-o"></i> Generar
                                                    </button>
                                                </td>    
                                                <td v-else v-text="'Sin Liquidación'"></td>
                                            </template>
                                            <template>
                                                <td v-if="equipamientos.tipoRecepcion == 1 && equipamientos.recepcion == 1">
                                                    <a class="btn btn-warning btn-sm"  target="_blank" v-bind:href="'/equipamiento/recepcionCocina/'+equipamientos.id">Ver Recepción</a>
                                                </td>
                                                <td v-else-if="equipamientos.tipoRecepcion == 2 && equipamientos.recepcion == 1">
                                                    <a class="btn btn-warning btn-sm"  target="_blank" v-bind:href="'/equipamiento/recepcionClosets/'+equipamientos.id">Ver Recepción</a>
                                                </td>
                                                <td v-else-if="equipamientos.tipoRecepcion == 0 && equipamientos.recepcion == 1">
                                                    <a class="btn btn-warning btn-sm"  target="_blank" v-bind:href="'/equipamiento/recepcionGeneral/'+equipamientos.id">Ver Recepción</a>
                                                </td>
                                                <td v-else>
                                                    
                                                </td>
                                            </template>
                                            <td> 
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones', equipamientos),listarObservacion(1,equipamientos.id)">Ver observaciones
                                                </button> 
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page<pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.last_page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio del modal para mostrar los datos del cliente -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-text="tituloModal"></h5>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row" v-if="paquete">
                                    <label class="col-md-2 form-control-label" for="text-input">Paquete</label>
                                    <div class="col-md-9">
                                        <textarea rows="3" cols="30" readonly v-model="paquete" class="form-control" placeholder="Descripcion del paquete"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="promocion">
                                    <label class="col-md-2 form-control-label" for="text-input">Promoción</label>
                                    <div class="col-md-9">
                                        <textarea rows="3" cols="30" readonly v-model="promocion" class="form-control" placeholder="Descripcion del paquete"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="proveedor" @click="selectEquipamiento(proveedor)">
                                            <option value="">Seleccione</option>
                                            <option v-for="proveedor in arrayProveedores" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.proveedor"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Equipamiento</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="equipamiento">
                                            <option value="">Seleccione</option>
                                            <option v-for="equipamiento in arrayEquipamientos" :key="equipamiento.id" :value="equipamiento.id" v-text="equipamiento.equipamiento"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" @click="solicitarEquipamiento()">Solicitar</button>
                                    </div>
                                </div>

                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th> 
                                            <th>Proveedor</th>
                                            <th>Equipamiento</th>
                                            <th>Fecha de solicitud</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="equipamientos in arrayEquipamientosLote" :key="equipamientos.id">
                                                <td class="td2">
                                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarEquipamiento(equipamientos)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                                </td>
                                                <td class="td2" v-text="equipamientos.proveedor"></td>
                                                <td class="td2" v-text="equipamientos.equipamiento"></td>
                                                <td class="td2" v-text="equipamientos.fecha_solicitud"></td>
                                        </tr>
                                    </tbody>
                                </table>  
                            </div>
                                    
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

            <!--Inicio del modal para diversos llenados de tabla en historial -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-text="tituloModal"></h5>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de anticipo</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_anticipo" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-2 form-control-label" for="text-input">$ Anticipo</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" v-model="anticipo" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 v-text="'$'+formatNumber(anticipo)"></h6>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion == 2">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de colocacion</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_colocacion" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row" v-if="tipoAccion == 2">
                                    <label class="col-md-2 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-8">
                                        <textarea v-model="observacion" cols="50" rows="4"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion == 3">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de liquidación</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_liquidacion" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion == 3">
                                    <label class="col-md-2 form-control-label" for="text-input">$ Liquidación</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" v-model="liquidacion" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 v-text="'$'+formatNumber(liquidacion)"></h6>
                                    </div>
                                </div>

                                
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-success" @click="actAnticipo()">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-success" @click="actColocacion()">Guardar</button>
                                <button type="button" v-if="tipoAccion == 3" class="btn btn-success" @click="actLiquidacion()">Guardar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                                    </div>
                                </div>


                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    
                                    <table class="table table-bordered table-striped table-sm">
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
                                                <td v-text="observacion.comentario" ></td>
                                                <td v-text="observacion.created_at"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                    
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

            <!--Inicio del modal para reubicar -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal4}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-text="tituloModal"></h5>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="contProy" @click="selectEtapa2(contProy)">
                                            <option value="">Proyecto</option>
                                            <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="contEtapa"> 
                                            <option value="">Etapa</option>
                                            <option v-for="etapa in arrayEtapas3" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input"></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="contManzana" class="form-control" placeholder="Manzana" >
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" @click="listarContRea(contProy,contEtapa,contManzana)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>

                                <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Seleccionar</th>
                                                <th># Ref</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Paquete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="contReasig in arrayReasignar" :key="contReasig.id">
                                                <td class="td2">
                                                    <button v-if="contReasig.id != id_reasig" title="Reasignar" type="button" @click="id_reasig = contReasig.id, lote_reasignar = contReasig.lote_id" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-exchange"></i>
                                                    </button>
                                                     <i v-else class="btn btn-success btn-sm fa fa-check"></i>
                                                </td>
                                                <td class="td2" v-text="contReasig.id" ></td>
                                                <td class="td2" v-text="contReasig.proyecto" ></td>
                                                <td class="td2" v-text="contReasig.etapa"></td>
                                                <td class="td2" v-text="contReasig.manzana"></td>
                                                <td class="td2" v-text="contReasig.num_lote"></td>
                                                <td class="td2" v-text="'Paquete: ' + contReasig.paquete + ' Promo: '+ contReasig.promocion"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>

                                
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" class="btn btn-success" @click="reubicar()">Reasignar</button>
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
    export default {
        data(){
            return{
                observacion:'',
                historial:0,

                arrayContratos : [],
                arrayEquipamientosLote: [],
                arrayObservacion : [],

                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayFraccionamientos2:[],
                arrayEtapas2:[],
                arrayProveedores:[],
                arrayEquipamientos:[],

                modal: 0,
                modal2: 0,
                modal3: 0,
                modal4: 0,
                tituloModal: '',

                //variables
                paquete:'',
                promocion:'',
                proveedor: 0,
                equipamiento: '',
                lote_id: 0,
                contrato_id: 0,
                solicitud_id: 0,
           
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',

                contProy:'',
                contEtapa:'',
                contManzana:'',
                id_reasig:'',
                lote_reasignar:'',
                arrayReasignar:[],

                arrayEtapas3:[],

                // Criterios para historial de equipamientos
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                criterio2 : 'lotes.fraccionamiento_id', 
                buscar2 : '',
                b_etapa2: '',
                b_manzana2: '',
                b_lote2: '',
                tipoAccion:0,

                fecha_anticipo:'',
                anticipo:0,
                fecha_liquidacion:'',
                liquidacion:0,
                fecha_colocacion:'',
                observacion:'',
                status:'',


                arrayHistorialEquipamientos : [],
                b_empresa:'',
                empresas:[],
               
            }
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

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
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


            isActived2: function(){
                return this.pagination2.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }

                var from = this.pagination2.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },

        },

        
        methods : {

            /**Metodo para mostrar los registros */
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                me.historial = 0;
                var url = '/equipamiento/indexContrato?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + 
                    '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                    for(var i=0;i<me.pagination.total;i++){
                       
                        if(me.arrayContratos[i].paquete == null || me.arrayContratos[i].paquete == ""){
                            me.arrayContratos[i].paquete= 'Sin paquete';
                        }
                         if(me.arrayContratos[i].promocion == null || me.arrayContratos[i].promocion == ""){
                            me.arrayContratos[i].promocion = 'Sin promoción';
                        }
                    }
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            listarHistorial(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                me.historial = 1;
                var url = '/equipamiento/indexHistorial?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + 
                    '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio +  '&status=' + 
                    me.status +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorialEquipamientos = respuesta.equipamientos.data;
                    me.pagination2 = respuesta.pagination;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarContRea(proyecto, etapa, manzana){
                let me = this;
                var url = '/equipamiento/contRea?proyecto=' + proyecto + '&etapa=' + etapa + '&manzana=' + manzana;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayReasignar = respuesta.contratos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarEquipamientosLote(){
                let me = this;
                var url = '/index/equipamiento/lote?lote_id=' + this.lote_id + '&contrato_id=' + this.contrato_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEquipamientosLote = respuesta.equipamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
                me.arrayFraccionamientos=[];
                me.arrayFraccionamientos2=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    me.arrayFraccionamientos2 = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEtapa(buscar){
                let me = this;
                me.b_etapa=""
                
                me.arrayEtapas=[];
                me.arrayEtapas2=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                    me.arrayEtapas2 = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEtapa2(buscar){
                let me = this;
                
                me.arrayEtapas3=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas3 = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectProveedores(){
                let me = this;
                
                me.arrayProveedores=[];
                var url = '/select_proveedor';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProveedores = respuesta.proveedor;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEquipamiento(proveedor){
                let me = this;
                me.equipamiento=""
                
                me.arrayEquipamientos=[];
                var url = '/select_equipamientos?buscar=' + proveedor;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEquipamientos = respuesta.equipamiento;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },
            cambiarPagina2(page,buscar,b_etapa,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorial(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },

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

            solicitarEquipamiento(){
                  let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/equipamiento/solicitar_equipamiento',{
                    'contrato_id':this.contrato_id,
                    'lote_id' : this.lote_id,
                    'equipamiento_id': this.equipamiento
                    
                }).then(function (response){
                    me.proveedor ='';
                    me.equipamiento ='';
                    me.listarContratos(me.pagination.current_page,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    me.listarEquipamientosLote();
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Equipamiento solicitado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            eliminarEquipamiento(data = []){
                this.solicitud_id=data['id'];
                //console.log(this.departamento_id);
                swal({
                title: '¿Desea eliminar?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/equipamiento/lote/eliminar', 
                        {params: {'id': this.solicitud_id,'contrato_id':this.contrato_id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Equipamiento borrado correctamente.',
                        'success'
                        )
                        me.listarEquipamientosLote();
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            terminarSolicitud(id){
                this.contrato_id=id;
                //console.log(this.departamento_id);
                swal({
                title: '¿Desea finalizar la solicitud de equipamientos para este contrato?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.put('/equipamiento/terminarSolicitud', 
                         {'id': this.contrato_id}).then(function (response){
                        swal(
                        'Hecho!',
                        'La solicitud de equipamientos ha sido finalizada con exito.',
                        'success'
                        )
                        me.listarContratos(page,buscar,b_etapa,b_manzana,b_lote,criterio);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            actCosto(id,costo){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actCosto',{
                    'fecha_anticipo':this.fecha_anticipo,
                    'costo' : costo,
                    'id':id,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actAnticipo(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actAnticipo',{
                    'fecha_anticipo':this.fecha_anticipo,
                    'anticipo' : this.anticipo,
                    'id':this.solicitud_id,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            reubicar(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/reubicar',{
                    'id':this.solicitud_id,
                    'contrato_id' : this.id_reasig,
                    'lote_id':this.lote_reasignar,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Equipamiento reasignado'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actLiquidacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actLiquidacion',{
                    'fecha_liquidacion':this.fecha_liquidacion,
                    'liquidacion' : this.liquidacion,
                    'id':this.solicitud_id,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            storeObservacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/equipamiento/registrarObservacion',{
                    'comentario' : this.observacion,
                    'solic_id':this.solicitud_id,
                    
                }).then(function (response){
                    me.listarObservacion(1,me.solicitud_id);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Observación guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actColocacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actColocacion',{
                    'fecha_colocacion':this.fecha_colocacion,
                    'comentario' : this.observacion,
                    'id':this.solicitud_id,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            listarObservacion(page, buscar){
                let me = this;
                var url = '/equipamiento/indexObservacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.modal2 = 0;
                this.modal3 = 0;
                this.modal4 = 0;
                this.fecha_anticipo = '';
                this.anticipo = '';
                this.proveedor = '';
                this.equipamiento = '';
                this.arrayReasignar = [];
                this.id_reasig = '';
                this.lote_reasignar = '';
            },

            abrirModal(accion,data =[]){
                    switch(accion){
                        case 'solicitar':
                        {
                            this.modal =1;
                            this.tituloModal='Solicitar equipamiento';
                            this.tipoAccion=1;
                            this.paquete = data['paquete'];
                            this.promocion = data['promocion'];
                            this.contrato_id = data['folio'];
                            this.lote_id = data['lote_id'];
                            this.proveedor = '';
                            this.selectProveedores();
                            this.listarEquipamientosLote();
                            break;
                        }
                        case 'anticipo':{
                            this.modal2 =1;
                            this.tituloModal='Anticipo';
                            this.tipoAccion=1;
                            this.fecha_anticipo = data['fecha_anticipo'];
                            this.anticipo = data['anticipo'];
                            this.solicitud_id = data['id'];
                            break;
                        }

                        case 'colocacion':{
                            this.modal2 =2;
                            this.tituloModal='Colocación';
                            this.tipoAccion=2;
                            this.fecha_colocacion = data['fecha_colocacion'];
                            this.solicitud_id = data['id'];
                            this.observacion = '';
                            break;
                        }

                        case 'liquidacion':{
                            this.modal2 =1;
                            this.tituloModal='Liquidación';
                            this.tipoAccion=3;
                            this.fecha_liquidacion = data['fecha_liquidacion'];
                            this.liquidacion = data['liquidacion'];
                            this.solicitud_id = data['id'];
                            break;
                        }

                        case 'observaciones':{
                            this.modal3 =1;
                            this.tituloModal='Observaciones';
                            this.solicitud_id = data['id'];
                            this.observacion = '';
                            break;
                        }

                        case 'reasignar':{
                            this.modal4 =1;
                            this.tituloModal='Reasignar equipamiento';
                            this.solicitud_id = data['id'];
                            this.lote_reasignar = '';
                            this.id_reasig = '';
                            break;
                        }
                    }
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
            
        },
       
        mounted() {
            this.listarContratos(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
            this.getEmpresa();
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
