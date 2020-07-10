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
                        <i class="fa fa-align-justify"></i>Avaluos
                        <button class="btn btn-warning" v-if="listado == 1" @click="listado = 2 , listarHistorialAvaluos(1, buscar2, b_etapa2, b_manzana2, b_lote2, criterio2)">Historial de avaluos</button>
                        <button class="btn btn-secondary" v-if="listado == 2" @click="listado = 1">Volver</button>
                    </div>

                    <!-- avaluos por enviar a ventas -->
                    <div v-if="listado == 1" class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="licencias.visita_avaluo">Fecha de visita</option>
                                        <option value="contratos.id">Folio</option>
                                        <option value="cliente">Cliente</option>
                                    </select>

                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa" @click="selectManzanas(buscar,b_etapa)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" @keyup.enter="listarAvaluos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" v-model="b_manzana" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                    <input type="text" @keyup.enter="listarAvaluos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar" >
                                    <input type="text" @keyup.enter="listarAvaluos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" v-else-if="criterio=='contratos.id' || criterio=='cliente'" v-model="buscar" class="form-control" placeholder="Texto a buscar">

                                    <input v-else type="date"  v-model="buscar" @keyup.enter="listarAvaluos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                   
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" @keyup.enter="listarAvaluos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" v-model="b_status" >
                                        <option value="">Status</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Revisión">Revisión</option>
                                        <option value="Reconsideración">Reconsideración</option>
                                        <option value="Visto bueno">Visto bueno</option>
                                        <option value="Detenido">Detenido</option>
                                        <option value="En Elaboración">En Elaboración</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                    <button type="submit" @click="listarAvaluos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>PDF</th> 
                                        <th>Folio</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Modelo</th>
                                        <th>Avance obra</th>
                                        <th>Solicitud Ventas</th>
                                        <th>Valor Solicitado</th>
                                        <th>Fecha solicitud avaluo</th>
                                        <th>Fecha de visita</th>
                                        <th>Estatus</th>
                                        <th>Fecha concluido</th>
                                        <th>Seguro de calidad</th>
                                        <th>Valor concluido</th>
                                        <th>Costo</th>
                                        <th>Enviado a ventas</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="avaluos in arrayAvaluos" :key="avaluos.folio">
                                        <template>
                                            <td class="td2" >
                                                <button title="Subir avaluo" type="button" @click="abrirModal('subirArchivo',avaluos)" class="btn btn-default btn-sm">
                                                <i class="icon-cloud-upload"></i>
                                                </button>
                                                <a title="Descarga de avaluo" class="btn btn-default btn-sm" v-if="avaluos.pdf != '' && avaluos.pdf != null"  v-bind:href="'/downloadAvaluo/'+avaluos.pdf">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                       </template> 
                                        
                                        <td class="td2" v-text="avaluos.folio"></td>
                                        <td class="td2" v-text="avaluos.nombre + ' ' + avaluos.apellidos"></td>
                                        <td class="td2" v-text="avaluos.fraccionamiento"></td>
                                        <td class="td2" v-text="avaluos.etapa"></td>
                                        <td class="td2" v-text="avaluos.manzana"></td>
                                        <td class="td2" v-text="avaluos.num_lote"></td>
                                        <td class="td2" v-text="avaluos.modelo"></td>
                                        <td class="td2" v-text="avaluos.avance + '%'"></td>
                                        <td class="td2" v-text="this.moment(avaluos.fecha_solicitud).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="'$'+formatNumber(avaluos.valor_requerido)"></td>

                                        <td class="td2" @click="abrirModal('fecha_ava_sol',avaluos)" v-if="avaluos.fecha_ava_sol"
                                            v-text="this.moment(avaluos.fecha_ava_sol).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="abrirModal('fecha_ava_sol',avaluos)" class="btn btn-default btn-sm" title="Ingresar fecha de solicitud">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </td>
                                        
                                        <td class="td2" @click="abrirModal('visita_avaluo',avaluos)" v-text="this.moment(avaluos.visita_avaluo).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" @click="abrirModal('status',avaluos)" v-text="avaluos.status"></td>
                                        <td class="td2" v-if="avaluos.fecha_concluido" @click="abrirModal('concluido_act',avaluos)"
                                            v-text="this.moment(avaluos.fecha_concluido).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="abrirModal('fecha_concluido',avaluos)" class="btn btn-default btn-sm" title="Ingresar fecha concluido">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </td>

                                        <td class="td2" v-if="avaluos.tipo_credito == 'Alia2' || avaluos.tipo_credito == 'Fovissste' " v-text="'Si'"></td>
                                        <td class="td2" v-else>No</td>


                                        <td class="td2"  v-if="avaluos.fecha_concluido" @click="abrirModal('concluido_act',avaluos)" v-text="'$'+formatNumber(avaluos.resultado)"></td>
                                        <td class="td2" v-else v-text="'$'+formatNumber(avaluos.resultado)"></td>

                                        <td class="td2" v-if="!avaluos.costo" @click="abrirModal('costo',avaluos)" v-text="'$'+formatNumber(avaluos.costo)"></td>
                                        <td class="td2" v-else v-text="'$'+formatNumber(avaluos.costo)" @click="abrirModal('costo_act',avaluos)" ></td>

                                        <td class="td2" v-if="avaluos.fecha_recibido"
                                            v-text="this.moment(avaluos.fecha_recibido).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="enviarVentas(avaluos.avaluoId)" class="btn btn-primary btn-sm" title="Enviar a ventas">
                                                Enviar a ventas
                                            </button>
                                        </td>
                                        <td class="td2">
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal('observaciones',avaluos)">Observaciones</button>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar, b_etapa, b_manzana, b_lote, criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, b_etapa, b_manzana, b_lote, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, b_etapa, b_manzana, b_lote, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, b_etapa, b_manzana, b_lote, criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar, b_etapa, b_manzana, b_lote, criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- historial de avaluos enviados -->
                    <div v-if="listado == 2" class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio2" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="licencias.visita_avaluo">Fecha de visita</option>
                                        <option value="contratos.id">Folio</option>
                                        <option value="cliente">Cliente</option>
                                    </select>

                                    
                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="buscar2" @click="selectEtapa(buscar2)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_etapa2" @click="selectManzanas(buscar2,b_etapa2)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" @keyup.enter="listarHistorialAvaluos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-model="b_manzana2" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                    
                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_lote2" class="form-control" placeholder="Lote a buscar">
                                    <input type="text" v-else-if="criterio2=='contratos.id' || criterio2=='cliente'" v-model="buscar2" class="form-control" placeholder="Texto a buscar">

                                    <input v-else type="date"  v-model="buscar2" @keyup.enter="listarHistorialAvaluos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <button type="submit" @click="listarHistorialAvaluos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    &nbsp;<a :href="'/historial/avaluos/excel?buscar=' + buscar2 + '&b_etapa=' + b_etapa2 + '&b_manzana=' + b_manzana2 + '&b_lote=' + b_lote2 +  '&criterio=' + criterio2"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>PDF</th> 
                                        <th>Folio</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Modelo</th>
                                        <th>Avance obra</th>
                                        <th>Solicitud Ventas</th>
                                        <th>Valor Solicitado</th>
                                        <th>Fecha solicitud avaluo</th>
                                        <th>Fecha de visita</th>
                                        <th>Estatus</th>
                                        <th>Fecha concluido</th>
                                        <th>Seguro de calidad</th>
                                        <th>Valor concluido</th>
                                        <th>Costo</th>
                                        <th>Enviado a ventas</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="avaluos in arrayHistorialAvaluos" :key="avaluos.folio">
                                        <template>
                                            <td class="td2" >
                                                <button title="Subir avaluo" type="button" @click="abrirModal('subirArchivo',avaluos)" class="btn btn-default btn-sm">
                                                    <i class="icon-cloud-upload"></i>
                                                </button>
                                                <a title="Descarga de avaluo" class="btn btn-default btn-sm" v-if="avaluos.pdf != '' && avaluos.pdf != null"  v-bind:href="'/downloadAvaluo/'+avaluos.pdf">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                       </template> 
                                        
                                        <td class="td2" v-text="avaluos.folio"></td>
                                        <td class="td2" v-text="avaluos.nombre + ' ' + avaluos.apellidos"></td>
                                        <td class="td2" v-text="avaluos.fraccionamiento"></td>
                                        <td class="td2" v-text="avaluos.etapa"></td>
                                        <td class="td2" v-text="avaluos.manzana"></td>
                                        <td class="td2" v-text="avaluos.num_lote"></td>
                                        <td class="td2" v-text="avaluos.modelo"></td>
                                        <td class="td2" v-text="avaluos.avance + '%'"></td>
                                        <td class="td2" v-text="this.moment(avaluos.fecha_solicitud).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="'$'+formatNumber(avaluos.valor_requerido)"></td>

                                        <td class="td2" @click="abrirModal('fecha_ava_sol',avaluos)" v-if="avaluos.fecha_ava_sol"
                                            v-text="this.moment(avaluos.fecha_ava_sol).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="abrirModal('fecha_ava_sol',avaluos)" class="btn btn-default btn-sm" title="Ingresar fecha de solicitud">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </td>

                                        <td class="td2" @click="abrirModal('visita_avaluo',avaluos)" v-text="this.moment(avaluos.visita_avaluo).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" @click="abrirModal('status',avaluos)" v-text="avaluos.status"></td>
                                        <td class="td2" v-if="avaluos.fecha_concluido" @click="abrirModal('concluido_act',avaluos)"
                                            v-text="this.moment(avaluos.fecha_concluido).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="abrirModal('fecha_concluido',avaluos)" class="btn btn-default btn-sm" title="Ingresar fecha concluido">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </td>

                                        <td class="td2" v-if="avaluos.tipo_credito == 'Alia2' || avaluos.tipo_credito == 'Fovissste' " v-text="'Si'"></td>
                                        <td class="td2" v-else>No</td>


                                        <td class="td2"  v-if="avaluos.fecha_concluido" @click="abrirModal('concluido_act',avaluos)" v-text="'$'+formatNumber(avaluos.resultado)"></td>
                                        <td class="td2" v-else v-text="'$'+formatNumber(avaluos.resultado)"></td>

                                        <td class="td2" v-if="!avaluos.costo" @click="abrirModal('costo',avaluos)" v-text="'$'+formatNumber(avaluos.costo)"></td>
                                        <td class="td2" v-else v-text="'$'+formatNumber(avaluos.costo)" @click="abrirModal('costo_act',avaluos)" ></td>

                                        <td class="td2" v-if="avaluos.fecha_recibido"
                                            v-text="this.moment(avaluos.fecha_recibido).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="enviarVentas(avaluos.avaluoId)" class="btn btn-primary btn-sm" title="Enviar a ventas">
                                                Enviar a ventas
                                            </button>
                                        </td>
                                        <td class="td2">
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal('observaciones',avaluos)">Observaciones</button>
                                        </td>
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
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived ? 'active' : '']">
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

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio del modal 1 avaluo-->
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
                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 1" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_ava_sol" class="form-control">
                                    </div>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 2" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_pago" class="form-control">
                                    </div>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 3 || tipoAccion == 6" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_concluido" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Valor concluido</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" v-model="resultado" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Monto">
                                    </div>
                                    <div class="col-md-3">
                                        <h6 v-text="'$'+formatNumber(resultado)"></h6>
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Costo</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" v-model="costo" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Costo">
                                    </div>
                                    <div class="col-md-3">
                                        <h6 v-text="'$'+formatNumber(costo)"></h6>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observación</label>
                                    <div class="col-md-9">
                                        <textarea rows="1" cols="30" class="form-control" v-model="observacion" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 4 || tipoAccion == 5" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_costo" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Costo</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" v-model="costo" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Costo">
                                    </div>
                                    <div class="col-md-3">
                                        <h6 v-text="'$'+formatNumber(costo)"></h6>
                                    </div>
                                    <button v-if="tipoAccion==5" type="button" class="btn btn-success" @click="registrarGasto()"><i class="fa fa-plus"></i>  Nuevo Gasto</button>
                                </div>

                                <div class="form-group row">
                                    <table class="table table-bordered table-striped table-sm" >
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Gasto</th>
                                                <th>Costo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="gasto in arrayGastos" :key="gasto.id">
                                                
                                                <td v-text="gasto.fecha" ></td>
                                                <td v-text="'Avaluo'" ></td>
                                                <td v-text="'$'+formatNumber(gasto.costo)"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button v-if="tipoAccion==1" type="button" class="btn btn-primary" @click="setFechaSolicitud()">Guardar</button>
                            <button v-if="tipoAccion==2" type="button" class="btn btn-primary" @click="setFechaPago()">Guardar</button>
                            <button v-if="tipoAccion==3" type="button" class="btn btn-primary" @click="setFechaConcluido()">Guardar</button>
                            <button v-if="tipoAccion==6" type="button" class="btn btn-primary" @click="updateFechaConcluido()">Guardar Cambios</button>
                            <button v-if="tipoAccion==4" type="button" class="btn btn-primary" @click="registrarGasto()">Guardar</button>
                            <button v-if="tipoAccion==5" type="button" class="btn btn-primary" @click="updateGasto()">Actualizar Costo</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal 1 consulta-->

            <!--Inicio del modal 2 avaluo-->
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
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Estatus</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="status">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Revisión">Revisión</option>
                                        <option value="Reconsideración">Reconsideración</option>
                                        <option value="Visto bueno">Visto bueno</option>
                                        <option value="Detenido">Detenido</option>
                                        <option value="En Elaboración">En Elaboración</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nueva observación</label>
                                <div class="col-md-9">
                                    <textarea rows="1" cols="30" class="form-control" v-model="observacion" placeholder="Observaciones"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button v-if="observacion != ''" class="btn btn-primary" @click="setStatus()">Guardar</button>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="estado in arrayStatus" :key="estado.id">
                                        
                                        <td v-text="this.moment(estado.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td v-text="estado.status" ></td>
                                        <td v-text="estado.observacion" ></td>
                                    </tr>                               
                                </tbody>
                            </table>
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
            <!--Fin del modal consulta-->

            <!--Inicio del modal 3 avaluo-->
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de visita</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="visita_avaluo" class="form-control">
                                    </div>
                                </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nueva observación</label>
                                <div class="col-md-9">
                                    <textarea rows="1" cols="30" class="form-control" v-model="observacion" placeholder="Observaciones"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button v-if="observacion != ''" class="btn btn-primary" @click="setVisitaAvaluo()">Guardar</button>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha de visita</th>
                                        <th>Observacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="visita in arrayVisitas" :key="visita.id">
                                        
                                        <td v-text="this.moment(visita.fecha_visita).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td v-text="visita.observacion" ></td>
                                    </tr>                               
                                </tbody>
                            </table>
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
            <!--Fin del modal consulta-->

            <!-- Modal para la carga pdf -->
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
                            <div>
                                <form  method="post" @submit="formSubmit" enctype="multipart/form-data">

                                        <strong>Seleccionar avaluo</strong>

                                        <input type="file" class="form-control" v-on:change="onImageChange">
                                        <br/>
                                        <button type="submit" class="btn btn-success">Cargar</button>
                                </form>
                            </div>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal4()">Cerrar</button>
                            </div>
                    </div> 
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal5}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
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
                proceso:false,
                id: 0,
                avaluoId:0,
                visita_avaluo:'',

                id_gasto:0,
                pdf:'',

                
                arrayAvaluos : [],
                arrayHistorialAvaluos: [],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayGastoAdmin:[],
                arrayGastos:[],
                arrayAllManzanas:[],
                arrayObservacion:[],

                arrayStatus:[],
                arrayVisitas:[],

                fecha_ava_sol:'',
                fecha_pago:'',
                fecha_concluido:'',
                fecha_costo:'',
                costo:0,
                observacion:'',
                status:'',
                resultado:0,

                modal : 0,
                modal2: 0,
                modal3: 0,
                modal4 :0,
                modal5:0,
                listado: 1,

                tituloModal : '',
           
                tipoAccion: 0,
                errorLote : 0,
                errorMostrarMsjLote : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                pagination2 : {
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
                criterio2 : 'lotes.fraccionamiento_id', 
                buscar2 : '',
                b_etapa2: '',
                b_manzana2: '',
                b_lote2: '',
                usuario:'',
                b_status:'',
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

            //funciones para carga de los avaluos    

            onImageChange(e){

                console.log(e.target.files[0]);

                this.pdf = e.target.files[0];

            },

            formSubmit(e) {

                e.preventDefault();

                let currentObj = this;
            
                let formData = new FormData();
           
                formData.append('pdf', this.pdf);
                axios.post('/formSubmitAvaluo/'+this.avaluoId, formData)
                .then(function (response) {
                   
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal4();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            /**Metodo para mostrar los registros */
            listarAvaluos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/avaluos/index?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + 
                '&b_lote=' + b_lote + '&b_status=' + me.b_status +  '&criterio=' + criterio +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvaluos = respuesta.avaluos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

                   
            listarHistorialAvaluos(page2, buscar2, b_etapa2, b_manzana2, b_lote2, criterio2){
                let me = this;
                var url = '/historial/avaluos/index?page=' + page2 + '&buscar=' + buscar2 + '&b_etapa=' + b_etapa2 + '&b_manzana=' + b_manzana2 + '&b_lote=' + b_lote2 +  '&criterio=' + criterio2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorialAvaluos = respuesta.avaluos.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            listarHistorial(buscar){
                let me = this;
                
                me.arrayFraccionamientos=[];
                var url = '/avaluos/historialVisita?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVisitas = respuesta.historial;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarStatus(buscar){
                let me = this;
                
                me.arrayFraccionamientos=[];
                var url = '/avaluos/historialStatus?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayStatus = respuesta.status;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarObservacion(buscar){
                let me = this;
                var url = '/avaluos/listarObs?id=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/avaluos/storeObservacion',{
                    'id': this.id,
                    'observacion': this.observacion
                }).then(function (response){
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

            selectManzanas(buscar1, buscar2){
                let me = this;
                me.b_manzana="";

                me.arrayAllManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEtapa(buscar){
                let me = this;
                
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

            listraGastos(contrato){
                let me = this;
                
                me.arrayGastos=[];
                var url = '/getAvaluos?folio=' + contrato;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGastos = respuesta.gasto;
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
                me.listarAvaluos(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },

            cambiarPagina2(page2,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page2;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorialAvaluos(page2,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2);
            },

            setVisitaAvaluo(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/avaluos/storeVisita',{
                    'contrato_id':this.id,
                    'visita_avaluo' : this.visita_avaluo,
                    'observacion' : this.observacion
                    
                }).then(function (response){
                    me.observacion = '';
                    me.listarHistorial(me.id);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Registro de visita agregado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            setStatus(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/avaluos/storeStatus',{
                    'avaluoId':this.avaluoId,
                    'status' : this.status,
                    'observacion' : this.observacion
                    
                }).then(function (response){
                    me.observacion = '';
                    me.listarStatus(me.avaluoId);
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Registro de visita agregado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            registrarGasto(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/gastos/storeAvaluo',{
                    'avaluoId':this.avaluoId,
                    'id':this.id,
                    'costo' : this.costo,
                    'fecha' : this.fecha_costo
                    
                }).then(function (response){
                    me.observacion = '';
                    me.cerrarModal();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Gasto registrado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            updateGasto(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/gastos/updateAvaluo',{
                    'avaluoId':this.avaluoId,
                    'gasto_id':this.id_gasto,
                    'costo' : this.costo,
                    'fecha' : this.fecha_costo
                    
                }).then(function (response){
                    me.observacion = '';
                    me.cerrarModal();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Gasto actualizado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
       
            setFechaSolicitud(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/avaluos/fechaSolicitud',{
                    'avaluoId':this.avaluoId,
                    'fecha_ava_sol' : this.fecha_ava_sol,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Fecha de solicitud ingresada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            getDatosCosto(contrato_id,costo){
                let me = this;
                
                me.arrayGastoAdmin=[];
                var url = '/getGastoAvaluo?folio=' + contrato_id + '&costo='+ costo;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGastoAdmin = respuesta.gasto;
                    me.id_gasto = me.arrayGastoAdmin[0].id;
                    me.fecha_costo = me.arrayGastoAdmin[0].fecha;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            setFechaPago(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/avaluos/fechaPago',{
                    'avaluoId':this.avaluoId,
                    'fecha_pago' : this.fecha_pago,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Fecha de pago ingresada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            setFechaConcluido(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/avaluos/fechaConcluido',{
                    'avaluoId':this.avaluoId,
                    'fecha_concluido' : this.fecha_concluido,
                    'resultado' : this.resultado,
                    'costo' : this.costo,
                    'id':this.id,
                    'observacion': this.observacion,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Fecha de pago ingresada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
            updateFechaConcluido(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/avaluos/update/fechaConcluido',{
                    'avaluoId':this.avaluoId,
                    'fecha_concluido' : this.fecha_concluido,
                    'resultado' : this.resultado,
                    'costo' : this.costo,
                    'id':this.id,
                    'avaluoId':this.avaluoId,
                    'gasto_id':this.id_gasto,
                    'costo' : this.costo,
                    'observacion': this.observacion,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
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

            noAplicaPago(id){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/avaluos/fechaPago',{
                    'avaluoId':id,
                    'fecha_pago' : '0000-01-01',
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Fecha de pago ingresada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            enviarVentas(id){
                this.avaluoId = id;
                swal({
                title: '¿Esta seguro de enviar el avaluo a ventas?',
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

                    axios.put('/avaluos/enviarVentas',{
                        'id': me.avaluoId,
                    }).then(function (response) {
                        me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                        swal(
                        'Hecho!',
                        'Avaluo enviado correctamente.',
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

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.observacion = '';
                this.fecha_pago = '';
                this.fecha_concluido = '';
                this.fecha_ava_sol = '';
                this.modal2=0;
                this.modal3=0;
                this.modal5=0;
                this.usuario='';
                this.visita_avaluo='';
            },


            cerrarModal4(){
                this.modal4 = 0;
                this.tituloModal = '';
                this.pdf='';
            },

      
            abrirModal(accion,data =[]){
                switch(accion){
                    
                    case 'fecha_ava_sol':
                    {
                        this.modal =1;
                        this.tituloModal='Ingresar fecha de solicitud';
                        this.tipoAccion = 1;
                        this.fecha_ava_sol = data['fecha_ava_sol'];
                        this.avaluoId = data['avaluoId'];
                        this.id = data['folio'];
                        break;
                    }  
                    
                    case 'fecha_pago':
                    {
                        this.modal =1;
                        this.tituloModal='Ingresar fecha de pago';
                        this.tipoAccion = 2;
                        this.fecha_pago = data['fecha_pago'];
                        this.avaluoId = data['avaluoId'];
                        this.id = data['folio'];
                        break;
                    } 

                    case 'fecha_concluido':
                    {
                        this.modal =1;
                        this.tituloModal='Avaluo concluido';
                        this.tipoAccion = 3;
                        this.fecha_concluido = data['fecha_concluido'];
                        this.resultado = data['resultado'];
                        this.avaluoId = data['avaluoId'];
                        this.costo = data['costo'];
                        this.id = data['folio'];
                        break;
                    } 

                    case 'costo':
                    {
                        this.modal =1;
                        this.tituloModal='Ingresar Costo';
                        this.tipoAccion = 4;
                        this.fecha_costo = '';
                        this.costo = data['costo'];
                        this.id = data['folio'];
                        this.avaluoId = data['avaluoId'];
                        break;
                    } 

                    case 'costo_act':
                    {
                        this.modal =1;
                        this.tituloModal='Editar Costo';
                        this.tipoAccion = 5;
                        this.fecha_costo = '';
                        this.costo = data['costo'];
                        this.id = data['folio'];
                        this.avaluoId = data['avaluoId'];
                        this.getDatosCosto(this.id,this.costo);
                        this.listraGastos(this.id);
                        break;
                    }

                    case 'concluido_act':
                    {
                        this.modal =1;
                        this.tituloModal='Editar Cconcluido';
                        this.tipoAccion = 6;
                        this.fecha_concluido = data['fecha_concluido'];
                        this.resultado = data['resultado'];
                        this.avaluoId = data['avaluoId'];
                        this.costo = data['costo'];
                        this.id = data['folio'];
                        this.getDatosCosto(this.id,this.costo);
                        break;
                    }

                    case 'status':
                    {
                        this.modal2 =1;
                        this.tituloModal='Historial Estatus';
                        this.avaluoId = data['avaluoId'];
                        this.id = data['folio'];
                        this.observacion='';
                        this.status=data['status'];
                        this.listarStatus(this.avaluoId);
                        break;
                    } 

                    case 'visita_avaluo':
                    {
                        
                        this.modal3 =1;
                        this.tituloModal='Historial de visitas';
                        this.visita_avaluo = data['visita_avaluo'];
                        this.avaluoId = data['avaluoId'];
                        this.id = data['folio'];
                        this.observacion='';
                        this.listarHistorial(this.id);
                        break;
                    } 

                    case 'subirArchivo':
                    {
                        this.modal4 =1;
                        this.tituloModal='Subir Archivo';
                        this.avaluoId=data['avaluoId'];
                        this.pdf=data['pdf'];
                        break;
                    }
                    case 'observaciones':{
                        this.modal5 = 1;
                        this.tituloModal='Observaciones';
                        this.observacion='';
                        this.usuario='';
                        this.id=data['avaluoId'];
                        this.listarObservacion(this.id);
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
            this.listarAvaluos(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
            this.getEmpresa();
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
